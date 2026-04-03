<?php

namespace Contus\Organizations\Repositories\AnnouncementReminders;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrgAncActivation;
use Contus\Organizations\Model\OrgAncDisableAcc;

class AnnouncementDisableAccRepository extends Repository
{

    protected $_announcmentDisableAcc;

    public function __construct(OrgAncDisableAcc $orgAnnouncementDisableAcc)
    {
        parent::__construct();
        $this->_announcmentDisableAcc = $orgAnnouncementDisableAcc;
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_announcmentDisableAcc);
        return $this;
    }


    public function addDisableAcc()
    {
        $this->setRules([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $this->_validate();

        $announcementDisableAcc = new OrgAncDisableAcc();
        $announcementDisableAcc->organization_id = $this->request->input('organization_id');
        $announcementDisableAcc->subject = $this->request->input('subject');
        $announcementDisableAcc->message = $this->request->input('message');
        $announcementDisableAcc->save();

        return response()->json([
            'status' => true,
            'data' => $announcementDisableAcc,
            'message' => trans('organizations::index.account-add.success'),
        ]);
    }
}
