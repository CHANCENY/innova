<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Install Module</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">File Zip</label>
                            <div class="col-md-10">
                                <input class="form-control" name="module" type="file">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        @CSRF
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
