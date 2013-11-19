<?php require_once 'general.js.php';?>
<script>
    function De_Archive($archive_type, $id)
    {
        if($archive_type === undefined)
        {
            modalAlert("$archive_type didn't define!");
            return;
        }
        
        
        if($archive_type === 0)
            $archive_type = "dearchive";
        else if($archive_type === 1)
            $archive_type = "archive";
        else
            modalAlert("$archive_type didn't define well!");
            
        
        $redir = false;
        if($id === undefined)
            $id = $('form[name="file-lister"]').serialize();
        
        if($id === parseInt($id))
        {
            $redir = true;
            $id = {'file[]' : $id}; // aka "file[]="+$id
        }
        $.ajax({
                    url:'/<?php echo $type?>/archive.'+$archive_type+'?ajax&suppress_layout=1', 
                    type:'POST',
                    data: $id,
                    complete:function(data) {
                        modalAlert('Deletion Result',data.responseText);
                        if($(data.responseText).hasClass('success'))
                            setTimeout(function(){
                                    $(".modal").modal('hide');
                                    if($redir)
                                        setTimeout(function(){
                                                window.location = '<?php
                                                    if($this->parent->parent_id!=Folder::ROOT_PARENT)
                                                        echo "/directory/{$this->parent->parent_id}";
                                                    else 
                                                        echo "/";
                                                ?>';
                                        },500);
                                    else
                                        Reload('<?php echo $type?>');
                            },1500);
                    }
        });
    }
</script>