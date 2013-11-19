<li class="dropdown" style="float: right">
    <a href="" class="dropdown-toggle " data-toggle="dropdown" style="color:white;font-variant: small-caps">
        <?php
            if(!User::GetInstance()->userprofile)
            {
                echo "Options";
            }
            else
                echo \User::GetInstance()->name() ?>
    </a>
    <ul class="dropdown-menu pull-right" role="menu"  >
        <li>
            <a href="<?php echo !User::GetInstance()->userprofile?'/profile/edit':'/profile'?>"><i class="icon-user"></i> <?php echo !User::GetInstance()->userprofile?"Create":""?> Profile</a>
        </li>
        <li>
            <a href="#"><i class="icon-eye-open"></i> Blog Setting</a>
        </li>
        <li>
            <a href="#"><i class="icon-wrench"></i> Account Setting</a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="/auth/logout"><i class="icon-off"></i> Logout</a>
        </li>
    </ul>
</li>