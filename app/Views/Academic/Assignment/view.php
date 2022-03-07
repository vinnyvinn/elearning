<?php
$classid = $quiz->class->id;
//$sectionid = $quiz->section->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white mb-0">View Assigment</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item text-white">Quiz</li>
                            <li class="breadcrumb-item text-white"><?php echo $quiz->name; ?></li>
                            <li class="breadcrumb-item text-white">View</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <a href="<?php echo site_url(route_to('admin.academic.assignments.edit', $quiz->id)) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
            <a target="_blank" href="<?php echo site_url(route_to('admin.academic.assignments.print_assignment', $quiz->id)) ?>" class="btn btn-info btn-sm"><i class="fa fa-print"></i> Print</a>
            <?php
            if  ($quiz->published == '0') {
                //publish
                ?>
                <a href="<?php echo site_url(route_to('admin.academic.assignments.publish', $quiz->id)) ?>" class="btn btn-sm btn-success">PUBLISH</a>
                <?php
            } else {
                //mark draft
                ?>
                <a href="<?php echo site_url(route_to('admin.academic.assignments.draft', $quiz->id)) ?>" class="btn btn-sm btn-dark">MARK DRAFT</a>
                <?php
            }
            ?>
        </div>
        <div class="card-body">
            <div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Marks out of <b><?php echo $quiz->out_of; ?></b></span>
                    </div>
                    <div class="col-md-6">
                        <span>Duration: <b><?php echo @$quiz->duration ? $quiz->duration.' minutes' : '-UNDEFINED-'; ?></b></span>
                    </div>
                </div>
                <hr/>
                <div>
                    <?php
                    if($quiz->items) {
                        $n = 0;
                        foreach ($quiz->items as $item) {
                            $n++;
                            ?>
                            <?php if ($item->instructions):?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><b>Instructions</b>: <?php echo $item->instructions;?></label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($item->precautions):?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><b>Precautions</b>: <?php echo $item->precautions;?></label>
                                    </div>
                                </div>
                            <?php endif;?>

                            <div class="row">
                                <div class="col-md-8">
                                    <p><b>Q<?php echo $item->question_number; ?>: </b><?php echo $item->question; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if(isset($item->image)) {
                                        ?>
                                        <img src="<?php echo base_url('uploads/assignments/'.$item->image); ?>" style="max-height: 200px; width: auto"/>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="alert alert-neutral border-default">
                                <?php
                                echo @$item->explanation;
                                ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
