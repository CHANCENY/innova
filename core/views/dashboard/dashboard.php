<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Welcome to Innova <?php echo $firstname; ?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg1"><i class="fa fa-users" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $dashboard->usersCount(); ?></h3>
                        <span class="widget-title1">Users <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg2"><i class="fa fa-file"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $dashboard->fileCount(); ?></h3>
                        <span class="widget-title2">Files <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg3"><i class="fa fa-connectdevelop" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $dashboard->routeCount(); ?></h3>
                        <span class="widget-title3">Routes <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg4"><i class="fa fa-database" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $dashboard->storageCount(); ?></h3>
                        <span class="widget-title4">Storages <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-8 col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title d-inline-block">Settings</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="d-none">
                                <tr>
                                    <th>Configuration</th>
                                    <th class="text-right">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="min-width: 200px;">
                                        <a class="avatar" href="<?php echo fullURI('settings/email'); ?>">E</a>
                                        <h2>Email Configuration</h2>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo fullURI('settings/email'); ?>" class="btn btn-outline-primary take-btn">
                                            <?php echo !empty($dashboard->mailSetting()) ? "Active" : "InActive"; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="min-width: 200px;">
                                        <a class="avatar" href="<?php echo fullURI('settings/errors'); ?>">E</a>
                                        <h2>Errors Configuration</h2>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo fullURI('settings/errors'); ?>" class="btn btn-outline-primary take-btn">
                                            <?php echo !empty($dashboard->errorSetting()) ? "Active" : "InActive"; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="min-width: 200px;">
                                        <a class="avatar" href="<?php echo fullURI('settings/forms'); ?>">C</a>
                                        <h2>CSRF Configuration</h2>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo fullURI('settings/forms'); ?>" class="btn btn-outline-primary take-btn">
                                            <?php echo !empty($dashboard->csrfSecurity()) ? "Active" : "InActive"; ?>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-4">
                <div class="card member-panel">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Others</h4>
                    </div>
                    <div class="card-body">
                        <ul class="contact-list">
                            <li>
                                <div class="contact-cont">
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis">Errors Count</span>
                                        <span class="contact-date"><?php echo $dashboard->errorCount(); ?></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-cont">
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis">Modules Count</span>
                                        <span class="contact-date"><?php echo $dashboard->modulesCount(); ?></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-8 col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title d-inline-block">Administrators </h4>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table mb-0 new-patient-table">
                                <tbody><?php if (!empty($dashboard->administrators())): foreach ($dashboard->administrators() as $key=>$administrator): ?>
                                <tr>
                                    <td>
                                        <img width="28" height="28" class="rounded-circle" src="<?php echo $administrator['image'] ?? null; ?>" alt="">
                                        <h2><?php echo $administrator['firstname']. ' '. $administrator['lastname']; ?></h2>
                                    </td>
                                    <td><a href="<?php echo fullURI('user/'. $administrator['id']); ?>" class="btn btn-primary btn-primary-one float-right">Profile</a></td>
                                </tr>
                                <?php endforeach; endif; ?></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
