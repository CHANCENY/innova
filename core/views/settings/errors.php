<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">Error Handling</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">
                <h6 class="card-title m-b-20">Error Access</h6>
                <div class="m-b-30">
                    <ul class="list-group">
                        <form method="post" action="#">
                            <li class="list-group-item">
                                Enabled
                                <div class="material-switch float-right">
                                    <input <?php echo !empty($setting['enabled']) ? 'checked' : null; ?> name="error_handle" id="staff_module" type="checkbox">
                                    <label for="staff_module" class="badge-success"></label>
                                </div>
                            </li>
                            <div class="form-group mt-4">
                                <button class="btn btn-primary" type="submit" name="error">Save</button>
                            </div>
                            @CSRF
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
