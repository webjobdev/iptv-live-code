<?php

/**
 * Admin UserGroup Repository
 *
 * To manage the functionalities related to the Admin UserGroup module from Admin UserGroup Controller
 * @name       AdminUserGroupRepository
 * @vendor Contus
 * @package User
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\User\Repositories;

use Contus\User\Contracts\IAdminUserGroupRepository;
use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Contus\User\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Auth;
use Illuminate\Support\Facades\Log;

class AdminUserGroupRepository extends BaseRepository implements IAdminUserGroupRepository
{


    public $_userGroup, $_user;

    /**
     * Construct method
     *
     * @vendor Contus
     * @package User
     * @param Contus\User\Models\UserGroup $userGroup
     * @param Contus\User\Models\User $user
     */
    public function __construct(UserGroup $userGroup, User $user)
    {
        parent::__construct();
        $this->_userGroup       = $userGroup;
        $this->_user            = $user;
        $this->setRules([
            'name'          =>  'required|unique:user_groups|max:50',
            'permissions'   =>  'required',
        ]);
    }

    /**
     * Fetch user group to display in admin block.
     *
     * @vendor Contus
     * @package User
     * @return response
     */
    public function getUserGroups($status)
    {
        return UserGroup::where('id', '!=', 1)->paginate(10);
    }
    /**
     * Fetch all user group to display in admin block.
     *
     * @vendor Contus
     * @package User
     * @return response
     */
    public static function getAllUserGroups()
    {
        return UserGroup::where('is_deletable', 1)->pluck('name', 'id');
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     * @package User
     * @return array
     */
    public function getGridHeadings()
    {
        return [
            StringLiterals::GRIDHEADING => [
                ['name' => trans('user::user.group_name'), 'value' => 'name', 'sort' => true],
                ['name' => trans('user::user.action'), 'value' => '', 'sort' => false]
            ]
        ];
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @package User
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        return $this->setGridModel($this->_userGroup)->setEagerLoadingModels(['users']);
    }

    /**
     * Store a newly created admin group.
     * Converts the permissions array to json format and saves in db.
     *
     * @param $id  input values
     *
     * @return response
     */
    public function addOrUpdateGroups($id = null)
    {
        if (!empty($id)) {
            $userGroup = $this->_userGroup->find($id);
            $this->setRule('name', 'required|unique:user_groups,name,' . $userGroup->id . '|max:50');
        } else {
            $userGroup = $this->_userGroup;
        }

        $newArray = [];
        $this->validate($this->request, $this->getRules());
        if (!empty($this->request->permissions)) {
            $newArray = array_merge($newArray, $this->request->permissions);
        }

        $userGroup->fill($this->request->except('_token'));
        $userGroup->permissions = json_encode(array_fill_keys($newArray, 1));
        return $userGroup->save();
    }

    /**
     * Fetch group to edit.
     *
     * @return response
     */
    public function getUserGroup($id)
    {
        $groupInfo = $this->_userGroup->find($id);
        $formatArray = json_decode($groupInfo->permissions, true);
        $result['key_info'] = $formatArray;
        $result['group_info'] = $groupInfo;
        return $result;
    }
    /**
     * Check the group name provied is unique group name.
     * check only if the request has the expected param
     *
     * @param int $id 
     * @return boolean
     */
    public function isUniqueGroupName($id = null)
    {
        return $this->isUniqueRequestValue($this->_userGroup, 'name', $id);
    }

    /**
     * Function to apply filter for search of user groups grid
     *
     * @vendor Contus
     * @package User
     * @param mixed $builderUserGroups
     * @return \Illuminate\Database\Eloquent\Builder $builderUserGroups The builder object of user groups grid.
     */
    protected function searchFilter($builderUserGroups)
    {
        $searchRecordUserGroups = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];

        /**
         * Loop the search fields of user groups grid and use them to filter search results.
         */
        foreach ($searchRecordUserGroups as $key => $value) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderUserGroups = $builderUserGroups->where($key, 'like', "%$value%")->where('is_deletable', 1);
        }

        return $builderUserGroups;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        return $builder;
    }
}
