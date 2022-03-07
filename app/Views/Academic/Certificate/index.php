<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Yearly Certificate</h6><br/>
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
                    <button type="button" class="btn btn-sm btn-secondary btn-block btnFilter" onclick="getStudents()">Filter</button>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="form-group">
                    <label>Turn Off Semester 2 ?</label><br/>
                    <label class="custom-toggle">
                        <input type="checkbox" name="turn_off_semester_2"
                               value="1" <?php echo get_option('turn_off_semester_2') == 1 ? 'checked' : ''; ?> id="turn_off_semester_2"/>
                        <span class="custom-toggle-slider rounded-circle"
                              data-label-off="No" data-label-on="Yes"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>
    $('#turn_off_semester_2').on('click',function (){
        var val = 0;
        if ($(this).is(':checked'))
            val = 1;
        var e = {
            url: "<?php echo site_url('ajax/remove-semester') ?>",
            loader: true,
            data: "val="+val
        }
        ajaxRequest(e, function (data) {

        })
    })
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

    function getStudents() {
        var clss = $('#classId').val();
        var section = $('#section_id').val();
        var e = {
            url: "<?php echo site_url(route_to('admin.academic.yearly_certificate.students')); ?>",
            loader: true,
            data: "class="+clss+"&section="+section
        }
        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>