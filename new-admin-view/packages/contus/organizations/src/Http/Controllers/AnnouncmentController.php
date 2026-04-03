<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;
use Illuminate\Support\Facades\Http;

class AnnouncmentController extends Controller {

    public function index() {
        return view('organizations::announcment.index');
    }

    public function getGridlist() {
        return view('organizations::announcment.gridView');
    }

    public function reminderIndex() {
        return view('organizations::announcment.reminders.index');
    }

    public function getRemindersGridlist() {
        return view('organizations::announcment.reminders.gridView');
    }

    public function notificationIndex() {
        return view('organizations::announcment.push-notifications.index');
    }

    public function getNotificationsGridlist() {
        return view('organizations::announcment.push-notifications.gridView');
    }

    public function addActivations() {
        return view('organizations::announcment.activation.index');
    }

    public function addDisabledAccounts() {
        return view('organizations::announcment.disabled-accounts.index');
    }
}
