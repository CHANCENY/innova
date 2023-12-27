<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Storages</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="/storages/storage/create" class="btn btn-primary float-right btn-rounded"><i class="fa fa-plus"></i> Add Storage</a>
            </div>
        </div>
        <form class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <form action="#" method="get">
                <div class="form-group form-focus">
                    <label class="focus-label">Storage Name</label>
                    <input name="storage_name" type="text" class="form-control floating">
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <button type="submit" class="btn btn-success btn-block"> Search </button>
            </div>
        </form>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table">
                        <thead>
                        <tr>
                            <th>Storage Name</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><?php foreach ($storages as $key=>$value): ?>
                            <td><?php echo ucfirst(str_replace('_',' ',$value)); ?></td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="/storages/storage/delete/<?php echo $value; ?>"><i class="fa fa-trash m-r-5"></i> Delete</a>
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
