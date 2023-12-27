
<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Authentication Form</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card-box">
          <h4 class="card-title">Authentication Settings</h4>
          <p>You can select which method to be in use by just active field then default implementation will be used.
          If you prefer to make your own implementation, then provide a template file path (eg custom/auth/password_less.php)</p>
          <form action="#" method="POST">
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Normal</label>
              <div class="col-md-9">
                <input type="text" name="normal" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Password Less</label>
              <div class="col-md-9">
                <input type="text" name="password_less" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-form-label">2FA Authentication</label>
              <div class="col-md-9">
                <input type="text" name="2fa" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label for="active" class="col-md-3 col-form-label">Active</label>
              <div class="col-md-9">
               <select id="active" name="active" class="form-control">
                 <option>--Select default---</option>
                 <option value="normal" selected>Normal</option>
                 <option value="password_less">Password Less</option>
                 <option value="2fa">2FA Authentication</option>
               </select>
              </div>
            </div>
            <div id="child-n" class="text-right">
              <button type="submit" name="auth_settings" value="auth" class="btn btn-primary">Submit</button>
            </div>
            @CSRF
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
