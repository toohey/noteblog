<div id="file-load-section">
    <div style="clear:both;"></div>
    <hr />
    <div style='margin-top: -10px;padding-left: 10;'>
    <?php require 'loaders/map.php'; ?>
    <?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/check-box.check.js.php'; ?>
    <?php require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/general.js.php'; ?>
    </div>
    <div id="ajax-call-result" style="margin-top: 10px;margin-bottom: -10px;"></div>
    <div style="clear:both;"></div>
    <div id="file-loader">
        <form action="/" method="post" name='file-lister' >
            <table class="table table-hover" id="file-tables">
                <thead style='border-bottom: 1px #e6e6e6 solid'>
                    <tr style="font-variant: small-caps;">
                        <td class="" style='width: 60%'>
                            <div class="checkbox inline">
                                <input class="checkbox" type="checkbox" name="chx" onchange="chx_changed($(this))"/>
                            </div>
                            Title
                        </td>
                        <td>
                            Last Modified
                        </td>
                        <td>
                            Created Date
                        </td>
                    </tr>
                </thead>
                <tbody class="">
                    <?php 
                        if(!isset($this->files)) $this->files = array();
                        $f = null;
                        foreach ($this->files as $file) :
                            $_type = $type;
                            $f = $_type."_id"; 
                            $f = $file->$f;
                    ?>
                    <tr class="controls file" id="file-<?php echo $f ?>">
                        <td style="margin-right:10px;" >
                            <div class="checkbox inline" style="margin-bottom: 5px;">
                                <input type="checkbox" name="file[]" id="checkbox-<?php echo $f?>" value="<?php echo $f?>" class="file-checkbox" onchange="check_changed($(this))"/>
                            </div>
                            <span style="margin-top: 2px;" >
                            <?php if($file->is_archived): ?>
                            <span id ='archived-<?php echo $f?>' class='icon-briefcase icon' data-toggle="tooltip" data-placement="top" title="" data-original-title="Archived"></span>
                            <?php endif; ?>
                            <?php if($file->is_trashed): ?>
                            <span id ='trashed-<?php echo $f?>' class='icon-trash icon' data-toggle="tooltip" data-placement="top" title="" data-original-title="Trash"></span>
                            <?php endif; ?>
                            <?php if($file->is_shared): ?>
                            <span id ='shared-<?php echo $f?>' class='icon-share icon' data-toggle="tooltip" data-placement="top" title="" data-original-title="Shared"></span>
                            <?php endif; ?>
                            <?php                                              
                                    switch($type)
                                    {
                                        case 'htm';
                                        case 'folder':
                                            require 'loaders/folder.php';
                                            break;
                                        case 'note':
                                            require 'loaders/note.php';
                                            break;
                                        case 'bookmark':
                                            require 'loaders/bookmark.php';
                                            break;
                                        case 'attach':
                                            require 'loaders/attach.php';
                                            break;
                                        default:
                                            throw new UnexpectedValueException("page extention \{$type\} isn't defined...");
                                    }
                            ?>
                            </span>
                        </td>
                        <td>
                            <div style="margin-top: 4px;"><?= date('d M Y H:i:s', $file->updated_at->getTimestamp()) ?></div>
                        </td>
                        <td >
                            <div style="margin-top: 4px;"><?= date('d M Y H:i:s', $file->created_at->getTimestamp()) ?></div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
<?php if(isset($this->next->EOQ) && !$this->next->EOQ) : ?>
<div class="alert alert-block alert-info text-center">
    <a href="<?php echo $action; ?><?php echo $this->next->link->o!=0? "/o/{$this->next->link->o}":"" ?>/l/<?php echo $this->next->link->l ?>.<?php echo $type!='attach'?"{$type}s":"{$type}es"?>#file-<?php echo $f; ?>"  class="text-info">See more</a>
</div>
</div>
<?php endif; ?>
<style>
    #file-loader .icon{margin-top: 4px;}
</style>