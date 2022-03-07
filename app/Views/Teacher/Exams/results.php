<?php
$classes = (new \App\Models\Subjectteachers())->groupBy('section_id')->where('teacher_id',$teacher->id)->findAll();
$sections = array();
foreach ($classes as $class){
    array_push($sections,$class->section);
}
$exams = getSession()->getExams();

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam Results</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a class="btn btn-sm btn-success record" href="#" style="display: none"><i
                                class="fa fa-plus"></i> Record Results
                    </a>
                    <?php do_action('teacher_quick_action_buttons', $teacher); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
 <div class="row">
 <div class="col-md-12">
<div class="card">
    <div class="card-header pb-0 mb--1">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <select class="form-control form-control-sm select2" id="examId" data-toggle="select2">
                        <option value="">--Select Exam--</option>
                        <?php
                        if($exams && count($exams) > 0) {
                            foreach ($exams as $exam) {
                                ?>
                                <option value="<?php echo $exam->id; ?>"><?php echo $exam->name;?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <select class="form-control form-control-sm select2" id="sectionId" data-toggle="select2">
                        <option>-- Select Class --</option>
                        <?php
                        if($sections && count($sections) > 0) {
                            foreach ($sections as $section) {
                                ?>
                                <option value="<?php echo $section->id; ?>"><?php echo $section->class->name.','.$section->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-sm btn-secondary btn-block btnFilter">Filter</button>
            </div>
        </div>
    </div>
    <div id="ajaxContent"></div>
</div>
 </div>
 </div>
</div>
<script>
    var sectionId = $("#sectionId").val();

    $('document').ready(function () {
        $('.btnFilter').on('click', function(e){
            e.preventDefault();
            var sectionId = $("#sectionId").val();
            var examId = $("#examId").val();
            if(sectionId == '' || examId == '') {
                toast('Error', 'Please select a class & exam', 'error');
            } else {
                getExamResults();
            }
        });
    });

    function getExamResults() {
        var exam = $('#examId').val();
        var section = $('#sectionId').val();
        var e = {
            url: "<?php echo site_url(route_to('teacher.exam.get_results')); ?>",
            loader: true,
            data: "exam="+exam+"&section="+section
        }
        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
            let url = exam+'/record-results'
            $('.record').attr('href',url);
            $('.record').show();

        })
    }

    var updateDom = function (data) {
        $("#ajaxContent").html(data);
        $('.datatable').dataTable();
    }
</script>