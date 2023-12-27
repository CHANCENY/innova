<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-4 col-3">
        <h4 class="page-title">Content Type</h4>
      </div>
      <div class="col-sm-8 col-9 text-right m-b-20">
        <a href="<?php echo fullURI('/types/create/new'); ?>" class="btn btn-primary float-right btn-rounded"><i class="fa fa-plus"></i> Add Type</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped custom-table">
            <thead>
            <tr>
              <th style="min-width:200px;">Name</th>
              <th class="text-right">Action</th>
            </tr>
            </thead>
            <tbody><?php if(!empty($data)): foreach ($data as $item): ?>
            <tr>
              <td>
                <h2><?php echo $item['label'] ?? null; ?></h2>
              </td>
              <td class="text-right">
                <div class="dropdown dropdown-action">
                  <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?php echo fullURI('/types/edit/'. $item['name']) ?>">
                      <i class="fa fa-pencil m-r-5"></i> Edit</a>
                    <a class="dropdown-item" href="<?php echo fullURI('/types/fields/'. $item['name']) ?>">
                      <i class="fa fa-list m-r-5"></i> Fields</a>
                    <a class="dropdown-item" href="<?php echo fullURI('/types/delete/'. $item['name']) ?>">
                      <i class="fa fa-trash m-r-5"></i> Delete</a>
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
</div>
