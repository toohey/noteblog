<?php if($type!='attach') throw new \iMVC\Exceptions\InvalideOperationException("this file is not suit for the { .$type }"); ?>
<span class="icon-tag icon" data-toggle="tooltip" data-placement="top" title="" data-original-title="File" ></span>
<a href="/attach/i/<?php echo $f ?>/p/<?php echo $pid; ?>.download?<?php echo $this->GetSecureGET(array($f, $pid, User::GetInstance()->user_id))?>" id='<?php echo $f ?>' type='attach' style="margin-left: 3px;">
<?php echo $file->attach_name; ?></a>