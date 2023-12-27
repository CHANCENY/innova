<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Routes Report</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Route Name</th>
                            <th>Route URI</th>
                            <th>Route ID</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody><?php foreach ($routes as $key => $value): ?>
                        <tr>
                            <td>
                                <strong><?php echo $key; ?></strong>
                            </td>
                            <td>
                                <strong><?php echo $value['route_name'] ?? null; ?></strong>
                            </td>
                            <td><?php echo $value['route_uri'] ?? null; ?></td>
                            <td><?php echo $value['controller']['id'] ?? null; ?></td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="<?php echo $host; ?>/routing/edit/<?php echo $value['controller']['id'] ?? null; ?>/<?php echo str_replace(' ', '-',$value['route_name']) ?? null; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="<?php echo $host; ?>/routing/delete/<?php echo $value['controller']['id'] ?? null; ?>@question"><i class="fa fa-trash m-r-5"></i> Delete</a>

                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>