<div class="page-wrapper" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-6">
                <h4 class="page-title">Assets</h4>
            </div>
            <div class="col-sm-4 col-6 text-right m-b-30">
                <a href="content/upload" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Asset</a>
            </div>
        </div>
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <form method="get">
                    <div class="form-group form-focus">
                        <label class="focus-label">File Name</label>
                        <input name="search" type="search" class="form-control floating">
                    </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <button type="submit" class="btn btn-success btn-block"> Search </button>
            </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table mb-0 datatable">
                    <thead>
                    <tr>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>File Type</th>
                        <th>View</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody><?php if (!empty($data)): foreach ($data as $key=>$file): ?>
                        <tr>
                            <td><?php $list = explode("/", $file['path'] ?? "");
                                echo end($list) ?? null; ?></td>
                            <td>
                                <strong><?php echo  formatBytes(intval($file['size'])); ?></strong>
                            </td>
                            <td><?php echo $file['extension'] ?? null; ?></td>
                            <td>
                                <?php if(in_array($file['extension'], ['jpg', 'png', 'jpeg', 'webp', 'svg'])): ?>
                                    <div>
                                        <img src="<?php echo $file['uri'] ?? null; ?>"
                                             title="<?php echo end($list) ?? null; ?>"
                                             alt="<?php echo end($list) ?? null; ?>"
                                             class="img-thumbnail img-fluid"
                                             style="width: 5rem;"
                                        />
                                        <span class="product-remove" title="remove"><i class="fa fa-close"></i></span>
                                    </div>
                                <?php else: ?>
                                    <a href="<?php  echo $file['uri'] ?? null; ?>"><?php echo end($list) ?? null; ?></a>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="content/update/<?php echo $file['fid'] ?? 0; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="content/delete/<?php echo $file['fid'] ?? 0; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div
