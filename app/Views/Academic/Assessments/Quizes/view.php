<?php
$classid = $quiz->class->id;
$options = (new \App\Models\AnswerOption())->findAll();
//$sectionid = $quiz->section->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white mb-0">View Quiz</h6>
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
            <a href="<?php echo site_url(route_to('admin.academic.assessments.quizes.edit_quiz', $quiz->id)) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
            <a target="_blank" href="<?php echo site_url(route_to('admin.academic.assessments.quizes.print_quiz', $quiz->id)) ?>" class="btn btn-info btn-sm"><i class="fa fa-print"></i> Print</a>
            <?php
            if  ($quiz->published == '0') {
                //publish
                ?>
                <a href="<?php echo site_url(route_to('admin.academic.assessments.quizes.publish', $quiz->id)) ?>" class="btn btn-sm btn-success">PUBLISH</a>
                <?php
            } else {
                //mark draft
                ?>
                <a href="<?php echo site_url(route_to('admin.academic.assessments.quizes.draft', $quiz->id)) ?>" class="btn btn-sm btn-dark">MARK DRAFT</a>
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
                            <div class="row">
                                <div class="col-md-8">
                                    <span><b>Q<?php echo $item->question_number; ?>: </b><?php echo $item->question; ?></span>
                                    <div class="ml-5">
                                        <?php
                                        $naswers_ = array_flatten(json_decode($item->answers));
                                        $correct = array_flatten(json_decode($item->corrects))[0];

                                        foreach ($options as $option){
                                            foreach ($naswers_ as $key=>$answer) {
                                                $opt_name = $option['name'];
                                                if (isset($answer->$opt_name)){
                                                    ?>
                                                    <label>
                                                        <input type="radio" <?php echo isset($answer->$opt_name) && $correct == $opt_name ? 'checked' : ''; ?> disabled /> <?php echo $answer->$opt_name; ?>
                                                    </label><br/>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if(isset($item->image)) {
                                        ?>
                                        <img src="<?php echo $item->image; ?>" style="max-height: 200px; width: auto"/>
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
