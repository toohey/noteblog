<script>
    function Upload()
    {
        $.get('/attach?ajax&p=<?php echo $pid; ?><?php echo $this->GetSecureGet(array($pid, User::GetInstance()->user_id))?>',
                    function(data){
                        $('#create-box').modal({keyboard:true});
                        $('#create-box .modal-body').html(data);
                        $('#create-box .modal-title').html('Upload attachmentes ...');
                    });
    }
</script>