<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FolderController
 *
 * @author dariush
 */
class ProfileController extends \iMVC\Controller\BaseController
{
    
    /**
     * TEMPLATE
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to 
     * @passed_params <i>as follow</i>
     * @|-URL
     * @|-POST
     * @|-GET
     * @view_variables 
     * @redirect <i>as follow</i>
     * @|-on_success
     * @|-on_failure
     * @session_settings
     * @cookie_settings
     */
    /**
     * @redirect <i>as follow</i>
     * @|-on_failure if not logged in to /auth/login.mini
     */
    public function Initiate() {
        if(!User::IsLoggedIn() && !$this->request->IsGET())
        {
            header("location: /auth/login");
            exit;
        }
        $this->layout->SetLayout("simple");
    }
    /**
     * The Index Action
     * @throws iMVC\Exceptions\NotImplementedException Always
     */
    public function IndexAction() {
        throw new iMVC\Exceptions\NotImplementedException(__METHOD__." is not implemented ....");
    }
    
    
    /**
     * The Edit Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-POST <b>step:</b> shows current step #<br /><b>hs:</b> hash-sum of <i>{'/user/profile/create/s/'.$_POST['step'].'/hs/'.date('Y-M-D').$_POST['step'].'create'}</i><br /><b>back:</b> if we are doing backward steps<br /><b>next:</b> if we are doing forward steps<br /><b>skip:</b> if we are skipping profile editting<br />
     * @view_variables <b>post_back:</b> to indicate that this is post back to page.<br /><b>Step:</b> shows the step # that we are in<br /><b>post:</b> holds previous step's content<br />
     * @redirect <i>as follow</i>
     * @|-on_success /
     * @|-on_failure /profile/edit if <b>!isset($_POST['step'])</b> or <b>miss-hashed</b>
     * @session_settings sets and finaly unsets <b>$_SESSION['app']['tmp'][__CLASS__][__METHOD__]</b> to hold steps data
     * @throws appException if Invalid step count encountered.
     */
    public function EditAction()
    {
        $this->layout->SetLayout('print');
        if($this->request->IsPOST())
        {
            //\iMVC\Tools\Debug::_var($this->request->POST);
            //\iMVC\Tools\Debug::_var($_SESSION['app']['tmp']);
        }
        else
        {
            if(!isset(User::GetInstance()->userprofile))
                $this->view->post_back = false;
            return;
        }
        
        if(!isset($_POST['step']) || !$_POST['step'] || \iMVC\Security\Hash::Generate('/user/profile/create/s/'.$_POST['step'].'/hs/'.date('Y-M-D').$_POST['step'].'create') != $_POST['hs'])
        {
            unset($_POST);
            header("location: /profile/edit");
            exit;
        }
        // getting the current posted step counter 
        $step = $_POST['step'];
        // we picked up our logical useful data then 
        // unset any junk data in $_POST
        unset($_POST['hs'], $_POST['step']);
        switch(true)
        {
            case array_key_exists('back', $_POST):
                // release useless data
                unset($_POST['back']);
                // for temp save the current step's data into session
                $_SESSION['app']['tmp'][__CLASS__][__METHOD__]['steps']["step".($step)] = $_POST;
                // backwarding the step counter
                $step--;
                // safty check point
                if($step<1)
                {
                    header('location: /profile/create');
                }
                // load previous step with it's content
                $this->view->Step = $step;
                $this->view->post_back = true;
                $this->view->post = $_SESSION['app']['tmp'][__CLASS__][__METHOD__]['steps']["step".($step)];
                break;
            case array_key_exists('next', $_POST):
                    // purize the $_POST array
                    unset($_POST['next']);
                    // In here we need to add user's each step's 
                    // data into a tmp storage so we save it into session
                    switch($step)
                    {
                       /* 
                        * We need to process every each steps' data
                        * So we save them in session for now
                        */
                        case 1:
                            // since this is the step 1 for precaution 
                            // we clear any previous tmp object realted 
                            // to this method
                            /*
                             * this lead to data lose if we backward from stage 2
                             * to stage 1, them all possible data stored in stage 2 or 3
                             * will be lost
                             */
                            // unset($_SESSION['app']['tmp'][__CLASS__][__METHOD__]);
                        case 2:
                        case 3:
                            // setting into session with unique relative {step} name 
                            $_SESSION['app']['tmp']
                                [__CLASS__][__METHOD__]
                                    ['steps']["step".($step)] = $_POST;

                            break;
                        default:
                            goto __SECURITY_CRACK;
                    }
                   /* 
                    * if the this is the final them
                    * we need to purge tmp data which 
                    * has been save into session into db
                    */
                    if($step==3)
                    {
                        $profile_data = array();
                        foreach(
                                $_SESSION['app']['tmp'][__CLASS__][__METHOD__]['steps'] as
                                $step => $data
                                )
                        {
                            $profile_data = array_merge($profile_data, $data);
                        }
                        \iMVC\Tools\Debug::_var($profile_data);
                        // load the obtained data into db
                        Userprofile::CreateProfileFromArray(User::GetInstance()->user_id, $profile_data);

                        // load created profile into session's user object
                        $user = User::GetInstance();
                        User::Logout();
                        User::Login($user->email, $user->password, 0);

                        // release tmp allocations
                        unset($_SESSION['app']['tmp'][__CLASS__]);
                        // head to the /user/account/index
                        header('location: /account');
                        exit;
                    }
                    // move into the next step
                    $step++;
                    // there is a data in next step! 
                    // we need to load it.
                    if(array_key_exists("step".($step), $_SESSION['app']['tmp'][__CLASS__][__METHOD__]['steps']))
                    {
                        $this->view->post = $_SESSION['app']['tmp'][__CLASS__][__METHOD__]['steps']["step".($step)];
                        $this->view->post_back = true;
                    }
                    $this->view->Step = $step;
                    return;
                break;
            case array_key_exists('skip', $_POST):
                // we need to load privious data if neccessry
                switch($step)
                {
                    case 1:
                    case 2:
                    case 3:
                        $profile_data = array();
                        foreach(
                                $_SESSION['app']['tmp'][__CLASS__][__METHOD__]['steps'] as
                                $step => $data
                                )
                        {
                            $profile_data = array_merge($profile_data, $data);
                        }
                        // if the user skipped just create a empty prfile in database
                        Userprofile::CreateProfileFromArray(User::GetInstance()->user_id, $profile_data);
                        $user = User::GetInstance();
                        User::Logout();
                        User::Login($user->email, $user->password, 0, 0, 1);
                        // release tmp allocations
                        unset($_SESSION['app']['tmp'][__CLASS__]);
                        // in here we are sure that the user profile created in db
                        // we just redirect the user to site's root page
                        header('location: /');
                        exit;
                    default:
                        // security failure! the app should not never ever 
                        // come to this case unless there are more steps 
                        // that are not counted here.
                        goto __SECURITY_CRACK;
                }
                break;
            default:
__SECURITY_CRACK:
                // just re-fix the counter, and start with step 1
                $this->view->Step = 1;
                exit;
                // an alter native action
                throw new appException("Invalid step count!($step)");
                break;
        }
    }
}
