<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Edit SECTION 3</h6>
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
             <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.frontend.update_slide')); ?>" enctype="multipart/form-data">
                 <input type="hidden" name="video" value="<?php echo $detail['video']?>">
               <div class="form-group">
                   <label>Title</label>
                   <input type="text" class="form-control" name="video_title" value="<?php echo $detail['title']?>" required>
               </div>
                 <div class="form-group">
                     <label>Description</label>
                     <textarea name="video_description" rows="3" class="form-control" required><?php echo $detail['description'];?></textarea>
                 </div>
                 <div class="form-group">
                     <label>Video</label>
                     <input type="file" name="home_video" class="form-control" accept="video/mp4"/>
                 </div>
                 <br/>
                 <button type="submit" class="btn btn-success">Update</button>
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
