<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Home Form</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card-box">
          <h4 class="card-title">Home controller Form</h4>
          <form action="#" method="POST">
            <div class="form-group row">
              <label for="home" class="col-md-3 col-form-label">Controller ID</label>
              <div class="col-md-9">
                <input id="home" type="text" name="home" class="form-control">
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
