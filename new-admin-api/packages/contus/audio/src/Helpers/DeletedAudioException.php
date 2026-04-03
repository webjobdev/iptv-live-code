<?php
/**
 * DeletedAudioException Helper file
 *
 * @name DeletedAudioException
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Helpers;

use Exception;

class DeletedAudioException extends Exception {
    public function errorMessage() {
        //error message
        return 'Error on line '.$this->getLine().' in '.$this->getFile().': <b>'.$this->getMessage().'</b> is a deleted audio';
    }
}
