<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="account-page">
                    <div class="account-center">
                        <div class="account-box">
                            <form method="post" class="form-signin" action="<?php echo $current_url; ?>">
                                <div class="account-logo">
                                    <a href="/"><img src="<?php echo $logo; ?>" alt=""></a>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" autofocus>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" autofocus>
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-primary account-btn" type="submit">Reset Password</button>
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
