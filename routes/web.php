<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SummaryReportController;
use App\Http\Controllers\CreatecampaignController;
use App\Http\Controllers\Campaignlist_Controller;
use App\Http\Controllers\Approvecampaign_Controller;
use App\Http\Controllers\Creditmanagement_Controller;
use App\Http\Controllers\GsmBoard_Controller;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Contextcreate_Controller;
use App\Http\Controllers\Cdrgeneration_Controller;
use App\Http\Controllers\Contextlist_Controller;
use App\Http\Controllers\Ivr_Approve_Controller;
use App\Http\Controllers\XlsxtocsvController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return  View('auth.login');
});


Route::post('/logout-form', [LogoutController::class, 'logout'])->name('logout-form');

Route::middleware(['auth.check'])->group(function () {

Route::post('file-import', [CreatecampaignController::class, 'sendMessage'])->name('file-import');
Route::get('file-export', [CreatecampaignController::class, 'fileExport'])->name('file-export');
Route::post('/save-received-message', [CreatecampaignController::class, 'saveReceivedMessage'])->name('save-received-message');

Route::get('/cancel', [CreatecampaignController::class, 'cancel'])->name('cancel');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/land', [LandController::class, 'land'])->name('land');

Route::get('/get_download_url', [ReportController::class, 'get_download_url'])->name('get_download_url');

Route::get('/summaryreport', [ReportController::class, 'summaryReport'])->name('summaryreport');

Route::get('/summary_report', [SummaryReportController::class, 'summary_Report'])->name('summary_report');

Route::get('/detailreport', [ReportController::class, 'detailReport'])->name('detailreport');

Route::get('/createcampaign', [CreatecampaignController::class, 'createCampaign'])->name('createcampaign');

Route::get('/get_context', [CreatecampaignController::class, 'get_context'])->name('get_context');

Route::get('/get_audio_by_context', [CreatecampaignController::class, 'get_audio_by_context'])->name('get_audio_by_context');

Route::get('/get_location', [Contextcreate_Controller::class, 'get_location'])->name('get_location');

Route::get('/get_language', [Contextcreate_Controller::class, 'get_language'])->name('get_language');

Route::get('/campaign_list', [Campaignlist_Controller::class, 'campaign_list'])->name('campaign_list');    

Route::get('/context_list', [Contextlist_Controller::class, 'context_list'])->name('context_list');

Route::get('/ivr_approve', [Ivr_Approve_Controller::class, 'IvrApprove'])->name('ivr_approve');

Route::post('/approve-ivr', [Ivr_Approve_Controller::class, 'ApproveIVR']);

Route::post('/decline-ivr', [Ivr_Approve_Controller::class, 'DeclineIVR']);

Route::get('/approve_campaign', [Approvecampaign_Controller::class, 'approve_campaign_list'])->name('approve_campaign');

Route::post('/stop_campaign', [Campaignlist_Controller::class, 'stop_campaign'])->name('stop_campaign');

Route::post('/restart_campaign', [Campaignlist_Controller::class, 'restart_campaign'])->name('restart_campaign');

Route::get('neron_details', [Campaignlist_Controller::class, 'neron_details'])->name('neron_details');

Route::get('/context_create', [Contextcreate_Controller::class, 'context_create'])->name('context_create');

Route::post('/prompt_create', [Contextcreate_Controller::class, 'prompt_create'])->name('prompt_create');

Route::get('/cdr_generation', [Cdrgeneration_Controller::class, 'cdr_generation'])->name('cdr_generation');

//Route::match(['get, 'post'], '/cdrs_generation', [Cdrgeneration_Controller::class, 'cdrs_generation'])->name('cdrs_generation');
Route::match(['get', 'post'], '/cdrs_generation', [Cdrgeneration_Controller::class, 'cdrs_generation'])->name('cdrs_generation');


Route::get('/get_user', [Cdrgeneration_Controller::class, 'get_user'])->name('get_user');

Route::get('/get_campaigns', [Cdrgeneration_Controller::class, 'get_campaigns'])->name('get_campaigns');

Route::post('/check_context', [Contextcreate_Controller::class, 'check_context'])->name('check_context');

Route::get('/get_sender_count', [Approvecampaign_Controller::class, 'get_sender_count'])->name('get_sender_count');

Route::get('/get_sender_id', [Approvecampaign_Controller::class, 'get_sender_id'])->name('get_sender_id');

Route::post('/decline_campaign', [Approvecampaign_Controller::class, 'decline_campaign'])->name('decline_campaign');

Route::post('/approve_campaign_send', [Approvecampaign_Controller::class, 'approve_campaign_send'])->name('approve_campaign_send');

Route::post('/update-flag', [CreatecampaignController::class, 'updateFlag']);
 WebSocketsRouter::webSocket('/', \App\WebSockets\SocketHandler\updatePostSocketHandler::class);

Route::post('/process-mobile-numbers', [MessageController::class, 'processMobileNumbers']);

Route::get('/gsm_board', [GsmBoard_Controller::class, 'gsm_board'])->name('gsm_board');

Route::get('/campaign_details', [GsmBoard_Controller::class, 'campaign_details'])->name('campaign_details');

Route::post('/add_board_name', [GsmBoard_Controller::class, 'add_board_name'])->name('add_board_name');

Route::get('/channel_status', [GsmBoard_Controller::class, 'channel_status'])->name('channel_status');

Route::get('/credit_management', [Creditmanagement_Controller::class, 'credit_management'])->name('credit_management');

Route::post('/add_credit', [Creditmanagement_Controller::class, 'add_credit'])->name('add_credit');

Route::post('/process-xlsx-to-csv', [XlsxtocsvController::class, 'processXlsxToCsv'])->name('process-xlsx-to-csv');

Route::post('/exportasCSV', [ReportController::class, 'exportasCSV'])->name('exportasCSV');

Route::post('/pdf-export', [ReportController::class, 'exportasPDF'])->name('exportasPDF');

Route::get('/download/{fileName}', [ReportController::class, 'download'])->name('file.download');

});

Route::get('/forgot-password', [VerificationController::class, 'forgotpassword'])->name('forgot-password');

Route::post('/send-otp', [VerificationController::class, 'sendOTP'])->name('send-otp');

Route::post('/verify-otp', [VerificationController::class, 'verifyOtp'])->name('verify-otp');


Route::get('/password-reset/{email}', [ResetPasswordController::class, 'PasswordReset'])->name('password-reset');
Route::post('/pass-reset', [ResetPasswordController::class, 'resetPassword'])->name('pass-reset');


Auth::routes();
