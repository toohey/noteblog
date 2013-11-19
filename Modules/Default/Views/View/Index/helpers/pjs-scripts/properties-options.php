<?php require 'move-item.js.php'; ?>
<ul class="dropdown-menu pull-right">
    <?php if(strtolower($this->request->action)=="indexaction"): ?>
        <?php if($type=='folder'): ?>
            <li>
                <a href="#" onclick="RenameFolder(<?php echo isset($properties_called_from_headline_menu_file)?'':$pid ?>);" class='select mono-select'>
                    <span class="icon-edit"></span> 
                    Rename
                </a>
            </li>
            <?php if(!isset($properties_called_from_headline_menu_file)): ?>
            <li>
                <a href="#" onclick='ShareFolder(<?php echo $pid ?>)' class='select multi-select'>
                    <span class="icon-share"></span> 
                        <?php echo $type=='note'?'Toggle-':($this->parent->is_shared?'Un-':'')?>Share
                </a>
            </li>
            <?php endif; ?>
        <?php endif; ?>
        <li class='select ' >
            <a href="#" onclick='MoveItems(<?php echo isset($properties_called_from_headline_menu_file)?'':$pid ?>)' >
                <span class="icon-share"></span> Move
            </a>
        </li>
    <li class="divider select mono-select"></li>
    <?php endif;?>
    <li>
        <a href="#" onclick="alert('TODO')" class='select mono-select'>
            <span class="icon-cog"></span> 
            Info
        </a>
    </li>
    <?php if(!isset($properties_called_from_headline_menu_file)): ?>
    <li class="divider select multi-select"></li>
    <li>
        <a href="#" onclick="DeleteItems(<?php echo $pid?>, 't');" class='select multi-select btn-danger'>
            <span class="icon-remove"></span> 
            Delete
        </a>
    </li>
<?php endif; ?>
</ul>