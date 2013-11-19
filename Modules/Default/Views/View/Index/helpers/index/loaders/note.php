<?php if($type!='note') throw new \iMVC\Exceptions\InvalideOperationException("this file is not suit for the { .$type }"); ?>
<span class="icon-file icon" data-toggle="tooltip" data-placement="top" title="" data-original-title="File" ></span>
<a href="/note/<?php echo $f ?>.view" id='<?php echo $f ?>' type='note' style="margin-left: 3px;">
<?php echo $file->note_name; ?></a>