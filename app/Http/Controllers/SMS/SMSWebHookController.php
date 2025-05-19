<?php

namespace App\Http\Controllers\SMS;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\SentSMSModel;
use Exception;
use Illuminate\Support\Facades\Log;
use CloudEvents\Serializers\JsonDeserializer;

class SMSWebHookController extends Controller
{
    //

    public function __invoke(Request $request)
    {
        // Log::info("httpsms.com webhook event received with type [{$request->header('X-Event-Type')}]");

        try {
            
             $httpSMSID = json_decode($request->getContent(),true);
            SentSMSModel::where('sent_sms.http_sms_id','=', $httpSMSID['data']['id'])->update([
                'webhook' => $request->getContent(),  // Saving the event data as a JSON string
            ]);

        } catch (Exception $exception) {
            Log::error($exception);
        }
    }


}
