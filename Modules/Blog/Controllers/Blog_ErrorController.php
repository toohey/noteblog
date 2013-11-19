<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * /blog/error
 */
class Blog_ErrorController extends \iMVC\Controller\BaseController
{
    /**
     * @responds_type <b>ANY</b>
     * @view_variables <b>error:</b> the unserialized exception from users' session
     * @redirect <i>as follow</i>
     * @|-on_failure in <b>!isset($_SESSION['app']['tmp']['exception'])</b> to /
     * @session_settings <b>unset($_SESSION['app']['tmp']['exception'])</b>
     */
    public function Initiate() {
        if(!isset($_SESSION['app']['tmp']['exception']))
        {
            header('location: /');
            exit;
        }
        $this->layout->SetLayout('print');
        $this->view->error = unserialize($_SESSION['app']['tmp']['exception']['e']);
        $this->SetRequest(unserialize($_SESSION['app']['tmp']['exception']['r']));
        unset($_SESSION['app']['tmp']['exception']);
        if(method_exists($this->view->error, 'SendErrorCode'))
        {
            $this->view->error->SendErrorCode();
        }
    }
    /**
     * The Index Action
     * @see Initiate()
     */
    public function IndexAction() 
    {
        if(isset($_GET['ajax']))
        {
            echo "<div class='alert alert-error text-center'><b>{$this->view->error->getMessage()}!</b></div>";
            exit;
        }
    }
}
