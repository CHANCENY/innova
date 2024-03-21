<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">Roles & Permissions</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <a href="permission/add" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Add Roles</a>
                <div class="roles-menu">
                    <ul>
                        <?php if(!empty($permissions)): $active = 'active'; foreach ($permissions as $permission): ?>
                        <li class="<?php echo $active ?? null; ?> role-item" >
                            <a class="roles" id="active" <?php $active = null; ?> data="<?php echo $permission['name'] ?? null; ?>"><?php echo $permission['label'] ?? null; ?></a>
                            <span class="role-action">
								<a href="permission/delete/<?php echo $permission['name'] ?? null; ?>">
									<span class="action-circle large delete-btn">
										<i class="material-icons">delete</i>
									</span>
								</a>
							</span>
                        </li>
                       <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">
                <h6 class="card-title m-b-20">Permission Access</h6>
                <div class="m-b-30">
                    <ul class="list-group" id="access-permission">
                        <li class="list-group-item">
                            Dashboard access
                            <div class="material-switch float-right">
                                <input id="dashboard_module" type="checkbox">
                                <label for="dashboard_module" class="badge-success"></label>
                            </div>
                        </li>
                        <li class="list-group-item">
                            Normal Access
                            <div class="material-switch float-right">
                                <input id="normal_module" type="checkbox">
                                <label for="normal_module" class="badge-success"></label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped custom-table">
                        <thead>
                        <tr>
                            <th>Action Permissions</th>
                            <th class="text-center">Read</th>
                            <th class="text-center">Write</th>
                            <th class="text-center">Create</th>
                            <th class="text-center">Delete</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        <tr>
                            <?php if(!empty($permissions)): foreach ($permissions as $permission): ?>
                            <td><?php echo $permission['label'] ?? null; ?></td>
                            <td class="text-center">
                                <input <?php echo !empty($permission['action_permissions']) && in_array('Read',$permission['action_permissions']) ? 'checked' : null; ?> type="checkbox" data="Read" class="actions" value="<?php echo $permission['name'] ?? null; ?>">
                            </td>
                            <td class="text-center">
                                <input <?php echo !empty($permission['action_permissions']) && in_array('Write',$permission['action_permissions']) ? 'checked' : null; ?> type="checkbox" data="Write" class="actions" value="<?php echo $permission['name'] ?? null; ?>">
                            </td>
                            <td class="text-center">
                                <input <?php echo !empty($permission['action_permissions']) && in_array('Create',$permission['action_permissions']) ? 'checked' : null; ?> type="checkbox" data="Create" class="actions" value="<?php echo $permission['name'] ?? null; ?>">
                            </td>
                            <td class="text-center">
                                <input <?php echo !empty($permission['action_permissions']) && in_array('Delete',$permission['action_permissions']) ? 'checked' : null; ?> type="checkbox" data="Delete" class="actions" value="<?php echo $permission['name'] ?? null; ?>">
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Permission access
     */
    const dashBoard = document.getElementById('dashboard_module');
    const normal = document.getElementById('normal_module');

    if(normal) {
        normal.addEventListener('change', (e)=>{
            const active = document.getElementById('active');
            const role = active.getAttribute('data');
           if(normal.checked){
               sendPermissionUpdates({id:role,access: 'normal_modules', action: 'add'})
           }else {
               sendPermissionUpdates({id:role,access: 'normal_modules', action: 'delete'})
           }
        })
    }

    if(dashBoard) {
        dashBoard.addEventListener('change', (e)=> {
            const active = document.getElementById('active');
            const role = active.getAttribute('data');
            if(dashBoard.checked) {
                sendPermissionUpdates({id:role,access: 'dashboard_modules', action: 'add'})
            }else {
                sendPermissionUpdates({id:role,access: 'dashboard_modules', action: 'delete'})
            }
        })
    }

    function sendPermissionUpdates(data) {
        const xhr = new XMLHttpRequest();
        xhr.open('post', 'permission/update', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
            if(this.status === 200) {
                window.location.reload();
            }
        }
        xhr.onerror = function () {
            console.error(this.message);
        }
        xhr.send(JSON.stringify(data));
    }

    /**
     * Active permission
     */
    const rolesItem = document.getElementsByClassName('role-item');
    if(rolesItem.length > 0) {
        for (let i = 0; i < rolesItem.length; i++){
            const item = rolesItem[i];
            if(item) {
                item.addEventListener('click', (e)=>{
                    for (let j = 0; j < rolesItem.length; j++) {
                        const itemNode = rolesItem[j];
                        if(itemNode) {
                            itemNode.classList.remove('active');
                            const a = itemNode.querySelector('#active');
                            if(a) {
                                a.removeAttribute('id');
                            }
                        }
                    }
                    item.classList.add('active')
                    const a = item.querySelector('a');
                    if(a) {
                        a.setAttribute('id', 'active');
                        checkAccess();
                    }
                })
            }
        }
    }

    function checkAccess() {
        const active = document.getElementById('active');
        const role = active.getAttribute('data');
        const xhr = new XMLHttpRequest();
        xhr.open('post', 'permission/update', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
            if(this.status === 200) {
                try{
                    const data = JSON.parse(this.responseText);
                    const access = data.access;
                    if(access) {
                        if(access.includes('dashboard_modules')) {
                            dashBoard.checked = true;
                        }else{
                            dashBoard.checked = false;
                        }
                        if(access.includes('normal_modules')) {
                            normal.checked = true;
                        }else {
                            normal.checked = false;
                        }
                    }else {
                        dashBoard.checked = false;
                        normal.checked = false;
                    }
                }catch (e){

                }
            }
        }
        xhr.send(JSON.stringify({id:role,action:'get'}));
    }
    document.addEventListener('DOMContentLoaded', ()=>{
        checkAccess();
    })

    /**
     * Actions permission
     */
    const actions = document.getElementsByClassName('actions');
    if(actions) {
        for (let i = 0; i < actions.length; i++) {
            actions[i].addEventListener('click', (e)=>{
                if(actions[i].checked) {
                    const action = actions[i].getAttribute('data');
                    const permission = e.target.value;
                    sendPermissionUpdates({id:permission,action:'actions-add',access:action})
                }else {
                    const action = actions[i].getAttribute('data');
                    const permission = e.target.value;
                    sendPermissionUpdates({id:permission,action:'actions-delete',access:action})
                }
            });
        }
    }
</script>