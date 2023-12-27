<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Category</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card-box">
          <h4 class="card-title">Category Form</h4>
          <form method="post" action="<?php echo $url ?? null; ?>">
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Category Name</label>
              <div class="col-md-9">
                <input type="text" value="<?php echo $value['name'] ?? null; ?>" name="category" required title="category name" class="form-control">
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