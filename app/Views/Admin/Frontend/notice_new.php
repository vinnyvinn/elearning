<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SECTION 2</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
<div class="row">
 <div class="col-md-10 offset-1">
     <div class="card">
         <div class="card-body">
             <form class="ajaxForm" loader="true" enctype="multipart/form-data" method="post" action="<?php echo site_url(route_to('admin.frontend.save_notice')); ?>">
                 <div class="form-group">
                     <label>Date</label>
                     <input type="text" class="form-control datepicker" name="date" value="<?php echo old('date', date('d/m/Y')); ?>" required/>
                 </div>
                 <div class="form-group">
                     <label>Title</label>
                     <input type="text" class="form-control" name="title" value="<?php echo old('title'); ?>" required />
                 </div>
                 <div class="form-group">
                     <label>Notice Information</label>
                     <textarea id="textarea" class="form-control" name="description" rows="4" required><?php echo old('description'); ?></textarea>
                 </div>
                 <div class="form-group">
                     <h3>Image(s)</h3>
                     <div class="table-responsive">
                         <table class="table" id="filesTable">
                             <thead>
                             <tr>
                                 <th>File</th>
                                 <th></th>
                             </tr>
                             </thead>
                             <tbody id="tableBody">
                             <tr>
                                 <td>
                                     <input type="file" name="image[]" class="form-control" accept="image/*" />
                                 </td>
                                 <td></td>
                             </tr>
                             </tbody>
                         </table>
                     </div>
                     <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                     <br/><br/>
                 </div>
                 <button type="submit" class="btn btn-success">Post Notice</button>
             </form>
         </div>
     </div>
 </div>
</div>
</div>

<script>
    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="image[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
    });
</script>
