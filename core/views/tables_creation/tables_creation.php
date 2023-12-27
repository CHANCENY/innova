<div class="col-lg-10 mt-lg-5 m-auto">
    <div class="row">
        <div class="col-sm-4 col-3">
            <h4 class="page-title"><?php echo $site ?></h4>
        </div>
        <div class="col-sm-8 col-9 text-right m-b-20">
            <a href="<?php echo $startOver; ?>" class="btn btn-primary float-right btn-rounded"><i class="fa fa-backward"></i> Start Over</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table">
                    <thead>
                    <tr>
                        <th style="min-width:200px;">Site Name</th>
                        <th>Database Name</th>
                        <th>Site Email</th>
                        <th>Database User</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <img width="28" height="28" src="<?php echo $logo; ?>" class="rounded-circle" alt=""> <h2><?php echo $site; ?></h2>
                        </td>
                        <td><?php echo $database; ?></td>
                        <td><?php echo $site_email; ?></td>
                        <td><?php echo $databaseUser; ?></td>
                        <td class="text-right">
                            <a class="btn btn-primary btn-rounded" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-forward m-r-5"></i> Continue</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="delete_employee" class="modal fade delete-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="<?php echo (new \Innova\request\Request())->httpSchema(). '/controllers/defaults/tables_creation/assets/image/storage.png'; ?>" alt="" data="<?php echo $loading; ?>" id="loading" width="200" height="150">
                    <h3>Initialize database tables creation</h3>
                    <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                        <button type="submit" data="<?php echo (new \Innova\request\Request())->httpSchema(); ?>" onclick="startTableCreation(this)" class="btn btn-primary">Start</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>