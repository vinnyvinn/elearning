<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Monthly Rank</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row mt-3 justify-content-center align-content-center" style="padding-left:1em;padding-right:1em">
                <div class="col-md-3 mb-1">
                    <select name="class" id="student_id" class="form-control form-control-sm select2"
                            data-toggle="select2" onchange="getRank()" required>
                        <option value="">Select a student</option>
                        <?php
                        $students = $parent->students;
                        if($students) {
                            if(count($students) > 1) {
                                foreach ($students as $student) {
                                    echo '<option value="' . $student->id . '">' . $student->profile->name . '</option>';
                                }
                            } else {
                                echo '<option value="' . $students[0]->id . '">' . $students[0]->profile->name . '</option>';
                                echo 'getRank()';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getRank()"><i
                            class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div id="ajaxContent">

        </div>
    </div>
</div>

<script>
    function getRank() {
        var student = $('#student_id').val();
        var d = {
            loader: true,
            data: "student="+student,
            url: "<?php echo site_url(route_to('parent.rank.get_ajax')); ?>"
        }
        ajaxRequest(d, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>