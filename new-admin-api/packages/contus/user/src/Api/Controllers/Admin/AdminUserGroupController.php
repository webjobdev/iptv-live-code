<?php

namespace Contus\User\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\User\Repositories\AdminUserGroupRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Auth;

class AdminUserGroupController extends ApiController {
    
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     */
    public function __construct(AdminUserGroupRepository $adminUserGroupRepository, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->repository = $adminUserGroupRepository;
        $this->uploadRepository = $uploadRepository;
    }
    /**
     * Method to get the info for admin user group
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function getInfo(){
        return $this->getSuccessJsonResponse(
            [
                'info' =>
                [
                    'rules' => [ 
                        'name'          =>  'required|unique:user_groups|max:50',
                        'permissions'   =>  'required'
                    ]
                ],
                'access_modules' => Config()->get('access.modules')   
            ]
        );
    }
    public function getUserGroup() {
        $getUserGroup = $this->repository->getAllUserGroups ();
        return (is_null ( $getUserGroup )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [ 'response' => $getUserGroup ] );
    }
    /**
     * Store a newly created groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $result = $this->repository->addOrUpdateGroups ();
        return ($result) 
        ? $this->getSuccessJsonResponse ([],trans( 'user::adminuser.user_group_add_success' ))
        : $this->getErrorJsonResponse([],trans( 'user::adminuser.user_group_add_error' ));
    }
    /**
     * Store a newly created groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id) {
        $result = $this->repository->addOrUpdateGroups ($id);
        return ($result) 
        ? $this->getSuccessJsonResponse ([],trans( 'user::adminuser.user_group_update_success' ))
        : $this->getErrorJsonResponse([],trans( 'user::adminuser.user_group_update_error' ));
    }
    /**
     * Method to fetch the edit info of usergroup
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEditInfo($id){
        $result = $this->repository->getUserGroup($id);
        return ($result) 
        ? $this->getSuccessJsonResponse (['data'=> $result],trans( 'user::adminuser.updated' ))
        : $this->getErrorJsonResponse([],trans( 'user::adminuser.updatedError' ));
    }
}