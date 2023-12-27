<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Create Storage</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form action="#" method="post">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Storage Name</label>
                                <input name="storage_name" id="storage_name" class="form-control" type="text">
                                <span id="validationMessage"></span>
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
                                        <th class="col-sm-2">Field</th>
                                        <th class="col-md-6">Type</th>
                                        <th style="width:100px;">Options</th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody id="storage_body_fields">
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <input name="field_1" class="form-control" type="text" style="min-width:150px">
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="type_1" class="select form-control">
                                                    <option>Please Select</option>
                                                    <option value="number">Number</option>
                                                    <option value="short text">Short text</option>
                                                    <option value="long text">Long text</option>
                                                    <option value="short">Boolean</option>
                                                    <option value="file large">Large file</option>
                                                    <option value="file medium">Medium file</option>
                                                    <option value="file small">Small file</option>
                                                    <option value="time create">On create timestamp</option>
                                                    <option value="time update">On update timestamp</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="option_1" class="select form-control">
                                                    <option>Please Select</option>
                                                    <option value="not empty">Not empty</option>
                                                    <option value="empty">Empty</option>
                                                    <option value="primary key">Primary Key</option>
                                                    <option value="unique">Unique</option>
                                                    <option value="on create">On create</option>
                                                    <option value="on update">On update</option>
                                                </select>
                                            </div>
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
                        <button name="save" class="btn btn-primary submit-btn">Save</button>
                    </div>
                    @CSRF
                </form>
            </div>
        </div>
    </div>
</div>
<div class="notification-box">
    <div class="msg-sidebar notifications msg-noti">
        <div id="uris"><?php echo $storages; ?></div>
    </div>
</div>
