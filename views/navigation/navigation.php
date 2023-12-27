<?php
global  $middle;
$logo = \Innova\modules\Site::logo();
$name = \Innova\modules\Site::name();
$currentUser = new \Innova\modules\CurrentUser();
?>
<div class="header">
    <div class="header-left">
        <a href="/" class="logo">
            <img src="<?php echo $logo; ?>" width="35" height="35" alt=""> <span><?php echo $name; ?></span>
        </a>
    </div>
    <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
    <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
    <ul class="nav user-menu float-right">
        <li class="nav-item dropdown has-arrow">
            <a href="<?php echo empty((new \Innova\modules\CurrentUser())->id()) ? '#' : fullURI('/user/'.(new \Innova\modules\CurrentUser())->id()); ?>" class="nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="<?php echo $currentUser->image(); ?>" width="24">
							<span class="status online"></span>
						</span>
                <span><?php echo $currentUser->firstname(); ?></span>
            </a>
        </li>
    </ul>
</div>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>
                <li class="active">
                    <a href="/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="<?php echo fullURI('/users/register'); ?>"><i class="fa fa-user-md"></i> <span>Create User</span></a>
                </li><?php if(empty((new \Innova\modules\CurrentUser())->id())): ?>
                <li>
                    <a href="<?php echo fullURI('/users/sign-in'); ?>"><i class="fa fa-user"></i> <span>Sign In</span></a>
                </li><?php else: ?>
                <li>
                  <a href="<?php echo fullURI('/users/logout/'.(new \Innova\modules\CurrentUser())->firstname()); ?>"><i class="fa fa-user"></i> <span>Sign out</span></a>
                </li>
                 <?php endif; ?><li>
                    <a href="<?php echo fullURI('/users/password'); ?>"><i class="fa fa-edit"></i> <span>Reset Password</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
