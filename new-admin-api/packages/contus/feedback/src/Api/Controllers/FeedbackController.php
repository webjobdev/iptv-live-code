<?php

namespace Contus\Feedback\Api\Controllers;

use Contus\Base\ApiController;
use Contus\Feedback\Repositories\FeedbackRepository;

class FeedbackController extends ApiController {

    public function __construct(FeedbackRepository $feedbackRespository) {
        parent::__construct();
        $this->repository = $feedbackRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addFeedback()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('feedback::index.add.success')])
                : $this->getErrorJsonResponse([], trans('feedback::index.add.error'));
        }
    }
}
