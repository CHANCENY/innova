<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
  });
</script>
<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <h4 class="page-title">Add Blog</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <form id="blog-create-form" method="post" enctype="multipart/form-data" action="#">
          <div class="form-group">
            <label>Blog Name</label>
            <input name="blog_title" required class="form-control" type="text">
          </div>
          <div class="form-group">
            <label>Blog Images</label>
            <div>
              <input name="blog_images[]" multiple class="form-control" type="file" onchange="handleFileSelect(event)">
              <small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png. Maximum 10 images only.</small>
            </div>
            <div class="row" id="preview">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Blog Category</label>
                <select class="select" name="category">
                  <option>--select category--</option>
                  <?php if(!empty($categories)): foreach ($categories as $key=>$value): ?>
                      <option value="<?php echo $value['cid'] ?? null; ?>"><?php echo $value['name'] ?? null; ?></option>
                  <?php endforeach; endif; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Old Images</label>
                <input type="hidden" class="form-control" name="images_old" value="">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Blog Description</label>
            <textarea name="blog_body" cols="30" rows="6" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label>Tags <small>(separated with a comma)</small></label>
            <input name="tags" type="text" placeholder="Enter your tags" data-role="tagsinput" class="form-control">
          </div>
          <div class="form-group">
            <label class="display-block">Blog Status</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="blog_active" value="1">
              <label class="form-check-label" for="blog_active">
                Active
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="blog_inactive" value="0">
              <label class="form-check-label" for="blog_inactive">
                Inactive
              </label>
            </div>
          </div>
          <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn">Publish Blog</button>
          </div>
          @CSRF
        </form>
      </div>
    </div>
  </div>
</div>