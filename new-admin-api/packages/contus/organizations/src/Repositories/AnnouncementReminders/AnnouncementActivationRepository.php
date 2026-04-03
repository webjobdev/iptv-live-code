<?php

namespace Contus\Organizations\Repositories\AnnouncementReminders;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrgAncActivation;

class AnnouncementActivationRepository extends Repository
{

    protected $_announcmentActivation;

    public function __construct(OrgAncActivation $orgAnnouncementActivation)
    {
        parent::__construct();
        $this->_announcmentActivation = $orgAnnouncementActivation;
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_announcmentActivation);
        return $this;
    }


    public function addAnnouncementReminder()
    {
        $this->setRules([
            'subject' => 'required|string',
            'message' => 'required|string',
            'activation_agree' => 'nullable'
        ]);

        $this->_validate();

        $announcementActivation = new OrgAncActivation();
        $announcementActivation->organization_id = $this->request->input('organization_id');
        $announcementActivation->subject = $this->request->input('subject');
        $announcementActivation->message = $this->request->input('message');
        $announcementActivation->activation_agree = $this->request->input('activation_agree');
        $announcementActivation->save();

        return response()->json([
            'status' => true,
            'data' => $announcementActivation,
            'message' => trans('organizations::index.activation-add.success'),
        ]);
    }
}
