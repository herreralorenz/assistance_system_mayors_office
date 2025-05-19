<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::get('/login', [App\Http\Controllers\Auth\LoginController::class])->middleware(['auth:sanctum','throttle:60,1']);



/**
 * Get seeder data
 */

 Route::get('/form-seeder', [App\Http\Controllers\FormSeeder\FormSeederController::class, 'index'])->middleware(['auth:sanctum','throttle:60,1']);

 Route::post('/login', [App\Http\Controllers\Auth\LoginController::class,'login'])->middleware(['auth','auth:sanctum']);
 
/**
 * Request for Assistance
 */

Route::post('/submit-request-for-assistance',[App\Http\Controllers\RequestForAssistance\RequestForAssistanceController::class,'store'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/autocomplete-fullname-beneficiary',[App\Http\Controllers\RequestForAssistance\AutoCompleteController::class,'autoCompleteNameBeneficiary'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/autocomplete-fullname-client',[App\Http\Controllers\RequestForAssistance\AutoCompleteController::class,'autoCompleteNameClient'])->middleware(['auth:sanctum','throttle:60,1']);
Route::post('/check-client-assistance',[App\Http\Controllers\RequestForAssistance\ClientBeneficiaryAssistanceCheckerController::class,'checkClientAssistance'])->middleware(['auth:sanctum','throttle:60,1']);
Route::post('/check-beneficiary-assistance',[App\Http\Controllers\RequestForAssistance\ClientBeneficiaryAssistanceCheckerController::class,'checkBeneficiaryAssistance'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/get-request-of-assistance-receipt/{id}',[App\Http\Controllers\RequestForAssistance\PrintReceiptController::class,'requestReceipt'])->middleware(['auth:sanctum','throttle:60,1']);
/**
 * Approval of Assistance
 */

Route::get('/approval-of-assistance-table',[App\Http\Controllers\ApprovalOfAssistance\ApproveAssistanceTableController::class,'index'])->middleware(['auth:sanctum','throttle:60,1']);
Route::patch('/approve-client/{id}', [App\Http\Controllers\ApprovalOfAssistance\ApproveDeclineClientAssistanceController::class,'approveClient'])->middleware(['auth:sanctum','throttle:60,1']);
Route::patch('/decline-client/{id}', [App\Http\Controllers\ApprovalOfAssistance\ApproveDeclineClientAssistanceController::class,'declineClient'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/approve-client-details/{id}',[App\Http\Controllers\ApprovalOfAssistance\ApproveAssistanceTableController::class,'show'])->middleware(['auth:sanctum','throttle:60,1']);
Route::patch('/update-approve-client/{id}',[App\Http\Controllers\ApprovalOfAssistance\ApproveDeclineClientAssistanceController::class,'updateApprovedClient'])->middleware(['auth:sanctum','throttle:60,1']);


//Get image

Route::get('/get-client-approve-image',[App\Http\Controllers\ApprovalOfAssistance\ApproveClientBeneficiaryImageController::class,'showClientApproveImage'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/get-beneficiary-approve-image',[App\Http\Controllers\ApprovalOfAssistance\ApproveClientBeneficiaryImageController::class,'showBeneficiaryApproveImage'])->middleware(['auth:sanctum','throttle:60,1']);

//Search Transaction Approve
Route::get('/search-client-approve', [App\Http\Controllers\ApprovalOfAssistance\SearchApproveController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/search-transactionID-approve', [App\Http\Controllers\ApprovalOfAssistance\SearchApproveController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);

/**
 * Bulk Approve Assistance
 */

 //Bulk Approve Assistance Table 
Route::get('/bulk-approval-of-assistance-table',[App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'bulkTransactionApproveTable'])->middleware(['auth:sanctum','throttle:60,1']);

//Searching
Route::get('/search-client-bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/search-transaction-date-bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'searchDateRequest'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/search-transaction-id-bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);

 //Bulk Approve
Route::patch('/bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'bulkApprove'])->middleware(['auth:sanctum','throttle:60,1']);

/**
 * Transaction Claim
 */

 Route::get('/claiming-of-assistance-table',[App\Http\Controllers\ClaimingOfAssistance\ClaimAssistanceTableController::class,'index'])->middleware(['auth:sanctum','throttle:60,1']);

 Route::get('/search-client-claim',[App\Http\Controllers\ClaimingOfAssistance\SearchClaimController::class,'searchClaimClient'])->middleware(['auth:sanctum','throttle:60,1']);
 Route::get('/search-transaction-id-claim',[App\Http\Controllers\ClaimingOfAssistance\SearchClaimController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);

 Route::get('/claim-client-details/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimAssistanceTableController::class,'show'])->middleware(['auth:sanctum','throttle:60,1']);

 Route::get('/get-client-claim-image',[App\Http\Controllers\ClaimingOfAssistance\ClaimClientBeneficiaryImageController::class,'showClientClaimImage'])->middleware(['auth:sanctum','throttle:60,1']);
 Route::get('/get-beneficiary-claim-image',[App\Http\Controllers\ClaimingOfAssistance\ClaimClientBeneficiaryImageController::class,'showBeneficiaryClaimImage'])->middleware(['auth:sanctum','throttle:60,1']);

 Route::post('/claim-client/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimUnclaimClientAssistanceController::class,'claimClient'])->middleware(['auth:sanctum','throttle:60,1']);
 Route::patch('/unclaim-client/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimUnclaimClientAssistanceController::class,'unclaimClient'])->middleware(['auth:sanctum','throttle:60,1']);
 Route::patch('/update-claim-client/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimUnclaimClientAssistanceController::class,'updateClaimedClient'])->middleware(['auth:sanctum','throttle:60,1']);

 //Bulk Approve Assistance Table 
Route::get('/bulk-claiming-of-assistance-table',[App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'bulkTransactionClaimTable'])->middleware(['auth:sanctum','throttle:60,1']);

//searching
Route::get('/search-client-bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/search-transaction-id-bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);
Route::get('/search-transaction-date-bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'searchDateRequest'])->middleware(['auth:sanctum','throttle:60,1']);

 //Bulk Claim
 Route::patch('/bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'bulkClaim'])->middleware(['auth:sanctum','throttle:60,1']);

 /**
  * Client
  */

  //Client 
  Route::get('/get-client-information-table',[App\Http\Controllers\Client\ClientInformationTableController::class,'index'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/search-client',[App\Http\Controllers\Client\ClientSearchController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);


  //show client
  Route::get('/get-client-details/{id}',[App\Http\Controllers\Client\ClientInformationTableController::class,'show'])->middleware(['auth:sanctum','throttle:60,1']);

  //edit & update client
  Route::get('/get-client-details/{id}/edit',[App\Http\Controllers\Client\ClientInformationTableController::class,'edit'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::patch('/update-client-details/{id}',[App\Http\Controllers\Client\ClientInformationTableController::class,'update'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-client-image',[App\Http\Controllers\Client\ClientImageController::class,'showClientImage'])->middleware(['auth:sanctum','throttle:60,1']);
  
  //delete
  Route::delete('/delete-client-details/{id}',[App\Http\Controllers\Client\ClientInformationTableController::class,'destroy'])->middleware(['auth:sanctum','throttle:60,1']);

  //client new transaction
  Route::get('/get-client-new-transaction-table',[App\Http\Controllers\Client\ClientNewTransactionController::class,'index'])->middleware(['auth:sanctum','throttle:60,1']);
  
  //get client when new transaction for same as client
  Route::get('/get-client-new-transaction/{id}',[App\Http\Controllers\Client\ClientNewTransactionController::class,'create'])->middleware(['auth:sanctum','throttle:60,1']);

  //submit new transaction
  Route::post('/new-transaction/{id}',[App\Http\Controllers\Client\ClientNewTransactionController::class,'store'])->middleware(['auth:sanctum','throttle:60,1']);

  /**
   * Transaction 
   */
  Route::get('/client-transaction-table',[App\Http\Controllers\Transactions\ClientTransactionController::class,'index'])->middleware(['auth:sanctum','throttle:60,1']);
  
  Route::get('/search-client-transaction',[App\Http\Controllers\Transactions\TransactionSearchController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/search-transaction-id-transaction',[App\Http\Controllers\Transactions\TransactionSearchController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-client-transaction-details/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'show'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-client-transaction-image',[App\Http\Controllers\Transactions\ClientBeneficiaryTransactionImageController::class,'showClientTransactionImage'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-beneficiary-transaction-image',[App\Http\Controllers\Transactions\ClientBeneficiaryTransactionImageController::class,'showBeneficiaryTransactionImage'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-client-transaction-beneficiary/{id}/beneficiary/{id2}/client/{id3}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'editBeneficiary'])->middleware(['auth:sanctum','throttle:60,1']);
  
  Route::patch('/update-client-transaction-beneficiary/{id}/beneficiary/{id2}/client/{id3}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'updateBeneficiary'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::post('/add-client-transaction-beneficiary/{id}/client/{id2}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'addBeneficiary'])->middleware(['auth:sanctum','throttle:60,1']);
  
  Route::patch('/update-client-transaction-assistance/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'updateAssistance'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::delete('/void-transaction/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'voidTransaction'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::delete('/delete-transaction/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'deleteTransaction'])->middleware(['auth:sanctum','throttle:60,1']);
  
  Route::get('/autocomplete-fullname-client-with-relationship/{id2}',[App\Http\Controllers\RequestForAssistance\AutoCompleteController::class,'autoCompleteNameBeneficiaryWithRelationship'])->middleware(['auth:sanctum','throttle:60,1']);

  //Transaction Report
  Route::get('/transaction-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'generateReport'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/download-all-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'downloadAllReport'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/download-claimed-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'downloadClaimedReport'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/download-unclaimed-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'downloadUnclaimedReport'])->middleware(['auth:sanctum','throttle:60,1']);


  //Bulk Printing
  Route::get('/bulk-printing-of-receipt-client',[App\Http\Controllers\Transactions\BulkPrintingOfReceiptController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/bulk-printing-of-receipt-transaction-id',[App\Http\Controllers\Transactions\BulkPrintingOfReceiptController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/bulk-printing-of-receipt-date',[App\Http\Controllers\Transactions\BulkPrintingOfReceiptController::class,'searchDate'])->middleware(['auth:sanctum','throttle:60,1']);


  //QR Code Claim

  Route::get('/get-client-claim-qr',[App\Http\Controllers\ClaimingOfAssistance\ClaimAssistanceQRController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::patch('/client-claim-qr',[App\Http\Controllers\ClaimingOfAssistance\ClaimAssistanceQRController::class,'claimAssistance'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-client-approve-qr',[App\Http\Controllers\ApprovalOfAssistance\ApproveAssistanceQRController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::post('/client-approve-qr',[App\Http\Controllers\ApprovalOfAssistance\ApproveAssistanceQRController::class,'approveClient'])->middleware(['auth:sanctum','throttle:60,1']);

  //Maintenance

  Route::get('/get-return-days',[App\Http\Controllers\Maintenance\ReturnDaysController::class,'returnDays'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::patch('/update-return-days',[App\Http\Controllers\Maintenance\ReturnDaysController::class,'updateReturnDays'])->middleware(['auth:sanctum','throttle:60,1']);

  //Users

  Route::get('/get-users',[App\Http\Controllers\Maintenance\UserRolesController::class,'getUsers'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/get-user-permissions/{id}',[App\Http\Controllers\Maintenance\UserRolesController::class,'getUserPermissions'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::post('/set-user-permissions/{id}',[App\Http\Controllers\Maintenance\UserRolesController::class,'setUserPermissions'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/search-user-roles',[App\Http\Controllers\Maintenance\UserRolesController::class,'searchUsers'])->middleware(['auth:sanctum','throttle:60,1']);
  
  Route::get('/get-auth-user-roles-permissions',[App\Http\Controllers\Maintenance\AuthCheckUserRolesPermissionsController::class,'checkUserRolePermission'])->middleware(['auth:sanctum','throttle:60,1']);
  

  Route::get('/get-user-details/{id}',[App\Http\Controllers\Maintenance\UserController::class,'getUserDetails'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::patch('/update-user-details/{id}',[App\Http\Controllers\Maintenance\UserController::class,'updateUserDetails'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::post('/add-user-details',[App\Http\Controllers\Maintenance\UserController::class,'addUserDetails'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::delete('/delete-user-details/{id}',[App\Http\Controllers\Maintenance\UserController::class,'deleteUserDetails'])->middleware(['auth:sanctum','throttle:60,1']);


  //SMS

  Route::get('/get-sms-transaction-details',[App\Http\Controllers\SMS\SendSMSController::class,'getApprovedTransactions'])->middleware(['auth:sanctum','throttle:60,1']);

  Route::get('/search-sms-transaction-details-client',[App\Http\Controllers\SMS\SendSMSController::class,'searchClient'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/search-sms-transaction-details-transaction-id',[App\Http\Controllers\SMS\SendSMSController::class,'searchTransactionID'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/search-sms-transaction-details-transaction-date',[App\Http\Controllers\SMS\SendSMSController::class,'searchDate'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::post('/send-bulk-sms',[App\Http\Controllers\SMS\SendSMSController::class,'sendBulkSMS'])->middleware(['auth:sanctum','throttle:60,1']);

  Route::get('/get-sms-message',[App\Http\Controllers\SMS\SMSMessageController::class,'getMessages'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::patch('/update-sms-message/{id}',[App\Http\Controllers\SMS\SMSMessageController::class,'updateMessage'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::delete('/delete-sms-message/{id}',[App\Http\Controllers\SMS\SMSMessageController::class,'deleteMessage'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::post('/add-sms-message',[App\Http\Controllers\SMS\SMSMessageController::class,'addMessage'])->middleware(['auth:sanctum','throttle:60,1']);

  Route::post('/sent-sms/webhook',App\Http\Controllers\SMS\SMSWebHookController::class);
  Route::get('/http-sms-usage',[App\Http\Controllers\SMS\SendSMSController::class,'getHTTPSMSUsage'])->middleware(['auth:sanctum','throttle:60,1']);

  //Dashboard

  Route::get('/dashboard-fetch-cost',[App\Http\Controllers\Dashboard\DashboardController::class,'getCosting'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/dashboard-fetch-transactions',[App\Http\Controllers\Dashboard\DashboardController::class,'getTransactions'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/dashboard-fetch-approved',[App\Http\Controllers\Dashboard\DashboardController::class,'getApproved'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/dashboard-fetch-claimed',[App\Http\Controllers\Dashboard\DashboardController::class,'getClaimed'])->middleware(['auth:sanctum','throttle:60,1']);

  Route::get('/dashboard-fetch-yearly-assistance',[App\Http\Controllers\Dashboard\DashboardController::class,'getYearlyAssistance'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/dashboard-fetch-barangay',[App\Http\Controllers\Dashboard\DashboardController::class,'getBarangay'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/dashboard-fetch-assistance-description',[App\Http\Controllers\Dashboard\DashboardController::class,'getAssistanceDescription'])->middleware(['auth:sanctum','throttle:60,1']);
  Route::get('/dashboard-fetch-sent-sms',[App\Http\Controllers\Dashboard\DashboardController::class,'getSentSMS'])->middleware(['auth:sanctum','throttle:60,1']);
  //Test Controller
  Route::get('/test',[App\Http\Controllers\TestController::class,'testUnclaimedTransaction'])->middleware(['auth:sanctum','throttle:60,1']);
