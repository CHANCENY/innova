<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Files Configurations</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-box">
                    <h4 class="card-title">Settings</h4>
                    <form action="#" method="post">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Max file size</label>
                            <div class="col-md-9">
                                <input type="number" value="<?php echo $upload_max_filesize ?? 0; ?>" name="upload_max_filesize" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Post max size</label>
                            <div class="col-md-9">
                                <input type="number" value="<?php echo $post_max_size ?? 0; ?>" name="post_max_size" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Max file uploads</label>
                            <div class="col-md-9">
                                <input type="number" value="<?php echo $max_file_uploads ?? 0; ?>" name="max_file_uploads" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Upload tmp directory</label>
                            <div class="col-md-9">
                                <input type="text" value="<?php echo $upload_tmp_dir ?? sys_get_temp_dir(); ?>" name="upload_tmp_dir" class="form-control">
                                <span>Note custom directory need to be writable.</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Max input time</label>
                            <div class="col-md-9">
                                <input type="number" value="<?php echo $max_input_time ?? 0; ?>" name="max_input_time" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Max execution time</label>
                            <div class="col-md-9">
                                <input type="number" value="<?php echo $max_execution_time ?? 0; ?>" name="max_execution_time" class="form-control">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                        @CSRF
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
