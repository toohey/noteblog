<?php if(!isset($this->notif_content) || !strlen($this->notif_content)) return; ?>
<script>
    $(document).ready(function(){
        setTimeout(function(){modalAlert('<?php echo $this->notif_content ?>') },300);
    });
</script>