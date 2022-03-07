<html>
    <head>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
        <title>Quizes</title>
    </head>
<body>
<hr/>
<div class="row justify-content-center">
    <div class="col-md-4 float-center text-center">
        <b>Session: <?php echo $quiz->session->name; ?></b><br/>
        <b>Semester: <?php echo $quiz->semester->name; ?></b><br/><br/>
        <b>Class: <?php echo $quiz->class->name; ?></b><br/><br/>
        <b>Given on <?php echo $quiz->given->format('d/m/Y'); ?></b><br/>
        <b>Deadline: <?php echo $quiz->deadline->format('d/m/Y'); ?></b><br/>
    </div>
</div>
<br/><br/><br/>
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
                        <p><b>Q<?php echo $n; ?>: </b><?php echo $item->question; ?></p>
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
<script>
    window.print();
    setTimeout(()=>{
        window.close();
    },2000)
</script>
</body>
</html>
