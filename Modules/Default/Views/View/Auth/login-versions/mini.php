<form class="form-inline" style="margin-top: 4px;margin-bottom: -4px;margin-right: -20px;z-index: 100" action="/auth/login" method="POST">
    <input type="text" class="input-append span2" placeholder="Email" name="email"  required="">
    <input type="password" class="input-append span2" placeholder="Password" name="password" required="">
    <button type="submit" class="btn btn-mini" style="margin-bottom: 6px;">Sign in</button>
    <label class="checkbox " style="color:white;">
        <input type="checkbox"> Remember me
    </label>
    <?php require 'pass-reset.inc'; ?>
</form>