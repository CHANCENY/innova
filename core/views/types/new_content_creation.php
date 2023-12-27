<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <h4 class="page-title">Content Type</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <form method="post" action="#">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="con">Name <span class="text-danger">*</span></label>
                <input id="con" name="content_name" class="form-control" type="text">
              </div>
              <div class="form-group">
                <label for="permission">Permission <span class="text-danger">*</span></label>
                <select multiple id="permission" name="permission[]" class="form-control">
                  <option value="">Select permission</option>
                  <option value="authentication">Authentication</option>
                  <option value="admins">Administrator</option>
                  <option value="anonymous">Anonymous</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="desc">Description</label>
                <textarea id="desc" name="description" class="form-control" type="text"></textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="display-block">Status</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="employee_active" value="1" checked>
              <label class="form-check-label" for="employee_active">
                Active
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="employee_inactive" value="0">
              <label class="form-check-label" for="employee_inactive">
                Inactive
              </label>
            </div>
          </div>
          <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn">Create Content Type</button>
          </div>
          @CSRF
        </form>
      </div>
    </div>
  </div>
</div>
