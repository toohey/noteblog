<?php
/**
 * /bookmark
 */
class AttachController extends iMVC\Controller\BaseController
{
    
    public function Initiate()
    {
    }
    
    public function IndexAction()
    {
        if(strtolower($this->request->TYPE)!="download")
        {
            $this->IsSecure($this->request->GET, array('p'));
            $this->IsGETSecured(array($_GET['p'], User::GetInstance()->user_id));
            $this->view->p = $_GET['p'];
            $this->view->t = time();
            $this->view->h = iMVC\Security\Hash::Generate($this->view->p.$this->view->t.\User::GetInstance()->user_id);
            if(isset($_GET['ajax']))
            {
                $this->layout->SuppressLayout();;
            }
        }
        else
        {
            $this->IsSecure($this->request->params, array('i', 'p'));
            $this->IsGETSecured(array($this->request->params['i'], $this->request->params['p'], User::GetInstance()->user_id));
            $a = new Attach;
            $a = $a->find($this->request->params['i']);
            if(!$a)
                throw new \iMVC\Exceptions\NotFoundException("The requested file does not exists");
            $add = PUBLIC_PATH."/{$a->attach_body}";
            $a->UpdateViewedDate($a->user_id, $a->attach_id);
            if(!file_exists($add) || !is_readable($add))
            {
                $a->delete();
                throw new \iMVC\Exceptions\NotFoundException("The requested file does not exist");
            }
            header("Content-Disposition: attachment; filename='{$a->attach_name}'");  
            header("Content-Type: Binary");
            echo readfile($add);
            exit;
        }
    }
    public function UploadAction()
    { 
        \iMVC\Tools\Debug::_var($_POST,0);
        \iMVC\Tools\Debug::_var($_FILES,0);
        if(!$this->request->IsPOST() || !isset($_FILES)) 
        { 
            throw new InvalidArgumentException("Invalid request, Or the file is <b>larger</b> than <b>".ini_get("upload_max_filesize")."</b>");
        }
        $this->IsSecure($this->request->POST, array('h','t','p'), array('h'=>iMVC\Security\Hash::Generate($_POST['p'].$_POST['t'].\User::GetInstance()->user_id)));
        $this->IsSecure($_FILES, array('file'));
        $error_file = array();
        $success_file = array();
        $f = array();
        $f[] = $dir = PUBLIC_PATH."/access/uploads/";
        $f[] = $dir = $dir.date('Y')."/";
        $f[] = $dir = $dir.date('m')."/";
        $f[] = $dir = $dir.(floor(30/date('d')))."/";
        foreach($f as $dir)
        {
            if(!file_exists($dir))
            {
                mkdir($dir);
                chmod($dir, 0777);
            }  
        }
        for($i =0 ;$i<count($_FILES['file']['tmp_name']);$i++)
        {
            $type = explode(".", $_FILES['file']['name'][$i]);
            $type = $type[count($type)-1];
            if(!file_exists($dir."$type/"))
            {
                mkdir($dir."$type");
                chmod($dir, 0777);
            }  
            $n = iMVC\Security\Hash::Generate($_FILES['file']['name'][$i]).".$type";
            $df = $dir."$type/".$n;
            $c = 0;
            while(file_exists($df))
            {
               $n = iMVC\Security\Hash::Generate(str_shuffle($n))."_$i.$type";
               $df = $dir.$n;
               $c++;
            }
            //die ($df);
            if(!move_uploaded_file($_FILES['file']['tmp_name'][$i], $df)) die("failed");
                    //$error_file[] = $_FILES['file']['name'][$i];
            else
            {
                $a = Attach::CreateAttach(User::GetInstance()->user_id, $_POST['p'], $_POST['dec'], str_replace(PUBLIC_PATH, "", $df));  
                if(!strlen($_POST['dec']))
                {
                    # one way to go
                    # $a->attach_name = "Attach ID# {$a->attach_id}";
                    $a->attach_name = $_FILES['file']['name'][$i];
                    $a->save();
                }
                $success_file[] = $_FILES['file']['name'][$i];
            }
        }
        if(count($error_file))
        {
            $this->view->message = "<div class='alert alert-error'>Unable to upload";
            foreach($error_file as $value)
            {
                $this->view->message = $this->view->message." <b>$value</b>";
            }
            $this->view->message .= "</div>";
        }
        if(count($success_file))
        {
            $this->view->message = "<div class='alert alert-success success'>";
            foreach($success_file as $value)
            {
                $this->view->message = $this->view->message."<b>$value</b>";
            }
            $this->view->message .= " uploaded successfully</div>";
        }
        if(isset($this->request->GET['ajax']))
        {
            echo $this->view->message;
            exit;
        }
        $_SESSION['app']['tmp']['alert']['messages'] = $this->view->message;
        if($_POST['p']==-1)
            $_POST['p'] = "";
        header("location: /directory/{$_POST['p']}.attaches");
        exit;
    }
    /**
     * The Delete Action
     * @responds_type <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-POST <b>file[]:</b> attachs' id
     * @|-GET <b>suppress_layout</b><br /><b>pid:</b> parent id<br /><b>hs:</b> hash-sum of '$pid.$user_id'
     * @view_variables <b>NO VIEW<b>
     * @redirect <i>as follow</i>
     * @|-on_success if not <b>ajax</b> otherwise /f/{$f->parent_id}.attachs
     * @|-on_failure failures would arise exceptions
     * @throws \iMVC\Exceptions\AccessDeniedException if $pid or $file didn't provide or hash-sum didn't match or didn't post with ajax call
     */
    public function DeleteAction()
    {
        if(!$this->request->IsPOST() || !isset($_REQUEST['hs']) || !array_key_exists("ajax", $this->request->GET))
            throw new \iMVC\Exceptions\AccessDeniedException();
        
        $this->layout->SuppressLayout();
        if(!isset($_REQUEST['pid']) || !isset($_REQUEST['file']))
            $this->IndexAction ();
        
        if(iMVC\Security\Hash::Generate($_REQUEST['pid'].(\User::GetInstance()->user_id))!=$_REQUEST['hs'])
        {
            $e = new \iMVC\Exceptions\AccessDeniedException("Invalid data passed.");
            if(array_key_exists("ajax", $this->request->GET))
            {
                $this->view->SuppressView();
                $e->SendErrorCode();
                echo "<div class='attach-deletion-alert alert alert-error' id='failure'>{$e->getMessage()}</div>";
            }
            else
                throw $e; 
            exit;
        }
        $opt =  isset($this->request->GET['r'])?"restored":"deleted";
        $success_string = "";
        $fail_string = "";
        $failed = false;
        $success = false;
        foreach ($_REQUEST['file'] as $key => $attach_id)
        {
            $f = Attach::GetAttach(User::GetInstance()->user_id, $attach_id);
            try{
                if(isset($this->request->GET['r']))
                {
                    Attach::Restore(\User::GetInstance()->user_id, $attach_id);
                }
                else 
                {
                   $a = Attach::Remove(\User::GetInstance()->user_id, $attach_id, isset($this->request->GET['t']));
                   if(!isset($this->request->GET['t']))
                       exec("rm ".PUBLIC_PATH.$a->attach_body);
                }
                $success_string .= "<b>'{$f->attach_name}'</b>, ";
                $success = true;
            }catch (Exception $e)
            {
                iMVC\Tools\Debug::_var($e);
                $failed = true;
                $fail_string .= "<b>'{$f->attach_name}'</b>, ";
            }
        }
        $success_string = substr($success_string, 0, strlen($success_string)-2);
        $fail_string = substr($fail_string, 0, strlen($fail_string)-2);
        $success_string = "<div class='attach-deletion-alert alert alert-success success ' id='success'>$success_string successfully $opt!</div>";
        $fail_string = "<div class='attach-deletion-alert alert alert-error' id='failure'>Failed on $fail_string</div>";
        if($failed)
            echo $fail_string;
        if($success)
            echo $success_string;
        if(isset($this->request->GET['ajax']))
            exit;
        else
            header("location: /f/{$f->parent_id}.attachs");
    }/**
     * The Share Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-URL <b>h:</b> hash-sum of '$attach_id.$parent_id' on <i>editting mode</i> and '$parent_id' on create mode<br /><b>n:</b> attach's id<br /><b>s:</b> current shared status
     * @view_variables <b>NO VIEW</b>
     * @redirect <i>as follow</i>
     * @|-on_success if <b>$_GET['redir']<b> is setted then to <b>$_GET['redir']<b> otherwise <b>'/attach/{$attach_id}'</b>
     * @|-on_failure any failure would arises exception 
     * @session_settings sets <b>$_SESSION['app']['tmp']['notif']['share']['attach']</b> as notification stuff
     * @throws \iMVC\Exceptions\InvalideArgumentException
     * @throws \iMVC\Exceptions\AccessDeniedException if miss-hashed
     */
    public function ShareAction()
    {
        $p = 0;
        if(!isset($this->request->params['h']) || !isset($this->request->params['n']) || !isset($this->request->params['s']))
        {
            if($this->request->IsPOST() && isset($_POST['file']))
            {
                $p = 1;
                $this->request->params['n'] = $_POST['file'];
                if(!isset($this->request->params['p']))
                    throw  new InvalidArgumentException;
            }
            else
                throw new \iMVC\Exceptions\InvalideArgumentException;
        }
        // the hash checksum is different when we are requesting share toggling!
        if(iMVC\Security\Hash::Generate($p?($this->request->params['p'].User::GetInstance()->user_id):($this->request->params['s'].$this->request->params['n']))!=$this->request->params['h'] || !is_numeric($this->request->params['s']))
        {
            throw new \iMVC\Exceptions\AccessDeniedException;
        }
        // now the request is secure
        // if it was a post and a multi request
        if(is_array($this->request->params['n']))
        {
            if(!$this->request->IsPOST()) throw new \iMVC\Exceptions\InvalideOperationException;
            
            $uid = User::GetInstance()->user_id;
            $n = null;
            foreach($this->request->params['n'] as $attach_id)
            {
                $n = Attach::GetAttach($uid, $attach_id);
                $n->is_shared = !$n->is_shared;
                $n->save();
            }
            echo "<div class='alert alert-success success text-center'>Operation was successful</div>";
            if(isset($_REQUEST['ajax'])) exit;
            if(!$n) header('location: /');
            else header("location: /f/{$n->parent_id}.attachs");
            exit;
        }
        else
        {
            // if was single GET request
            $r = Attach::Share(User::GetInstance()->user_id, $this->request->params['n'], !$this->request->params['s']);
            $notif = new stdClass();
            $notif->success = $r;
            $notif->status = 1;
            $notif->content = "This attach has <b>".(!$this->request->params['s']?"shared":"un-shared")."</b>".($r?" successfully.":" with failure.");        
            $_SESSION['app']['tmp']['notif']['share']['attach'] = serialize($notif);

            if(isset($_GET['redir']))
                header ("location: {$_GET['redir']}");
            else
                header('location: /attach/'.$this->request->params['n']);
        }
        // our fail-safe
        header('location: /');
        exit;
    }
    public function ArchiveAction()
    {
        if(!$this->request->IsPOST() || !isset($this->request->POST['file']))
        {
            $this->request->POST['file'] = array();
        }
        $this->view->  SuppressView();
        $uid = User::GetInstance()->user_id;
        $this->_i = 0;
        $this->  ToRespond(function($type, $t) use($uid)
        {
            foreach($t->request->POST['file'] as $attach_id )
            {
                if($type=='archive')
                    Attach::SetArchive($uid,  $attach_id, 1);
                elseif($type=='dearchive')
                    Attach::SetArchive($uid,  $attach_id, 0);
                else throw new InvalidArgumentException("Invalid page $type");
                $t->_i++;
            }
        });
        
        if(!isset($this->request->GET['ajax']))
        {
            header ('location: /');
            exit;
        }
        else
        {
            echo "<div class='alert alert-success success text-success'><b>{$this->_i}</b> item(s) has been <b>".strtolower($this->request->TYPE)."</b></div>";
        }
    }
}