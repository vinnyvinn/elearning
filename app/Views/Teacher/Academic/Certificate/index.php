<?php
$sections = (new \App\Models\Sections())->where('advisor',$teacher->id)->where('session',active_session())->findAll();
?>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control form-control-sm select2" id="section_id" data-toggle="select2">
                            <option value=""> -- Select Class -- </option>
                            <?php
                            if($sections && count($sections) > 0) {
                                foreach ($sections as $section) {
                                    echo '<option value="' . $section->id . '">' . $section->class->name.','.$section->name . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-sm btn-secondary btn-block btnFilter" onclick="getStudents()">Filter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>

    function getStudents() {
        var section = $('#section_id').val();
        var e = {
            url: "<?php echo site_url(route_to('teacher.academic.yearly_certificate.students')); ?>",
            loader: true,
            data: "section="+section
        }
        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>