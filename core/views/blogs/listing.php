<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-4 col-3">
        <h4 class="page-title">Blogs</h4>
      </div>
      <div class="col-sm-8 col-9 text-right m-b-20">
        <a href="/blogs/create" class="btn btn-primary float-right btn-rounded"><i class="fa fa-plus"></i> Add Blog</a>
      </div>
    </div>
    <form method="get" action="#" class="row filter-row">
      <div class="col-sm-6 col-md-3">
        <div class="form-group form-focus">
          <label class="focus-label">Title</label>
          <input type="text" class="form-control floating">
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
              <th style="min-width:200px;">Name</th>
              <th>Category</th>
              <th>Publish</th>
              <th class="text-right">Action</th>
            </tr>
            </thead>
            <tbody><?php if(!empty($blogs)): foreach ($blogs as $blog): ?>
            <tr><?php $images = \Innova\Blogger\Blogs::blogImages($blog['blog_images']); ?>
              <td>
                <img width="28" height="28" src="<?php echo $images[0] ?? null; ?>" class="rounded-circle" alt=""> <h2><?php echo $blog['blog_title'] ?? null; ?></h2>
              </td>
              <td><?php $term = \Innova\Blogger\Category::term($blog['blog_category']); echo $term['name'] ?? null; ?></td>
              <td><?php if($blog['blog_status'] == 1): ?>
                <span class="custom-badge status-green">Published</span>
                <?php else: ?>
                  <span class="custom-badge status-grey">unPublished</span>
                <?php endif; ?>
              </td>
              <td class="text-right">
                <div class="dropdown dropdown-action">
                  <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="/blogs/update/<?php echo $blog['blog_id'] ?? null; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                    <a class="dropdown-item" href="/blogs/delete/<?php echo $blog['blog_id'] ?? null; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
