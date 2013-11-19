<?php if($type!='folder') throw new \iMVC\Exceptions\InvalideOperationException("this file is not suit for the \{$type}"); ?>
<span class="icon-folder-close icon" data-toggle="tooltip" data-placement="top" title="" data-original-title="Folder" ></span>
<a href="/directory/<?php echo $f ?>.folders" id='<?php echo $f ?>' type='folder' style="margin-left: 3px;">
<?php echo $file->folder_name; ?></a>