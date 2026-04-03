<?php

use Contus\Subscribers\Api\Controllers\Admin\ActivationSubscriberController;
use Contus\Subscribers\Api\Controllers\Admin\AssignedDeviceController;
use Contus\Subscribers\Api\Controllers\Admin\CreditCardController;
use Contus\Subscribers\Api\Controllers\Admin\CustomStreamController;
use Contus\Subscribers\Api\Controllers\Admin\DefaultPaymentGatewayController;
use Contus\Subscribers\Api\Controllers\Admin\PartnerProductController;
use Contus\Subscribers\Api\Controllers\Admin\PaymentHistoryController;
use Contus\Subscribers\Api\Controllers\Admin\SubscriberIndexController;
use Contus\Subscribers\Api\Controllers\Admin\SubscriberNoteController;
use Contus\Subscribers\Api\Controllers\Admin\UserDeviceController;
use Contus\Subscribers\Api\Controllers\Admin\VodController;
use Illuminate\Support\Facades\Route;


Route::prefix('api/admin')->namespace('Contus\Subscribers\Api\Controller\Admin')->group(function () {
    Route::group(['middleware' => ['cors']], function () {
        Route::group(['middleware' => 'jwt-auth'], function () {

            Route::get('subscriber/info', [SubscriberIndexController::class, 'getInfo']);
            Route::post('subscribers/records', [SubscriberIndexController::class, 'postRecords']);
            Route::post('subscribers/get-all/records', [SubscriberIndexController::class, 'getAll']);
            Route::post('subscribers/get-all/action', [SubscriberIndexController::class, 'postAction']);
            Route::post('subscribers/add', [SubscriberIndexController::class, 'postAdd']);
            Route::post('subscribers/action', [SubscriberIndexController::class, 'postAction']);

            Route::get('subscriber/device/info', [UserDeviceController::class, 'getInfo']);
            Route::post('subscribers/device/add', [UserDeviceController::class, 'postAdd']);
            Route::post('subscribers-device/edit/{id}', [UserDeviceController::class, 'postEdit']);
            Route::post('subscribers-device/toggle/{id}', [UserDeviceController::class, 'postToggle']);
            Route::post('devices/action', [UserDeviceController::class, 'postAction']);
            Route::post('devices/update-status', [UserDeviceController::class, 'postUpdateStatus']);
            Route::post('devices/records', [UserDeviceController::class, 'postRecords']);

            Route::get('subscriber-subscriptions/activation/info', [ActivationSubscriberController::class, 'getInfo']);
            Route::post('subscriber/assigned-device/info', [ActivationSubscriberController::class, 'assigneInfo']);
            Route::post('subscribers-subscriptions/activation/records', [ActivationSubscriberController::class, 'postRecords']);
            Route::post('subscribers-subscriptions/activation/action', [ActivationSubscriberController::class, 'postAction']);
            Route::post('subscriber/add/device-slot', [ActivationSubscriberController::class, 'postAdd']);
            Route::post('subscriber/add/only-assigned-device', [ActivationSubscriberController::class, 'postAssignedDevice']);
            Route::post('subscriber/payment/refund', [ActivationSubscriberController::class, 'refund']);
            Route::post('subscriber/payment/cancel', [ActivationSubscriberController::class, 'paymentCancel']);

            Route::post('only-assigned-device/records', [AssignedDeviceController::class, 'postRecords']);
            Route::post('subscriber/set-primary-device', [AssignedDeviceController::class, 'setPrimaryDevice']);
            Route::post('subscriber/unlink-device', [AssignedDeviceController::class, 'unlinkDevice']);

            Route::get('subscriber/assigned-device/info', [PartnerProductController::class, 'getInfo']);
            Route::post('subscriber/partner-product/records', [PartnerProductController::class, 'postRecords']);

            Route::get('subscriber/credit-card/info', [CreditCardController::class, 'getInfo']);
            Route::post('subscriber/credit-card/add', [CreditCardController::class, 'postAdd']);
            Route::post('subscriber/credit-card/edit/{id}', [CreditCardController::class, 'postEdit']);
            Route::post('subscriber/credrt-card/action', [CreditCardController::class, 'postAction']);
            Route::post('subscriber/credit-card/records', [CreditCardController::class, 'postRecords']);

            Route::get('subscriber/payment-history/info', [PaymentHistoryController::class, 'getInfo']);
            Route::post('payment/comment/add', [PaymentHistoryController::class, 'postadd']);
            Route::post('subscriber/payment-history/records', [PaymentHistoryController::class, 'postRecords']);

            Route::get('subscriber/note/info', [SubscriberNoteController::class, 'getInfo']);
            Route::post('subscriber/note/add', [SubscriberNoteController::class, 'postAdd']);
            Route::post('subscriber/note/edit/{id}', [SubscriberNoteController::class, 'postEdit']);
            Route::post('subscriber/note/action', [SubscriberNoteController::class, 'postAction']);
            Route::post('subscriber/note/records', [SubscriberNoteController::class, 'postRecords']);

            Route::get('subscriber/custom-stream/info', [CustomStreamController::class, 'getInfo']);
            Route::post('subscriber/add/channel-list', [CustomStreamController::class, 'addChannel']);
            Route::post('subscriber/add/channel-list/edit/{id}', [CustomStreamController::class, 'postEdit']);
            Route::post('subscriber/custom-stream/records', [CustomStreamController::class, 'postRecords']);
            Route::post('subscriber/custom-stream/action', [CustomStreamController::class, 'postAction']);

            Route::get('subscriber/video-on-demand/info', [VodController::class, 'getInfo']);
            Route::post('subscriber/add/vod-list', [VodController::class, 'addVod']);
            Route::post('subscriber/add/vod-list/edit/{id}', [VodController::class, 'postEdit']);
            Route::post('subscriber/video-on-demand/records', [VodController::class, 'postRecords']);

            Route::get('default/payment-gateway/records', [DefaultPaymentGatewayController::class, 'getRecords']);

            Route::post('subscriber/not-assign-device', [AssignedDeviceController::class, 'postNotAssignDevice']);
            Route::post('subscriber/delete-slot', [AssignedDeviceController::class, 'postDeleteSlot']);
        });
    });
});
