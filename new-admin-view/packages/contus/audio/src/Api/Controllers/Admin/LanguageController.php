<?php

/**
 * Language Controller
 *
 * To manage the Audio Language.
 *
 * @name       Language Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Audio\Repositories\LanguageRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;

class LanguageController extends ApiController
{
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     */

    public function __construct(LanguageRepository $languageRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $languageRepository;
        $this->uploadRepository = $uploadRepository;
    }
    /**
     * get Information for create form
     * return various information request by the form
     * request will be having query param which refer to language
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd()
    {
        return $this->getSuccessJsonResponse([
            StringLiterals::RULES => $this->repository->getRules(),
        ]);
    }

    /**
     * Add the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    public function postAdd()
    {
        $addLanguage = $this->repository->addOrUpdateLanguage();
        if ($addLanguage) {
            $isLanguageAdd = false;
            if ($addLanguage) {
                $isLanguageAdd = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::languages.added'));
            }
            return ($isLanguageAdd) ? $this->getSuccessJsonResponse([
                StringLiterals::STATUS => 'success',
                StringLiterals::MESSAGE => trans('audio::languages.success.added')
            ]) : $this->getErrorJsonResponse([
                [
                    StringLiterals::STATUS => 'error',
                    StringLiterals::MESSAGE => trans('audio::languages.error.added')
                ]
            ]);
        } else if ($addLanguage == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::languages.session_expire'));
        } else {
            return $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::languages.error.updated')]);
        }
    }

    /**
     * Add the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    public function postEdit($id)
    {
        $editLanguage = $this->repository->addOrUpdateLanguage($id);
        $isLanguageEdit = false;
        if ($editLanguage) {
            $isLanguageEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::languages.updated'));
        }
        return ($isLanguageEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::languages.success.updated')
        ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::languages.error.updated')]);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo()
    {

        return $this->getSuccessJsonResponse([
            'info' => [
                StringLiterals::RULES => $this->repository->getRules(),
                'locale' => trans('validation'),
                'isActive' => [
                    'In-active',
                    'Active'
                ],
            ]
        ]);
    }

}
