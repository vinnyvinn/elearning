<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Class Groups</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                 <?php do_action('departments_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
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
                        <option value="">Select Section</option>

                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getGroups()"><i
                            class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="ajaxData"></div>
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

    function getGroups() {
        var section = $('#section_id').val();
        if(section != '') {
            var e = {
                url: "<?php echo site_url(route_to('admin.academic.groups.get')); ?>",
                data: "section="+section,
                loader: true
            }
            ajaxRequest(e, function (data) {
                $('#ajaxData').html(data);
            })
        } else {
            toast("error", "Please select a section", 'error');
        }
    }
</script>