<?php

/**
 * AudioController
 *
 * To manage the audio management such as upload, create, edit and delete
 *
 * @name AudioController
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Api\Controllers\Admin;

use Contus\Audio\Helpers\UploadHandler;
use Contus\Audio\Repositories\AudioBaseRepository;
use Contus\Audio\Repositories\AudioRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as Makefile;

class AudioController extends ApiController
{
    /**
     * Class construct method initialization
     */
    public function __construct()
    {
        parent::__construct();
        $this->repository = new AudioRepository();
        $this->audioBaseRepository = new AudioBaseRepository();
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }
    /**
     * Method to handle file upload using fine uploader js.
     *
     * @vendor contus
     * @return void
     */
    public function postHandleFineUploader(){
        $type =  $this->request->type;
        $localChunkPath = ($type && $type == 'audioAds')?config("contus.audio.audiomedia.audioAds.temporary_storage_path_chunks"):config("contus.audio.audiomedia.audio.temporary_storage_path_chunks");
        $localFilePath = ($type && $type == 'audioAds')?config("contus.audio.audiomedia.audioAds.temporary_storage_path"):config("contus.audio.audiomedia.audio.temporary_storage_path");
        $chunksFolder = base_path('public' . DIRECTORY_SEPARATOR . $localChunkPath);
        $filesFolder = base_path('public' . DIRECTORY_SEPARATOR . $localFilePath);
        
        if (!file_exists(base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'audios'))) {
            Makefile::makeDirectory(base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'audios'), 0777, true, true);
        }
        if (!file_exists($filesFolder)) {
            Makefile::makeDirectory($filesFolder, 0777, true, true);
        }
        if (!file_exists($chunksFolder)) {
            Makefile::makeDirectory($chunksFolder, 0777, true, true);
        }
        $uploader = new UploadHandler();
        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        // all files types allowed by default
        $uploader->allowedExtensions = array();
        // Specify max file size in bytes.
        $uploader->sizeLimit = null;
        // Specify the input name set in the javascript.
        // matches Fine Uploader's default inputName value by default
        $uploader->inputName = "qqfile";
        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = $chunksFolder;
        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "POST") {
            header("Content-Type: text/plain");
            // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
            // For example: /myserver/handlers/endpoint.php?done
            if (isset($_GET["done"])) {
                $result = $uploader->combineChunks($filesFolder);
                $result["uploadName"] = $uploader->getUploadName();
            } else {
                // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                $result = $uploader->handleUpload($filesFolder);
                $result["uploadName"] = $uploader->getUploadName();
            }
            echo json_encode($result);
        } else if ($method == "DELETE") {
            $result = $uploader->handleDelete($filesFolder);
            echo json_encode($result);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    }
    /**
     * Method to save new audios
     *
     * @vendor Contus
     * @return \Illuminate\Http\Response
     */
    public function postAdd()
    {
        $audioId = $this->repository->addAudio();
        return ($audioId) ? $this->getSuccessJsonResponse(['audio' => $audioId], trans('audio::audio.messages.audio_upload_success'))
        : $this->getErrorJsonResponse([], trans('audio::audio.messages.audio_upload_error'));
    }

    /**
     * Add the specified resource in storage.
     *
     *
     *
     * @return \Illuminate\Http\Response
     */

    public function postAddAudio()
    {
        $addAudio = $this->repository->addOrUpdateAudio();
        if ($addAudio) {
            $isAudioAdd = false;
            if ($addAudio) {
                $isAudioAdd = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::audio.added'));
            }
            return ($isAudioAdd) ? $this->getSuccessJsonResponse([
                StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::audio.success.added'),
            ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::audio.error.added')]);
        } else if ($addArtist == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::audio.session_expire'));
        } else {
            return $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::audio.error.updated')]);
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
        $editAudio = $this->repository->addOrUpdateAudio($id);
        $isAudioEdit = false;
        if ($editAudio) {
            $isAudioEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::audio.updated'));
        }
        return ($isAudioEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::audio.success.updated'),
        ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::audio.error.updated')]);
    }

    /**
     * Method to get basic informations
     *
     * @vendor Contus
     * @return Illuminate\Http\Response
     */
    public function getInfo()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'artists' => $this->audioBaseRepository->getAllAudioArtists(),
                'audio_language' => $this->audioBaseRepository->getAllAudioLanguageCategory(),
                'albums' => $this->repository->getAllAlbum(),
                'rules' => $this->repository->getRules(),
            ],
        ]);
    }

    /**
     * Function to archive Audio in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postDeleteAction()
    {
        $result = '';
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            $isActionCompleted = $this->repository->audioDelete($this->request->input(StringLiterals::SELECTED_CHECKBOX));
            if ($this->request->get('status') == 'single-delete-action') {
                $result = $isActionCompleted ? $this->getSuccessJsonResponse([], trans('audio::audio.message.delete-success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('status') == 'bulk-delete-action') {
                $result = $isActionCompleted ? $this->getSuccessJsonResponse([], trans('audio::audio.message.bulk-delete-success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }

        }
        return $result;
    }

    /**
     * Function to bulk activate or deactivate the audios in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postBulkUpdateStatus()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('isStatus') == 'activate') {
                $isActionCompleted = $this->repository->ActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'activate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('audio::audio.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->ActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('audio::audio.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }
    /**
     * get Detail Audio view and edit template
     *
     * @return \Illuminate\Http\View
     */
    public function getAudioToEdit($id)
    {
        $getAudio = $this->repository->getAudio($id);
        return (is_null($getAudio)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $getAudio]);
    }

    /**
     * Function to get complete audio details of a audio.
     *
     * @param integer $id
     * The id of the audio whose details are to be fetched.
     * @return \Contus\Base\response A JSON string which contains all the information of the video.
     */
    public function getCompleteAudioDetails($id)
    {
        $audioDetails = $this->repository->getCompleteAudioDetails($id);
        return (is_null($audioDetails)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $audioDetails]);
    }

}
