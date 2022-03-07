<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $exam->name; ?> : <?php echo @$title ? $title : 'Time Tables'; ?></h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.exams.index')); ?>">Exams</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.exams.view.index', $exam->id)); ?>"><?php echo $exam->name; ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo @$title ? $title : 'Time Tables'; ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a class="btn btn-sm btn-success" href="<?php echo site_url(route_to('admin.exam.record_results', $exam->id)); ?>"><i
                            class="fa fa-plus"></i> Record Results
                    </a>
                    <?php do_action('exam_quick_action_buttons', $exam); ?>
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
<!--    <div class="card bg-white text-white">-->
<!--        <div class="ct-example card-header-pills" style="padding-bottom: 0; margin-bottom: 0">-->
<!--            <ul class="nav nav-tabs-code nav-justified">-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'overview' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('admin.exams.view.index', $exam->id)); ?><!--" >Time Tables</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'results' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('admin.exams.view.results', $exam->id)); ?><!--" >Results</a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
    <div class="">
        <?php echo @$html; ?>
    </div>
</div>