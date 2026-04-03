<?php

/**
 * Notification Repository
 *
 * To manage the functionalities related to the Notification module from Notification Controller
 *
 * @name NotificationRepository
 * @vendor Contus
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Notification\Models\Notification;
use Contus\Notification\Models\NotificationUser;
use Illuminate\Support\Facades\Mail;

class NotificationRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_notification;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Notification
     * @param Contus\Notification\Models\Notification $notification
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * function to Send Email
     *
     * @param object $toUserDetail
     * @param string $subject
     * @param string $content
     */
    public function email($toUserDetail, $subject, $content)
    {
        try {
            Mail::send('base::email', ['content' => $content], function ($message) use ($subject, $toUserDetail) {
                \Log::info('mail sent.......');
                $message->from(env('MAIL_SENDER_ADDRESS'), config()->get('settings.general-settings.site-settings.site_name'));
                $message->to($toUserDetail->email, $toUserDetail->name)->subject($subject);
            });
        } catch (\Exception $exception) {
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }

    /**
     * function to add notification
     *
     * @param object $customer
     * @param object $users
     * @param string $type
     * @param int $type_id
     * @param string $content
     * @param string $type_type
     * @param object $curUser
     */
    public function addNotifications($user, $typeData = null, $type, $content, $sender_id = null)
    {
        $notification = new Notification();
        $notification->content = $content;
        $notification->video_id = ($typeData) ? $typeData->id : null;
        $notification->user_id = $user->id;
        $notification->type = $type;
        $notification->read_at = null;
        $notification->sender_id = $sender_id;
        if ($notification->save()) {
            $this->addCount($user->id);
        }
        return true;
    }

    public function addCount($user_id)
    {
        $notification = NotificationUser::where('user_id', $user_id);
        if ($notification->exists()) {
            $notification->increment('count');
        } else {
            $notificationUser = new NotificationUser();
            $notificationUser->new_video = 1;
            $notificationUser->reply_comment = 1;
            $notificationUser->user_id = $user_id;
            $notificationUser->count = 1;
            $notificationUser->save();
        }
        return true;
    }
}
