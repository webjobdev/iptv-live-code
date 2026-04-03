<?php

namespace Contus\Feedback\Repositories;

use Contus\Base\Repository;
use Contus\Feedback\Model\Feedback;
use Illuminate\Support\Facades\Log;

class FeedbackRepository extends Repository {

    protected $_feedback;

    public function __construct(Feedback $feedback) {
        parent::__construct();
        $this->_feedback = $feedback;
    }

    public function prepareGrid() {
        $this->setGridModel($this->_feedback);
        return $this;
    }

    public function addFeedback() {
        $this->setRules([
            'subject' => 'required',
            'message' => 'required',
            'image' => 'nullable',
        ]);

        $feedback = new Feedback();
        $feedback->subject = $this->request->input('subject');
        $feedback->message = $this->request->input('message');
        if ($this->request->hasFile('image')) {
            $file = $this->request->file('image');
            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('uploads/feedback');
                $file->move($destinationPath, $filename);
                $feedback->image = 'uploads/feedback/' . $filename;
            }
        }
        $feedback->save();

        return response()->json([
            'success' => true,
            'message' => trans('feedback::index.add.success'),
        ]);
    }

    public function getGridHeadings() {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('api-access::index.subject'), 'value' => 'subject', 'sort' => true, 'class' => false],
            ['name' => trans('api-access::index.message'), 'value' => 'message', 'sort' => true, 'class' => false],
            ['name' => trans('api-access::index.image'), 'value' => 'image', 'sort' => true, 'class' => false],
        ]];
    }
}
