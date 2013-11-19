<script>
<?php 
    $type = 'note';
    $pid = $this->note->parent_id;
?>
 function DeleteNote()
 {      
        confirm('Confirm','Are you sure do want to delete this note&hellip;?','No','Yes',function(){
            $.ajax({
                    url:'/note/delete?ajax&suppress_layout=1&pid=<?php echo $pid?>&hs=<?php echo \iMVC\Security\Hash::Generate($pid.User::GetInstance()->user_id) ?>', 
                    type:'POST',
                    data:{'file[]':'<?php echo $this->note->note_id ?>'},
                    complete:function(data) {
                        modalAlert('Deletion Result',data.responseText);
                        if($(data.responseText).hasClass('success'))
                            setTimeout(function(){
                                $(".modal").modal('hide');
                                setTimeout(function(){window.location = '<?php echo $this->note->parent_id==Note::ROOT_PARENT?'/':'/directory/'.$this->note->parent_id ?>.notes'},500);
                            },1000);
                    }});
        });
 }
</script>