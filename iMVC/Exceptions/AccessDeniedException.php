<?php

namespace iMVC\Exceptions;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of accessDeniedException
 *
 * @author dariush
 */
require_once 'AppException.php';

class AccessDeniedException extends \iMVC\Exceptions\AppException
{
    public function __construct($message = null, $code = null, $previous = null) {
        parent::__construct(strlen($message)?$message:"Access denied to <b>".$_SERVER['REQUEST_URI']."</b>.", $code, $previous);
        $this->SendErrorCode(403);
    }
}

?>
