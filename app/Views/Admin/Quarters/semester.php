<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">New Semester</h6>
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
         <form class="ajaxFormi" loader="true" method="post"
               action="<?php echo site_url(route_to('admin.school.quarters.save_semester')); ?>">

             <div class="modal-body">
                 <div class="form-group">
                     <label for="sess">Semester Name</label>
                     <input type="text" class="form-control" name="name"
                            value="<?php echo old('name') ?>" required/>
                 </div>
                 <input type="hidden" name="session" value="<?php echo $session;?>">
                 <div class="form-group">
                     <label>Opening Date</label>
                     <input type="text" class="form-control datepicker" name="opening_date"
                            value="<?php echo old('opening_date'); ?>"/>
                 </div>
                 <div class="form-group">
                     <label>Closing Date</label>
                     <input type="text" class="form-control datepicker" name="closing_date"
                            value="<?php echo old('closing_date'); ?>"/>
                 </div>
                 <div class="form-group">
                     <button type="submit" class="btn btn-success">Save</button>
                 </div>
         </form>
     </div>
    </div>
</div>