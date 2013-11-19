<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/general.js.php'; ?>
<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/delete-items.js.php'; ?>
<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/rename-create-folder.js.php'?>
<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/share-folder.js.php'?>
<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/de-archive.js.php'; ?>
<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/upload.js.php'; ?>
<?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/bookmark.js.php'; ?>
<div class="input pull-left " id='option-btn' style="margin-right: 10px;">
  <div class="btn-group">
    <button class="btn dropdown-toggle" data-toggle="dropdown">
        <span class="icon-tasks"></span>
        Options
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li class="dropdown-submenu">
            <a href="#"><span class='icon-cog'></span> Creations</a>
            <ul class="dropdown-menu pull-right" style="min-width: 200px;"-->
                <li>
                    <a onclick="CreateFolder()"><span class="icon-folder-open"></span> Create folder</a>
                </li>
                <li>
                    <a href="/note/edit<?php echo $pid!=Folder::ROOT_PARENT?"/p/$pid":""?>?<?php echo $this->GetSecureGET(array($pid))?>"><span class="icon-file"></span> Create note</a>
                </li>
                <li>
                    <a href="#" onclick="Bookmark()"><span class="icon-bookmark"></span> Add a bookmark</a>
                </li>
                <li>
                    <a href="#" onclick="Upload()"><span class="icon-tags"></span> Upload attachment</a>
                </li>
            </ul>        
        </li>
        <?php require 'actions.js.php'; ?>
    </ul>
  </div>
</div>