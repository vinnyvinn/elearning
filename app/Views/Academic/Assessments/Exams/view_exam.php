<?php
$options = (new \App\Models\AnswerOption())->findAll();

?>
<div>
    <a target="_blank" href="<?php echo site_url(route_to('admin.academic.assessments.exams.print_exam', $exam->id)) ?>" class="btn btn-info"><i class="fa fa-print"></i> Print</a>
</div>
<hr/>
<div class="row">
    <div class="col-md-6">
        <span>Marks out of <b><?php echo $exam->out_of; ?></b></span>
    </div>
    <div class="col-md-6">
        <span>Duration: <b><?php echo @$exam->duration ? $exam->duration.' minutes' : '-UNDEFINED-'; ?></b></span>
    </div>
</div>
<hr/>
<div>
    <?php
    if($exam->items) {
        $n = 0;
        foreach ($exam->items as $item) {
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

                        <img src="<?php echo @$item->image; ?>" class="img-responsive" style="max-height: 200px; width: auto; max-width: 300px;"/>

                </div>
            </div>
            <div class="alert alert-neutral border-info">
                <?php
                    echo @$item->explanation;
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>