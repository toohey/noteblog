<div class="modal-backdrop"></div>
<div class="modal fade in span5" style="padding: 10px;top:20%;left:28%">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="modal-title"><h3>Login</h3></div>
      </div>
        <div class="modal-body" >
            <?php if(isset($this->error['general'])) : ?>
            <h4 class='text-error'>General Errors</h4>
            <ol style='' class='text-error'>
            <?php foreach($this->error['general'] as $error) : ?>
                <li>
                    <?php echo $error ?>
                </li>
            <?php endforeach; ?>
            </ol>
            <?php endif; ?>
                <form action="/auth/login" method="POST">
                    <div class="">
                        <div class="pull-left">
                            <label class="inline" style="margin-top: 5px;">Enter your email:</label>
                        </div>
                        <input class="inline input-medium span5 pull-right" type="text" name="email" required="" placeholder="Email Address" maxlength="100"/>
                    </div>
                    <div class="clear">
                        <div class="pull-left">
                            <label class="inline" style="margin-top: 5px;">Enter your password:</label>
                        </div><input class="inline input-medium pull-right span5" type="password" name='password' required="" placeholder="Password" maxlength="100"/>
                    </div>
                    <div class="clear pull-right" style="margin-top:15px;">
                        <label class="checkbox ">
                            <input type="checkbox" name='remmeber_me'> Remember me
                        </label>
                        <?php require 'pass-reset.inc'; ?>
                    </div>
                    <div class="modal-footer clear">
                        <input class="btn btn-primary pull-right" type="submit" value="Signin" />
                    </div>
                </form>
        </div>
    </div>
  </div>
</div>