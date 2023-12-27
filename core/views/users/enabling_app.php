<div class="page-wrapper">
  <div class="content">
    <div class="account-center">
      <div class="account-box">
        <form action="#" method="POST" class="form-signin">
          <div class="">
            <img src="<?php echo $image; ?>" alt="">
          </div>
          <div class="form-group">
            <label for="code">App Code.</label>
            <input id="code" name="code" type="text" autofocus="" class="form-control">
          </div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary account-btn">Verify</button>
          </div>
          @CSRF
        </form>
      </div>
    </div>
  </div>
</div>
