<li class="dropdown" style="float: right">
    <a href="" class="dropdown-toggle " data-toggle="dropdown" style="color:white;font-variant: small-caps">
        Signup
    </a>
    <div class="dropdown-menu pull-right span4" role="menu" style="padding: 10px;">
        <form class="form" style="margin-top: 4px;margin-bottom: -4px;margin-right: -20px;z-index: 100" action="/auth/signup" method="POST">
            <input type="text" class="input-block-level input-xlarge" placeholder="Email" name="email" required=""><br />
            <input type="password" class="input-block-level input-xlarge" placeholder="Password" name="password" required=""><br />
            <input type="password" class="input-block-level input-xlarge" placeholder="Re-Type Password" name="conf_pass" required=""><br />
            <div class="form-actions" style="margin: -10px;margin-right: 10px;margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="margin-bottom: 6px;">Sign up</button>
            </div>
        </form>
    </div>
</li>