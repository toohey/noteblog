<form action="/auth/reset.reset?<?php echo $this->GetSecureGET(array(session_id())); ?>" method="POST">
    <div class="">
        <div class="pull-left">
            <label class="inline" style="margin-top: 5px;">Enter your email:</label>
        </div>
        <input class="inline input-medium span5 pull-right" type="text" name="email" required="" placeholder="Email Address" maxlength="100"/>
    </div>
    <div class="clear pull-right" style="margin-top:15px;">
    </div>
    <div class="modal-footer clear">
        <input class="btn btn-primary pull-right" type="submit" value="Reset" />
    </div>
</form>