<?php

/**
 * Genre Controller
 *
 * To manage the Audio Genre.
 *
 * @name       Genre Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Api\Controllers\Admin;

use Contus\Audio\Repositories\GenreRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Http\Request;

class GenreController extends ApiController
{

    /**
     * Construct method
     */

    public function __construct(GenreRepository $genreRepository)
    {
        parent::__construct();
        $this->repository = $genreRepository;
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
        $addGenre = $this->repository->addOrUpdateGenre();
        if ($addGenre) {
            $isGenreAdd = false;
            if ($addGenre) {
                $isGenreAdd = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::genres.added'));
            }
            return ($isGenreAdd) ? $this->getSuccessJsonResponse([
                StringLiterals::STATUS => 'success',
                StringLiterals::MESSAGE => trans('audio::genres.success.added'),
            ]) : $this->getErrorJsonResponse([
                [
                    StringLiterals::STATUS => 'error',
                    StringLiterals::MESSAGE => trans('audio::genres.error.added'),
                ],
            ]);
        } else if ($addGenre == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::genres.session_expire'));
        } else {
            return $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::genres.error.updated')]);
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
        $editGenre = $this->repository->addOrUpdateGenre($id);
        $isGenreEdit = false;
        if ($editGenre) {
            $isGenreEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::genres.updated'));
        }
        return ($isGenreEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::genres.success.updated'),
        ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::genres.error.updated')]);
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
                    'Active',
                ],
            ],
        ]);
    }

}
