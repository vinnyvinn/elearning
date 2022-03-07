<div class="card">
    <div class="card-header">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="form-group">
                    <select class="form-control form-control-sm select2" id="examId" data-toggle="select2">
                        <option value=""> -- Select Exam -- </option>
                        <?php
                        $model = new \App\Models\Exams();
                        $current_exams = $model->where('session', active_session())->orderBy('id', 'DESC')->findAll();
                        if($current_exams && count($current_exams) > 0) {
                            foreach ($current_exams as $exam) {
                                ?>
                                <option value="<?php echo $exam->id; ?>"><?php echo $exam->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-sm btn-secondary btn-block btnFilter" onclick="getExamSchedule()">Filter</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="ajaxContent"></div>
    </div>
</div>
<script>
    function getExamSchedule() {
        var exam = $('#examId').val();
        var clss = "<?php echo $class->id; ?>";

        var e = {
            url: "<?php echo site_url(route_to('admin.academic.get_exam_schedule')); ?>",
            loader: true,
            data: "exam="+exam+"&class="+clss
        }

        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>