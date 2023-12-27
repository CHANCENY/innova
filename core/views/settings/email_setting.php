<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Email Configuration</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-box">
                    <form action="#" method="post">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email Address</label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Username</label>
                            <div class="col-md-9">
                                <input type="text" name="user" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">SMTP</label>
                            <div class="col-md-9">
                                <input type="text" name="smtp" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">PORT</label>
                            <div class="col-md-9">
                                <input type="number" name="port" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Reply</label>
                            <div class="col-md-9">
                                <input type="email" name="reply" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        @CSRF
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-box">
                    <h4 class="card-title">Mail Configuration in System.</h4>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody><?php foreach ($config as $key=>$value): ?>
                                <tr>
                                    <td><?php echo $value['name'] ?? null; ?></td>
                                    <td><?php echo $value['email'] ?? null; ?></td>
                                    <td><?php if(!empty($value['active'])): ?>
                                        <a href="<?php echo $host; ?>?action=inactive&key=<?php echo $key; ?>" class="btn btn-success">Active</a>
                                        <?php else: ?>
                                            <a href="<?php echo $host; ?>?action=active&key=<?php echo $key; ?>" class="btn btn-danger">Inactive</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
