<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CronjobController;

use App\Http\Controllers\Advertiser  as Advertiser;
use App\Http\Controllers\Publisher as Publisher;
use App\Http\Controllers\Admin as Admin;


use App\Services\TelegramPush;
use App\Services\WeeklyReportGenerate;
use App\Services\PaymentAutoPay;
use App\Services\CampaignStatusUpdate;

use App\Models\CampaignPublishGroup;
use App\Models\PublisherReport;
use App\Models\CampaignReport;
use Secureweb\Socialmarketing\Models\Campaignmessage;
use Illuminate\Http\Request;

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
	return redirect()->route('login');
});

Route::get('/check-info', function () {
	echo "<pre>";
	// new WeeklyReportGenerate();
	// new TelegramPush();
	// new CampaignStatusUpdate();
});
Route::get('/trancate', function () {
	exit;
	CampaignPublishGroup::truncate();
	// Campaignmessage::truncate();
	// CampaignReport::truncate();
	// PublisherReport::truncate();
});

Route::get('/test', [TestController::class,'index'])->name('index');

Route::get('register', function () { return view('auth.register'); });

Route::post('register', [UserController::class,'register'])->name('register');
Route::post('login', [UserController::class,'login'])->name('login');
Route::get('verify-email/{token}', [UserController::class,'verify_email'])->name('verify-email');

Route::get('telegram/{trackingid}/{utmf}/{publisher_id}/{telegram_group_id}/{unique}',[UserController::class,'telegrm_track'])->name('telegrm_track');

Route::get('/dashboard', [HomeController::class,'index'])->middleware(['auth']);
Route::get('/logout',  [HomeController::class,'logout'])->name('logout');
Route::get('/logoutauto',  [HomeController::class,'logoutauto'])->name('logoutauto');

/* Support */
Route::post('/support',[UserController::class,'send_support_email']);
// Route::get('/support',[UserController::class,'send_support_email_get']);

Route::post('/update_notification',[UserController::class,'update_notification']);
Route::post('/update_notification_admin',[UserController::class,'update_notification_admin']);



Route::any('/autologin', [UserController::class,'loginfromMarketplace'])->name('autologin');

Route::get('/csv/{uuid}/download', [HomeController::class,'download'])->name('csv.download');

// Route::get('push-telegram', [CronjobController::class,'push_telegram']);
// Route::get('delete-telegram', [CronjobController::class,'delete_telegram']);


/*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {

	Route::get('/dashboard', [Admin\AdminController::class,'index'])->name('index');
	Route::get('/settings', [Admin\SettingsController::class,'index'])->name('index');
	Route::post('/settings/save', [Admin\SettingsController::class,'save'])->name('settings.save');
	Route::get('/settings/email', [Admin\SettingsController::class,'save_email_view'])->name('settings.email');
	Route::post('/settings/save/email', [Admin\SettingsController::class,'save_email'])->name('settings.save.email');
	/*Route::get('/settings/hours', [Admin\SettingsController::class,'save_hours_view'])->name('settings.hours');*/
	Route::post('/settings/save/hours', [Admin\SettingsController::class,'save_hours'])->name('settings.save.hours');
	Route::get('/settings/publisherpay', [Admin\SettingsController::class,'publisherpay_view'])->name('settings.publisherpay');
	Route::post('/settings/publisherpay', [Admin\SettingsController::class,'publisherpay'])->name('settings.publisherpay');
	Route::post('/update-campaign',[Admin\CampaignController::class,'campaign_approvel']);
	Route::post('/campaign-view',[Admin\CampaignController::class,'campaign_view']);
	Route::post('/user/save',[Admin\AdminUserController::class,'save'])->name('user.save');
	Route::post('/user/update',[Admin\AdminUserController::class,'update'])->name('user.update');
	Route::get('/user/delete/{id}',[Admin\AdminUserController::class,'delete'])->name('user.destroy');
	Route::get('/advertisers/trash',[Admin\AdminAdvertiserController::class,'trash'])->name('advertisers.trash');
	Route::get('/advertisers/restore/{id}',[Admin\AdminAdvertiserController::class,'restore'])->name('advertisers.restore');
	Route::get('/advertisers/funds',[Admin\AdminAdvertiserController::class,'funds_view'])->name('advertisers.funds');
	Route::post('/advertisers/funds',[Admin\AdminAdvertiserController::class,'funds_add'])->name('advertisers.funds');
	Route::get('/advertisers/campaigns/{id}',[Admin\AdminAdvertiserController::class,'campaigns'])->name('advertisers.campaigns');
	Route::get('/publishers/trash',[Admin\AdminPublisherController::class,'trash'])->name('publishers.trash');
	Route::get('/publishers/restore/{id}',[Admin\AdminPublisherController::class,'restore'])->name('publishers.restore');
	Route::get('/publishers/groups',[Admin\AdminPublisherController::class,'all_groups'])->name('publishers.groups');
	Route::post('/publishers/groupStatus',[Admin\AdminPublisherController::class,'group_status_update']);
	Route::get('/publishers/delete/{id}',[Admin\AdminPublisherController::class,'groups_delete'])->name('publishers.delete');
	Route::get('/publishers/reports/{id}',[Admin\AdminPublisherController::class,'reports'])->name('publishers.reports');
	Route::get('/publishers/payments/{id}',[Admin\AdminPublisherController::class,'payments'])->name('publishers.payments');
	Route::get('/export',[Admin\ImportExportController::class,'ecport_view'])->name('export.csv');
	Route::post('/export',[Admin\ImportExportController::class,'ecport_csv_file'])->name('export.csv');
	Route::get('/import',[Admin\ImportExportController::class,'import_csv_file'])->name('import.csv');
	Route::post('/import',[Admin\ImportExportController::class,'import_publisher_csv'])->name('import.report.csv');
	Route::get('/markpaid/{id}',[Admin\ImportExportController::class,'mark_paid'])->name('markpaid');
	Route::get('/campaigns/approvedlist',[Admin\CampaignController::class,'approved_list'])->name('campaign.approvelist');
	Route::get('/campaigns/pendinglist',[Admin\CampaignController::class,'pending_list'])->name('campaign.pendinglist');

	Route::get('/tiers/trash',[Admin\TierController::class,'tiers_trash'])->name('tiers.trash');
	Route::get('/tiers/restore/{id}',[Admin\TierController::class,'tiers_restore'])->name('tiers.restore');
	Route::delete('/tiers/trash/delete/{id}',[Admin\TierController::class,'tiers_trash_delete'])->name('tiers.trash.delete');

	Route::resources([
		'campaigns' 	=> Admin\CampaignController::class,
		'advertisers' 	=> Admin\AdminAdvertiserController::class,
		'publishers' 	=> Admin\AdminPublisherController::class,
		'tiers'			=> Admin\TierController::class
	]);
});

/*
|--------------------------------------------------------------------------
| Publisher Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('publisher')->name('publisher.')->middleware(['isPublisher','publisherTelegram'])->group(function () {

	Route::get('/dashboard', [Publisher\PublisherController::class,'index'])->name('index');
	Route::get('/bot-setup', [Publisher\PublisherController::class,'bot_setup_instruction'])->name('bot.setup');
	Route::get('/reports', [Publisher\ReportsController::class,'index'])->name('index');
	Route::post('/reports', [Publisher\ReportsController::class,'reports_data'])->name('reports.data');
	Route::get('/campiagns', [Publisher\CampaignController::class,'index'])->name('index');
	Route::get('/payments', [Publisher\PaymentsController::class,'index'])->name('index');

	Route::get('/settings/telegram-group', [Publisher\SettingsController::class,'telegram_group_view'])->name('settings.telegram.group');
	Route::get('/settings/telegram-add', [Publisher\SettingsController::class,'telegram_group_add_view'])->name('settings.telegram.add');
	Route::post('/settings/telegram-group', [Publisher\SettingsController::class,'telegram_group'])->name('settings.telegram.group');	
	Route::post('/settings/telegram-update', [Publisher\SettingsController::class,'telegram_group_update'])->name('settings.telegram.update');
	Route::post('/settings/telegram-updatefreq', [Publisher\SettingsController::class,'updatefreq'])->name('settings.telegram.updatefreq');
	Route::get('/settings/telegram-edit/{id}', [Publisher\SettingsController::class,'telegram_group_edit_view'])->name('settings.telegram.edit');
	Route::get('/settings/telegram-delete/{id}', [Publisher\SettingsController::class,'telegram_group_delete'])->name('settings.telegram.delete');

	Route::get('/settings/payment-update', [Publisher\SettingsController::class,'payment_update'])->name('settings.telegram.update');
	Route::get('/settings/payment-locked', [Publisher\SettingsController::class,'payment_locked'])->name('settings.telegram.update');

	Route::resources([
		'profile' => Publisher\ProfileController::class,
		'settings' => Publisher\SettingsController::class,
	]);

});

/*
|--------------------------------------------------------------------------
| Advertiser Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('advertiser/funds/webhook', [Advertiser\FundsController::class,'payment_webhook_responce']);
Route::post('advertiser/funds/webhooks', [Advertiser\FundsController::class,'payment_webhook_responce']);

Route::prefix('advertiser')->name('advertiser.')->middleware(['isAdvertiser','auth'])->group(function () {

	Route::get('/dashboard', [Advertiser\AdvertiserController::class,'index'])->name('index');
	Route::get('/campaigns', [Advertiser\CampaignController::class,'index'])->name('campiagns');
	Route::get('/campaigns/create', [Advertiser\CampaignController::class,'add_campaign_view'])->name('campiagns.create');
	Route::post('/campaigns/create', [Advertiser\CampaignController::class,'add_campaign'])->name('campaigns.create');
	Route::get('/campaigns/funds/add', [Advertiser\CampaignController::class,'add_campaign_funds_view'])->name('campaigns.funds.add');
	Route::post('/campaigns/funds/add', [Advertiser\CampaignController::class,'add_campaign_funds'])->name('campaigns.funds.add');

	Route::get('/campaigns/edit/{id}', [Advertiser\CampaignController::class,'edit'])->name('campaigns.edit');
	Route::post('/campaigns/update', [Advertiser\CampaignController::class,'update_campaign'])->name('campaigns.update');
	Route::get('/campaigns/delete/{id}', [Advertiser\CampaignController::class,'delete'])->name('campaigns.delete');
	Route::get('/campaigns/trash', [Advertiser\CampaignController::class,'trash_campaign'])->name('campaigns.trash');
	Route::get('/campaigns/restore/{id}', [Advertiser\CampaignController::class,'restore_campaign'])->name('campaigns.restore');
	Route::get('/campaigns/deletepermanent/{id}', [Advertiser\CampaignController::class,'delete_campaign_permanent'])->name('campaigns.deletepermanent');	
	Route::post('/remove-a', [Advertiser\CampaignController::class,'remove_anchar_tag'])->name('remove.a');
	Route::post('/update-campaign',[Advertiser\CampaignController::class,'campaign_status_update']);	
	Route::post('/get-campaign-clicks',[Advertiser\AdvertiserController::class,'get_camapign_clicks_by_date']);
	Route::get('/funds/success',[Advertiser\FundsController::class,'funds_success']);
	Route::get('/funds/cancle',[Advertiser\FundsController::class,'funds_cancle']);

	Route::post('/tire-cpc', [Advertiser\CampaignController::class,'tire_cpc'])->name('tire.cpc');
	
	Route::resources([
		'funds' => Advertiser\FundsController::class,
		'profile' => Advertiser\ProfileController::class,
	]);

	// Route::get('/campaigns/list', [CampaignController::class,'list'])->name('campaigns.list');
});
