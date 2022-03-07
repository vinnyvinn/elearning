<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">Top Performers Background Image</h6>
               </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-1">
            <div class="card">
                <div class="card-body">
                    <form class="ajaxFormi" loader="true" method="post" action="<?php echo site_url(route_to('admin.settings.background-image')); ?>" enctype="multipart/form-data">
                        <h4>Image</h4>

                        <div class="form-group">
                            <input type="file" name="student_background_image"
                                   class="form-control" accept="image/*"/>
                        </div>

                        <br/>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>