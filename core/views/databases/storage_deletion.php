<div class="page-wrapper">
    <div class="content">
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
                                <a class="btn btn-danger" href="/storages/storage/delete/<?php echo $value; ?>"><i class="fa fa-trash m-r-5"></i> Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

