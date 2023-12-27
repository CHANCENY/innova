<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-4 col-3">
        <h4 class="page-title">Categories</h4>
      </div>
      <div class="col-sm-8 col-9 text-right m-b-20">
        <a href="/categories/create" class="btn btn-primary float-right btn-rounded"><i class="fa fa-plus"></i> Add Category</a>
      </div>
    </div>
    <form method="get" action="#" class="row filter-row">
      <div class="col-sm-6 col-md-3">
        <div class="form-group form-focus">
          <label class="focus-label">Category Name</label>
          <input type="text" name="category" class="form-control floating">
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <button type="submit" class="btn btn-success btn-block"> Search </button>
      </div>
    </form>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped custom-table">
            <thead>
            <tr>
              <th style="min-width:200px;">Category Name</th>
              <th class="text-right">Action</th>
            </tr>
            </thead>
            <tbody><?php if(!empty($categories)): foreach ($categories as $category): ?>
            <tr>
              <td>
                <h2><?php echo $category['name'] ?? null; ?></h2>
              </td>
              <td class="text-right">
                <div class="dropdown dropdown-action">
                  <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="/categories/update/<?php echo $category['cid'] ?? null; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                    <a class="dropdown-item" href="/categories/delete/<?php echo $category['cid'] ?? null; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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