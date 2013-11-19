<script>
    function CreateBlog()
    {       
        // because of styling matters this cannot be done with modalAlert() function
        $.post('/blog/create.template?ajax&suppress_layout=1<?php echo $this->GetSecureGET(array(User::GetInstance()->user_id))?>', 
                function(data) {
                    $('#create-box').modal({keyboard:true});
                    $('#create-box .modal-title').html('Create a blog ...');
                    $('#create-box .modal-body').html(data);
                });
    }
</script>