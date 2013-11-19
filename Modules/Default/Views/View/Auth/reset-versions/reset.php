<?php //\iMVC\Tools\Debug::_var($this,1); ?>
<?php if(!isset($this->message)) throw new \iMVC\Exceptions\InvalideArgumentException("The `\$message` variable does not provided"); ?>
<?php if(!isset($this->success)) $this->success = 1; ?>
<div class='alert alert-<?php echo $this->success?"success":"error"?> text-center'>
    <p style="font-size: larger"><?php echo $this->message; ?></p>
</div>
<?php 
    if(!$this->success)
        require 'html.php'; 
?>