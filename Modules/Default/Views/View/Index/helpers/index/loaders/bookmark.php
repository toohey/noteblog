<?php if($type!='bookmark') throw new \iMVC\Exceptions\InvalideOperationException("this file is not suit for the \{$type}"); ?>
<span class="icon-bookmark icon" data-toggle="tooltip" data-placement="top" title="" data-original-title="Bookmark" ></span>
<a href="/bookmark/<?php echo $f ?>.bookmark" id='<?php echo $f ?>' type='bookmark' style="margin-left: 3px;" <?php if($type=='bookmark' && strlen($file->bookmark_body)): ?> data-toggle="tooltip" data-placement="top" title="" data-original-title='<?php echo substr($file->bookmark_body,0,30);?>'<?php endif;?>>
<?php echo $file->bookmark_name; ?></a>
<small>[ 
    <a href='/bookmark/<?php echo $file->bookmark_id;?>.bookmark' target="__blank" data-toggle="tooltip" data-placement="top" title="" data-original-title='Open in a new page'>^</a> ]  
</small>