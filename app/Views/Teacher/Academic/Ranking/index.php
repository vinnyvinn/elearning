<?php
$classes = (new \App\Models\Subjectteachers())->where('teacher_id',$teacher->id)->orderBy('id')->findAll();
$sections = array();
foreach ($classes as $class){
    array_push($sections,$class->section);
}
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                  <h6 class="h2 text-white d-inline-block mb-0">Semester Ranking</h6>
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
                <div class="col-md-3 mb-1">
                    <select name="class" id="section_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select a class</option>
                        <?php
                        foreach ($sections as $section) {
                         echo '<option value="' . $section->id . '">' . $section->class->name.','.$section->name . '</option>';
                        }
                        ?>
               </select>
                </div>

                <div class="col-md-3 mb-1">
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
                            <option value="yearly_average">Yearly Average</option>
                        <?php }
                        ?>

                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getRanks()"><i
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
    function getRanks() {
        var section = $('#section_id').val();
        var semester = $('#semester_id').val();
        if(section != '' && semester != '') {

            var data = {
                url: "<?php echo site_url(route_to('teacher.academic.semester_ranking.get')) ?>",
                data: "section=" + section + "&semester=" + semester,
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