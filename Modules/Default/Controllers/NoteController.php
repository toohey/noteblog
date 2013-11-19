<?php

/**
 * /note
 */
class NoteController extends \iMVC\Controller\BaseController
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
     * @view_variables <b>shared_notif:</b> if any notification is setted<br /><b>notif_content:</b> the notification content<br /><b>notif_success:</b> if the notifcation indicates success or not
     * @session_settings if <b>isset($_SESSION['app']['tmp']['notif']['share']['note'])</b> unset it and set view variables 
     */
    public function Initiate() {
        if(!User::IsLoggedIn())
        {
            header("location: /auth/login");
        }
        
        if(isset($_SESSION['app']['tmp']['notif']['share']['note']))
        {
            $notif = unserialize($_SESSION['app']['tmp']['notif']['share']['note']);
            
            $this->view->shared_notif = isset($notif->status) && $notif->status;

            $this->view->notif_content = $notif->content;

            $this->view->notif_success = !isset($notif->success) || $notif->success; ;

            unset($_SESSION['app']['tmp']['notif']['share']['note']);
        }
    }
    /**
     * The Index Action
     * @see ShowAction
     */
    public function IndexAction() 
    {
        $this->ShowAction();
    }
    /**
     * The Delete Action
     * @responds_type <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-POST <b>file[]:</b> nodes' id
     * @|-GET <b>suppress_layout</b><br /><b>pid:</b> parent id<br /><b>hs:</b> hash-sum of '$pid.$user_id'
     * @view_variables <b>NO VIEW<b>
     * @redirect <i>as follow</i>
     * @|-on_success if not <b>ajax</b> otherwise /directory/{$f->parent_id}.notes
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
                echo "<div class='node-deletion-alert alert alert-error' id='failure'>{$e->getMessage()}</div>";
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
        foreach ($_REQUEST['file'] as $key => $note_id)
        {
            $f = Note::GetNote(User::GetInstance()->user_id, $note_id);
            try{
                if(isset($this->request->GET['r']))
                        Note::Restore(\User::GetInstance()->user_id, $note_id);
                else 
                        Note::Remove(\User::GetInstance()->user_id, $note_id, isset($this->request->GET['t']));
                $success_string .= "<b>'{$f->note_name}'</b>, ";
                $success = true;
            }catch (Exception $e)
            {
                echo $e->getMessage();
                $failed = true;
                $fail_string .= "<b>'{$f->note_name}'</b>, ";
            }
        }
        $success_string = substr($success_string, 0, strlen($success_string)-2);
        $fail_string = substr($fail_string, 0, strlen($fail_string)-2);
        $success_string = "<div class='node-deletion-alert alert alert-success success ' id='success'>$success_string successfully $opt!</div>";
        $fail_string = "<div class='node-deletion-alert alert alert-error' id='failure'>Failed on $fail_string</div>";
        if($failed)
            echo $fail_string;
        if($success)
            echo $success_string;
        if(isset($this->request->GET['ajax']))
            exit;
        else
            header("location: /directory/{$f->parent_id}.notes");
    }
    
    /**
     * The Show Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-URL <b>/note/$note_id</b>
     * @view_variables  <b>note:</b> the note instance
     * @redirect <i>as follow</i>
     * @|-on_success /note/{$note_id}
     * @|-on_failure /
     * @throws iMVC\Exceptions\NotFoundException if note doesn't exists
     */
    public function ShowAction()
    {
        if(!count($this->request->params))
        {
            header('location: /');
            exit;
        }
        if(!($n = Note::GetNote(User::GetInstance()->user_id, $this->request->partial_params[0])))
                throw new iMVC\Exceptions\NotFoundException('The request note does not exists...');
        
        // update note id
        $n->  UpdateViewedDate($n->user_id, $n->note_id);
        
        $this->view->note = $n;
        $this->layout->SetLayout('print');
        $this->view->SetView('Show');
        
    }
    
    /**
     * The Edit Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-URL <b>h:</b> hash-sum of '$note_id.$parent_id' on <i>editting mode</i> and '$parent_id' on create mode<br /><b>p:</p> note's parent id<br /><b>n:</b> note's id
     * @|-POST <b>note_title</b><br /><b>note_body</b><br /><b>h:</b> hash-sum of '$note_id.$parent_id' on <i>editting mode</i> and '$parent_id' on create mode<b>n:</n> note's id if we are in edditing mode<br /><b>p:</b> parent's id if we are not in edditing mode
     * @|-GET <b>suppress_layout:</b> to suppress layout <br /><b>action:</b> to get what is the action, can be <i>[rename|create]</i><br /><b>pid:</b> parent_id<br /><b>hs:</b> hash-sum of '<i>pid</i>.<i>user_id</i>'<br /><b>version:</b> version of passed data<br /><b>node_name:</b> on <u>POST</u>: editted node name.<br /><b>ajax:</b> is this request is ajax or not.
     * @view_variables <b>editting:</b> if we are on editting mode<br /><b>note:</b> regardless of mode we pass a note instance alway which conatins $parent_id<br /><b>shared_notif:</b> if we need to show that note's shared status has been changed or not
     * @redirect <i>as follow</i>
     * @|-on_success if not <b>ajax</b> <i>"/note/{$note_id}"</i>
     * @|-on_failure /
     * @throws \iMVC\Exceptions\AccessDeniedException if miss-hashed
     */
    public function EditAction()
    {
        if(!$this->request->IsPOST())
        {
            $pid = Folder::ROOT_PARENT;
            if(isset($this->request->params['p']))
                $pid = $this->request->params['p'];
            
            $this->view->editting = isset($this->request->params['n']);
            
            if($this->view->editting)
                $this->IsGETSecured(array($this->request->params['n'], $pid));
            else
                $this->IsGETSecured(array($pid));
            
            // now the request is secure
            $this->layout->SetLayout('no-style');
            if(!$this->view->editting)
            {
                $this->view->note = new stdClass();
                $this->view->note->parent_id = $pid;
            }
            else
            {
                $n = $this->view->note = Note::GetNote(User::GetInstance()->user_id, $this->request->params['n']);
                $this->view->shared_notif = false;
                
                if(!$n)
                    throw new iMVC\Exceptions\NotFoundException("The note does not exists...");
            }
        }
        else
        {
            $this->IsSecure($_POST,array('p', 'note_title', 'note_body'));
            $this->view->editting = isset($this->request->POST['n']);
            if($this->view->editting)
                $this->IsSecure($_POST,array('n'));
            
            $this->IsGETSecured(array($_POST['p'],($this->view->editting?$_POST['n']:$this->view->editting)  ,User::GetInstance()->user_id));
            
            $_POST['note_title'] = htmlspecialchars($_POST['note_title']);
            $f = null;
            // now the request is secure
            if(!$this->view->editting)
            {
                $f = Note::CreateNote(User::GetInstance()->user_id, $_POST['p'], $_POST['note_title'], $_POST['note_body']);
            }
            else
            {
                $f = Note::Edit(User::GetInstance()->user_id, $_POST['n'], $_POST['note_title'], $_POST['note_body']);
            }
            header('location: /note/'.$f->note_id.".view");
            exit;
        }
    }
    /**
     * The Share Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-URL <b>h:</b> hash-sum of '$note_id.$parent_id' on <i>editting mode</i> and '$parent_id' on create mode<br /><b>n:</b> note's id<br /><b>s:</b> current shared status
     * @view_variables <b>NO VIEW</b>
     * @redirect <i>as follow</i>
     * @|-on_success if <b>$_GET['redir']<b> is setted then to <b>$_GET['redir']<b> otherwise <b>'/note/{$note_id}'</b>
     * @|-on_failure any failure would arises exception 
     * @session_settings sets <b>$_SESSION['app']['tmp']['notif']['share']['note']</b> as notification stuff
     * @throws \iMVC\Exceptions\InvalideArgumentException
     * @throws \iMVC\Exceptions\AccessDeniedException if miss-hashed
     */
    public function ShareAction()
    {
        $p = 0;
        if(!$this->IsSecure($this->request->params, array('n','s'), array(), 0))
        {
            $p = 1;
            if($this->request->IsPOST() && isset($_POST['file']))
            {
                $this->request->params['n'] = $_POST['file'];
                if(!isset($this->request->params['p']))
                    throw  new InvalidArgumentException;
            }
            elseif(isset($_GET['ajax']))
            {
                echo "<div class='alert alert-error text-center'>Operation was <b>failure</b>!</div>";
                exit;
            }
            else
                throw new \iMVC\Exceptions\InvalideArgumentException;
        }
        if($p)
            $this->IsGETSecured(array($this->request->params['p'],  User::GetInstance()->user_id));
        else
            $this->IsGETSecured(array($this->request->params['s'], $this->request->params['n']));
        // the hash checksum is different when we are requesting share toggling!
        if(!is_numeric($this->request->params['s']))
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
            foreach($this->request->params['n'] as $note_id)
            {
                $n = Note::GetNote($uid, $note_id);
                $n->is_shared = !$n->is_shared;
                $n->save();
            }
            echo "<div class='alert alert-success success text-center'>Operation was <b>successful</b></div>";
            if(isset($_REQUEST['ajax'])) exit;
            if(!$n) header('location: /');
            else header("location: /directory/{$n->parent_id}.notes");
            exit;
        }
        else
        {
            // if was single GET request
            $r = Note::Share(User::GetInstance()->user_id, $this->request->params['n'], !$this->request->params['s']);
            $notif = new stdClass();
            $notif->success = $r;
            $notif->status = 1;
            $notif->content = "This note has <b>".(!$this->request->params['s']?"shared":"un-shared")."</b>".($r?" successfully.":" with failure.");        
            $_SESSION['app']['tmp']['notif']['share']['note'] = serialize($notif);
            if(isset($_GET['redir']))
                header ("location: {$_GET['redir']}");
            else 
                header("location: /note/{$this->request->params['n']}.view");
            exit;
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
            foreach($t->request->POST['file'] as $note_id )
            {
                if($type=='archive')
                    Note::SetArchive($uid,  $note_id, 1);
                elseif($type=='dearchive')
                    Note::SetArchive($uid,  $note_id, 0);
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
