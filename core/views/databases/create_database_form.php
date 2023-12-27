<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Database Creation Form</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"><?php echo $msg; ?>
                <div class="card-box">
                    <h4 class="card-title">Database</h4>
                    <form action="<?php echo $url;?>" method="POST">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Database Type</label>
                            <div class="col-md-9">
                                <select name="database_type" class="form-control">
                                    <option>--Select database type--</option>
                                    <option value="mysql">MySQL</option>
                                    <option value="pgsql">PostgreSQL</option>
                                    <option value="sqlite">SQLite</option>
                                    <option value="sqlsrv">Microsoft SQL Server</option>
                                    <option value="oci">Oracle Database</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Database Host</label>
                            <div class="col-md-9">
                                <input type="text" name="database_host" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Database Name</label>
                            <div class="col-md-9">
                                <input type="text" name="database_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Database User</label>
                            <div class="col-md-9">
                                <input type="text" name="database_user" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Database Password</label>
                            <div class="col-md-9">
                                <input type="password" name="database_password" class="form-control">
                            </div>
                        </div>
                </div>
                <div class="text-right">
                    <button type="submit" name="create_db" value="create_db" class="btn btn-primary">Submit Database</button>
                </div>
                @CSRF
                </form>
            </div>
        </div>
    </div>
</div>
</div>
