<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name CommentsRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Illuminate\Support\Facades\Config;
use Contus\Video\Models\ReplyComment;
use Contus\Notification\Repositories\NotificationRepository;

class CommentsRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $comments;

    /**
     * Construct method
     *
     * @param Comment $comment
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(Comment $comment, NotificationRepository $notificationRepository)
    {
        parent::__construct();
        $this->comments = $comment;
        $this->notification = $notificationRepository;
    }

    /**
     * Method to add comment by validating the user
     *
     * @return number
     */
    public function addComment()
    {
        $this->setRule('comment', 'filled');
        if ($this->_validate()) {
            $this->comments->comment = $this->request->comment;
            $this->comments->video_id = $this->request->video_id;
            if (config()->get('auth.providers.users.table') == 'users') {
                $this->comments->user_type = 'admin';
                $this->comments->user_id = $this->authUser->id;
            } else {
                $this->comments->user_type = 'customer';
                $this->comments->customer_id = $this->authUser->id;
            }
            $this->comments->parent_id=0;
            $this->comments->is_active = 1;
            $this->comments->creator_id = $this->authUser->id;
            return ($this->comments->save()) ? 1 : 0;
        }
    }

    /**
     * Method to add comment by validating the user based on parent comment
     *
     * @return number
     */
    public function addChildComment()
    {
        $this->setRules(['comment' => 'required'], ['parent_id' => 'required']);
        $this->_validate();
        $this->comments = $this->comments->find($this->request->parent_id);
        if (is_object($this->comments) && !empty ($this->comments->id)) {
            $attachComment = new ReplyComment ();
            $attachComment->comment = $this->request->comment;
            if (config()->get('auth.providers.users.table') == 'users') {
                $attachComment->user_type = 'admin';
                $attachComment->user_id = $this->authUser->id;
            } else {
                $attachComment->user_type = 'customer';
                $attachComment->customer_id = $this->authUser->id;
            }
            $attachComment->creator_id = $this->authUser->id;
            $return = ($this->comments->ReplyComment()->save($attachComment)) ? 1 : 0;
            $this->notification->notify('rcomment', $this->comments->id);
            return $return;
        }
    }

    /**
     * Function to get all comments
     *
     * @return object
     */
    public function getAllComments()
    {
        return $this->comments->get();
    }

    /**
     * Function to update the status for comments
     *
     * @param int $id
     * @param string $status
     * @return number
     */
    public function updateStatus($id, $status)
    {
        $comment = $this->comments = $this->comments->find($id);
        $comment->is_active = $status;
        $return = $comment->save();
        $this->notification->notify('comment', $id);
        return ($return) ? 1 : 0;

    }

    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings()
    {
        return ['heading' => [['name' => trans('video::videos.name'), 'value' => 'name', 'sort' => false], ['name' => trans('video::videos.student'), 'value' => '', 'sort' => false], ['name' => 'comments', 'value' => '', 'sort' => false], ['name' => trans('video::playlist.status'), 'value' => 'is_active', 'sort' => false], ['name' => trans('video::collection.added_on'), 'value' => '', 'sort' => false]]];
    }

    /**
     * Get headings for grid
     *
     * @return array
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->comments)->setEagerLoadingModels(['video' => function ($query) {
            $query->get();
        }, 'customer' => function ($query) {
            $query->get();
        }]);
        return $this;
    }

    /**
     * Function to apply filter for search of Comments grid
     *
     * @param mixed $searchComments
     * @return \Illuminate\Database\Eloquent\Builder $searchComments The builder object of comments grid.
     */
    protected function searchFilter($searchComments)
    {
        $searchRecordGroup = $this->request->has('searchRecord') && is_array($this->request->input('searchRecord')) ? $this->request->input('searchRecord') : [];
        $title = $is_active = null;
        extract($searchRecordGroup);
        if ($title) {
            $searchComments = $searchComments->where('title', 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $searchComments = $searchComments->where('is_active', $is_active);
        }
        return $searchComments;
    }
     /**
     * Function to get Comments for Particular Video
     *
     * @param mixed $searchComments
     * @return \Illuminate\Database\Eloquent\Builder 
     */

    public function browseVideoComments()
    {   
      
        $video_id=intval($this->request->video_id);
        $order_by=$this->request->has('order_by')?$this->request->order_by :'desc';
     
        $commentList    = $this->comments->where('video_id', $video_id)->whereNull('parent_id')->where('is_active',1)->orderBy('_id', $order_by)->paginate(config('access.perpage'));
       
        
        return $commentList;
    }
     /**
     * Function to get Comments for Particular Video
     *
     * @param mixed $searchComments
     * @return \Illuminate\Database\Eloquent\Builder 
     */

    public function browsereplyVideoComments()
    {
        $video_id=intval($this->request->video_id);
        $comment_id=$this->request->comment_id;  

        $order_by=$this->request->has('order_by')?$this->request->order_by :'desc';
        $replyComments=$this->comments->where('video_id',$video_id)->where('parent_id',$comment_id)->orderBy('_id',$order_by)->paginate(config('access.perpage'));
               
        return $replyComments;
    }
     /**
     * Function to get Comments for Particular Video
     *
     * @param mixed $searchComments
     * @return \Illuminate\Database\Eloquent\Builder 
     */
    public function browseallreplyVideoComments()
    {
        $video_id=intval($this->request->video_id);
        $comment_id=$this->request->comment_id;   
        $replyComments=$this->comments->where('video_id',$video_id)->where('parent_id',$comment_id)->orderBy('_id', 'desc')->paginate(config('access.perpage'));
               
        return $replyComments;

    }
     /**
     * Function to Delete Comments
     *
     * @param mixed $searchComments
     * @return \Illuminate\Database\Eloquent\Builder 
     */
    public function deleteComment()
    { 
        $comment_id=$this->request->comment_id;
        
        try {          

            $comment = Comment::where(function($query) use ($comment_id) {
                $query->where('_id', $comment_id)->orWhere('parent_id', $comment_id);
            })->delete();

            return ($comment) ? 1 : 0;
        } catch(\Exception $e) {
              
            return 0;
        }
        
    }
}