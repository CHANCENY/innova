<div class="main-wrapper account-wrapper">
  <div class="account-page">
    <div class="account-center">
      <div class="account-box">
        <form action="<?php echo $current; ?>" method="POST" class="form-signin">
          <div class="account-logo">
            <a href="/"><img src="<?php echo $logo; ?>" alt=""></a>
          </div><?php echo $msg; ?>
          <div class="form-group">
            <label>2FA Code</label>
            <input type="text" name="otp" autofocus="" class="form-control">
          </div>
          <div class="form-group text-center">
            <button type="submit" name="login" value="login" class="btn btn-primary account-btn">Submit</button>
          </div>
          @CSRF
        </form>
      </div>
    </div>
  </div>
</div>
