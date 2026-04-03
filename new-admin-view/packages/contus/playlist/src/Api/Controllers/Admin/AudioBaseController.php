<?php

/**
 * AudioBaseController
 *
 * @name       AudioBaseController
 * @vendor     Contus
 * @package    Audio
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;

class AudioBaseController extends ApiController
{
    /**
     * Class construct method initialization
     */
    public function __construct(UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->uploadRepository = $uploadRepository;
    }
    /**
     * Method to upload thumbnail
     * 
     * @vendor Contus
     * @return Illuminate\Http\Response
     */
    public function postUploadThumbnail(){
        $moduleName = $this->request->module;
        $tempImageInfo = $this->uploadRepository->setModelIdentifier($moduleName)->tempPrepare()->tempUpload($moduleName, $this->request->size);
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('base::audio.messsage.unable_to_upload'))
            : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }
}