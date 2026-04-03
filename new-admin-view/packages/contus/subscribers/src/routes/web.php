<?php

use Contus\Customer\Models\Subscribers;
use Illuminate\Support\Facades\Route;
use Contus\Subscribers\Http\Controllers\Admin\SubscriberIndexController;

Route::prefix('admin')->namespace('Contus\Subscribers\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => []], function () {

        Route::get('subscribers', [SubscriberIndexController::class, 'index'])->name('subscribers.index');
        Route::get('subscribers/gridlist', [SubscriberIndexController::class, 'subscriberGridList']);

        Route::get('subscribers/devices', [SubscriberIndexController::class, 'deviceIndex'])->name('devices');
        Route::get('devices/gridlist', [SubscriberIndexController::class, 'deviceGridList'])->name('devices.gird.view');

        Route::get('subscriber/activation', [SubscriberIndexController::class, 'activationIndex'])->name('activation');
        Route::get('subscriber/download-pdf/{data}', [SubscriberIndexController::class, 'downloadPdf']);

        Route::get('activation/subscriber/add-slot', [SubscriberIndexController::class, 'addslot'])->name('subsciber.add.slot');
        Route::get('activation/subscriber/view-slot', [SubscriberIndexController::class, 'viewslot'])->name('subsciber.view.slot');
        Route::get('activation/gridlist', [SubscriberIndexController::class, 'subscriptionGridList']);

        // Route::get('subscriber/activation/{id}', [SubscriberIndexController::class, 'activationIndex'])->name('activation');
        Route::get('assigne-device/gridlist', [SubscriberIndexController::class, 'assignGridList']);

        Route::delete('subscribers/destroy', [SubscriberIndexController::class, 'destroy'])->name('subscribers.destroy');
        Route::get('subscribers/detail/add', [SubscriberIndexController::class, 'postadd'])->name('subscribers.detail.add');

        Route::get('subscribers/credit-card', [SubscriberIndexController::class, 'creditcardIndex'])->name('subscribers.credit-card');
        Route::get('activation/credit-card/gridlist', [SubscriberIndexController::class, 'creditcardGridList']);

        Route::get('subscribers/payment-history', [SubscriberIndexController::class, 'paymentIndex'])->name('subscribers.payment-history');
        Route::get('activation/payment-history/gridlist', [SubscriberIndexController::class, 'paymentGridList']);

        Route::get('subscribers/patner-product', [SubscriberIndexController::class, 'partnerIndex'])->name('subscribers.patner-product');
        Route::get('activation/partner-product/gridlist', [SubscriberIndexController::class, 'partnerGridList']);

        Route::get('subscriber/notes', [SubscriberIndexController::class, 'notesIndex'])->name('subscribers.notes');
        Route::get('activation/notes/gridlist', [SubscriberIndexController::class, 'notesGridList'])->name('subscribers.notes.gridlist');

        Route::get('subscriber/custom-stream', [SubscriberIndexController::class, 'customstream'])->name('subscriber.custom.stream');
        Route::get('subscriber/custom-stream/tv-channel/gridlist', [SubscriberIndexController::class, 'tvChannelGridList']);

        Route::get('subscriber/custom-stream/video-on-demand', [SubscriberIndexController::class, 'videoOnDemand'])->name('subscriber.video.demand');
        Route::get('subscriber/custom-stream/video-on-demand/gridlist', [SubscriberIndexController::class, 'videoOnDemandGridList']);

        Route::post('subscriber/set-primary-device', [SubscriberIndexController::class, 'setPrimaryDevice']);
        Route::post('subscriber/unlink-device', [SubscriberIndexController::class, 'unlinkDevice']);
        Route::post('subscriber/delete-slot', [SubscriberIndexController::class, 'deleteSlot']);
    });
});
    Route::group(['prefix' => 'api/admin', 'middleware' => ['api'], 'namespace' => 'Contus\Subscribers\Http\Controllers\Admin'], function () {
        Route::post('subscriber/set-primary-device', [SubscriberIndexController::class, 'setPrimaryDevice']);
        Route::post('subscriber/unlink-device', [SubscriberIndexController::class, 'unlinkDevice']);
        Route::post('subscriber/delete-slot', [SubscriberIndexController::class, 'deleteSlot']);
        Route::post('subscriber/create-slot', [SubscriberIndexController::class, 'createSlot']);
    });
// });
