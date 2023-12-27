<div class="page-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-5 col-5">
        <h4 class="page-title">Fields: <?php echo $data['label'] ?? null; ?></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped custom-table mb-0 datatable">
            <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Type</th>
              <th class="text-right">Key</th>
            </tr>
            </thead>
            <tbody><?php if(!empty($data['fields'])): foreach ($data['fields'] as $key=>$item): ?>
            <tr>
              <td><?php echo $key; ?></td>
              <td><?php echo $item['label'] ?? null; ?></td>
              <td><?php echo $item['type'] ?? null; ?></td>
              <td class="text-right">
                <?php echo $item['name'] ?? null; ?>
              </td>
            </tr>
            <?php endforeach; endif; ?></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-sm-12">
        <form>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="table-responsive">
                <table class="table table-hover table-white">
                  <thead>
                  <tr>
                    <th style="width: 20px">#</th>
                    <th class="col-sm-2">Item</th>
                    <th class="col-md-6">Description</th>
                    <th style="width:100px;">Unit Cost</th>
                    <th style="width:80px;">Qty</th>
                    <th>Amount</th>
                    <th> </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1</td>
                    <td>
                      <input class="form-control" type="text" style="min-width:150px">
                    </td>
                    <td>
                      <input class="form-control" type="text" style="min-width:150px">
                    </td>
                    <td>
                      <input class="form-control" style="width:100px" type="text">
                    </td>
                    <td>
                      <input class="form-control" style="width:80px" type="text">
                    </td>
                    <td>
                      <input class="form-control form-amt" readonly="" style="width:120px" type="text">
                    </td>
                    <td><a href="javascript:void(0)" class="text-success font-18" title="Add"><i class="fa fa-plus"></i></a></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>
                      <input class="form-control" type="text" style="min-width:150px">
                    </td>
                    <td>
                      <input class="form-control" type="text" style="min-width:150px">
                    </td>
                    <td>
                      <input class="form-control" style="width:100px" type="text">
                    </td>
                    <td>
                      <input class="form-control" style="width:80px" type="text">
                    </td>
                    <td>
                      <input class="form-control form-amt" readonly="" style="width:120px" type="text">
                    </td>
                    <td><a href="javascript:void(0)" class="text-danger font-18" title="Remove"><i class="fa fa-trash-o"></i></a></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="text-center m-t-20">
            <button class="btn btn-primary submit-btn">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
