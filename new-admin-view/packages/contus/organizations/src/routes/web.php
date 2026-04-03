<?php



use Contus\Organizations\Http\Controllers\OrgPaymentSerivceController;
use Illuminate\Support\Facades\Route;
use Contus\Organizations\Http\Controllers\AnnouncmentController;
use Contus\Organizations\Http\Controllers\NotificationController;
use Contus\Organizations\Http\Controllers\OrganizationController;
use Contus\Organizations\Http\Controllers\ContetntSetsController;
use Contus\Organizations\Http\Controllers\ShoppingCartController;
use Contus\Organizations\Http\Controllers\AddSubscriberController;
use Contus\Organizations\Http\Controllers\AppCustomizationController;
use Contus\Organizations\Http\Controllers\MonetizationPlanController;
use Contus\Organizations\Http\Controllers\GeneralOrganizationsController;
use Contus\Organizations\Http\Controllers\PartnerProductController;

Route::prefix('admin')->namespace('Contus\Customer\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::get('organizations/gridlist', [OrganizationController::class, 'getGridlist']);

        // ==========***********==========
        // organization setting code 
        // ==========***********==========
        Route::get('general/details', [GeneralOrganizationsController::class, 'showdetails']);
        Route::post('fetchorgname/organizations/records', [GeneralOrganizationsController::class, 'searchOrganizations'])->name('orgname.organizations.records');
        Route::post('/organization/clone/{id}', [GeneralOrganizationsController::class, 'clone'])->name('organization.clone');
        Route::delete('/organization/destroy', [GeneralOrganizationsController::class, 'destroy'])->name('organizations.destroy');
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // add subscriber
        // ==========***********==========
        Route::get('add-subscribers', [AddSubscriberController::class, 'index']);
        Route::get('view-subscribers', [AddSubscriberController::class, 'view']);
        Route::get('org/view-subscribers/gridlist', [AddSubscriberController::class, 'getGridlist']);
        Route::get('devices', [AddSubscriberController::class, 'deviceIndex']);
        Route::get('devices/gridlist', [AddSubscriberController::class, 'deviceGridList']);
        // ==========***********==========
        // ==========***********==========

        // Route::get('activation', [SubscriberIndexController::class, 'activationIndex']);
        // Route::get('devices/gridlist', [SubscriberIndexController::class, 'deviceGridList']);

        // ==========***********==========
        // organization content sets
        // ==========***********==========
        Route::get('contentset', [ContetntSetsController::class, 'showdetails']);
        Route::get('channel/content-set/gridlist', [ContetntSetsController::class, 'ChannelGridlist']);
        Route::get('add/channel/content-set', [ContetntSetsController::class, 'addChannelContetnt']);
        Route::get('channel/content-set/edit', [ContetntSetsController::class, 'chnledit']);
        Route::get('channel/content-set/view', [ContetntSetsController::class, 'chnlview']);
        // Route::get('channel/content-set', [ContetntSetsController::class, 'ChannelDetails']);

        Route::get('live-event/content-set', [ContetntSetsController::class, 'LiveEventDetails']);
        Route::get('live-event/content-set/gridlist', [ContetntSetsController::class, 'LiveGridlist']);
        Route::get('add/live-event/content-set', [ContetntSetsController::class, 'addLiveEventContetnt']);
        Route::get('live-event/content-set/edit', [ContetntSetsController::class, 'liveedit']);
        Route::get('live-event/content-set/view', [ContetntSetsController::class, 'liveview']);

        Route::get('vod/content-set', [ContetntSetsController::class, 'VodDetails']);
        Route::get('vod/content-set/gridlist', [ContetntSetsController::class, 'VodGridlist']);
        Route::get('add/vod/content-set', [ContetntSetsController::class, 'addVodContetnt']);
        Route::get('vod/content-set/edit', [ContetntSetsController::class, 'vodedit']);
        Route::get('vod/content-set/view', [ContetntSetsController::class, 'vodview']);

        Route::get('tv-show/content-set', [ContetntSetsController::class, 'TvShowDetails']);
        Route::get('tv-show/content-set/gridlist', [ContetntSetsController::class, 'TvShowGridlist']);
        Route::get('add/tv-show/content-set', [ContetntSetsController::class, 'addTvShowContetnt']);
        Route::get('tv-show/content-set/edit', [ContetntSetsController::class, 'tvshowedit']);
        Route::get('tv-show/content-set/view', [ContetntSetsController::class, 'tvshowview']);
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // patner Product
        // ==========***********==========
        Route::get('partner-product', [PartnerProductController::class, 'index']);
        Route::get('organizations/partner-product/gridlist', [PartnerProductController::class, 'PpGridList']);
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // monetization plan
        // ==========***********==========
        Route::get('monetization-plan/subscription', [MonetizationPlanController::class, 'Index']);
        Route::get('monetization-plan/subscription/gridlist', [MonetizationPlanController::class, 'subscrGridList']);
        Route::get('monitization-plan/subscription/add', [MonetizationPlanController::class, 'addSubscription']);
        Route::get('monitization-plan/subscription/edit/{id}', [MonetizationPlanController::class, 'editSubscription']);

        Route::get('monetization-plan/accessories', [MonetizationPlanController::class, 'AccIndex']);
        Route::get('organizations/monetization-plan/accessories/gridlist', [MonetizationPlanController::class, 'AccGridList']);
        // ==========***********==========
        // ==========***********==========

        //========================================= Announcment & Reminders START =========================================//

        // announcement
        Route::get('announcment', [AnnouncmentController::class, 'index'])->name('announce.index');
        Route::get('announcment/gridlist', [AnnouncmentController::class, 'getGridlist']);
        Route::get('announcment/add', [AnnouncmentController::class, 'addAnnouncement']);

        // announcement reminders
        Route::get('reminders', [AnnouncmentController::class, 'reminderIndex'])->name('reminders.index');
        Route::get('reminders/gridlist', [AnnouncmentController::class, 'getRemindersGridlist']);
        Route::get('reminders/add', [AnnouncmentController::class, 'addReminders']);

        // announcement activation
        Route::get('push-notifications', [AnnouncmentController::class, 'notificationIndex'])->name('notification.index');
        Route::get('push-notifications/gridlist', [AnnouncmentController::class, 'getNotificationsGridlist']);
        Route::get('push-notifications/add', [AnnouncmentController::class, 'addNotifications']);

        // activation
        Route::get('activation/add', [AnnouncmentController::class, 'addActivations'])->name('activation.create');

        // disabled accounts
        Route::get('disabled-accounts/add', [AnnouncmentController::class, 'addDisabledAccounts'])->name('accounts.create');

        //========================================= Announcment & Reminders END =========================================//


        // ==========***********==========
        // app customization
        // ==========***********==========
        // banner_carousels
        Route::get('app-customization/promotion/banner_carousels', [AppCustomizationController::class, 'showdetails']);
        Route::get('org/app-customization/promotion/banner_carousels/gridlist', [AppCustomizationController::class, 'bnrGridList']);
        Route::get('app-customization/banner_carousels/add', [AppCustomizationController::class, 'bnrAdd']);

        // banner_carousels_subscription
        Route::get('app-customization/banner_carousels_subscription', [AppCustomizationController::class, 'carSubIndex']);
        Route::get('app-customization/banner_carousels_subscription/gridlist', [AppCustomizationController::class, 'carSubGrid']);
        Route::get('app-customization/banner_carousels_subscription/add', [AppCustomizationController::class, 'carSubAdd']);
        Route::get('app-customization/banner_carousels_subscription/edit/{id}', [AppCustomizationController::class, 'carSubEdit']);

        // Featured Rows
        Route::get('app-customization/promotion/features-row', [AppCustomizationController::class, 'featureIndex']);
        Route::get('org/app-customization/promotion/features-row/gridlist', [AppCustomizationController::class, 'featuregridList']);

        // row order
        Route::get('app-customization/promotion/row-order', [AppCustomizationController::class, 'RowIndex']);
        Route::get('org/app-customization/promotion/row-order/gridlist', [AppCustomizationController::class, 'RowGridList']);
        Route::get('app-customization/promotion/row-order/add', [AppCustomizationController::class, 'RowAdd']);
        Route::get('app-customization/promotion/row-order/view', [AppCustomizationController::class, 'RowView']);

        // general
        Route::get('app-customization/general', [AppCustomizationController::class, 'GenIndex']);
        Route::get('org/app-customization/general/gridlist', [AppCustomizationController::class, 'GenGridList']);

        // setting
        Route::get('app-customization/setting', [AppCustomizationController::class, 'SetIndex']);
        Route::get('org/app-customization/setting/gridlist', [AppCustomizationController::class, 'SetGridList']);
        Route::get('app-customization/setting/add', [AppCustomizationController::class, 'SetAdd']);
        Route::get('app-customization/setting/edit/{id}', [AppCustomizationController::class, 'SetEdit']);

        // channel
        Route::get('app-customization/channel-listing', [AppCustomizationController::class, 'ChnlIndex']);
        Route::get('org/app-customization/channel-listing/gridlist', [AppCustomizationController::class, 'ChnlGridList']);
        Route::get('app-customization/channel-listing/add/{id}', [AppCustomizationController::class, 'ChnlAdd']);
        Route::get('app-customization/channel-listing/view', [AppCustomizationController::class, 'ChnlEdit']);
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // payment service
        // ==========***********==========
        Route::get('organizations/payment-service', [OrgPaymentSerivceController::class, 'index']);
        Route::get('organizations/payment-service/gridlist', [OrgPaymentSerivceController::class, 'GridView']);

        Route::get('organizations/payment-service/currency', [OrgPaymentSerivceController::class, 'currencyIndex']);
        Route::get('organizations/payment-service/currency/gridlist', [OrgPaymentSerivceController::class, 'currencyGridView']);

        Route::get('organizations/payment-service/currency-converter', [OrgPaymentSerivceController::class, 'converterIndex']);
        Route::get('organizations/payment-service/currency-converter/gridlist', [OrgPaymentSerivceController::class, 'converterGridView']);
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // shopping cart code
        // ==========***********==========
        Route::get('shoppingcart', [ShoppingCartController::class, 'showdetails']);
        Route::get('shoppingcart/gridlist', [ShoppingCartController::class, 'getGridlist']);
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // notification code
        // ==========***********==========
        Route::get('announcment/notification/info', [NotificationController::class, 'showdetails'])->name('announcment.notification.info');
        Route::get('announcment/notification/info/gridlist', [NotificationController::class, 'getGridlist']);
        // ==========***********==========
        // ==========***********==========
    });
});
