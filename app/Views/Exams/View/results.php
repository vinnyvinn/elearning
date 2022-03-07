<div class="card">
    <div class="card-header pb-0 mb--1">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control form-control-sm select2" id="classId" data-toggle="select2">
                        <option value=""> -- Please Select a class -- </option>
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
                <button type="button" class="btn btn-sm btn-secondary btn-block btnFilter">Filter</button>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    <div id="ajaxContent"></div>
</div>
<script>
    $('document').ready(function () {
        $('.btnFilter').on('click', function(e){
            e.preventDefault();
            var classId = $("#classId").val();
            if(classId == '') {
                toast('Error', 'Please select a class', 'error');
            } else {
                getTimetable();
            }
        });
    });

    var getTimetable = function () {
        var classId = $("#classId").val();
        var data = {
            url : "<?php echo site_url('admin/exams/'.$exam->id.'/results/'); ?>" + classId,
            data: "class_id="+classId,
            loader: true
        };
        ajaxRequest(data, updateDom)
    }

    var updateDom = function (data) {
        $("#ajaxContent").html(data);
        $('.datatable').dataTable();
    }
</script>