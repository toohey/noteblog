<div class="modal-backdrop"></div>
<div class="modal fade in span5" style="padding: 10px;top:20%;left:28%">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="modal-title"><h3>Signup</h3></div>
      </div>
        <div class="modal-body">
<?php if(isset($this->error['general'])) : ?>
<h4 class='text-error'>General Errors</h4>
<ol style='' class='text-error'>
<?php foreach($this->error['general'] as $key => $error) : ?>
    <li>
        <?php echo $error ?>
    </li>
<?php endforeach; ?>
</ol>
<?php endif; ?>
    <form action="/auth/signup" method="POST">
        <div class="">
            <div class="pull-left">
                <label class="inline <?php echo isset($this->error['email']) && strlen($this->error['email'])?'text-error':''?>" style="margin-top: 5px;">Enter your email:
                    <?php if(isset($this->error['email']) && strlen($this->error['email'])): ?>
                    <span class="dropdown inline">
                        <a href="" class="dropdown-toggle icon-question-sign" data-toggle="dropdown"></a>
                        <ul class="dropdown-menu pull-left" role="menu" >
                            <li style="width: 200px;color:black" class="text-center">
                                <?php echo $this->error['email'] ?>
                            </li>
                        </ul>
                    </span>
                    <?php endif; ?>
                </label>
            </div>
            <input class="inline input-medium span5 pull-right" type="text" name="email" required placeholder="Email Address" maxlength="100"/>
        </div>
        <div class="clear">
            <div class="pull-left">
                <label class="inline <?php echo isset($this->error['password']) && strlen($this->error['password'])?'text-error':''?>" style="margin-top: 5px;">Enter your password:
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
            </div><input class="inline input-medium pull-right span5" type="password" name='password' required="" placeholder="Password" maxlength="100" title="<?php echo $this->error['password']?>"/>
        </div>
        <div class="clear">
            <div class="pull-left">
                <label class="inline <?php echo isset($this->error['password']) && strlen($this->error['password'])?'text-error':''?>" style="margin-top: 5px;">Confirm your password:
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
            <input class="inline input-medium pull-right span5" type="password" name="conf_pass" maxlength="100" required="" placeholder="Confirm Password" /> 
        </div>
        <div class="modal-footer clear">
            <input class="btn btn-primary pull-right" type="submit" value="Submit" />
        </div>
    </form>
        </div>
        </div>
  </div>
</div>