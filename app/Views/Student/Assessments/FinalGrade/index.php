<?php




?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Final Grade</h6>
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
        <div class="card-header">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="semester" class="form-control form-control-sm selec2" id="semester_id">
                            <?php
                            if($session = getSession()) {
                                $semesters = $session->semesters;
                                if($semesters) {
                                    ?>
                                    <option>Select Semester</option>
                                    <?php
                                    foreach ($semesters as $semester) {
                                        ?>
                                        <option value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <option>No semesters found</option>
                                    <?php
                                }
                            } else {
                                ?>
                                <option>No semesters found</option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary btn-sm btn-block" onclick="getResults()">Filter</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="ajaxContent">

        </div>
    </div>
</div>
<script>
    function getResults() {
        var sem = $("#semester_id").val();
        if(sem == '') {
            toast("Error", "Please select a semester", 'error');
        } else {
            var d = {
                url: "<?php echo site_url(route_to('student.assessments.final_grade.get_results')); ?>",
                data: "semester="+sem,
                loader: true
            }
            ajaxRequest(d, function (e) {
                $("#ajaxContent").html(e);
            })
        }
    }
</script>
