<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Top Ten Exam Results</h6><br/>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="#" class="btn btn-danger btn-sm print_result" style="display: none"><i class="fa fa-print"></i>Print</a>
                    <a href="#" class="btn btn-danger btn-sm download_result" style="display: none"><i class="fa fa-download"></i>Download</a>
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control form-control-sm select2" id="classId" onchange="getSections(this.value)" data-toggle="select2">
                            <option value=""> -- Select Class -- </option>
                            <?php
                            $classes = (new \App\Models\Classes())->where('session', active_session())->findAll();
                            if($classes && count($classes) > 0) {
                                foreach ($classes as $class) {
                                    ?>
                                    <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control form-control-sm select2" id="section_id" data-toggle="select2">
                            <option value=""> -- Select Section -- </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-secondary btn-block btnFilter" onclick="getExamResults()">Filter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>
    var exam;
    var clss;
    var section;
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
   $('.print_result').on('click',function (){
       printResult();
   })
    $('.download_result').on('click',function (){
        downloadResult();
    })
    function getExamResults() {
        exam = $('#examId').val();
        clss = $('#classId').val();
        section = $('#section_id').val();
        var e = {
            url: "<?php echo site_url(route_to('admin.academic.exam.get_results_top_ten')); ?>",
            loader: true,
            data: "exam="+exam+"&class="+clss+"&section="+section
        }
        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
            $('.print_result').show();
            $('.download_result').show();
        })
    }
    function printResult(){
        var url = "/admin/academic/exams/print-results-top-ten/"+exam+'/'+clss+'/'+section;
        window.location.href = url;
    }
    function downloadResult(){
        var url = "/admin/academic/exams/download-results-top-ten/"+exam+'/'+clss+'/'+section;
        window.location.href = url;
    }
</script>