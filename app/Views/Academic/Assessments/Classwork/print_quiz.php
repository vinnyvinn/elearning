<?php
$options = (new \App\Models\AnswerOption())->findAll();
?>
<html lang="en">
    <head>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
        <style>

        </style>
        <title>Class Work</title>
    </head>
<body>
<hr/>
<div class="row justify-content-center">
    <div class="col-md-4 float-center text-center">
        <b>Session: <?php echo $classwork->session->name; ?></b><br/>
        <b>Semester: <?php echo $classwork->semester->name; ?></b><br/><br/>
        <b>Class: <?php echo $classwork->class->name; ?></b><br/><br/>
        <b>Given on <?php echo $classwork->given->format('d/m/Y'); ?></b><br/>
        <b>Deadline: <?php echo $classwork->deadline->format('d/m/Y'); ?></b><br/>
    </div>
</div>
<br/><br/><br/>
<div class="row">
    <div class="col-md-6">
        <span>Marks out of <b><?php echo $classwork->out_of; ?></b></span>
    </div>
    <div class="col-md-6">
        <span>Duration: <b><?php echo @$classwork->duration ? $classwork->duration.' minutes' : '-UNDEFINED-'; ?></b></span>
    </div>
</div>
<hr/>
<div>
    <?php
    if($classwork->items) {
        $n = 0;
        foreach ($classwork->items as $item) {
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
<script>
    window.print();
    setTimeout(()=>{
     window.close();
    },2000)
</script>
</body>
</html>