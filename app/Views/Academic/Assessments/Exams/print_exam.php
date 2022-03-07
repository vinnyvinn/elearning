<?php
$options = (new \App\Models\AnswerOption())->findAll();
?>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <style>

    </style>
    <title>Exams</title>
</head>
<body>
<hr/>
<div class="row justify-content-center">
    <div class="col-md-4 float-center text-center">
        <b>Session: <?php echo $exam->session->name; ?></b><br/>
        <b>Semester: <?php echo $exam->semester->name; ?></b><br/><br/>
        <b>Class: <?php echo $exam->class->name; ?></b><br/><br/>
        <b>Given on <?php echo $exam->given->format('d/m/Y'); ?></b><br/>
        <b>Deadline: <?php echo $exam->deadline->format('d/m/Y'); ?></b><br/>
    </div>
</div>
<br/><br/><br/>
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
<script>
    window.print();
    setTimeout(()=>{
     window.close();
    },2000)
</script>
</body>
</html>