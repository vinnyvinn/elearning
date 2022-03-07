<?php
?>
<div class="container">
    <h1 class="title text-center mt-5" style="text-decoration: underline">STUDENT REGISTRATION</h1>
    <h4>
      <?php echo get_option('student_description')?>
    </h4>
</div>
<section id="contact" class="contact section">
    <div class="container">
            <form>
            <input type="hidden" name="form" value="student" />
            <div class="row">
               <div class="col-md-6">
                   <a href="<?php echo site_url(route_to('app.student_registration')); ?>?new=1" class="btn btn-success btn-block text-white" style="color: #fff;font-size: 30px">New(አዲስ)</a>
               </div>
                <div class="col-md-6">
                    <a href="<?php echo site_url(route_to('app.student_registration')); ?>?new=0"  class="btn btn-success btn-block" style="color: #fff;font-size: 30px">Existing(ነባር)</a>
                </div>
            </div>
                <div class="row">
                <div class="col-md-12">
                    <?php
                      $docs = get_option('student_doc') ? json_decode(get_option('student_doc')) :'';
                      ?>
                    <div class="image-slider slider-all-controls controls-inside">
                        <ul class="slides">
                            <?php if (is_array($docs)):
                                foreach ($docs as $doc):
                                    ?>
                                    <li>
                                        <img alt="Kids Expo"
                                             src="<?php echo base_url('uploads/files/'.$doc); ?>" class="mission_img">
                                    </li>
                                <?php endforeach;endif;?>
                        </ul>
                    </div>
            </div>

        </form>
    </div>
</section>
<script>
    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="title[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="file" name="doc[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
        //$('#filesTable').append(html);
    });

    $('#class_list').on('change', function(e) {
        var class_id = $(this).val();
        $('#section_list').html('');
        var url = '<?php echo site_url(); ?>' + 'ajax/class/' + class_id + '/sections';
        $.get(url, function (data, status) {
            $('#section_list').html(data);
        })
    })
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#imgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#profPic").change(function() {
        readURL(this);
    });
    function parentReadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#ParentImgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#parentProfPic").change(function() {
        parentReadURL(this);
    });
</script>