<?php

namespace Contus\User\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\User\Repositories\AdminUserRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\User\Repositories\AdminUserGroupRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Auth;

class NewAuthController extends ApiController {
    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'info' =>
            [
                'rules' =>
                [
                    'email' => 'required|email|max:255',
                    'password' => 'required'
                ]
            ]
        ]);
    }
}
