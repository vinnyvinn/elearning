<?php
$class = (new \App\Models\Classes())->find($class);
$section = (new \App\Models\Sections())->find($section);
$check_all_signs = (new \App\Models\DirectorSign())->where('session', active_session())->where('class',$class->id)->where('section',$section->id)->where('is_signed', 0)->get()->getLastRow();
$check_all_signs2 = (new \App\Models\DirectorSign())->where('session', active_session())->where('class',$class->id)->where('section',$section->id)->where('is_signed', 1)->findAll();

$comments = (new \App\Models\StudentComment())->findAll();

if($section) {
    $students = $section->students;
} else {
    $students = $class->students;
}

?>
<div class="table-responsive" style="overflow-x:auto;">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 10%">Name</th>
                <th>Class</th>
                <th style="width: 5%">Section</th>
                <th style="text-align: center">Action</th>
                <th style="width: 10%">
                    Director Sign
                    <input type="checkbox" name="director_sign" class="checkAll" value="all" id="checkAll" <?php if(!empty($check_all_signs2) && count($students) == count($check_all_signs2)){?> checked <?php }?>>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        $n = 0;
        $total_marks = [];
        $sub_count = 0;
        foreach ($students as $student) {
            $home = (new \App\Models\Homeroom())->where('student',$student->id)->where('class',$class->id)->where('section',$section->id)->where('session',active_session())->get()->getLastRow();
            $d_sign = (new \App\Models\DirectorSign())->where('session', active_session())->where('class',$class->id)->where('section',$section->id)->where('student', $student->id)->get()->getLastRow();
            $n++;


            $subjects = $student->class->subjects;

            $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
            $session = getSession();
            $semesters = $session->semesters;
            ?>
            <tr>
                <td style="width: 5%"><?php echo $n; ?></td>
                <td><?php echo $student->profile->name; ?></td>
                <td style="overflow-y: hidden; text-overflow: initial; white-space: nowrap;max-width: 150px"><?php echo $student->class->name; ?></td>
                <td style="overflow-y: hidden; text-overflow: initial; white-space: nowrap;max-width: 150px"><?php echo $student->section->name; ?></td>
                <td style="text-align: center">
                    <a class="btn btn-sm btn-info" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.report-card', $student->id)); ?>">View/Edit</a>
                    <a style="color:#fff" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target=".edit_<?php echo $student->id; ?>">Comment & Sign
                    </a>
                    <div class="modal fade edit_<?php echo $student->id; ?>" role="document"
                         aria-labelledby="modal-default"
                         style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form class="ajaxForm" loader="true" method="post"
                                      action="<?php echo site_url(route_to('admin.academic.yearly_certificate.save_homeroom_teacher_comment', $student->id)); ?>">
                                    <input type="hidden" name="id" value="<?php echo $student->id; ?>" />
                                    <input type="hidden" name="class" value="<?php echo $class->id; ?>" />
                                    <input type="hidden" name="section" value="<?php echo $section->id; ?>" />
                                    <input type="hidden" name="homeroom_id" value="<?php echo isset($home->id) ? $home->id : ''; ?>" />
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="modal-title-default">Homeroom Teacher Comment</h6>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <?php

                                    ?>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="sess">1 <sup>st</sup> Semester Comment &nbsp;&nbsp;&nbsp;&nbsp;
                                            <span style="text-align: right;margin-left: 32%;color: red;font-weight: 800">
                                                 <?php echo getSemesterAverage($student->id,$semesters[0]->id);?>

                                            </span>
                                            </label>
                                            <select name="first_sem_comment" class="form-control" required>
                                                <option value="none">None</option>
                                                <?php foreach ($comments as $comment):?>
                                                    <option value="<?php echo $comment['id'];?>" <?php if ((isset($home->first_sem_comment)) && $home->first_sem_comment == $comment['id']){ echo 'selected';}?>><?php echo $comment['description'];?></option>
                                                 <?php endforeach;?>
                                            </select>
                                            <div style="margin-left: 65%;margin-top: 2%">
                                                <input type="checkbox" id="sign1" name="first_sem_sign" value="1" <?php if(isset($home->first_sem_sign) && $home->first_sem_sign){?> checked<?php }?>>
                                                <label  style="font-size: 16px"> Sign</label>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="sess">2 <sup>nd</sup> Semester Comment &nbsp;&nbsp;&nbsp;&nbsp;
                                                <span style="text-align: right;margin-left: 32%;color: red;font-weight: 800">
                                                 <?php echo getSemesterAverage($student->id,$semesters[1]->id);?>
                                            </span>
                                            </label>
                                            <select name="second_sem_comment" class="form-control">
                                                <option value="none">None</option>
                                                <?php foreach ($comments as $comment):?>
                                                    <option value="<?php echo $comment['id'];?>" <?php if ((isset($home->second_sem_comment)) && $home->second_sem_comment == $comment['id']){ echo 'selected';}?>><?php echo $comment['description'];?></option>
                                                <?php endforeach;?>
                                            </select>

                                            <div style="margin-left: 65%;margin-top: 2%">
                                                <input type="checkbox" id="sign1" name="second_sem_sign" value="1" <?php if(isset($home->second_sem_sign) && $home->second_sem_sign){?> checked<?php }?>>
                                                <label style="font-size: 16px"> Sign</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Comment</button>
                                        <button type="button" class="btn btn-link  ml-auto"
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="width: 10%">
                <input type="checkbox" name="director_sign" class="director_sign" value="<?php echo $student->id;?>" <?php if (isset($d_sign) && ($d_sign->is_signed==1)){?> checked <?php }?>>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>

<style>

</style>

<script>
  $(function (){
      $("#checkAll").click(function(){
         // $('input:checkbox').not(this).prop('checked', this.checked);
          $('.director_sign').not(this).prop('checked', this.checked);
           var url = '<?php echo site_url(route_to('admin.academic.yearly_certificate.director_sign')); ?>';
           var data = {
               class:'<?php echo $class->id;?>',
               section:'<?php echo $section->id;?>',
               is_signed: $(this).is(':checked') ? 1 : 0
           }
          $.ajax({
              url:url,
              method:'POST',
              data:{data},
              success: function (resp){
               //   console.log(resp)
              }
          })
      });

      $(".director_sign").click(function(){
           var url = '<?php echo site_url(route_to('admin.academic.yearly_certificate.director_sign')); ?>';
           var data = {
               student:$(this).val(),
               class:'<?php echo $class->id;?>',
               section:'<?php echo $section->id;?>',
               single_sign:'yes',
               is_signed: $(this).is(':checked') ? 1 : 0
           }
          $.ajax({
              url:url,
              method:'POST',
              data:{data},
              success: function (resp){
               //   console.log(resp)
              }
          })
      });
  })

  $(document).ready(function () {
          $('#datatable').dataTable({
              dom: 'Bfrtip',
              colReorder: true,
              buttons: [
                  {
                      extend: 'copy'
                  },
                  {
                      extend: 'excel',
                  },
                  {
                      extend: 'pdf',
                  },
                  {
                      extend: 'print',
                  },
              ],
              "aoColumnDefs": [
                  { "sType": "numeric", "aTargets": [ 0, -1 ] }
              ]
          });
  })
</script>