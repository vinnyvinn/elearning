<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Exam List</title>
</head>
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body" style="margin-left: 30%">
                <div class="row">
                    <div>
                        <table style="text-align: center">
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900">Exam List </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $model = new \App\Models\Exams();
                $current_exams = $model->where('session', active_session())
                    ->groupStart()
                    ->where('class', NULL)
                    ->orWhere('class', '')
                    ->groupEnd()
                    ->groupStart()
                    ->where('section', NULL)
                    ->orWhere('section', '')
                    ->groupEnd()
                    ->orderBy('id', 'DESC')->findAll();
                if($current_exams && count($current_exams) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table" id="exams_table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Session</th>
                                <th>Starting Date</th>
                                <th>Ending Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($current_exams as $current_exam) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $current_exam->name; ?></td>
                                    <td><?php echo $current_exam->session ? $current_exam->session->name : '-'; ?></td>
                                    <td><?php echo $current_exam->starting_date; ?></td>
                                    <td><?php echo $current_exam->ending_date; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            No exams found
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    </div>
</body>

<script>
      window.print();
       setTimeout(() => {
           window.close();
       },3000)


</script>

