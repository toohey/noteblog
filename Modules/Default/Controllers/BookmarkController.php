<?php
/**
 * /bookmark
 */
class BookmarkController extends iMVC\Controller\BaseController
{    
    public function Initiate()
    {
    }
    
    public function IndexAction()
    {
        $b = Bookmark::GetBookmark(User::GetInstance()->user_id, $this->request->partial_params[0]);
        $b->UpdateViewedDate($b->user_id, $b->bookmark_id);
        header("location: {$b->bookmark_body}");
        exit;
    }
    public function CreateAction()
    {       
        /*if(!isset($this->request->params['p']) || !isset($this->request->params['h']))
            throw new iMVC\Exceptions\InvalideArgumentException;
        if(\iMVC\Security\Hash::Generate($this->request->params['p'].User::GetInstance()->user_id)!=$this->request->params['h'])
            throw new \iMVC\Exceptions\InvalideOperationException;*/
        // data are secure now
        $this->ToRespond(function($type, $t){
            switch($type)
            {
                default :
                case 'template':
                    $t->IsGETSecured(array($_GET['p'], User::GetInstance()->user_id));
                    $t->isSecure($t->request->GET, array('p'));
                    
                    if(($t->view->ajax = isset($t->request->GET['ajax'])))
                        $t->layout->SuppressLayout();                        
                    
                    $t->view->p = $t->request->GET['p'];
                    $t->view->h = $t->GetSecureGET(array($t->view->p, User::GetInstance()->user_id));
                    break;
                case 'action':
                    $t->isSecure($t->request->POST, array('p', 'bookmark_name','bookmark_body'));
                    $t->IsGETSecured(array($_POST['p'], User::GetInstance()->user_id));
                    $URL_FORMAT = 
                        '/^(https|ftp|http|ssh|sftp|tftp?):\/\/'.                                           // protocol
                        '(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+'.                          // username
                        '(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?'.                       // password
                        '@)?(?#'.                                                                                    // auth requires @
                        ')((([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)*'.                                 // domain segments AND
                        '[a-z][a-z0-9-]*[a-z0-9]'.                                                              // top level domain  OR
                        '|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}'.
                        '(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])'.                                       // IP address
                        ')(:\d+)?'.                                                                                      // port
                        ')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'.                  // path
                        '(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'.                         // query string
                        '?)?)?'.                                                                                          // path and query string optional
                        '(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'.                       // fragment
                        '$/i';
                    if(!(filter_var($_POST['bookmark_body'], FILTER_VALIDATE_EMAIL) || preg_match($URL_FORMAT, $_POST['bookmark_body'])))
                    {
                        if(!isset($t->request->GET['ajax']))
                            throw new iMVC\Exceptions\InvalideArgumentException("The books can only be E-mail or URL");
                        else
                            echo "<div class='alert alert-error'>The books can only be <b>E-mail</b> or <b>URL</b></div>";
                        exit;
                    }
                    if(filter_var($_POST['bookmark_body'], FILTER_VALIDATE_EMAIL))
                        $_POST['bookmark_body'] = "mailto:{$_POST['bookmark_body']}";
                        
                    Bookmark::CreateBookmark(User::GetInstance()->user_id, $t->request->POST['p'], $_POST['bookmark_name'], $_POST['bookmark_body']);
                    if(!isset($t->request->GET['ajax']))
                        if($_POST['p']==-1) header('location: /');
                        else header("location: /directory/{$_POST['p']}.bookmarks");
                    else
                        echo "<div class='alert alert-success success '><a href='{$_POST['bookmark_body']}'>{$_POST['bookmark_name']}</a> successfully created!</div>";
                    exit;
            }
        });
    }
    /**
     * The Delete Action
     * @responds_type <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-POST <b>file[]:</b> bookmarks' id
     * @|-GET <b>suppress_layout</b><br /><b>pid:</b> parent id<br /><b>hs:</b> hash-sum of '$pid.$user_id'
     * @view_variables <b>NO VIEW<b>
     * @redirect <i>as follow</i>
     * @|-on_success if not <b>ajax</b> otherwise /directory/{$f->parent_id}.bookmarks
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
                echo "<div class='bookmark-deletion-alert alert alert-error' id='failure'>{$e->getMessage()}</div>";
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
        foreach ($_REQUEST['file'] as $key => $bookmark_id)
        {
            $f = Bookmark::GetBookmark(User::GetInstance()->user_id, $bookmark_id);
            try{
                if(isset($this->request->GET['r']))
                        Bookmark::Restore(\User::GetInstance()->user_id, $bookmark_id);
                else 
                        Bookmark::Remove(\User::GetInstance()->user_id, $bookmark_id, isset($this->request->GET['t']));
                $success_string .= "<b>'{$f->bookmark_name}'</b>, ";
                $success = true;
            }catch (Exception $e)
            {
                iMVC\Tools\Debug::_var($e);
                $failed = true;
                $fail_string .= "<b>'{$f->bookmark_name}'</b>, ";
            }
        }
        $success_string = substr($success_string, 0, strlen($success_string)-2);
        $fail_string = substr($fail_string, 0, strlen($fail_string)-2);
        $success_string = "<div class='bookmark-deletion-alert alert alert-success success ' id='success'>$success_string successfully $opt!</div>";
        $fail_string = "<div class='bookmark-deletion-alert alert alert-error' id='failure'>Failed on $fail_string</div>";
        if($failed)
            echo $fail_string;
        if($success)
            echo $success_string;
        if(isset($this->request->GET['ajax']))
            exit;
        else
            header("location: /directory/{$f->parent_id}.bookmarks");
    }/**
     * The Share Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-URL <b>h:</b> hash-sum of '$bookmark_id.$parent_id' on <i>editting mode</i> and '$parent_id' on create mode<br /><b>n:</b> bookmark's id<br /><b>s:</b> current shared status
     * @view_variables <b>NO VIEW</b>
     * @redirect <i>as follow</i>
     * @|-on_success if <b>$_GET['redir']<b> is setted then to <b>$_GET['redir']<b> otherwise <b>'/bookmark/{$bookmark_id}'</b>
     * @|-on_failure any failure would arises exception 
     * @session_settings sets <b>$_SESSION['app']['tmp']['notif']['share']['bookmark']</b> as notification stuff
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
            foreach($this->request->params['n'] as $bookmark_id)
            {
                $n = Bookmark::GetBookmark($uid, $bookmark_id);
                $n->is_shared = !$n->is_shared;
                $n->save();
            }
            echo "<div class='alert alert-success success text-center'>Operation was successful</div>";
            if(isset($_REQUEST['ajax'])) exit;
            if(!$n) header('location: /');
            else header("location: /directory/{$n->parent_id}.bookmarks");
            exit;
        }
        else
        {
            // if was single GET request
            $r = Bookmark::Share(User::GetInstance()->user_id, $this->request->params['n'], !$this->request->params['s']);
            $notif = new stdClass();
            $notif->success = $r;
            $notif->status = 1;
            $notif->content = "This bookmark has <b>".(!$this->request->params['s']?"shared":"un-shared")."</b>".($r?" successfully.":" with failure.");        
            $_SESSION['app']['tmp']['notif']['share']['bookmark'] = serialize($notif);

            if(isset($_GET['redir']))
                header ("location: {$_GET['redir']}");
            else
                header('location: /bookmark/'.$this->request->params['n']);
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
            foreach($t->request->POST['file'] as $bookmark_id )
            {
                if($type=='archive')
                    Bookmark::SetArchive($uid,  $bookmark_id, 1);
                elseif($type=='dearchive')
                    Bookmark::SetArchive($uid,  $bookmark_id, 0);
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