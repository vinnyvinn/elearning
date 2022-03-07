<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $section->class->name; ?> : <?php echo $section->name; ?> : <?php echo @$title ? $title : 'Overview'; ?></h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('teacher.classes.index')); ?>">Classes</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('teacher.classes.view', $section->class->id)); ?>"><?php echo $section->class->name; ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('teacher.class.sections.view', $section->id)); ?>"><?php echo $section->name; ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo @$title ? $title : 'Overview'; ?></li>
                        </ol>
                    </nav>
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
                    <a class="nav-link <?php echo @$page == 'overview' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('teacher.class.sections.view', $section->id)); ?>" >Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'timetable' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('teacher.class.sections.timetable', $section->id)); ?>" >Time Table</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'asp_schedule' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('teacher.class.sections.asp_schedule', $section->id)); ?>" >After School Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'groups' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('teacher.class.sections.groups', $section->id)); ?>" >Student Groups</a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'assignments' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('teacher.class.sections.assignments', $section->id)); ?><!--" >Assignments</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'notes' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('teacher.class.sections.notes', $section->id)); ?><!--" >Notes</a>-->
<!--                </li>-->
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'students' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('teacher.class.sections.students', $section->id)); ?>" >Students</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="">
        <?php echo @$html; ?>
    </div>
</div>