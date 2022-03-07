<?php
$data = array();
for($i=1; $i<10; $i++){
    array_push($data,$i);
}
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Submit Assignment</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <div>
                <?php
                if ($assignment->items){
                ?>
                    <form class="customSubmiti" method="post" data-parsley-validate
                          action="<?php echo site_url(route_to('student.assignments.assignment_writing.save')); ?>">
                        <div id="theQuestions">
                            <input type="hidden" name="student" value="<?php echo $student->id?>">
                            <input type="hidden" name="assignment" value="<?php echo $assignment->id?>">
                                <?php foreach ($assignment->items as $item):?>
                                <div class="item" style="background-color: #edeef2; padding:5px">
                                 <?php if ($item->instructions):?>
                                   <div class="row">
                                     <div class="col-md-12">
                                     <p><b>Instructions</b>: <span style="text-decoration: underline"><?php echo $item->instructions;?></span></p>
                                     </div>
                                     </div>
                                      <?php endif;?>
                                    <?php if ($item->precautions):?>
                                        <div class="row">
                                            <div class="col-md-12">
                                               <p><b>Precautions</b>: <span style="text-decoration: underline"><?php echo $item->precautions;?></span></p>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <p><b>Q<?php echo $item->question_number; ?>: </b><?php echo $item->question; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if(isset($item->image)) {
                                        ?>
                                        <img src="<?php echo base_url('uploads/assignments/'.$item->image); ?>" style="max-height: 200px; width: auto"/>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Answer</label>
                            <textarea name="answer[<?php echo $item->question_number?>]" class="form-control mytextarea"></textarea>
                           </div>
                           </div>
                              <?php endforeach;?>
                       <div class="mt-1"></div>
                       <button type="submit" class="btn btn-primary btn-block mt-4">Submit Assignment</button>
                    </form>
                <?php }
                else{
                ?>
                <p>Assignment questions could not be found.</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script>
    var getSubjects = function (classId) {
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
                data: "class=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#subject_id').html(data);
            });
        }
    };

    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('.item').remove();
    });

    $(document).on('click', '#addRow', function (e) {
        e.preventDefault();
        var the_html = ""; //unset. Memory is the b word
        the_html = $('#questionTemplates').html();
        $("#theQuestions").append(the_html);
    });

    //TODO: Ensure only one checbox is checked
    // $(document).on('change', '#theQuestions .item input[type="checkbox"]', function (e) {
    //     $(this).parents('.item checkbox').removeAttr('checked');
    //     $(this).attr('checked', 'checked');
    // });

    $('.customSubmit').submit(function (e) {
        e.preventDefault();
        var index = 1;
        var quiz = [];
        $('#theQuestions .item').each(function () {
            var row = {};

            $(this).find('input,select,textarea').each(function () {
                if ($(this).is(':checkbox')) {
                    row[$(this).attr('name')] = $(this).is(':checked');
                } else {
                    if($(this).attr('type') == 'file') {
                        row['image'] = $(this).attr('src');
                    } else {
                        row[$(this).attr('name')] = $(this).val();
                    }
                }
            });
            row['index'] = index;
            index++;
            quiz.push(row);
        });
        // var fd = new FormData();
        // fd.append('quiz', JSON.stringify(quiz));
        // fd.append('out_of', $('input[name="out_of"]').val());
        var data = {
            quiz: quiz,
            out_of: $('input[name="out_of"]').val(),
            duration: $('input[name="duration"]').val(),
            classid: $('select[name="class"]').val(),
            class: $('select[name="class"]').val(),
            semester: $('select[name="semester"]').val(),
            subject: $('select[name="subject"]').val(),
            given: $('input[name="given"]').val(),
            deadline: $('input[name="deadline"]').val(),
            name: $('input[name="name"]').val(),
        };
        var e = {
            data: JSON.stringify(data),
            loader: true,
            url: "<?php echo site_url(route_to('admin.academic.new_assignment_writing')); ?>"
        }

        //Use fd as you would any form data.
        // Check /home/ben/HTML/Perfex/perfex_crm/modules/hrm/views/new.php:527 for an example

        console.log(quiz);
        server(e);
    })
    function readTheImage(node) {
        if (node.files && node.files[0]) {
            var FR= new FileReader();

            FR.addEventListener("load", function(e) {
                $(node).attr('src', e.target.result);
                $(node).parent().parent().parent().find('#xPrev').attr('src', e.target.result);
                //document.getElementById("b64").innerHTML = e.target.result;
            });

            FR.readAsDataURL( node.files[0] );
        }
    }

</script>

<?php foreach ($assignment->items as $key => $value):?>
    <script>
        var qn = "<?php echo $value->question_number;?>";
       CKEDITOR.replace("answer["+qn+"]" );
    </script>
<?php endforeach;?>
