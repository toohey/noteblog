<?php 
    if(User::IsLoggedIn()):
        $type = substr($this->view->request->TYPE, 0, strlen($this->view->request->TYPE)-1);
        if($type=='attache')$type = substr($type, 0, strlen($type)-1);
        $type = $type=='htm'?'folder':$type;
        if(isset($this->view->pid))
            $pid = $this->view->pid;
        else $pid = -1;
        $action = '/'.str_replace("Action", "", $this->view->request->action);
        if(strtolower($type)=='htm')$type='folder';
        $uri = "";
        if(strtolower($this->view->request->module)!="default")
            $uri.="/{$this->view->request->module}";
        if(strtolower($this->view->request->action)!="indexaction")
            $uri.="$action";
        if($pid != -1)
            $uri .= "/directory/$pid";
?>
<ul class="nav nav-tabs" id='nav-tab-note-folder' style="">
    <li id='folders-tab-link' class="<?php echo $type=="folder"?"active":"" ?>">
        <a href="<?php echo $uri.".folders"?>">
                <span class="icon-folder-close"></span>Folders <?php echo (isset($this->view->folders_count) && $type!="folder" && $type!='htm')?"<span style='color:a6a6a6'>({$this->view->folders_count})</span>":"" ?>
        </a>
    </li>
    <li id='notes-tab-link' class="<?php echo $type=="note"?"active":"" ?>">
        <a href="<?php echo $uri.".notes";?>">
            <span class="icon-file"></span>Notes <?php echo (isset($this->view->notes_count) && $type!="note")?"<span style='color:a6a6a6'>({$this->view->notes_count})</span>":"" ?>
        </a>
    </li>
    <li id='bookmarks-tab-link' class="<?php echo $type=="bookmark"?"active":"" ?>">
        <a href="<?php echo $uri.".bookmarks"?>" >
            <span class="icon-bookmark"></span>Bookmarks <?php echo (isset($this->view->bookmarks_count) && $type!="bookmark")?"<span style='color:a6a6a6'>({$this->view->bookmarks_count})</span>":"" ?>
        </a>
    </li>
    <li id='attaches-tab-link' class="<?php echo $type=="attach"?"active":"" ?>">
        <a href="<?php echo $uri.".attaches"?>">
            <span class="icon-tags"></span>Attachments <?php echo (isset($this->view->attachs_count) && $type!="attach")?"<span style='color:a6a6a6'>({$this->view->attachs_count})</span>":"" ?>
        </a>
    </li>
</ul>
<script>
    function FO()
    {
        $.get('/bookmark/link?ajax', 
                function(data)
                {
                    $('body').show(data);
                });
    }
</script>
<?php endif; ?>