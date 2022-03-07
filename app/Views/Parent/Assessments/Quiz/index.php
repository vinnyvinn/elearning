<?php



?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Quizes</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-4">
                    <select class="form-control select2" data-toggle="select2" id="student"
                            name="student">
                        <?php
                        if (count($parent->students) > 1) {
                            ?>
                            <option value="">-- Select Student --</option>
                            <?php
                        foreach ($parent->students as $student) {
                            ?>
                            <option value="<?php echo $student->id; ?>" <?php if ($parent->students[0]->id == $student->id):?>selected<?php endif;?>><?php echo $student->profile->name; ?></option>
                            <script>
                                $(document).ready(function () {
                                    getClassWork();
                                })
                            </script>
                        <?php
                        }
                        ?>
                        <?php
                        } else {
                        ?>
                            <option selected value="<?php echo $parent->students[0]->id; ?>"><?php echo $parent->students[0]->profile->name; ?></option>
                            <script>
                                $(document).ready(function () {
                                    getClassWork();
                                })
                            </script>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="semester" class="form-control select2" data-toggle="select2">
                        <?php
                        $sems = getSession()->semesters;
                        if($sems) {
                            foreach ($sems as $sem) {
                                ?>
                                <option value="<?php echo $sem->id; ?>"><?php echo $sem->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-sm btn-success btn-block" onclick="getClassWork()"><i
                                class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            <hr/>
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>
    function getClassWork() {
        var student = $('#student').val();
        var semester = $('#semester').val();
        if(student == '' || semester == '') {
            toast("Error", "Please select both fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('parent.continuous_assessments.view_quiz')); ?>",
                loader: true,
                data: "student=" + student + "&semester=" + semester
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>