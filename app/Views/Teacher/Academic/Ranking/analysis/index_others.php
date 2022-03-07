<?php
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Others</h6>
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
            <div class="row mt-3 justify-content-center" style="padding-left:1em;padding-right:1em">
                <div class="col-md-6 mb-1">
                    <select name="semester" id="semester_id" class="form-control form-control-sm" required>
                        <option value="">--Select Semester--</option>
                        <?php
                        $semesters = @getSession()->semesters;
                        if(!empty($semesters) && count($semesters) > 0) {
                            foreach ($semesters as $semester) {
                                ?>
                                <option value="<?php echo $semester->id ?>"><?php echo $semester->name; ?></option>
                                <?php
                            }?>
                        <?php }
                        ?>

                    </select>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getResults()"><i
                                class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent">
                <div class="alert alert-warning">
                    Please use the form above to filter results
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getResults() {
        var semester = $('#semester_id').val();
        if(semester != '') {

            var data = {
                url: "<?php echo site_url(route_to('admin.academic.semester_analysis_others.get')) ?>",
                data: "semester=" + semester,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxContent').html(data);
            });
        } else {
            toast('Error', 'Please select all fields in the filter', 'error');
        }
    }
</script>