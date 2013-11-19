<?php

/**
 * /folders
 */
require 'Partials/FolderController/Partial_FolderController_1.php';
class FolderController extends Partial_FolderController_1
{
    public function ExplorerAction()
    {
            if(!$this->request->IsPOST() || !isset($_POST['ajax']))
                throw new \iMVC\Exceptions\AccessDeniedException("Invalid Request.");

            if(!isset($_POST['cwd']) || $_POST['cwd'] === 'Index')
                $_POST['cwd'] = '';

            if(isset($_POST['ajax']))
                $this->layout->  SuppressLayout ( );
            $const_folders = array(''=>'My Categories','recent'=>'Recent',
                    'starred'=>'Starred','shared' => 'Shared',
                    'archives'=>'Archives', 'trashes'=>'Trashes','all'=>'All Items');

            $this->view->list = $const_folders;
            $this->view->cwd = $_POST['cwd'];
    }
    
    public function ArchiveAction()
    {
        if(!$this->request->  IsPOST() || !isset($this->request->POST['file']))
        {
            $this->request->POST['file'] = array();
        }
        $this->view->  SuppressView();
        $uid = User::GetInstance()->user_id;
        $this->_i = 0;
        $this->  ToRespond(function($type, $t) use($uid)
        {
            foreach($t->request->POST['file'] as $folder_id )
            {
                if($type=='archive')
                    Folder::SetArchive($uid,  $folder_id, 1);
                elseif($type=='dearchive')
                    Folder::SetArchive($uid,  $folder_id, 0);
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
    
    public function MoveAction()
    {
        if(!$this->request->IsPOST()) throw new \iMVC\Exceptions\InvalideOperationException;
        $this->IsSecure($_GET,array('p', 't'));
        if(!$this->IsSecure($_POST,array('file'), array(), 0))
            throw new \iMVC\Exceptions\InvalideOperationException("No item selected ...");
        $this->IsGETSecured(array($_GET['p'], $_GET['t'], User::GetInstance()->user_id));
        if(isset($_REQUEST['ajax']))
            $this->layout->SuppressLayout();
        
        $this->view->t = $_GET['t'];
        
        $tclass = strtolower($_GET['t']);
        $tclass = strtoupper($tclass[0]).substr($tclass, 1);
        
        $items = array('Folder', 'Note', 'Bookmark', 'Attach');
        if(!in_array($tclass, $items))
            throw new \iMVC\Exceptions\InvalideArgumentException("Invalid type { $tclass } supplied!");
        
        $this->view->ajax = isset($_REQUEST['ajax']);
        $this->view->file = "";
        foreach($_POST['file'] as $value)
        {
            $this->view->file .= "file[]=$value&";
        }
        $this->view->p = $_GET['p'];
        $this->view->reloading = false;
        $this->ToRespond(function($type, $t) use($tclass){
            switch($type)
            {
                case 'dive':
                    $t->view->reloading = true;
                default :
                    if(!isset($_POST['d']))
                        $_POST['d']  =  Folder::ROOT_PARENT;
                    $t->view->folders = Folder::GetChildren(User::GetInstance()->user_id, $_POST['d']);
                    $t->view->map = Folder::GetRouteMap(User::GetInstance()->user_id, $_POST['d']);
                    if($_POST['d']==Folder::ROOT_PARENT)
                        $t->view->parent = Folder::GetRoots_RootFolder();
                    else
                        $t->view->parent = Folder::GetFolder(User::GetInstance()->user_id, $_POST['d']);
                    break;
                case 'gc':
                    $t->IsSecure($_POST, array('d'));
                    $dist = $_POST['d'];
                    $path_to_dist = array();
                    foreach(Folder::GetRouteMap(User::GetInstance()->user_id, $dist) as $path)
                    {
                        $path_to_dist[] = $path->folder_id;
                    }
                    foreach($_POST['file'] as $item)
                    {
                        if($tclass == "Folder" && in_array($item, $path_to_dist))
                        {
                            throw new InvalidArgumentException("The folder cannot move to its sub-folders");
                        }
                        $func = "Move{$tclass}";
                        $tclass::$func(User::GetInstance()->user_id, $t->view->p, $item, $dist);  
                    }
                    echo "<div class='alert alert-success success'>".(count($_POST['file'])>1?"All items":"The item")." moved <b>successfully</b>!</div>";
                    exit;
                    break;
            }
        });
    }
}
