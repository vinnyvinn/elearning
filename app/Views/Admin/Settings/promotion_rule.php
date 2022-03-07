<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Promotion Rule</h6>
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
        <div class="card-body">
            <form class="ajaxForm" method="post"  loader="true" action="<?php echo site_url(route_to('admin.settings.save_promotion_rule')); ?>">
             <div class="form-group">
                 <label>Pass Mark</label>
                 <input type="number" class="form-control" name="promotion_pass_mark" value="<?php echo  get_option('promotion_pass_mark')?>" required>
             </div>
                <div class="form-group">
                    <label>Number of Subject Failed</label>
                    <input type="number" class="form-control" name="no_subject_failed" value="<?php echo  get_option('no_subject_failed')?>" required>
                </div>
    <button class="btn btn-success" type="submit">Save Changes</button>
    </form>
</div>
</div>
</div>