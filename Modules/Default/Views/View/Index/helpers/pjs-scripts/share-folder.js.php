<script>
    function ShareFolder($id)
    {
        $t=0;
        if($id === undefined)
        {
            $t = 1;
            $id = $('form[name="file-lister"]').serialize();
            <?php if($type=='folder'): ?>
            $no = $('form[name="file-lister"] :checked').length;
            if(!$no) return;
            while($no--)
            {
                if($no > 2)
                {
                    modalAlert('For sake of avoiding mistakes, you cannot share multi-folders at once.');
                    return;
                }
                else
                {
                    if($('form[name="file-lister"] input[name="chx"]:checked').length!==0)
                        $no=0;
                }
            }
            <?php endif; ?>
        }

        if($id === parseInt($id))
        {
            $id = {'file[]':$id};
        }
        // now we only one folder to share! it doesn't matter that if it came from checkboxes or it passed
        confirm('<b style="color:red;font-variant:small-caps"><?php echo $type=='folder'?'Danger':'Public'?> Zone</b>','<div class="alert alert-danger" style="font-variant:small-caps"><?php if($type=='folder'): ?>\n\
                    <b><?php echo ($this->parent->is_shared?"Un-":"")?>Shareing</b> a folder would <?php echo ($this->parent->is_shared?"un-":"")?>share <b>all of its subfolders matteriasl</b> at once. <b>This cannot be un-done!</b><br />\n\
                    <?php endif; ?><b>Are you sure?</b><?php if($type=='folder'): ?></div><?php endif;?>', "Call it off", "It's OK", function(){
                    $('.modal').modal('hide');
                    <?php if($type=='folder'): ?>
                        waitAlert('<div class="alert alert-info">This \n\
                                        may take while, depeneds on sub-items count and \n\
                                        some other stuff!\n\
                                        <div class="text-warning text-center">You <b>may close this dialog</b> but <b>do not redirect</b> or <b>close \n\
                                        the window untill</b> you <b>get confirm</b> that the <b>operation \n\
                                        is done</b>!<br /><b style="font-variant:small-caps">otherwise the operation may end unfinished!!</b></div></div>');
                    <?php endif; ?>
                    $.ajax({
                        url:'/<?php echo $type?>/share/p/<?php echo $pid?>/'+($t==1?"t/1/":"")+'s/<?php echo $this->parent->is_shared?>?ajax<?php echo $this->GetSecureGET(array($pid, User::GetInstance()->user_id)) ?>', 
                        type:'POST',
                        data: $id,
                        complete:function(data) {
                            setTimeout(function(){
                                $('.modal').modal('hide');
                                modalAlert('Done!', data.responseText);
                                if(!$(data.responseText).hasClass('success')) return;
                                    setTimeout(function(){
                                <?php if($type=='folder'): ?>
                                        document.location.reload(true);
                                <?php else: ?>
                                        $('.modal').modal('hide');
                                        Reload();
                                <?php endif; ?>
                                    },1500);
                            },300);
                        }
                    });
                    });
    }
    /*$(document).ready(function(){
        $(document).on('keyup', null, 'ctrl+a', function($e){
            alert('a');
            ShareFolder();
        });
    });*/
</script>