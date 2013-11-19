<script>
    function MoveItems($id)
    {
        if($id === undefined)
            $id = $('form[name="file-lister"]').serialize();

        if($id === parseInt($id))
            $id = {'file[]':$id};
        $.ajax({
                    url:'/folder/move?ajax&suppress_layout=1&t=<?php echo $type ?>&p=<?php echo $pid?><?php echo $this->GetSecureGET(array($pid, $type, User::GetInstance()->user_id)) ?>', 
                    type:'POST',
                    data: $id,
                    complete:function(data) {
                        $('body').append(data.responseText);
                    }});
    }
</script>