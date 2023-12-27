
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Site Form</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-box">
                    <h4 class="card-title">Site Information</h4>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Site Name</label>
                            <div class="col-md-9">
                                <input type="text" name="site_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Site Slogan</label>
                            <div class="col-md-9">
                                <input type="text" name="site_slogan" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Site EmailAddress</label>
                            <div class="col-md-9">
                                <input type="email" name="site_email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Site Logo</label>
                            <div class="col-md-9">
                                <input type="file" name="site_logo" class="form-control">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="create_site" value="create_db" class="btn btn-primary">Submit Site</button>
                        </div>
                        @CSRF
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>