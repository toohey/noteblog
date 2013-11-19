<?php



class Partial_FolderController_1 extends \iMVC\Controller\BaseController
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
     * 
     */
    public function Initiate()
    {
        if(!User::IsLoggedIn())
        {
            header("location: /auth/login");
        }
    }
    /**
     * The Index Folder
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>Nothing</b>
     * @passed_params <b>Don't matter</b>
     * @view_variables <b>Nothing</b>
     * @redirect <i>as follow</i>
     * @|-on_success <b>Never success</b>
     * @|-on_failure <b>Always to error page</b>
     * @throws \iMVC\Exceptions\AccessDeniedException always will be thrown
     */
    public function IndexAction() {  throw new \iMVC\Exceptions\AccessDeniedException("Invalid request"); }
    
    public function CreateAction()
    {
        if($this->request->IsPOST())
        {
            $this->IsSecure($_POST,array('folder_name', 'p'));
            $this->IsGETSecured(array($_POST['p'], User::GetInstance()->user_id));
            $folder_name= htmlspecialchars($_POST['folder_name']);
            Folder::CreateFolder(User::GetInstance()->user_id, $_POST['p'], $folder_name);
            if(!isset($_GET['ajax']))
            {
                header("location: /directory/{$_POST['p']}");
            }
            exit;
        }
        else
        {
            $this->IsSecure($_GET, array('p'));
            $this->IsGETSecured(array($_GET['p'], User::GetInstance()->user_id));
            $this->view->p = $this->request->GET['p'];  
            if(isset($_GET['suppress_layout'])) $this->layout->SuppressLayout();
        }
    }
    public function RenameAction()
    {
        if($this->request->IsPOST())
        {
            $this->IsSecure($_POST,array('f', 'folder_name', 'p'));
            $this->IsGETSecured(array($_POST['f'], $_POST['p']));
            $folder_name= htmlspecialchars($_POST['folder_name']);
            Folder::Rename(User::GetInstance()->user_id, $_POST['f'], $folder_name);
            if(!isset($_GET['ajax']))
            {
                header("location: /directory/{$_POST['p']}");
            }
            exit;
        }
        else
        {
            $this->IsSecure($_GET, array('file', 'p'));
            $this->IsGETSecured(array($_GET['p'], User::GetInstance()->user_id));
            if(!count($_GET['file']))
            {
                if(isset($_GET['ajax'])) throw new \iMVC\Exceptions\InvalideOperationException("No file selected...");
                header('location: /');
                exit;
            }
            $this->view->f = $_GET['file'][0];
            $this->view->fname = Folder::GetFolder(User::GetInstance()->user_id, $this->view->f)->folder_name;
            $this->view->p = $this->request->GET['p'];  
            if(isset($_GET['suppress_layout'])) $this->layout->SuppressLayout();
        }
    }
    /**
     * The Delete Action
     * @responds_type <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-POST <b>file[]:</b> folders' id
     * @|-GET <b>suppress_layout</b><br /><b>pid:</b> parent id<br /><b>hs:</b> hash-sum of '$pid.$user_id'
     * @view_variables <b>NO VIEW<b>
     * @redirect <i>as follow</i>
     * @|-on_success if not <b>ajax</b> otherwise /directory/{$f->parent_id}.folders
     * @|-on_failure failures would arise exceptions
     * @throws \iMVC\Exceptions\AccessDeniedException if $pid or $file didn't provide or hash-sum didn't match or didn't post with ajax call
     */
    public function DeleteAction()
    {
        if(!$this->request->IsPOST() || !isset($_REQUEST['hs']))
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
                echo "<div class='folder-deletion-alert alert alert-error' id='failure'>{$e->getMessage()}</div>";
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
        foreach ($_REQUEST['file'] as $key => $folder_id)
        {
            $f = Folder::GetFolder(User::GetInstance()->user_id, $folder_id);
            try{
                if(isset($this->request->GET['r'])) { 
                        Folder::Restore(\User::GetInstance()->user_id, $folder_id);
                }
                else
                        Folder::Remove(\User::GetInstance()->user_id, $folder_id, isset($this->request->GET['t']));
                $success_string .= "<b>'{$f->folder_name}'</b>, ";
                $success = true;
            }catch (Exception $e)
            {
                $failed = true;
                $fail_string .= "<b>'{$f->folder_name}'</b>, ";
            }
        }
        $success_string = substr($success_string, 0, strlen($success_string)-2);
        $fail_string = substr($fail_string, 0, strlen($fail_string)-2);
        $success_string = "<div class='folder-deletion-alert alert alert-success success ' id='success'>$success_string successfully $opt!</div>";
        $fail_string = "<div class='folder-deletion-alert alert alert-error' id='failure'>Failed on $fail_string</div>";
        if($failed)
            echo $fail_string;
        if($success)
            echo $success_string;
        if(isset($this->request->GET['ajax']))
            exit;
        else
            header("location: /directory/{$f->parent_id}.folders");
    }
    /**
     * The Share Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>ANY</b>
     * @passed_params <i>as follow</i>
     * @|-URL <b>h:</b> hash-sum of '$parent_id.$user_id'<br /><b>p:</b> folders' parrent id<br /><b>s:</b> current shared status
     * @|-POST <b>file[]:</b> array of folders' id(but we only do it for the first element-for performance possible issues)
     * @|-GET <b>ajax</b> if the request is on ajax
     * @view_variables <b>NO VIEW</b>
     * @redirect <i>as follow</i>
     * @|-on_success if not on ajax mode and if <b>$_GET['redir']<b> is setted then to <b>$_GET['redir']<b> otherwise <b>'/directory/{$note_id}'</b>
     * @|-on_failure any failure would arises exception 
     * @throws \iMVC\Exceptions\InvalideArgumentException
     * @throws \iMVC\Exceptions\AccessDeniedException if miss-hashed
     */
    public function ShareAction()
    {
        $this->IsGETSecured(array($this->request->params['p'], User::GetInstance()->user_id));
        // get max time
        $max_time = ini_get('max_execution_time');
        // a function to calc. time in sec.millisec
        function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);   
        }
        // checkout the process start time
        $time_start = microtime_float();
        // secure data existence
        if($this->IsSecure(array($this->request->params['p'], $this->request->params['s']), array('p', 's'), array(), 0)
            || !count($_POST['file']) || !is_numeric($this->request->params['s']))
        {
            throw new \iMVC\Exceptions\InvalideArgumentException;
        }
        // now we have secure request
        $this->view->SuppressView ();
        // get origin folder
        $f = Folder::GetFolder(User::GetInstance()->user_id, $_POST['file'][0]);
        // calc. prefered share status to APPLY
        // if {$this->request->params['s']} setted it means we will do a toggle sharing here!
        $prefer_share_status = !(isset($this->request->params['t'])?$f->is_shared:$this->request->params['s']);
        /**
         * entering sharing suff
         */
        // an list to hold queues
        $l[][] = $f;
        // changed share status counter
        $counter= 0;
        // while we have any thing
        while(count($l))
        {
            // fetch the first element in array 
            $cf = array_shift($l);
            // which is an array too
            foreach ($cf as $key => $file) 
            {
                // if the $file is instance of Folder
                if(isset($file->folder_name))
                {
                    // targeting all sharable items
                    $target_items = array('Folder', 'Note', 'Bookmark', 'Attach');
                    foreach($target_items as $value)
                    {
                        // get sub-$value of $file which is in oblique with $prefer_share_status
                        $_f = $value::GetWith_SharedStatus_Children($f->user_id, $file->folder_id, !$prefer_share_status);
                        // if there are any?
                        if($_f)
                        {
                            // enqueue it
                            $l[] = $_f;
                        }
                    }
                }
                // change the share status to $prefer_share_status
                $file->is_shared = $prefer_share_status;   
                // save the changes
                $file->save();   
                // increment changes counter 
                $counter++;
            }
            // if we reach to 75% of max execution time put out an alert.
            if((microtime_float()-$time_start)>(0.75*$max_time))
            {
                // remaining items
                $c = 0;
                foreach ($l as $key => $value) {
                    $c += count($value);
                }
                // find a link as suggestion to narrow down ally area
                if(count($l))
                {
                    // latest folder instance
                    $cf = null;
                    // latest note instance, we will use it in case of if all 
                    // of folders have shared and only files are remained
                    // we well address to its $parent_id directory!
                    $ff = null;
                    while(!$cf && count($l))
                    {
                        $cf = array_shift($l);
                        foreach ($cf as $key => $file)
                            if(isset($file->folder_name))
                                $cf = $file;
                            else
                                $ff = $file;
                    }
                    // echo general part of message
                    echo "<div class='alert alert-error'>OMG! your have a <b>huge sub-items</b> under current folder! So the <b>execution time exceed</b> from its limit.<br />
                        Shared items count is <b>$counter</b> and also <b>at least $c item(s)</b> that <b>didn't shared</b>.<br />
                        <b>The solution</b> for this is you <b>limit down</b> your apply area <b>by going to sub-folders</b> and come back from <b>down to up</b>.";
                    // fetch link's $parent_id
                    if(!isset($cf->folder_id))
                    {
                        if($ff)
                        {
                            $cf = new Folder();
                            $cf->folder_id = $ff->parent_id;
                        }
                    }
                    // for fail safe! this if will always be true!
                    if(isset($cf->folder_id))
                    {
                        // we find any suggestion link found, echo it
                        echo "<br />You may want to start with <b><a href='/directory/{$cf->folder_id}'>here</a></b>.<br />
                            <blockquote>Note that the link <b>was only a suggestion</b> there may be some folders that <b>didn't shared</b> and also <b>not in sub-folders</b> of given <a href='/directory/{$cf->folder_id}'>link</a>.</blockquote>";
                    }
                    // exit
                    exit;
                }
            }   
        }
        // if we get here it means it was a success!
        if(isset($_GET['ajax']))
        {
            // output the result
            echo "<div class='alert alert-success success '><b style='font-variant:small-caps'>Cheers!</b>, <b>$counter</b> item(s) has been <b>".($prefer_share_status?"Shared":"Un-Shared")."</b> successfully.</div>";
            exit;
        }
        else // relocate the browser
            header('location: /directory/'.$this->request->params['n']);
        exit;
    }
}