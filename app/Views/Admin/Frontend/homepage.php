<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SECTION 3</h6>
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
             <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.frontend.save_slide')); ?>" enctype="multipart/form-data">
                 <div class="form-group">
                     <h3>Video</h3>
                     <div class="table-responsive">
                         <table class="table" id="filesTableInfo">
                             <thead>
                             <tr>
                                 <th>Title</th>
                                 <th>Description</th>
                                 <th>Video</th>
                                 <th></th>
                             </tr>
                             </thead>
                             <tbody id="tableBodyInfo">
                             <tr>
                                 <td>
                                     <input type="text" name="video_title[]" class="form-control">
                                 </td>
                                 <td>
                                   <input type="text" name="video_description[]" class="form-control"/>
                                 </td>
                                 <td>
                                     <input type="file" name="home_video[]" class="form-control" accept="video/mp4"/>
                                 </td>
                                 <td></td>
                             </tr>
                             </tbody>
                         </table>
                     </div>
                     <button type="button" class="btn btn-default btn-sm" id="addFileInfo"><i class="fa fa-plus"></i> Add Row</button>
                     <br/><br/>
                 </div>
                 <br/>
                 <button type="submit" class="btn btn-success">Save</button>
             </form>
         </div>
     </div>
 </div>
</div>
</div>

<script>
    //Video File
    $(document).on('click', '#removeRowInfo', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileInfo', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="video_title[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="text" name="home_description[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="file" name="home_video[]" class="form-control" accept="video/mp4"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowInfo" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyInfo").append(html);
        //$('#filesTable').append(html);
    });


    function readURL(input, prevHolder) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(prevHolder).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("[type='file']").change(function (e) {
        var prevHolder = "#" + e.target.id + "_prev";
        readURL(this, prevHolder);
    });
</script>
