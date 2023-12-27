<div class="page-wrapper">
    <div class="content">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h4 class="page-title">Change Password</h4>
                    <form method="POST" action="<?php echo $current_url ?>">
                        <?php if(!$currentUser): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($currentUser): ?>
                        <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input type="password" name="old_password" class="form-control">
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>New password</label>
                                    <input type="password" name="new_password" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Confirm password</label>
                                    <input type="password" name="confirm_password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-sm-12 text-center m-t-20">
                                <button type="submit" name="reset_pass" class="btn btn-primary submit-btn">Update Password</button>
                            </div>
                        </div>
                        @CSRF
                    </form>
                </div>
            </div>
        </div>
</div>