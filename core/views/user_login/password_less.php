<div class="main-wrapper account-wrapper">
  <div class="account-page">
    <div class="account-center">
      <div class="account-box">
        <form action="<?php echo fullURI('/users/sign-in'); ?>" method="POST" class="form-signin">
          <div class="account-logo">
            <a href="/"><img src="<?php echo $logo; ?>" alt=""></a>
          </div><?php echo $msg; ?>
          <div class="form-group">
            <label>EmailAddress</label>
            <input type="email" name="username" autofocus="" class="form-control">
          </div>
          <div class="form-group text-center">
            <button type="submit" name="login" value="login" class="btn btn-primary account-btn">Login</button>
          </div>
          <div class="text-center register-link">
            Don’t have an account? <a href="<?php echo fullURI('/users/register'); ?>">Register Now</a>
          </div>
          @CSRF
        </form>
      </div>
    </div>
  </div>
</div>
