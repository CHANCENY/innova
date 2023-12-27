<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table datatable mb-0">
                        <thead>
                        <tr>
                            <th>Route Name</th>
                            <th>Route URI</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $route['route_name'] ?? null; ?></td>
                            <td><?php echo $route['route_uri'] ?? null; ?></td>
                            <td><a class="btn float-right btn-danger" href="<?php echo $host; ?>/routing/delete/<?php echo $final; ?>">Delete</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
