<div class="page-wrapper">
    <div class="content">
    <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Create Route</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form action="<?php echo $action; ?>" method="POST">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Controller Location <span class="text-danger">*</span></label>
                                <input class="form-control" id="module_path" name="module_path" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Database Connection <span class="text-danger">*</span></label>
                                <select name="database_connect" class="select form-control">
                                    <option>Select Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Route Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="route_name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Route URI <span class="text-danger">*</span></label>
                                <input class="form-control" id="route_uri" name="route_uri" type="text">
                                <span id="validationMessage" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Route Access <span class="text-danger">*</span></label>
                                <select name="access[]" multiple class="select form-control">
                                    <option>Select Access</option>
                                    <option value="admins">Admin</option>
                                    <option value="authenticated">Authenticated</option>
                                    <option value="anonymous">Anonymous</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Route Storage Access <span class="text-danger">*</span></label>
                                <select name="database_tables_allowed[]" multiple class="select form-control">
                                    <option>Select Access</option>
                                    <option value="all">All</option><?php foreach ($storages as $key): ?>
                                        <option value="<?php echo $key ?? null; ?>"><?php echo ucfirst($key); ?></option>
                                <?php  endforeach; ?></select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Database Queries <span class="text-danger">*</span></label>
                                <select name="allowed_query[]" multiple class="select form-control">
                                    <option>Select Query</option>
                                    <option value="select">SELECT</option>
                                    <option value="insert">INSERT</option>
                                    <option value="delete">DELETE</option>
                                    <option value="update">UPDATE</option>
                                    <option value="create">CREATE</option>
                                    <option value="drop">DROP</option>
                                    <option value="show">SHOW</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Access Methods <span class="text-danger">*</span></label>
                                <select name="method[]" multiple class="select form-control">
                                    <option>Please Select</option>
                                    <option value="POST">Post</option>
                                    <option value="GET">Get</option>
                                    <option value="PUT">Put</option>
                                    <option value="DELETE">Delete</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-white">
                                    <thead>
                                    <tr>
                                        <th style="width: 20px">#</th>
                                        <th class="col-sm-2">Param Key</th>
                                        <th class="col-md-6">Type</th>
                                        <th style="width:150px;">Options</th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody id="params_body">
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <input class="form-control" name="param_key_1" type="text" style="min-width:150px">
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="params_type_1" class="select form-control">
                                                    <option>Select Type</option>
                                                    <option value="string">string</option>
                                                    <option value="numerical">numerical</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="params_option_1" style="width:150px" class="select form-control">
                                                <option>Select Type</option>
                                                <option value="required">Required</option>
                                                <option value="optional">Optional</option>
                                            </select>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="addSeasonForm()" class="text-success font-18 mx-2" title="Add"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" onclick="removeSeasonForm(this)" class="text-danger font-18" title="Remove"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="text-center m-t-20">
                        <button name="create_reoute" value="route" class="btn btn-primary submit-btn">Save Route Information</button>
                    </div>
                    @CSRF
                </form>
            </div>
        </div>
    </div>
    <div class="notification-box">
        <div class="msg-sidebar notifications msg-noti">
           <div id="uris"><?php echo $uris; ?></div>
        </div>
    </div>
</div>