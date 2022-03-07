<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <?php //d($subject); ?>
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-8 col-9">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $subject->name; ?>: <?php echo @$title; ?></h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.classes.index')); ?>">Classes</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.classes.view', $section->class->id)); ?>"><?php echo $section->class->name; ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.class.sections.view', $section->id)); ?>"><?php echo $section->name; ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $subject->name; ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 col-3 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target=".new_assignment">New Assignment</button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target=".new_notes">Upload Notes</button>
                    <?php do_action('subject_actions', $subject, $section); ?>
                </div>
                <div class="modal fade new_notes" tabindex="-1"
                     role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                  action="<?php echo site_url(route_to('admin.subjects.notes', $subject->id, $section->id)); ?>">
                                <input type="hidden" name="class" value="<?php echo $section->class->id; ?>"/>
                                <input type="hidden" name="section" value="<?php echo $section->id; ?>"/>
                                <input type="hidden" name="subject" value="<?php echo $subject->id; ?>"/>
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">Upload Notes</h6>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Title</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name'); ?>"
                                               required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="4"><?php echo old('description'); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">File</label>
                                        <input type="file" class="form-control"
                                               name="file"
                                               required/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-link  ml-auto"
                                            data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade new_assignment" tabindex="-1"
                     role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                  action="<?php echo site_url(route_to('admin.subjects.assignments', $subject->id, $section->id)); ?>">
                                <input type="hidden" name="class" value="<?php echo $section->class->id; ?>"/>
                                <input type="hidden" name="section" value="<?php echo $section->id; ?>"/>
                                <input type="hidden" name="subject" value="<?php echo $subject->id; ?>"/>
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Assignment</h6>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="4" required><?php echo old('description'); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Books to Cover</label>
                                        <textarea class="form-control" name="books" rows="4"><?php echo old('books'); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">File</label>
                                        <input type="file" class="form-control"
                                               name="file"
                                               required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Deadline</label>
                                        <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline'); ?>" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-link  ml-auto"
                                            data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card .ct-example .nav .nav-item .nav-link.active {
        /*background-color: #5e72e4 !important;*/
        /*color: white !important;*/
    }
</style>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card bg-white text-white">
        <div class="ct-example card-header-pills" style="padding-bottom: 0; margin-bottom: 0">
            <ul class="nav nav-tabs-code nav-justified">
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'overview' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.subjects.view', $subject->id, $section->id)); ?>" >Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'notes' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.subjects.notes', $subject->id, $section->id)); ?>" >Notes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'assignments' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.subjects.assignments', $subject->id, $section->id)); ?>" >Assignments</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="">
        <?php echo @$html; ?>
    </div>
</div>