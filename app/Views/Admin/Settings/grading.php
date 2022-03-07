<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Grading Systems Summary</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    fieldset{
        border: 2px solid black !important;
        padding: 3% !important;
    }
    legend{
        margin-bottom: 0 !important;
    }
</style>
 <?php
$classes = getSession()->classes->findAll();
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">

            <form class="ajaxForm" method="post"  loader="true" action="<?php echo site_url(route_to('admin.settings.save-grading')); ?>">
                <div class="form-group row">
                    <label class="col-md-3" style="max-width: 3%;padding-top: 0.5%">A</label>
                    <input type="text" name="grade_a" class="form-control col-md-9" placeholder="enter grade" value="<?php echo get_option('grade_a')?:'90-100'?>">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" style="max-width: 3%;padding-top: 0.5%">B</label>
                    <input type="text" name="grade_b" class="form-control col-md-9" placeholder="enter grade" value="<?php echo get_option('grade_b')?:'80-89'?>">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" style="max-width: 3%;padding-top: 0.5%">C</label>
                    <input type="text" name="grade_c" class="form-control col-md-9" placeholder="enter grade" value="<?php echo get_option('grade_c')?:'70-79'?>">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" style="max-width: 3%;padding-top: 0.5%">D</label>
                    <input type="text" name="grade_d" class="form-control col-md-9" placeholder="enter grade" value="<?php echo get_option('grade_d')?:'60-69'?>">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" style="max-width: 3%;padding-top: 0.5%">F</label>
                    <input type="text" name="grade_f" class="form-control col-md-9" placeholder="enter grade" value="<?php echo get_option('grade_f')?:'<60'?>">
                </div>

             <div class="form-group">
                 <button class="btn btn-success" type="submit">Save Changes</button>
             </div>
            </form>
</div>
</div>
</div>

