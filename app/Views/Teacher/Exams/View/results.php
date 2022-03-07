<?php
$classes = (new \App\Models\Subjectteachers())->groupBy('section_id')->where('teacher_id',$teacher->id)->findAll();
$sections = array();
foreach ($classes as $class){
    array_push($sections,$class->section);
}
$exams = getSession()->getExams();

?>
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
<script>
    var sectionId = $("#sectionId").val();
    setTimeout(()=>{
     //  getTimetable();
    },1000)
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
        })
    }

    var updateDom = function (data) {
        $("#ajaxContent").html(data);
        $('.datatable').dataTable();
    }
</script>