<?php
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Quarter Ranking</h6>
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
                    <select name="class" id="class_id" class="form-control form-control-sm select2"
                            data-toggle="select2"
                            onchange="getSections($(this).val())" required>
                        <option value="">Select a class</option>
                        <?php
                        $classes = getSession()->classes()->findAll();

                        foreach ($classes as $class) {
                         echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                        }
                        ?>
               </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="section" id="section_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="quarter" id="quarter_id" class="form-control form-control-sm" required>
                        <option value="">--Select Quarter--</option>
                        <?php
                        $quarters = @getSession()->quarters;
                        if(!empty($quarters) && count($quarters) > 0) {
                            foreach ($quarters as $q) {
                                ?>
                                <option value="<?php echo $q->id ?>"><?php echo $q->name; ?></option>
                                <?php
                            }?>
                        <?php }
                        ?>
                        <?php
                        $semesters = @getSession()->semesters;
                        foreach ($semesters as $semester):
                        ?>
                        <option value="<?php echo 'sem_'.   $semester->id;?>"><?php echo $semester->name;?></option>
                        <?php endforeach;?>
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
    function getSections(classId) {
        var data = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
            data: "session=" + classId,
            loader: true
        };
        ajaxRequest(data, function (data) {
            $('#section_id').html(data);
        });
    }

    function getRanks() {
        var classId = $('#class_id').val();
        var section = $('#section_id').val();
        var quarter = $('#quarter_id').val();
        if(classId != '' && section != '' && quarter != '') {

            var data = {
                url: "<?php echo site_url(route_to('admin.academic.quarter_ranking.get')) ?>",
                data: "class=" + classId + "&section=" + section + "&quarter=" + quarter,
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