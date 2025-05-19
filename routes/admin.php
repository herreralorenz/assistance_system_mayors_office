<?php


use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Navigate the Admin Component
 */

Route::get('/request/details',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/print/request-for-assistance-receipt/{id}',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/approve/client',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/approve/client/{id}',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/approve/bulk',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/claim/client',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/claim/client/{id}',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/claim/bulk',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/client/information',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/client/information/{id}',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/client/information/{id}/edit',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/client/new-transaction',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/client/new-transaction/{id}/beneficiary',[App\Http\Controllers\AdminController::class,'index']);
// Route::get('/client/new-transaction/{id}/beneficiary/submit',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/client-transactions',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/client-transaction/{id}',[App\Http\Controllers\AdminController::class,'index']);

Route::get('/print/request-for-assistance-receipt',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/client-transactions/{id}/beneficiary/{id2}/client/{id3}',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/client-transactions/{id}/client/{id2}/add-beneficiary',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/report',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/bulk-printing',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/print/bulk-printing-of-receipt-holder',[App\Http\Controllers\AdminController::class,'index']);

/**
 * Get seeder data
 */

 Route::get('/form-seeder', [App\Http\Controllers\FormSeeder\FormSeederController::class, 'index']);


/**
 * Request for Assistance
 */

Route::post('/submit-request-for-assistance',[App\Http\Controllers\RequestForAssistance\RequestForAssistanceController::class,'store']);
Route::get('/autocomplete-fullname-beneficiary',[App\Http\Controllers\RequestForAssistance\AutoCompleteController::class,'autoCompleteNameBeneficiary']);
Route::get('/autocomplete-fullname-client',[App\Http\Controllers\RequestForAssistance\AutoCompleteController::class,'autoCompleteNameClient']);
Route::get('/check-client-assistance',[App\Http\Controllers\RequestForAssistance\ClientBeneficiaryAssistanceCheckerController::class,'checkClientAssistance']);
Route::get('/check-beneficiary-assistance',[App\Http\Controllers\RequestForAssistance\ClientBeneficiaryAssistanceCheckerController::class,'checkBeneficiaryAssistance']);
Route::get('/get-request-of-assistance-receipt/{id}',[App\Http\Controllers\RequestForAssistance\PrintReceiptController::class,'requestReceipt']);
/**
 * Approval of Assistance
 */

Route::get('/approval-of-assistance-table',[App\Http\Controllers\ApprovalOfAssistance\ApproveAssistanceTableController::class,'index']);
Route::patch('/approve-client/{id}', [App\Http\Controllers\ApprovalOfAssistance\ApproveDeclineClientAssistanceController::class,'approveClient']);
Route::patch('/decline-client/{id}', [App\Http\Controllers\ApprovalOfAssistance\ApproveDeclineClientAssistanceController::class,'declineClient']);
Route::get('/approve-client-details/{id}',[App\Http\Controllers\ApprovalOfAssistance\ApproveAssistanceTableController::class,'show']);
Route::patch('/update-approve-client/{id}',[App\Http\Controllers\ApprovalOfAssistance\ApproveDeclineClientAssistanceController::class,'updateApprovedClient']);


//Get image

Route::get('/get-client-approve-image',[App\Http\Controllers\ApprovalOfAssistance\ApproveClientBeneficiaryImageController::class,'showClientApproveImage']);
Route::get('/get-beneficiary-approve-image',[App\Http\Controllers\ApprovalOfAssistance\ApproveClientBeneficiaryImageController::class,'showBeneficiaryApproveImage']);

//Search Transaction Approve
Route::get('/search-client-approve', [App\Http\Controllers\ApprovalOfAssistance\SearchApproveController::class,'searchClient']);
Route::get('/search-transactionID-approve', [App\Http\Controllers\ApprovalOfAssistance\SearchApproveController::class,'searchTransactionID']);

/**
 * Bulk Approve Assistance
 */

 //Bulk Approve Assistance Table 
Route::get('/bulk-approval-of-assistance-table',[App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'bulkTransactionApproveTable']);

//Searching
Route::get('/search-client-bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'searchClient']);
Route::get('/search-transaction-date-bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'searchDateRequest']);
Route::get('/search-transaction-id-bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'searchTransactionID']);

 //Bulk Approve
Route::patch('/bulk-approve', [App\Http\Controllers\ApprovalOfAssistance\BulkApproveAssistanceTableController::class,'bulkApprove']);

/**
 * Transaction Claim
 */

 Route::get('/claiming-of-assistance-table',[App\Http\Controllers\ClaimingOfAssistance\ClaimAssistanceTableController::class,'index']);

 Route::get('/search-client-claim',[App\Http\Controllers\ClaimingOfAssistance\SearchClaimController::class,'searchClaimClient']);
 Route::get('/search-transaction-id-claim',[App\Http\Controllers\ClaimingOfAssistance\SearchClaimController::class,'searchTransactionID']);

 Route::get('/claim-client-details/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimAssistanceTableController::class,'show']);

 Route::get('/get-client-claim-image',[App\Http\Controllers\ClaimingOfAssistance\ClaimClientBeneficiaryImageController::class,'showClientClaimImage']);
 Route::get('/get-beneficiary-claim-image',[App\Http\Controllers\ClaimingOfAssistance\ClaimClientBeneficiaryImageController::class,'showBeneficiaryClaimImage']);

 Route::post('/claim-client/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimUnclaimClientAssistanceController::class,'claimClient']);
 Route::patch('/unclaim-client/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimUnclaimClientAssistanceController::class,'unclaimClient']);
 Route::patch('/update-claim-client/{id}',[App\Http\Controllers\ClaimingOfAssistance\ClaimUnclaimClientAssistanceController::class,'updateClaimedClient']);

 //Bulk Approve Assistance Table 
Route::get('/bulk-claiming-of-assistance-table',[App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'bulkTransactionClaimTable']);

//searching
Route::get('/search-client-bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'searchClient']);
Route::get('/search-transaction-id-bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'searchTransactionID']);
Route::get('/search-transaction-date-bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'searchDateRequest']);

 //Bulk Claim
 Route::patch('/bulk-claim', [App\Http\Controllers\ClaimingOfAssistance\BulkClaimAssistanceTableController::class,'bulkClaim']);

 /**
  * Client
  */

  //Client 
  Route::get('/get-client-information-table',[App\Http\Controllers\Client\ClientInformationTableController::class,'index']);
  Route::get('/search-client',[App\Http\Controllers\Client\ClientSearchController::class,'searchClient']);


  //show client
  Route::get('/get-client-details/{id}',[App\Http\Controllers\Client\ClientInformationTableController::class,'show']);

  //edit & update client
  Route::get('/get-client-details/{id}/edit',[App\Http\Controllers\Client\ClientInformationTableController::class,'edit']);
  Route::patch('/update-client-details/{id}',[App\Http\Controllers\Client\ClientInformationTableController::class,'update']);
  Route::get('/get-client-image',[App\Http\Controllers\Client\ClientImageController::class,'showClientImage']);
  
  //delete
  Route::delete('/delete-client-details/{id}',[App\Http\Controllers\Client\ClientInformationTableController::class,'destroy']);

  //client new transaction
  Route::get('/get-client-new-transaction-table',[App\Http\Controllers\Client\ClientNewTransactionController::class,'index']);
  
  //get client when new transaction for same as client
  Route::get('/get-client-new-transaction/{id}',[App\Http\Controllers\Client\ClientNewTransactionController::class,'create']);

  //submit new transaction
  Route::post('/new-transaction/{id}',[App\Http\Controllers\Client\ClientNewTransactionController::class,'store']);

  /**
   * Transaction 
   */
  Route::get('/client-transaction-table',[App\Http\Controllers\Transactions\ClientTransactionController::class,'index']);
  
  Route::get('/search-client-transaction',[App\Http\Controllers\Transactions\TransactionSearchController::class,'searchClient']);
  Route::get('/search-transaction-id-transaction',[App\Http\Controllers\Transactions\TransactionSearchController::class,'searchTransactionID']);
  Route::get('/get-client-transaction-details/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'show']);
  Route::get('/get-client-transaction-image',[App\Http\Controllers\Transactions\ClientBeneficiaryTransactionImageController::class,'showClientTransactionImage']);
  Route::get('/get-beneficiary-transaction-image',[App\Http\Controllers\Transactions\ClientBeneficiaryTransactionImageController::class,'showBeneficiaryTransactionImage']);
  Route::get('/get-client-transaction-beneficiary/{id}/beneficiary/{id2}/client/{id3}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'editBeneficiary']);
  
  Route::patch('/update-client-transaction-beneficiary/{id}/beneficiary/{id2}/client/{id3}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'updateBeneficiary']);
  Route::post('/add-client-transaction-beneficiary/{id}/client/{id2}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'addBeneficiary']);
  
  Route::patch('/update-client-transaction-assistance/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'updateAssistance']);
  Route::delete('/void-transaction/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'voidTransaction']);
  Route::delete('/delete-transaction/{id}',[App\Http\Controllers\Transactions\ClientTransactionController::class,'deleteTransaction']);
  
  //Transaction Report
  Route::get('/transaction-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'generateReport']);
  Route::get('/download-all-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'downloadAllReport']);
  Route::get('/download-claimed-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'downloadClaimedReport']);
  Route::get('/download-unclaimed-report',[App\Http\Controllers\Transactions\TransactionReportController::class,'downloadUnclaimedReport']);


  //Bulk Printing
  Route::get('/bulk-printing-of-receipt-client',[App\Http\Controllers\Transactions\BulkPrintingOfReceiptController::class,'searchClient']);
  Route::get('/bulk-printing-of-receipt-transaction-id',[App\Http\Controllers\Transactions\BulkPrintingOfReceiptController::class,'searchTransactionID']);
  Route::get('/bulk-printing-of-receipt-date',[App\Http\Controllers\Transactions\BulkPrintingOfReceiptController::class,'searchDate']);



?>