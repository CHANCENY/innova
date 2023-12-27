<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h4 class="page-title">Upload File</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <form action="#" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>File</label>
                        <div>
                            <input name="file" id="fileInput" class="form-control" type="file">
                            <small class="form-text text-muted">Max. file size: 50 MB.</small>
                        </div>
                        <div class="row" id="preview">
                        </div>
                    </div>
                    <div class="m-t-20 text-center">
                        <button class="btn btn-primary submit-btn">Save</button>
                    </div>
                    @CSRF
                </form>
            </div>
        </div>
    </div>
    <div class="notification-box">
        <script type="application/javascript">
            // Get references to the input element and the preview element
            const fileInput = document.getElementById('fileInput');
            const preview = document.getElementById('preview');

            // Add an event listener to the file input field
            fileInput.addEventListener('change', function () {
                preview.innerHTML = ''; // Clear the preview

                // Loop through the selected files
                for (const file of fileInput.files) {
                    const fileReader = new FileReader();

                    // Read the file and create a preview
                    fileReader.onload = function (e) {
                        const div = document.createElement('div');
                        const img = image(e.target.result);
                        div.className = "col-md-3 col-sm-3 col-4 col-lg-3 col-xl-2";
                        div.innerHTML = img;
                        preview.appendChild(div);
                    };

                    // Read the file as a data URL
                    fileReader.readAsDataURL(file);
                }
            });

            function image(link) {
                return ` <div class="product-thumbnail">
                                    <img src="${link}" class="img-thumbnail img-fluid" alt="">
                                    <span class="product-remove" title="remove"><i class="fa fa-close"></i></span>
                                </div>`
            }
        </script>
    </div>
</div>

