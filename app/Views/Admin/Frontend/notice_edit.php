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
             <form class="ajaxForm" loader="true" method="post"
                   action="<?php echo site_url(route_to('admin.frontend.notice_board.update', $event->id)); ?>">
                 <input type="hidden" name="id" value="<?php echo $event->id; ?>" />
                 <div class="modal-header">
                     <h6 class="modal-title" id="modal-title-default">Update Notice</h6>
                     <button type="button" class="close" data-dismiss="modal"
                             aria-label="Close">
                         <span aria-hidden="true">×</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="form-group">
                         <label>Date</label>
                         <input type="text" class="form-control datepicker" name="date" value="<?php echo old('date', $event->date_created) ?>" required/>
                     </div>
                     <div class="form-group">
                         <label>Title</label>
                         <input type="text" class="form-control" name="title" value="<?php echo old('title', $event->title) ?>" required />
                     </div>
                     <div class="form-group">
                         <label>Notice Information</label>
                         <textarea id="textarea" class="form-control" name="description" rows="4" required><?php echo old('description', $event->info) ?></textarea>
                     </div>
                     <div class="form-group">
                         <h3>Image(s)</h3>
                         <div class="table-responsive">
                             <table class="table" id="filesTableE">
                                 <thead>
                                 <tr>
                                     <th>File</th>
                                     <th></th>
                                 </tr>
                                 </thead>
                                 <tbody id="tableBodyE">
                                 <tr>
                                     <td>
                                         <input type="file" name="image[]" class="form-control" accept="image/*" />
                                     </td>
                                     <td></td>
                                 </tr>
                                 </tbody>
                             </table>
                         </div>
                         <button type="button" class="btn btn-default btn-sm" id="addFileE"><i class="fa fa-plus"></i> Add Row</button>
                         <br/><br/>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="submit" class="btn btn-success">Update</button>
                     <button type="button" class="btn btn-link  ml-auto"
                             data-dismiss="modal">Close
                     </button>
                 </div>
             </form>
             </div>
     </div>
 </div>
</div>
</div>

<script>
    $(document).on('click', '#removeRowE', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileE', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="image[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowE" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyE").append(html);
        //$('#filesTable').append(html);
    });
</script>
