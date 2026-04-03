<?php

/**
 * Email Templates Repository
 * To manage the functionalities related to the Customer module from Email Templates Controller
 *
 * @name EmailTemplatesRepository
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Cms\Models\EmailTemplates;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\EmailTemplatesTranslation;


class EmailTemplatesRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the email template object
     *
     * @var object
     */
    protected $_emailTemplates;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Cms
     *
     * @param Contus\Cms\Models\EmailTemplates $emailTemplates
     */
    public function __construct(EmailTemplates $emailTemplates) {
        parent::__construct ();
        $this->_emailTemplates = $emailTemplates;
    }
    /**
     * Store a newly created email template or update the email template.
     * @vendor Contus
     *
     * @package Cms
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateEmailTemplates($id = null) {
        if (! empty ( $id )) {
            $emailTemplates = $this->_emailTemplates->find ( $id );
            if (! is_object ( $emailTemplates )) {
                return false;
            }
            $this->setRules ( [ 'name' => 'sometimes|required','is_active' => 'sometimes|required|boolean','subject' => 'sometimes|required','content' => 'sometimes|required' ] );
            $emailTemplates->updator_id = \Auth::user()->id;
        } else {
            $this->setRules ( [ 'name' => 'required|max:255','subject' => 'required','content' => 'required' ] );
            $emailTemplates = new EmailTemplates ();
            $emailTemplates->is_active = 1;
            $emailTemplates->creator_id = \Auth::user()->id;
        }
        $this->_validate ();
        $emailTemplates->fill ( $this->request->except ( '_token' ) );
        return ($emailTemplates->save ()) ? 1 : 0;
    }

    public function updateEmailTemplatesLanguage($id) {
        if(!empty($id)) {
            $this->setRules([
                'name' => StringLiterals::REQUIRED, 
                'subject' => StringLiterals::REQUIRED,
                'content' => StringLiterals::REQUIRED,
            ]);
            $this->validate($this->request, $this->getRules());
            $emailtemplate_translation;
            if (EmailTemplatesTranslation::where('email_template_id', '=', $id)->where('language_id', '=' ,$this->request->languageCode)->count() > 0) {
                $emailtemplate_translation = EmailTemplatesTranslation::where('email_template_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $emailtemplate_translation = new EmailTemplatesTranslation;
                $emailtemplate_translation->email_template_id = $id;
                $emailtemplate_translation->language_id = $this->request->languageCode;
            }
            // dd($video_tramslation);
            $emailtemplate_translation->name = $this->request->name;
            $emailtemplate_translation->subject = $this->request->subject;
            $emailtemplate_translation->content = $this->request->content;
            if ($emailtemplate_translation->save()) {
                $isEmailTemplate = true;
            }
            else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Get one email template using id
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return object
     */
    public function getEmailTemplates($id) {
        return $this->_emailTemplates->select ( [ 'id','name','slug','subject','content','is_active' ] )->with('EmailTemplatesTranslation')->find ( $id );
    }
    /**
     * Get all email templates
     *
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getAllEmailTemplates() {
        return $this->_emailTemplates->select ( [ 'id','name','slug','subject','content','is_active' ] )->paginate ( 10 )->toArray ();
    }
    /**
     * Delete one Email template using ID
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return boolean
     */
    public function deleteEmailTemplate($id) {
        $data = $this->_emailTemplates->find ( $id );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }
    public function fetchEmailTemplate($slug) {
        return $this->_emailTemplates->where ( 'slug', $slug )->first ();
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Cms
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_emailTemplates );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($emailBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
         return $emailBuilder->selectRaw('email_templates.*, email_templates.id as formatted_created_date');
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Cms
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderEmail) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderEmail = $builderEmail->where ( $key, 'like', "%$value%" );
        }

        return $builderEmail;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::emailtemplate.name' ),StringLiterals::VALUE => 'name','sort' => false ],[ 'name' => trans ( 'cms::emailtemplate.subject' ),StringLiterals::VALUE => '','sort' => false ],
        [ 'name' => trans ( 'cms::emailtemplate.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::emailtemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }
}