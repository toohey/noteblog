<?php $reading = (strtolower($this->request->action)=='indexaction' || strtolower($this->request->action)=='showaction'); ?>
<div class='pull-right' style='<?php echo $reading?"margin-top: -40px;":""?>'>
    <?php if($reading || isset($this->editting) && $this->editting) :?>
    <?php $this->parent =  new stdClass(); $this->parent->parent_id = $this->note->parent_id; ?>
    <?php require_once MODULE_PATH.'Default/Views/View/Index/helpers/pjs-scripts/de-archive.js.php';?>
        <div class="btn-group">
            <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="icon-tasks"></span>
                Action
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php if($reading): ?>
                <li><a href='/note/edit/n/<?php echo $this->note->note_id?><?php echo $this->note->parent_id==Folder::ROOT_PARENT?"":"/p/{$this->note->parent_id}" ?>?<?php echo $this->GetSecureGET(array($this->note->note_id, $this->note->parent_id)) ?>' class='select mono-select' id='edit-folder'><span class="icon-edit"></span> Edit</a></li>
                <li class="divider select mono-select"></li>
                <?php endif; ?>
                <li><a href='/note/share/n/<?php echo $this->note->note_id ?>/s/<?php echo $this->note->is_shared?>?<?php echo $this->GetSecureGET(array($this->note->is_shared,$this->note->note_id)) ?>' class='select multi-select' id='drp-menu-delete-sel'><span class="icon-share"></span> <?php echo $this->note->is_shared?"Un-":""?>Share</a></li>
                <li><a href='#' onclick='De_Archive(<?php echo $this->note->is_archived?0:1?>, <?php echo $this->note->note_id ?>)' class='select multi-select' id='drp-menu-delete-sel'><span class="icon-briefcase"></span> <?php echo $this->note->is_archived?"Dearchive":"Archive"?></a></li>

                <li class="divider select multi-select"></li>
                <li><a onclick="DeleteNote();" class='select multi-select btn-danger' id='drp-menu-delete-sel'><span class="icon-remove"></span> Delete</a></li>
            </ul>
        </div>
        <a href='/note/edit<?php echo $this->note->parent_id!=Folder::ROOT_PARENT?"/p/{$this->note->parent_id}":""?>?<?php echo $this->GetSecureGET(array($this->note->parent_id))?>' class='btn'>Make New</a>    
        <?php endif; ?>
        <a href='<?php echo $this->note->parent_id==-1?"/":"/directory/{$this->note->parent_id}" ?>.notes' class='btn btn-info'>Go Back</a>
</div>