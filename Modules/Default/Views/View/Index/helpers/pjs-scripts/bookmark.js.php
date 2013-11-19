<script>
    function Bookmark()
    {
        $.post('/bookmark/create.template?ajax&p=<?php echo $pid; ?><?php echo $this->GetSecureGet(array($pid, User::GetInstance()->user_id))?>',
                    function(data){
                        $('#create-box').modal({keyboard:true});
                        $('#create-box .modal-body').html(data);
                        $('#create-box .modal-title').html('Create new bookmark...');
                        $('#create-box .modal-body input[name=\'folder_name\']').focus();
                    });
    }
</script>