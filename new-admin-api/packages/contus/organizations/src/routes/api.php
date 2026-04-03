<?php

use Contus\Organizations\Api\Controller\AddsubscribersController;
use Contus\Organizations\Api\Controller\AnnouncementReminders\AncPushNotificationController;
use Contus\Organizations\Api\Controller\AnnouncementReminders\AncActivationController;
use Contus\Organizations\Api\Controller\AnnouncementReminders\AncDisableAccController;
use Contus\Organizations\Api\Controller\AnnouncementReminders\AncRemindersController;
use Contus\Organizations\Api\Controller\AnnouncementReminders\AnnouncmentController;
use Contus\Organizations\Api\Controller\AnnouncmentNotificationController;
use Contus\Organizations\Api\Controller\AppCustomization\AppCustomiztionBannerCarouselController;
use Contus\Organizations\Api\Controller\AppCustomization\AppCustomiztionFeaturedRow;
use Contus\Organizations\Api\Controller\AppCustomization\BannerCarouselsSubscription;
use Contus\Organizations\Api\Controller\AppCustomization\ChannelListingController;
use Contus\Organizations\Api\Controller\AppCustomization\RowOrderController;
use Contus\Organizations\Api\Controller\AppCustomizationController;
use Contus\Organizations\Api\Controller\ChannelContentSetController;
use Contus\Organizations\Api\Controller\LiveEventContentSetController;
use Contus\Organizations\Api\Controller\MonetizationPlanController;
use Contus\Organizations\Api\Controller\OrganizationPaymentController;
use Contus\Organizations\Api\Controller\OrganizationsAccessoriesController;
use Contus\Organizations\Api\Controller\OrganizationsController;
use Contus\Organizations\Api\Controller\GeneralOrganizationsController;
use Contus\Organizations\Api\Controller\MonetizationPlanssController;
use Contus\Organizations\Api\Controller\OrganizationSetting;
use Contus\Organizations\Api\Controller\PartnerProductController;
use Contus\Organizations\Api\Controller\PaymentServices\PaymentServiceController;
use Contus\Organizations\Api\Controller\PaymentServices\PaymentServiceCurrencyController;
use Contus\Organizations\Api\Controller\PaymentServices\PaymentServiceCurrencyConverterController;
use Contus\Organizations\Api\Controller\ShoppingcartController;
use Contus\Organizations\Api\Controller\TvShowContentSetController;
use Contus\Organizations\Api\Controller\VodContentSetController;
use Contus\Organizations\Repositories\AnnouncementReminders\AnnouncementReminderRepository;
use Contus\Organizations\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->namespace('Contus\Organizations\Api\Controller')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('organizations/info', [OrganizationsController::class, 'getInfo']);
            Route::post('organizations/add', [OrganizationsController::class, 'postAdd']);
            Route::post('organizations/records', [OrganizationsController::class, 'postRecords']);
            Route::post('/search-organizations', [OrganizationsController::class, 'search'])->name('organizations.search');

            // organization setting code
            Route::get('organization/detail/info', [GeneralOrganizationsController::class, 'getInfo']);
            Route::post('organization/general/setting/add', [GeneralOrganizationsController::class, 'postAdd']);
            Route::post('organizations/logo/upload', [GeneralOrganizationsController::class, 'postThumbnail']);
            Route::post('organization/setting/add', [GeneralOrganizationsController::class, 'postAddSetting']);
            Route::post('organizations/general/settingrecords/records', [GeneralOrganizationsController::class, 'postRecords']);

            // content set
            Route::get('channel/content-set/info', [ChannelContentSetController::class, 'getInfo']);
            Route::post('channel/content-set/save', [ChannelContentSetController::class, 'postAdd']);
            Route::post('channel/content-set/action', [ChannelContentSetController::class, 'postAction']);
            Route::post('channel/content-set/poster', [ChannelContentSetController::class, 'postPosters']);
            Route::post('channel/content-set/update/{id}', [ChannelContentSetController::class, 'postEdit']);
            Route::post('channel/content-set/records', [ChannelContentSetController::class, 'postRecords']);

            Route::get('live-event/content-set/info', [LiveEventContentSetController::class, 'getInfo']);
            Route::post('live-event/content-set/save', [LiveEventContentSetController::class, 'postAdd']);
            Route::post('live-event/content-set/action', [LiveEventContentSetController::class, 'postAction']);
            Route::post('live-event/content-set/poster', [LiveEventContentSetController::class, 'postPosters']);
            Route::post('live-event/content-set/update/{id}', [LiveEventContentSetController::class, 'postEdit']);
            Route::post('live-event/content-set/records', [LiveEventContentSetController::class, 'postRecords']);

            Route::get('vod/content-set/info', [VodContentSetController::class, 'getInfo']);
            Route::post('vod/content-set/save', [VodContentSetController::class, 'postAdd']);
            Route::post('vod/content-set/action', [VodContentSetController::class, 'postAction']);
            Route::post('vod/content-set/poster', [VodContentSetController::class, 'postPosters']);
            Route::post('vod/content-set/update/{id}', [VodContentSetController::class, 'postEdit']);
            Route::post('vod/content-set/fetch/records', [VodContentSetController::class, 'fetchRecords']);
            Route::post('vod/content-set/records', [VodContentSetController::class, 'postRecords']);

            Route::get('tv-show/content-set/info', [TvShowContentSetController::class, 'getInfo']);
            Route::post('tv-show/content-set/save', [TvShowContentSetController::class, 'postAdd']);
            Route::post('tv-show/content-set/action', [TvShowContentSetController::class, 'postAction']);
            Route::post('tv-show/content-set/poster', [TvShowContentSetController::class, 'postPosters']);
            Route::post('tv-show/content-set/update/{id}', [TvShowContentSetController::class, 'postEdit']);
            // Route::post('tv-show/content-set/fetch/records', [TvShowContentSetController::class, 'fetchRecords']);
            Route::post('tv-show/content-set/records', [TvShowContentSetController::class, 'postRecords']);

            //========================================= Monetization Plan START =========================================//

            // monetizationplan code
            Route::get('monetization-plan/subscription/info', [MonetizationPlanController::class, 'getInfo']);
            // Route::post('organization/monetizationplan/records', [MonetizationPlanController::class, 'postRecords']);

            Route::post('organization/monetizationplanss/records', [MonetizationPlanssController::class, 'postRecords']);
            Route::post('organization/monetizationplanss/add', [MonetizationPlanssController::class, 'postadd']);
            Route::post('organization/monetizationplanss/edit/{id}', [MonetizationPlanssController::class, 'postEdit']);
            Route::post('organization/monetizationplanss/action', [MonetizationPlanssController::class, 'postAction']);

            Route::get('organization/monetization-plan/accessories/info', [OrganizationsAccessoriesController::class, 'getInfo']);
            Route::post('organization/monetization-plan/accessories/records', [OrganizationsAccessoriesController::class, 'postRecords']);
            Route::post('organization/monetization-plan/accessories/create', [OrganizationsAccessoriesController::class, 'postAdd']);
            Route::post('organization/monitization-plan/accessories/edit/{id}', [OrganizationsAccessoriesController::class, 'postEdit']);
            Route::post('monitization-plan/accessories/toggle-edit/{id}', [OrganizationsAccessoriesController::class, 'toggleEdit']);
            Route::post('organization/monetization-plan/accessories/action', [OrganizationsAccessoriesController::class, 'postAction']);

            Route::post('organization/monetizationplanss/toggle-publish-now/{id}', [MonetizationPlanssController::class, 'togglePublishNow']);

            //========================================= Monetization Plan END =========================================//

            // payment code
            Route::post('organization/payment/create', [OrganizationPaymentController::class, 'storepayment']);
            Route::post('organization/payment/failure', [OrganizationPaymentController::class, 'failurepayment']);
            Route::post('organization/payment/records', [OrganizationPaymentController::class, 'postRecords']);

            // app customization code
            Route::get('organization/customization/info', [AppCustomizationController::class, 'getInfo']);
            Route::post('organization/customization/add', [AppCustomizationController::class, 'postAdd']);
            Route::post('organization/customization/records', [AppCustomizationController::class, 'postRecords']);


            //========================================= Announcment & Reminders START =========================================//
            // announcment
            Route::get('announcment/info', [AnnouncmentController::class, 'getInfo']);
            Route::post('announcment/add', [AnnouncmentController::class, 'postAdd']);
            Route::post('announcment/records', [AnnouncmentController::class, 'postRecords']);

            // announcment reminders
            Route::get('announcment/reminders/info', [AncRemindersController::class, 'getInfo']);
            Route::post('announcment/reminders/add', [AncRemindersController::class, 'postAdd']);
            Route::post('reminders/records', [AncRemindersController::class, 'postRecords']);
            Route::post('announcment/reminders/destroy/{id}', [AncRemindersController::class, 'postDestroy']);
            Route::post('announcement/reminders/status-update', [AncRemindersController::class, 'postStatusUpdate']);

            // announcment activation TOA
            Route::post('announcment/activation/add', [AncActivationController::class, 'postAdd']);

            // announcment disabled account
            Route::post('announcment/disabled-account/add', [AncDisableAccController::class, 'postAdd']);

            // announcment push notifications
            Route::get('announcment/push-notifications/info', [AncPushNotificationController::class, 'getInfo']);
            Route::post('announcment/push-notifications/add', [AncPushNotificationController::class, 'addNotification']);
            Route::post('push-notifications/records', [AncPushNotificationController::class, 'postRecords']);
            Route::post('push-notifications/action', [AncPushNotificationController::class, 'postAction']);

            //========================================= Announcment & Reminders END =========================================//

            // shopping cart code
            Route::get('shoppingcart/info', [ShoppingcartController::class, 'getInfo']);
            Route::post('shoppingcart/records', [ShoppingcartController::class, 'postRecords']);
            Route::post('shoppingcart/action', [ShoppingcartController::class, 'postAction']);

            // notification code
            Route::get('announcment/notification/info', [AnnouncmentNotificationController::class, 'getInfo']);
            Route::post('announcment/notification/info/records', [AnnouncmentNotificationController::class, 'postRecords']);

            // add organizations subscriber
            // Route::get('subscriber/info', [AddsubscribersController::class, 'getInfo']);
            Route::post('subscriber/add', [AddsubscribersController::class, 'postAdd']);

            // partner product
            Route::get('organizations/partner-product/info', [PartnerProductController::class, 'getInfo']);
            Route::post('organizations/partner-product/create', [PartnerProductController::class, 'postAdd']);
            Route::post('organizations/partner-product/edit/{id}', [PartnerProductController::class, 'postEdit']);
            Route::post('organizations/partner-product/records', [PartnerProductController::class, 'postRecords']);
            Route::post('organizations/partner-product/action', [PartnerProductController::class, 'postAction']);
            Route::post('organization/partner-product/thumbnail', [PartnerProductController::class, 'postThumbnail']);

            //========================================= app customization START =========================================//
            // row order
            Route::get('organization/app-customization/promotion/row-order', [RowOrderController::class, 'getInfo']);
            Route::post('organization/app-customization/promotion/row-order/create', [RowOrderController::class, 'postAdd']);
            Route::post('organization/app-customiztion/promotion/row-order/save-order', [RowOrderController::class, 'saveOrder']);
            Route::post('organization/app-customization/promotion/row-order/action', [RowOrderController::class, 'postAction']);
            Route::post('organization/app-customization/promotion/row-order/records', [RowOrderController::class, 'postRecords']);

            // get assigned content sets from org content set of VOD, Tvshow, Channel, Live Event
            Route::post('vod/assigned-content/records', [RowOrderController::class, 'getAssignedVodContents']);
            Route::post('channel/assigned-content/records', [RowOrderController::class, 'getAssignedChannelContents']);
            Route::post('tvshow/assigned-content/records', [RowOrderController::class, 'getAssignedTvShowContents']);
            Route::post('live-event/assigned-content/records', [RowOrderController::class, 'getAssignedLiveEventContents']);


            Route::post('organization/app-customization/promotion/row-order/thumbnail', [RowOrderController::class, 'postThumbnail']);
            Route::post('organization/app-customization/promotion/row-order/poster', [RowOrderController::class, 'postPosters']);

            // banner_carousels_subscription
            Route::get('organization/app-customization/banner_carousels_subscription', [BannerCarouselsSubscription::class, 'getInfo']);
            Route::post('organization/app-customization/banner_carousels_subscription/create', [BannerCarouselsSubscription::class, 'postAdd']);
            Route::post('organization/app-customization/banner_carousels_subscription/edit/{id}', [BannerCarouselsSubscription::class, 'postEdit']);
            Route::post('organization/app-customization/banner_carousels_subscription/action', [BannerCarouselsSubscription::class, 'postAction']);
            Route::post('organization/app-customization/banner_carousels_subscription/records', [BannerCarouselsSubscription::class, 'postRecords']);

            Route::post('organization/ac/banner_carousels_subscription/thumbnail', [BannerCarouselsSubscription::class, 'postThumbnail']);
            Route::post('organization/ac/banner_carousels_subscription/poster', [BannerCarouselsSubscription::class, 'postPosters']);

            // channel listing
            Route::get('organization/app-customization/channel-listing/info', [ChannelListingController::class, 'getInfo']);
            Route::post('organization/app-customization/channel-listing/create', [ChannelListingController::class, 'postAdd']);
            Route::post('organization/app-customization/channel-listing/records', [ChannelListingController::class, 'postRecords']);

            // setting
            Route::get('app-customization/setting/info', [OrganizationSetting::class, 'getInfo']);
            Route::post('app-customization/setting/create', [OrganizationSetting::class, 'postAdd']);
            Route::post('organization/app-customization/setting/edit/{id}', [OrganizationSetting::class, 'postEdit']);
            Route::post('app-customization/setting/records', [OrganizationSetting::class, 'postRecords']);
            Route::post('app-customization/setting/action', [OrganizationSetting::class, 'postAction']);

            // general app customization
            Route::get('app-customization/general/info', [AppCustomizationController::class, 'getInfo']);
            Route::post('app-customization/general/create', [AppCustomizationController::class, 'postAdd']);
            Route::post('app-customization/general/edit/{id}', [AppCustomizationController::class, 'postEdit']);
            Route::post('app-customization/general/records', [AppCustomizationController::class, 'postRecords']);
            Route::post('app-customization/general/action', [AppCustomizationController::class, 'postAction']);

            Route::post('app-customization/general/thumbnail', [AppCustomizationController::class, 'postThumbnail']);

            // Featured Rows
            Route::post('org/app-customiztion/featured-rows/edit/{id}', [AppCustomiztionFeaturedRow::class, 'postEdit']);
            Route::post('org/app-customiztion/featured-rows/channel-delele/{id}', [AppCustomiztionFeaturedRow::class, 'postDeletechannel']);
            Route::post('org/app-customiztion/featured-rows/tvshow-delete/{id}', [AppCustomiztionFeaturedRow::class, 'postDeleteTvshow']);
            Route::post('org/app-customiztion/featured-rows/movie-delete/{id}', [AppCustomiztionFeaturedRow::class, 'postDeleteMovie']);


            // banner carousel
            Route::post('org/app-customiztion/banner_carousel/edit/{id}', [AppCustomiztionBannerCarouselController::class, 'postEdit']);
            Route::post('organization/app-customiztion/banner_carousel/toggle/edit/{id}', [AppCustomiztionBannerCarouselController::class, 'postToggle']);
            Route::post('org/app-customiztion/banner_carousel/banner/delete/{id}', [AppCustomiztionBannerCarouselController::class, 'postDelete']);

            Route::post('organization/app-customiztion/banner_carousels/thumbnail', [AppCustomiztionBannerCarouselController::class, 'postThumbnail']);
            //========================================= app customization End =========================================//


            // ========================= Shopping Cart START ===============================//

            // shopping cart status update and delete record API
            Route::post('shopping-cart/monetization-plan/status-update', [MonetizationPlanssController::class, 'statusUpdate']);
            Route::post('shopping-cart/monetization-plan/destroy/{id}', [MonetizationPlanssController::class, 'removePlan']);

            Route::post('shopping-cart/plans/records', [ShoppingcartController::class, 'postRecords']);
            Route::post('shopping-cart/plan-add', [ShoppingcartController::class, 'addCustomPlan']);
            Route::post('shopping-cart/plan-edit/{id}', [ShoppingcartController::class, 'editCustomPlan']);
            Route::post('shopping-cart/plan-destroy/{id}', [ShoppingcartController::class, 'removeCustomPlan']);

            // update table records after drag and drop
            Route::post('shopping-cart/update-records', [ShoppingcartController::class, 'updateTableRecords']);
            Route::post('shopping-cart/update-status', [ShoppingcartController::class, 'updateCustomPlansStatus']);


            // ========================= Shopping Cart END ===============================//



            //========================================= payment services Start =========================================//
            Route::get('organization/payment-service/info', [PaymentServiceController::class, 'getInfo']);
            Route::post('organization/payment-service/toggle/edit/{id}', [PaymentServiceController::class, 'postToggle']);
            Route::post('organization/payment-service/default', [PaymentServiceController::class, 'postDefault']);
            Route::post('organization/payment-service/sysdft', [PaymentServiceController::class, 'postEdit']);
            Route::post('organization/payment-service/records', [PaymentServiceController::class, 'postRecords']);

            Route::get('organization/payment-service/currency/info', [PaymentServiceCurrencyController::class, 'getInfo']);
            Route::post('organization/payment-service/currency', [PaymentServiceCurrencyController::class, 'postToggle']);
            Route::post('organization/payment-service/currency/edit/{id}', [PaymentServiceCurrencyController::class, 'postEdit']);
            Route::post('organization/payment-service/currency/records', [PaymentServiceCurrencyController::class, 'postRecords']);

            Route::get('organization/payment-service/currency-converter/info', [PaymentServiceCurrencyConverterController::class, 'getInfo']);
            Route::post('organization/payment-service/currency-converter/toggle/edit/{id}', [PaymentServiceCurrencyConverterController::class, 'postToggle']);
            Route::post('organization/payment-service/currency-converter/sysdft/edit/{id}', [PaymentServiceCurrencyConverterController::class, 'postEdit']);
            Route::post('organization/payment-service/currency-converter/records', [PaymentServiceCurrencyConverterController::class, 'postRecords']);
            //========================================= payment services End =========================================//

            Route::get('organization/record-counts/{id}', [OrganizationsController::class, 'getRecordsCounts']);
        });
    });
});
