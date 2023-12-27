<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Edit Route</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="#" method="post">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title"> <?php echo $name; ?></h4>
                                <div class="form-group">
                                    <label>Route Name:</label>
                                    <input name="route_name" value="<?php echo $route['route_name'] ?? null; ?>" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Route URI:</label>
                                    <input name="route_uri" value="<?php echo $route['route_uri'] ?? null; ?>" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Method:</label>
                                    <select multiple name="method[]" class="select">
                                        <option>Select State</option>

                                        <option value="GET" <?php echo in_array('GET', $route['headers']['method']) ? 'selected' : null; ?>>GET</option>
                                        <option value="POST" <?php echo in_array('POST', $route['headers']['method']) ? 'selected' : null; ?>>POST</option>
                                        <option value="PUT" <?php echo in_array('PUT', $route['headers']['method']) ? 'selected' : null; ?>>PUT</option>
                                        <option value="DELETE" <?php echo in_array('DELETE', $route['headers']['method']) ? 'selected' : null; ?>>DELETE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Access:</label>
                                    <select multiple name="access[]" class="select">
                                        <option>Select State</option>

                                        <option value="admins" <?php echo in_array('admins', $route['controller']['access']) ? 'selected' : null; ?>>Admins</option>
                                        <option value="authenticated" <?php echo in_array('authenticated', $route['controller']['access']) ? 'selected' : null; ?>>Authenticated</option>
                                        <option value="anonymous" <?php echo in_array('anonymous', $route['controller']['access']) ? 'selected' : null; ?>>Anonymous</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="card-title">Database</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Allowed Query:</label>
                                            <select multiple name="allowed_query[]" class="select">
                                                <option>Select State</option>

                                                <option value="select" <?php echo in_array('select', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>SELECT</option>
                                                <option value="insert" <?php echo in_array('insert', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>INSERT</option>
                                                <option value="delete" <?php echo in_array('delete', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>DELETE</option>
                                                <option value="create" <?php echo in_array('create', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>CREATE</option>
                                                <option value="update" <?php echo in_array('update', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>UPDATE</option>
                                                <option value="show" <?php echo in_array('show', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>SHOW</option>
                                                <option value="drop" <?php echo in_array('drop', $route['controller']['database']['allowed_query']) ? 'selected' : null; ?>>DROP</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tables Allowed:</label>
                                            <select multiple name="database_tables_allowed[]" class="select">
                                                <option>Select State</option><?php foreach ($tables as $key=> $table): ?>
                                                <option value="<?php echo $table; ?>" <?php echo in_array($table, $route['controller']['database']['database_tables_allowed']) ? 'selected' : null; ?>><?php echo ucfirst($table); ?></option>
                                            <?php endforeach; ?></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button name="route_edit" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    @CSRF
                </form>
            </div>
        </div>
    </div>
</div>