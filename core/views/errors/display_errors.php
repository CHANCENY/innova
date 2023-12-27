<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Errors</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link active" href="#basic-justified-tab1" data-toggle="tab">View</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#basic-justified-tab2" data-toggle="tab">Action</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="basic-justified-tab1">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="card-block"><?php if($view_details === false): ?>
                                            <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Error Message</th>
                                                    <th>Error File</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(!empty($errors)): foreach ($errors as $error): ?>
                                                    <tr>
                                                        <td><?php echo date('Y-m-d', $error['time']); ?></td>
                                                        <td><?php echo $error['message'] ?? null; ?></td>
                                                        <td>
                                                            <a href="<?php echo $error['record']; ?>" class="text-wrap"><?php echo $error['file'] ?? null; ?></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; endif; ?>
                                                </tbody>
                                            </table>
                                            </div><?php else: ?>
                                            <div class="blog-view">
                                                <article class="blog blog-single-post">
                                                    <h3 class="blog-title" style="word-break: break-all;"><?php echo $errors[0]['file'] ?? null; ?></h3>
                                                    <div class="blog-info clearfix">
                                                        <div class="post-left">
                                                            <ul>
                                                                <li><a href="#"><i class="fa fa-calendar"></i> <span><?php echo date('Y-m-d',$errors[0]['time']) ?? null; ?></span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="blog-image">
                                                    </div>
                                                    <div class="blog-content">
                                                        <p><strong>Line: </strong> <?php echo $errors[0]['line'] ?? null; ?></p>
                                                        <p><strong>Message: </strong> <?php echo $errors[0]['message'] ?? null; ?></p>
                                                        <blockquote>
                                                            <p><strong>Trace: </strong><?php echo $errors[0]['trace'] ?? null; ?></p>
                                                        </blockquote>
                                                        <p><strong>File: </strong> <?php echo $errors[0]['file'] ?? null; ?></p>
                                                    </div>
                                                </article>
                                            </div>
                                        <?php endif; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="basic-justified-tab2">
                            <a href="<?php echo $delete; ?>" class="btn btn-danger text-white">Clean errors</a>
                            <a href="<?php echo $settings; ?>" class="btn btn-primary text-white">Error settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
