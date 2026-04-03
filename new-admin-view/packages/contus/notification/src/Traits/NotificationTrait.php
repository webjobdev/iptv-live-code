<?php

/**
 * NotificationTrait
 *
 * To manage the functionalities for send the notification.
 *
 * @vendor Contus
 *
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Notification\Traits;

use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Cms\Models\EmailTemplates;
use Contus\Video\Models\Comment;
use Contus\User\Models\User;
use Contus\Notification\Models\Notification;
use Contus\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Contus\Video\Models\Question;
use Contus\Video\Models\Answer;
use Contus\Video\Models\Video;
use Contus\Notification\Models\NotificationUser;

trait NotificationTrait {
    /**
     * Function to add notification
     *
     *
     * @param string $type
     * @param int $typeId
     */
    public function notify($type, $typeId) {
        $curl = curl_init ();

        $headers [] = 'X-REQUEST-TYPE:mobile';
        if(!empty(auth ()->user ())) {
            $headers [] = 'X-ACCESS-TOKEN:' . !empty(auth()->user()) ? auth()->user()->access_token : '';
            $headers [] = 'X-USER-ID:' . (!empty(auth ()->user ())) ? auth ()->user ()->id : '';
        }
        else {
            $headers [] = 'X-ACCESS-TOKEN:';
            $headers [] = 'X-USER-ID:0';
        }      
        $post = [ 'type' => $type,'type_id' => $typeId ];
        
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            curl_setopt ( $curl, CURLOPT_URL, url ( 'api/v1/notify' ) );
        } else {
            curl_setopt ( $curl, CURLOPT_URL, url ( 'api/admin/notify' ) );
        }

        curl_setopt ( $curl, CURLOPT_POST, TRUE );
        curl_setopt ( $curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $post );
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $curl, CURLOPT_USERAGENT, 'api' );
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 1 );
        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $curl, CURLOPT_FORBID_REUSE, true );
        curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 1 );
        curl_setopt ( $curl, CURLOPT_DNS_CACHE_TIMEOUT, 5 );
        curl_setopt ( $curl, CURLOPT_FRESH_CONNECT, true );
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTREDIR, 3);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec ( $curl );
    }
    /**
     * Function to set notification
     *
     * @param string $type
     * @param int $typeId
     */
    public function setNotifys($type, $typeId) {
        switch ($type) {
            case 'comment' :
                $this->comemnt ( $typeId );
                break;
            case 'rcomment' :
                $this->replyComment ( $typeId );
                break;
            case 'answer' :
                $this->answer ( $typeId );
                break;
            case 'question' :
                $this->question ( $typeId );
                break;
            case 'video' :
                $this->video ( $typeId );
                break;
            case 'live' :
                $this->livevideo ( $typeId );
                break;
            default :
                break;
        }
    }
    /**
     * function to trigger notifcation via curl api
     */
    public function setNotify() {
        $this->setNotifys ( $this->request->type, $this->request->type_id );
    }

    /**
     * function to Send Email
     *
     * @param object $toUserDetail
     * @param string $subject
     * @param string $content
     */
    public function email($toUserDetail, $subject, $content) {
        try {
            Mail::send ( 'base::layouts.email', [ 'content' => $content ], function ($message) use ($subject, $toUserDetail) {
                $message->from ( env('MAIL_SENDER_ADDRESS'), config ()->get ( 'settings.general-settings.site-settings.site_name' ) );
                $message->to ( $toUserDetail->email, $toUserDetail->name )->subject ( $subject );
            } );
        }
        catch ( \Exception $e ) {
            app ( 'log' )->info ( 'Email is not working with configured mail id ' . env ( 'MAIL_USERNAME' ) );
        }
    }
    /**
     * function to select data to send email notification
     *
     * @param object $customers
     * @param object $users
     * @param object $email
     */
    public function sendEmailNotification($customers, $users, $email) {
        foreach ( $customers as $customer ) {
            $getcolumn = new Customer ();
            $getcolumn = array_map ( function ($str) {
                return '##' . strtoupper ( $str ) . '##';
            }, $getcolumn->getTableColumns () );
            $email->content = str_replace ( $getcolumn, $customer->toArray (), $email->content );
            $this->email ( $customer, $email->subject, $email->content );
        }
        foreach ( $users as $customer ) {
            $getcolumn = new User ();
            $getcolumn = array_map ( function ($str) {
                return '##' . strtoupper ( $str ) . '##';
            }, $getcolumn->getTableColumns () );
            $email->content = str_replace ( $getcolumn, $customer->toArray (), $email->content );
            $this->email ( $customer, $email->subject, $email->content );
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
    public function addNotification($customer, $users, $type, $type_id, $content, $type_type, $curUser) {
        foreach ( $customer as $c ) {
            $this->saveUserNotification ( 'customer', $c, $type, $type_id, $content, $type_type, $curUser );
        }
    }
    /**
     * Push notification for both android and ios
     *
     * @param object $customers
     * @param string $type
     * @param int $typeId
     * @param string $notificationText
     */
    public function pushNotification($customers, $type, $typeId, $notificationText) {
        if (is_object ( $customers )) {
            $androidDeviceToken = clone $customers;
            $deviceTokenAPNS = clone $customers;
            $deviceTokenAPNS = $deviceTokenAPNS->where ( 'device_type', 'IOS' )->whereRaw ( 'device_token != "" and device_token != " "  and device_token != "0"' )->pluck ( 'device_token' )->toArray ();
            $data = array ("message" => $notificationText,"noteType" => $type,'noteId' => $typeId );
            if ($deviceTokenAPNS) {
                $this->apnsPushNotification ( $deviceTokenAPNS, $data );
            }
            $androidDeviceToken = $androidDeviceToken->where ( 'device_type', 'Android' )->whereRaw ( 'device_token != "" and device_token != " "  and device_token != "0"' )->pluck ( 'device_token' )->toArray ();
            if ($androidDeviceToken) {
                $this->fcmPushNotification ( $androidDeviceToken, $data );
            }
        }
    }


    /**
     * Push notification for both android and ios
     *
     * @param object $customers
     * @param string $type
     * @param int $typeId
     * @param string $notificationText
     */
    public function sendPushNotification($customers, $type, $typeId, $notificationText)
    {
        if(!empty($customers)) {
            $customerArray = $customers->makeVisible(['device_token', 'device_type']);
            $fcmData = array("message" => $notificationText,"notification_type" => $type,'video_id' => $typeId );

            if($type == 'subscription') {
                // Note : For subscription we used to send notification one by one user. Inorder to reuse the below code we convert the customer object into array of objects
                $customerArray = [$customers];
            }

            foreach($customerArray as $cust) {
                if($cust->device_token != '') {
                    if ($cust->device_type == 'Android') {
                        $format = [
                          'registration_ids'=> [$cust->device_token],
                          'priority' => "high",
                          'data'=> $fcmData
                      ];
                    } elseif ($cust->device_type == 'IOS') {
                        $notify = ['body'=> $fcmData['message'],'badge'=>1,'sound'=>'ping.aiff'];
                        $format = [
                                  'to'=> $cust->device_token,
                                  'notification'=>$notify,
                                  'priority' => "high",
                                  'data'=> $fcmData
                              ];
                    }
                }
                if (!empty($format)) {
                    $this->fcmPushNotification($format);
                }
            }
        }
    }

    /**
     * Function for FCM Push Notification
     *
     * @param string|array $reg_id
     * @param array $data
     */
    public function fcmPushNotification($fields)
    {
        try {
            $googleApiKey = env('FCM_KEY');
            $googleGcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $headers = array($googleGcmUrl,'Content-Type: application/json','Authorization: key=' . $googleApiKey );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $googleGcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\ErrorException $e) {
            \Log::error($e);
        }
    }

    
    /**
     * function to add notification for comment
     *
     * @param int $typeId
     */
    private function comemnt($typeId) {
        $comment = Comment::where ( 'id', $typeId )->where ( 'is_notify', 0 )->where ( 'is_active', 1 )->first ();
        if ($comment) {
            $comment->is_notify = 1;
            $comment->save ();
            $video = $comment->video ()->first ();
            $customers = array ();
            $users = array ();
            if ($comment->customer_id) {
                $curUser = Customer::where ( 'id', $comment->customer_id )->first ();
                $customers = $video->comments ()->where ( 'customer_id', '!=', $comment->customer_id )->where ( 'comments.is_active', 1 )->join ( 'customers', function ($join) {
                    $join->on ( 'customers.id', '=', 'comments.customer_id' )->where ( 'notify_comment', '=', 0 );
                } )->groupby ( 'customer_id' );
            } else {
                $curUser = auth ()->user ();
                $customers = $video->comments ()->where ( 'comments.is_active', 1 )->join ( 'customers', function ($join) {
                    $join->on ( 'customers.id', '=', 'comments.customer_id' )->where ( 'notify_comment', '=', 0 );
                } )->groupby ( 'customer_id' );
            }
            /**
             * Add Notification
             */
            $notificationText = $curUser->name . ' has commented on video ' . $video->title;
            $this->addNotification ( $customers->get (), $users, 'comment', $video->id, $notificationText, 'videos', $curUser );
            /**
             * Push Notification
             */
            $notificationText = $curUser->name . ' has commented on video ' . $video->title;
            $this->pushNotification ( $customers, 'comment', $video->id, $notificationText );
        }
    }

    /**
     * function to add notification for reply of question
     *
     * @param int $typeId
     */
    private function answer($typeId) {
        $question = Question::where ( 'id', $typeId )->where ( 'is_active', 1 )->first ();
        if ($question) {
            $video = $question->video ()->first ();
            $customers = array ();
            $users = array ();
            if ($question->customer_id) {
                $curUser = auth()->user();
                $customers = $question->customer ()->where ( 'notify_reply_comment', '=', 0 );
                /**
                 * Add Notification
                 */
                if (isset ( $curUser ['id'] )) {
                    $notificationText = $curUser->name . ' has answered to your query on video ' . $video->title;
                    $this->addNotification ( $customers->get (), $users, 'answer', $video->id, $notificationText, 'videos', $curUser );
                    $this->pushNotification ( $customers, 'answer', $video->id, $notificationText );
                }
            }
        }
    }
    /**
     * function to add notification for reply of comment
     *
     * @param int $typeId
     */
    private function replyComment($typeId) {
        $comment = Comment::where ( 'id', $typeId )->where ( 'is_active', 1 )->first ();
        if ($comment) {
            $video = $comment->video ()->first ();
            $customers = array ();
            $users = array ();
            if ($comment->customer_id) {
                $curUser = auth()->user();
                $customers = $comment->customer ()->where ( 'notify_reply_comment', '=', 0 );
                /**
                 * Add Notification
                 */
                if (isset ( $curUser ['id'] )) {
                    $notificationText = $curUser->name . ' has replied to your comment on video ' . $video->title;
                    $this->addNotification ( $customers->get (), $users, 'answer', $video->id, $notificationText, 'videos', $curUser );
                    $this->pushNotification ( $customers, 'answer', $video->id, $notificationText );
                }
            }
        }
    }

    /**
     * function to add notification for add a new videos
     *
     * @param int $typeId
     */
    private function video($typeId) {
        $video = Video::where ( 'id', $typeId )->where ( 'is_archived', 0 )->where ( 'is_active', 1 )->where ( 'job_status', 'Complete' )->first ();
        if (!empty($video) && count ( $video->toArray() ) > 0) {
            $customers = Customer::selectRaw('id, device_token, device_type')->where ( 'is_active', 1 )->whereHas('notificationUser' , function($query) {
                $query->where('new_video', 1);
            })->get();

            $curUser = auth()->user();
            /**
             * Add Notification
             */
            $notificationText = config ()->get ( 'settings.general-settings.site-settings.site_name' ) . ' added a new video ' . $video->title;
            $this->addNotification ( $customers, [ ], 'video', $video->id, $notificationText, 'videos', $curUser );
            /**
             * Push Notification
             */
            
            $this->sendPushNotification ( $customers, 'video', $video->id, $notificationText );
            // $video->notification_status = 1;
            $video->is_notify = 0;
            $video->save ();
        }
    }

    /**
     * function to add notification for add a new live videos
     *
     * @param int $typeId
     */
    private function livevideo($typeId) {
        $video = Video::where ( 'id', $typeId )->where ( 'is_archived', 0 )->where ( 'is_active', 1 )->where ( 'job_status', 'Complete' )->where ( 'is_live', 1 )->where ( 'liveStatus', '!=', 'complete' )->first ();
        if (count ( $video ) > 0) {
            $date = $video->scheduledStartTime;
            $customers = Customer::where ( 'is_active', 1 )->where ( 'notify_videos', 0 )->where ( 'notify_email', 0 );
            /**
             * Add Notification
             */
            $notificationText = config ()->get ( 'settings.general-settings.site-settings.site_name' ) . ' Schedule next live on ' . $date . ' ' . $video->title;
            $this->addNotification ( $customers->get (), [ ], 'live', $video->id, $notificationText, 'videos' );
            /**
             * Push Notification
             */
            $this->pushNotification ( $customers, 'live', $video->id, $notificationText );
        }
    }
    /**
     * function to save user notification
     *
     * @param string $userType
     * @param object $c
     * @param string $type
     * @param int $type_id
     * @param string $content
     * @param string $type_type
     * @param object $curUser
     */
    private function saveUserNotification($userType, $c, $type, $type_id, $content, $type_type, $curUser) {
        $notification = new Notification();
        $notification->content = $content;
        $notification->video_id = ($type_id) ? $type_id : null;
        $notification->user_id = $c->id;
        $notification->type = $type;
        $notification->read_at = null;
        $notification->sender_id = 0;


        $notification->save ();
    }
    /**
     * Function for FCM Push Notification
     *
     * @param string|array $reg_id
     * @param array $data
     */
    /*public function fcmPushNotification($reg_id, $data) {
        try {
            $googleApiKey = 'AAAAmgoUZTE:APA91bF0875y3x-uuVvKrs121EfCTnXsEWjMiLat4fZ4wdeoz_SnUQU1KCqlTcJPw7oL7Tqp5ZWFORVYzngQx-o8SVUZ0F-CZMGvsH_0qcbHomUA_PLFmvEF-mLKN_r0BGkG4Tg8T2oq';
            $googleGcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $fields = array ('registration_ids' => $reg_id,'priority' => "high",'data' => $data );
            $headers = array ($googleGcmUrl,'Content-Type: application/json','Authorization: key=' . $googleApiKey );
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $googleGcmUrl );
            curl_setopt ( $ch, CURLOPT_POST, true );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $fields ) );
            $result = curl_exec ( $ch );
            curl_close ( $ch );
            return $result;
        }
        catch ( \ErrorException $e ) {}
    }*/
    /**
     * Function for APNS Push Notification
     *
     * @param string|array $deviceToken
     * @param array $data
     */
    public function apnsPushNotification($deviceToken, $data) {
        $passphrase = '';
        $ctx = stream_context_create ();
        stream_context_set_option ( $ctx, 'ssl', 'local_cert', './LS.pem' );
        stream_context_set_option ( $ctx, 'ssl', 'passphrase', $passphrase );
        $fp = stream_socket_client ( 'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx );
        $body ['aps'] = array ('alert' => $data ['message'],'data' => $data,'badge' => 0,'sound' => 'default' );
        $payload = json_encode ( $body );
        $result = '';
        foreach ( $deviceToken as $token ) {
            try {
                $msg = chr ( 0 ) . pack ( 'n', 32 ) . pack ( 'H*', $token ) . pack ( 'n', strlen ( $payload ) ) . $payload;
                $result = fwrite ( $fp, $msg, strlen ( $msg ) );
            }
            catch ( \ErrorException $e ) {}
        }
        fclose ( $fp );
        return $result;
    }
}