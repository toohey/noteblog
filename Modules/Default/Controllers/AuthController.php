<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthController
 *
 * @author dariush
 */
class AuthController extends \iMVC\Controller\BaseController
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
    public function Initiate()
    {
    }
    /**
     * The Index Action
     * @throws iMVC\Exceptions\NotImplementedException Always
     */
    public function IndexAction() {
        throw new iMVC\Exceptions\NotImplementedException(__METHOD__." is not implemented ....");
    }
    
    /**
     * The Login Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY:</b> for all respond type responds <i>html</i>
     * @passed_param 
     * @|-POST <b>email</b>, <b>password</b>, <b>remmember_me<b><i>(OPTIONAL)</i>
     * @redirect <i>as follow</i>
     * @|-on_success /
     * @|-on_failure /
     * @session_settings <b>['app']['errors'][]</b> if username or password didn't match.
     * @cookie_settings if checked <i>remmeber_me</i> we set a secure <i>uid</i>.
     * @view_variables 
     */
    public function LoginAction()
    {
        $this->layout->SetLayout('simple');
        $this->view->error = array('password'=>'', 'email'=>'');
        if($this->request->IsPOST())
        {    
            $email = $this->request->POST['email'];
            $password = $this->request->POST['password'];
            $remmber = isset($this->request->POST['remmeber_me']) && $this->request->POST['remmeber_me']==='on';
            $u = User::Login($email, $password, 1, $remmber);
            if(!$u)
            {
                $this->view->error['general'][] ="Invalid User or password!";
            }
            else
            {
                header("location: /");
                exit;
            }
        }
        $t = $this;
        $this->ToRespond(function($type) use($t)
        {
            if($type!='html')
                $t->layout->SuppressLayout();
            if($type !== "info" && User::IsLoggedIn())
            {
                header('location: /');
                exit;
            }
            $t->view->type = $type;
        });
    }
    
    /**
     * 
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>.html</b><br /><b>.menu</b> & <b>.raw</b> in both the layout would be suppressed.
     * @passed_params <i>as follow</i>
     * @|-URL
     * @|-POST <b>email</b>, <b>password</b>, <b>conf_pass</b>
     * @|-GET
     * @view_variables <b>error['general'][]</b> if any error happens
     * @redirect <i>as follow</i>
     * @|-on_success /profile/edit
     * @|-on_failure /auth/signup
     * @session_settings <b>see<b> LoginAction
     * @cookie_settings <b>see<b> LoginAction
     */
    public function SignupAction()
    {
        if(User::IsLoggedIn())
        {
            header('location: /');
            exit;
        }
        $this->layout->SetLayout('simple');
        $this->view->error = array('password'=>'', 'email'=>'');
        if($this->request->IsPOST())
        {
            if($this->request->POST['password']!=$this->request->POST['conf_pass'])
            {
                $this->view->error['password'] = "The passwords do not match!";
                return;
            }
            $email = $this->request->POST['email'];
            $password = $this->request->POST['password'];
            $u = new \User;
            try
            {
                $u->Join($email, $password);
                header("location: /profile/edit");
                exit;
            }
            catch(Exception $e)
            {
                switch (true)
                {
                    case iMVC\Tools\String::Contains($e->getMessage(), "SQLSTATE[23000]"):
                        $this->view->error['general'][] = "A user with '<b>$email</b>' email address is currently exists!";
                        break;
                    default:
                        $this->view->error['general'][] = $e->getMessage();
                }
                return;
            }
        }
        $t = $this;
        $this->ToRespond(function ($type) use($t)
        {
            switch($type)
            {
                case 'menu':
                case 'raw':
                    $t->layout->SuppressLayout();
                    break;
            }
            $t->view->type = $type;
        });
    } 
    
    /** 
     * The Logout Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @passed_params <i>as follow</i>
     * @redirect <i>as follow</i>
     * @|-on_success /
     * @|-on_failure /
     * @session_settings remove <i>session</i> settings
     * @cookie_settings remove only <i>uid</i> in <i>cookie</i> settings
     */
    public function LogoutAction()
    {
        $this->view->SuppressView();
        User::Logout();
        header("location: /");
    }
    
    public function ResetAction()
    {
        $this->layout->SetLayout('print');
        $this->ToRespond(function($type, $_this){
                $_this->view->type = $type;
                switch($type)
                {
                    default:
                        $_this->IsGETSecured(array(session_id()));
                        $_this->view->type = "html";
                        break;
                    case 'mail':
                        $_this->IsGETSecured(array());
                        $_this->IsSecure($_GET, array('link'));
                        $_this->view->link = $_GET['link'];
                        $_this->layout->SuppressLayout();
                        $_this->view->SetView("resetmail");
                        break;
                    case 'action':
                        if(!$_this->request->IsPOST()) throw new iMVC\Exceptions\InvalideOperationException;
                        $_this->IsSecure($_POST, array('password', 'conf_pass', 'email', 'expire'));
                        
                        $_this->IsGETSecured(array(session_id(), $_POST['email'], $_POST['expire']));
                        
                        if(time() > $_POST['expire'])
                            throw new iMVC\Exceptions\InvalideOperationException("The reset link has been expired please request an other one!");
                        
                        if(!User::exists(array('conditions'=>array('email'=>$_POST['email']))))
                            throw new \iMVC\Exceptions\NotFoundException("The user does not exists!");
                        
                        if($_this->IsSecure($_POST, array(), array('password' => $_POST['conf_pass']), 0))
                        {
                            $u = User::find(array('conditions'=>array('email'=>$_POST['email'])));
                            $u->password = User::Hashgen($_POST['password']);
                            $u->save();
                            User::Login($_POST['email'], $_POST['password'], 1, isset($_POST['remmeber_me']));
                            header('location: /');
                            exit;
                        }
                        $_this->view->type = "configure";
                        $_this->view->error=array('password' => "passwords do not match!");
                        $_GET['u'] = $_POST['email'];
                        $_GET['e'] = $_POST['expire'];
                        foreach(explode("&", $_this->GetSecureGET(array($_GET['u'], $_GET['e']))) as $value)
                        {
                            if(!strlen($value)) continue;
                            $value = explode("=", $value);
                            $_GET[$value[0]] = $value[1];
                        }
                    case 'configure':
                        $_this->IsSecure($_GET, array('u', 'e'));
                        $_this->IsGETSecured(array($_GET['u'], $_GET['e']));
                        
                        if(time() > $_GET['e'])
                            throw new iMVC\Exceptions\InvalideOperationException("The reset link has been expired please request an other one!");
                        
                        $_this->view->email = $_GET['u'];
                        $_this->view->expire = $_GET['e'];
                        break;
                    case 'reset':
                        $_this->IsGETSecured(array(session_id()));
                        if(!$_this->request->IsPOST()) throw new iMVC\Exceptions\InvalideOperationException;
                        $_this->IsSecure($_POST, array('email'));
                        $e_exists = User::exists(array('conditions'=>array('email'=>$_POST['email'])));
                        if(!$e_exists)
                        {
                            $_this->view->success = 0;
                            $_this->view->message = "The email `<b>{$_POST['email']}</b>` does not exists!";
                            header('HTTP/1.1 404 EMAIL NOT FOUND');
                            return;
                        };
                        $e = time()+3600; # an on hour window!
                        $link = "{$GLOBALS[CONFIGS]['site']['address']}/auth/reset.configure?e=$e&u={$_POST['email']}{$_this->GetSecureGET(array($_POST['email'], $e))}";
                        $get = array('link'  => $link);
                        $k = explode("&", $_this->GetSecureGET(array()));
                        foreach($k as $value)
                        {
                            $l = explode("=", $value);
                            if(!strlen($l[0])) continue;
                            $get[$l[0]] = $l[1];
                        }
                        $f = new iMVC\Routing\FakeRequest("/auth/reset.mail", $get);
                        $c = $f->Send(1);
                        $args = array('from' => array('address' => $GLOBALS[CONFIGS]['mail']['from']['address'], 'name' => $GLOBALS[CONFIGS]['mail']['from']['name']),                     
                            'to' =>array(array('address' => $GLOBALS[CONFIGS]['mail']['from']['address'], 'name' => $GLOBALS[CONFIGS]['mail']['from']['name'])), 
                            'subject' => "Testing mail",
                            'content' =>$c,               
                            'base_dir_name' => PUBLIC_PATH);
                        require_once 'Mail/GmailMailer.php';
                        $gm = new \iMVC\Mail\GmailMailer($GLOBALS[CONFIGS]['mail']['username'], $GLOBALS[CONFIGS]['mail']['password']);
                        try
                        { 
                            $gm->Send($args);    
                        }
                        catch(\ErrorException $e) 
                        { 
                            $_this->view->message = $e->getMessage();
                            $_this->view->success = 0;
                            return;
                        }
                        $_this->view->message = "A reset link has sent to your email adress, please check your inbox...<br />
                            <b>Note: </b>You have only <b>an one hour window</b> to reset your password with emailed link, 
                            <b>after one hour</b> you have to request a new reset link!";#<br /><a href='$link'>The Link</a>";
                        break;
                }
        });
    }
}
