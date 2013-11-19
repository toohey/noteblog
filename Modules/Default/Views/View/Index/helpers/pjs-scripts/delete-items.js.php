<?php require_once 'general.js.php'; ?>
<script>
    function DeleteItems($id, $t)
    {
        $redir = false;
        if($id === undefined)
        {
            $id = $('form[name="file-lister"]').serialize();
            if($id.length===0)
                return;
        }
        
        if($id === parseInt($id))
        {
            $redir = true;
            $id = {'file[]' : $id}; // aka "file[]="+$id
        }
        if($t === undefined)
            $t = '';
        else $t = "&"+$t;
        
        ajax = function(){
            $.ajax({
                    url:'/<?php echo $type?>/delete?ajax'+$t+'&suppress_layout=1&pid=<?php echo $pid?>&hs=<?php echo \iMVC\Security\Hash::Generate($pid.User::GetInstance()->user_id) ?>', 
                    type:'POST',
                    data: $id,
                    complete:function(data) {
                        modalAlert('Deletion Result',data.responseText);
                        if($(data.responseText).hasClass('success'))
                            setTimeout(function(){
                                $(".modal").modal('hide');
                                setTimeout(function(){ 
                                    if($redir)
                                        window.location = '<?php
                                            if($this->parent->parent_id!=Folder::ROOT_PARENT)
                                                echo "/directory/{$this->parent->parent_id};";
                                            else 
                                                echo "/";
                                        ?>';
                                    else
                                        Reload('<?php echo $type?>');
                                },500);
                            },1500);
                    }});
        };
        if($t === '&r')
            ajax();
        else
            confirm('Confirm','Are you sure do want to delete <?php echo $type?>&hellip;?','No','Yes',ajax);
    }
</script>