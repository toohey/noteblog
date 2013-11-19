<script>
    $(document).ready(function(){
        chkbox_active_counter = 0;
        update_menu_items ();
    });
    function chx_changed($this){
        $t = $this;
        chkbox_active_counter = 0;
        $("form[name='file-lister'] input.file-checkbox").each(function()
        {
            if(this===$t) return;
            this.checked = $t.is(":checked");
            if(this.checked)
                chkbox_active_counter++;
        });
        update_menu_items();
    }
    function check_changed($this){
        c = $this.is(":checked");
        if(c)
            chkbox_active_counter++;
        else
            chkbox_active_counter--;
        update_menu_items();
    };
    function update_menu_items()
    {
        if(chkbox_active_counter<0) chkbox_active_counter = 0;
        if(chkbox_active_counter>0)
        {
            $('.select').show();
            $('.no-select').hide();
        }
        else
        {
            $('.select').hide();
            $('.no-select').show();
        }    
        if(chkbox_active_counter===1)
            $('.mono-select').show();
        else
        {
            $('.mono-select').hide();
        }
    }
</script>