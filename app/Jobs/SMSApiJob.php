<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeUnique;

use Illuminate\Support\Facades\DB;
use App\Models\SentSMSModel;
use App\Models\SMSMessageModel;
use App\Models\TransactionApproveModel;
use App\Models\TransactionApproveStatusConditionModel;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;

class SMSApiJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $smsMessage;
    public $selectedSMS;
    public $currentQueueSending;

    public function __construct($smsMessage, $selectedSMS)
    {
        $this->smsMessage = $smsMessage;
        $this->selectedSMS = $selectedSMS;
    }

    public function handle(): void
    {

       
      
        // Redis sorted set to track SMS sent timestamps
        $smsSentKey = 'sms_sent_timestamps';
        
        // Get the current time in seconds
        $currentTime = time();

        // Remove timestamps older than 60 seconds
        Redis::zRemRangeByScore($smsSentKey, 0, $currentTime - 900);

        // Count how many SMS were sent in the last 60 seconds
        $sentCount = Redis::zCard($smsSentKey);
        $smsCount = count($this->selectedSMS);

        // Check if the total count in the last 60 seconds will exceed 60
        if (($sentCount + $smsCount) <= 35) {
            // Start a transaction to ensure atomicity
            DB::beginTransaction();
            
            try {
                // Process each SMS
                foreach ($this->selectedSMS as $key => $value) {
                    $apikey = env('HTTP_SMS_KEY');
                    $fromNumber = env('HTTP_SMS_FROM_CONTACT_NUMBER');

                    $transactionApproveStatusConditionModel = TransactionApproveStatusConditionModel::where('transaction_approve_id','=',$value['id'])->where('transaction_approve_status_id','=',2)->first();
                    
                    if($transactionApproveStatusConditionModel){

                     // Store the response in SentSMSModel
                    $response = Http::withHeaders([
                        'x-api-key' => $apikey
                    ])->post('https://api.httpsms.com/v1/messages/send', [
                        'content' => $this->smsMessage->message."\n\n"."Transaction ID: ".$value['transaction']['transaction_id'],
                        "encrypted" => false,
                        'from' => $fromNumber,
                        'to' => $value['transaction']['client']['contact_number'][0]['contact_number'],
                    ]);

             

                    $sentSMS = SentSMSModel::create([
                        'transaction_approve_id' => $value['id'],
                        'message_id' => $this->smsMessage->id,
                        'response' => json_encode($response->json(), JSON_PRETTY_PRINT),
                        'http_sms_id' => $response['data']['id'],
                    ]);

                    

                    // Record the SMS timestamp in Redis to track when it was sent
                    Redis::zAdd($smsSentKey, $currentTime, $currentTime . '-' . $value['id']);
                    
                    }
                    
                }

                // Commit the transaction if everything goes well
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                // Handle error if needed
            } catch (ModelNotFoundException $e) {
                DB::rollBack();
                // Handle not found error if needed
            }
        } else {
            // Get the oldest timestamp in the last 60 seconds
            $oldest = Redis::zRange($smsSentKey, 0, 0, ['WITHSCORES' => true]);
            $oldestTimestamp = isset($oldest) ? array_values($oldest)[0] : $currentTime;

            // Calculate how many seconds remain before you can send again
            $delaySeconds =  max(1, 900 - ($currentTime - $oldestTimestamp));

            // $delaySeconds = max(1, ($oldestTimestamp + 60) - $currentTime);

            // Re-dispatch the job with the appropriate delay
            self::dispatch($this->smsMessage, $this->selectedSMS)->delay($delaySeconds);
        }
    }
}
