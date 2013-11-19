<script>
    function CreateFolder()
    {
        // because of styling matters this cannot be done with modalAlert() function
        $.get('/folder/create?ajax&suppress_layout=1&p=<?php echo $pid?><?php echo $this->GetSecureGET(array($pid, User::GetInstance()->user_id)) ?>', 
                function(data) {
                    //alert(data);
                    $('#create-box').modal({keyboard:true});
                    $('#create-box .modal-body').html(data);
                    $('#create-box .modal-title').html('Create a folder...');
                    Reload();
                });
    }
    function RenameFolder($id)
    {
        if($id === undefined)
            $id = $('form[name="file-lister"]').serialize();
        
        if($id === parseInt($id))
            $id = {'file[]':$id};//"file%5B%5D="+$id; // aka "file[]="+$id
        // because of styling matters this cannot be done with modalAlert() function
        $.get('/folder/rename?ajax&suppress_layout=1&p=<?php echo $pid?><?php echo $this->GetSecureGET(array($pid, User::GetInstance()->user_id)) ?>', 
                $id,
                function(data) {
                    //alert(data);
                    $('#create-box').modal({keyboard:true});
                    $('#create-box .modal-body').html(data);
                    $('#create-box .modal-title').html('Renaming a folder...');
                    Reload();
                });
    }
</script>