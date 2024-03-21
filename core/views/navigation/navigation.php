<?php
$host = (new \Innova\request\Request())->httpSchema();
global  $middle;
$logo = \Innova\modules\Site::logo();
$name = \Innova\modules\Site::name();
$currentUser = new \Innova\modules\CurrentUser();
?>
<?php if(\Innova\Routes\SecureController::dashBoardAccess()): ?>
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
                <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="<?php echo $currentUser->image(); ?>" width="24">
							<span class="status online"></span>
						</span>
                    <span><?php echo $currentUser->firstname(); ?></span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo $host ?? ''; ?>/user/<?php echo $uid ?? 0; ?>">My Profile</a>
                </div>
            </li>
        </ul>
    </div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="menu-title">Main</li>
                    <li class="">
                        <a href="/<?php echo $middle; ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>
                    <li>
                        <a href="<?php echo fullURI('/errors/display'); ?>"><i class="fa fa-list"></i> <span>Errors</span></a>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fa fa-link"></i> <span> Routes </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="<?php echo fullURI('/routing/listing'); ?>"><i class="fa fa-list"></i><span>Routes List</span></a></li>
                            <li>
                                <a href="<?php echo fullURI('/routing/create'); ?>"><i class="fa fa-plus"></i> <span>Route Creation</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fa fa-user"></i> <span> Users </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li>
                                <a href="<?php echo fullURI('/users/password') ?>"><i class="fa fa-refresh"></i> <span>Reset Password</span></a>
                            </li><?php if(empty($currentUser->id())): ?>
                            <li>
                            <a href="<?php echo fullURI('/users/sign-in'); ?>"><i class="fa fa-user"></i> <span>Sign In</span></a>
                            </li><?php else: ?>
                            <li>
                              <a href="<?php echo fullURI('/users/logout/'.$currentUser->firstname()); ?>"><i class="fa fa-user"></i> <span>Sign out</span></a>
                            </li>
                          <?php endif; ?>
                            <li>
                                <a href="<?php echo $middle; ?>/users/register"><i class="fa fa-user-plus"></i> <span>Create User</span></a>
                            </li>
                            <li>
                                <a href="<?php echo $middle; ?>/users/listing"><i class="fa fa-list"></i> <span>Users</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fa fa-folder"></i> <span> Storages </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="<?php echo fullURI('/storages/storage/listing'); ?>"><i class="fa fa-list"></i><span>Storage List</span></a></li>
                            <li><a href="<?php echo fullURI('/storages/storage/create'); ?>"><span><i class="fa fa-plus"></i>Create Storage</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fa fa-gear"></i> <span> Settings </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="<?php echo fullURI('/settings/errors'); ?>"><i class="fa fa-gear"></i><span>Errors</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/storage/update'); ?>"><span><i class="fa fa-database"></i>Storages Update</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/files'); ?>"><span><i class="fa fa-file"></i>Files Settings</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/email'); ?>"><span><i class="fa fa-gear"></i>Email Settings</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/site'); ?>"><span><i class="fa fa-info"></i>Site</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/forms'); ?>"><span><i class="fa fa-lock"></i>Forms Security</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/authentications'); ?>"><span><i class="fa fa-lock"></i>Authentications</span></a></li>
                          <li><a href="<?php echo fullURI('/settings/home'); ?>"><span><i class="fa fa-lock"></i>Home setting</span></a></li>
                            <li><a href="<?php echo fullURI('/settings/permission'); ?>"><span><i class="fa fa-shield"></i>Permissions</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fa fa-file"></i> <span> Files Managements </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="<?php echo fullURI('/files/content'); ?>"><i class="fa fa-list"></i><span>Files</span></a></li>
                            <li><a href="<?php echo fullURI('/files/content/upload'); ?>"><span><i class="fa fa-upload"></i>Upload</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fa fa-file-zip-o"></i> <span> Extend </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="<?php echo fullURI('/extend/modules/install'); ?>"><i class="fa fa-download"></i><span>PHP module install</span></a></li>
                            <li><a href="<?php echo fullURI('/extend/library/install'); ?>"><span><i class="fa fa-download"></i>Libraries install</span></a></li>
                        </ul>
                    </li>
                </ul>
                <!--Custom menus-->
              <ul>
                <li class="menu-title">Custom</li>
                <?php if(!empty($menus)): foreach ($menus as $key=>$menu): ?>
                <?php if(is_numeric($key)):  ?>
                <li class="">
                  <a href="<?php echo $menu['link'] ?? null; ?>" title="<?php echo $menu['title']; ?>"><i class="<?php echo $menu['class']; ?>"></i> <span><?php echo $menu['text']; ?></span></a>
                </li>
                <?php elseif (gettype($key) === 'string'): $list = explode(':',$key); $groupName = $list[0]; $groupClass = end($list); ?>
                <li class="submenu">
                  <a href="#"><i class="<?php echo $groupClass; ?>"></i> <span> <?php echo $groupName; ?> </span> <span class="menu-arrow"></span></a>
                  <ul style="display: none;">
                    <?php foreach ($menu as $k): ?>
                    <li>
                      <a href="<?php echo $k['link'] ?? null; ?>" title="<?php echo $k['title']; ?>"><i class="<?php echo $k['class']; ?>"></i> <span><?php echo $k['text']; ?></span></a>
                    </li>
                   <?php endforeach; ?>
                  </ul>
                </li>
               <?php endif; endforeach; endif; ?>
              </ul>
            </div>
        </div>
    </div>
<?php endif; ?>