<?php

/**
 * /
 */
require 'Partials/IndexController/Partial_IndexController_1.php';
class IndexController extends Partial_IndexController_1
{
    /**
     * The Index Action
     * @responds_type <b>GET</b>, <b>POST</b>
     * @responds_to <b>.html:</b> aka .folders<br/> <b>.folders:</b> for a loading folders <br /><b>.notes:</b> for loading notes <br/><b>[.folders|.notes].reload:</b> for reloading either folders or notes without any layouts<br />
     * @passed_params <i>as follow</i>
     * @|-URL <b>f as params</b> aka asked $folder_id<br />
     * @view_variables <b>$pid:</b> hold current items'<i>(either folders or notes)</i> parent id<br /> <b>$reloading:</b> if the page is reloading set to true<br /><b>$map:</b> an array of map to parrent folder<br /><b>$files:</b> for either notes or folders this varibale hold current items with same API<br /><b>$[folders|notes]_count</b> on <i>.notes</i> we set $folders_count to indicate how many folders exists in current parent folder likewise for <i>.folders</i> for $notes_count
     * @throws InvalidArgumentException if none of above respond type provided this exception would arises
     */    
    public function IndexAction()
    {
        if(isset($this->request->params['directory']))
        {
            $this->view->pid = $this->request->params['directory'];
        }
        else
        {
            $this->view->pid = Folder::ROOT_PARENT;
        }
        
        if($this->view->pid!=-1) 
        {
                $this->view->parent = Folder::GetFolder(User::GetInstance ()->user_id, $this->view->pid);
                $this->view->parent->UpdateViewedDate(User::GetInstance ()->user_id, $this->view->parent->folder_id);
        }
        else
        {
            $this->view->parent = Folder::GetRoots_RootFolder();
        }
        $this->Explorer("GetChildren", array(User::GetInstance ()->user_id, $this->view->pid));
        $this->view->map = Folder::GetRouteMap(User::GetInstance()->user_id, $this->view->pid);
    }
    
    public function RecentAction()
    {
        $this->Explorer("GetRecentUsed");
    }
    
    public function StarredAction()
    {
        $this->Explorer("GetStars");
    }
    
    public function SharedAction()
    {
        $this->Explorer("GetShared");
    }
    
    public function ArchivesAction()
    {
        $this->Explorer("GetArchives");
    }
    
    public function TrashesAction()
    {
        $this->Explorer("GetTrashes");
    }
    
    public function AllAction()
    {
        $this->Explorer("GetAll");
    }
}

?>
