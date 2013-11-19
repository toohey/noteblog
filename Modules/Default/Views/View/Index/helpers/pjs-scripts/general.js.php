<?php require 'create-box.html'; ?>
<script type="text/javascript">
    
    $(document).ready(function(){
       $('*[data-toggle="tooltip"]').tooltip(); 
    });
    function Reload($part, $forced)
    {
        if($part === undefined )
            $part = "<?php echo $type?>";
        if($forced === undefined )
            $forced = false;
        $(".select").hide();
        $('#nav-tab-note-folder .active').removeClass('active');
        $('#nav-tab-note-folder #<?php echo $type=="attach"?"{$type}es":"{$type}s"?>-tab-link').addClass("active");
        if($forced || $part === '<?php echo $type?>')
        {
            $.post('<?php echo $uri?>/o/<?php echo $this->request->params['o']?>/l/<?php echo $this->request->params['l']?>.<?php echo $type=="attach"?"{$type}es":"{$type}s"?>.reload?ajax',
                        function(data)
                        {
                            $("div#file-load-section").html(data);
                        });
        }
        chkbox_active_counter = 0;
        update_menu_items ();
        return true;
    }
</script>