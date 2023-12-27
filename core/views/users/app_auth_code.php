<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="account-page">
          <div class="account-center">
            <div class="account-box">
              <form method="post" class="form-signin" action="<?php echo $url; ?>">
                <div class="account-logo">
                  <a href="/"><img src="<?php echo $logo; ?>" alt=""></a>
                </div>
                <div class="form-group">
                  <label for="code">2FA Code.</label>
                  <input id="code" type="password" name="code" class="form-control" autofocus>
                </div>
                <div class="form-group text-center">
                  <button class="btn btn-primary account-btn" type="submit">Submit and Login</button>
                </div>
                @CSRF
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
