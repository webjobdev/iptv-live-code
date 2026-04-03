<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\SubscriberNoteRepository;

/**
 * SubscriberNoteController class
 *
 * This class is used to manage subscriber notes in the admin panel.
 */

class SubscriberNoteController extends ApiController {
    /**
     * SubscriberNoteController constructor.
     *
     * @param SubscriberNoteRepository $subscriberNoteRepository
     */

    public function __construct(SubscriberNoteRepository $subscriberNoteRepository) {
        parent::__construct();
        $this->repository = $subscriberNoteRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->postAdd()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.subscriber_note.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.subscriber_note.error'));
        }
    }

    public function postEdit($noteId) {
        $isUpdated = false;
        if ($this->repository->postAdd($noteId)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.subscriber_note_edit.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.subscriber_note_edit.error'));
        }
    }
}
