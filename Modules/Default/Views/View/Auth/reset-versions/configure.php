<?php 
    if(!isset($this->error['password']))
        $this->error = array('password' => "");
?>
<form action="/auth/reset.action?<?php echo $this->GetSecureGET(array(session_id(), $this->email, $this->expire)); ?>" method="POST">
        <input type="hidden" name="email" value="<?php echo $this->email?>">
        <input type="hidden" name="expire" value="<?php echo $this->expire?>">
        <div class="clear">
            <div class="pull-left">
                <label class="inline <?php echo isset($this->error['password']) && strlen($this->error['password'])?'text-error':''?>" style="margin-top: 5px;">Enter your new password:
                    <?php if(isset($this->error['password']) && strlen($this->error['password'])): ?>
                    <span class="dropdown inline">
                        <a href="" class="dropdown-toggle icon-question-sign" data-toggle="dropdown"></a>
                        <ul class="dropdown-menu pull-left" role="menu" >
                            <li style="width: 200px;color:black" class="text-center">
                                <?php echo $this->error['password'] ?>
                            </li>
                        </ul>
                    </span>
                    <?php endif; ?>
                </label>
            </div><input class="inline input-medium pull-right span5" type="password" name='password' required="" placeholder="New Password" maxlength="100" title="<?php echo $this->error['password']?>"/>
        </div>
        <div class="clear">
            <div class="pull-left">
                <label class="inline <?php echo isset($this->error['password']) && strlen($this->error['password'])?'text-error':''?>" style="margin-top: 5px;">Confirm your new password:
                    <?php if(isset($this->error['password']) && strlen($this->error['password'])): ?>
                    <span class="dropdown inline">
                        <a href="" class="dropdown-toggle icon-question-sign" data-toggle="dropdown"></a>
                        <ul class="dropdown-menu pull-left" role="menu" >
                            <li style="width: 200px;color:black" class="text-center">
                                <?php echo $this->error['password'] ?>
                            </li>
                        </ul>
                    </span>
                    <?php endif; ?>
                </label>
            </div>
            <input class="inline input-medium pull-right span5" type="password" name="conf_pass" maxlength="100" required="" placeholder="Confirm New Password" /> 
        </div>
    <div class="clear pull-right" style="margin-top:15px;">
        <label class="checkbox ">
            <input type="checkbox" name='remmeber_me'> Remember me
        </label>
    </div>
    <div class="modal-footer clear">
        <input class="btn btn-primary pull-right" type="submit" value="Reset & Login" />
    </div>
</form>