<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Semester Ranking- Yearly Average</title>
    <style>

    </style>
</head>
<?php
 $section = (new \App\Models\Sections())->find($section);
  ?>
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body" style="margin-left: 30%">
                <div class="row">
                    <div>
                        <table style="text-align: center;border: none">
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 30px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 30px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 30px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                             <th style="border: none !important;"><b style="font-size: 30px;font-weight: 900">Semester Ranking- Yearly Average </b> </th>
                            </tr>
                            <tr>
                           <th style="border: none !important;"><b style="font-size: 30px;font-weight: 900"><?php echo $section->class->name.' '.$section->name;?> </b> </th>
                          </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $students = $section->students;
                ?>
                <style>
                    td,th{
                        border: 1px solid black !important;
                    }
                </style>
                <div class="table-responsive">
                    <?php
                    $semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
                    $data = studentResult($students,$semesters[0]->id,$semesters[1]->id);
                    $keys = array_keys($data);
                    $stud = (new \App\Models\Students())->find(array_slice($keys,0,1));
                    $subjects = $stud[0]->class->subjects;
                    ?>

                    <table class="table datatable" id="">
                        <thead>
                        <tr>
                            <th style="font-size: 20px">Name</th>
                            <th style="font-size: 20px">Sex</th>
                            <th style="font-size: 20px">Semester</th>
                            <?php foreach ($subjects as $subject):?>
                                <th style="font-size: 20px"><?php echo $subject->name;?></th>
                            <?php endforeach;?>
                            <th style="font-size: 20px">Total</th>
                            <th style="font-size: 20px">Average</th>
                            <th style="font-size: 20px">Rank</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $key => $items):
                            $student = (new \App\Models\Students())->find($key);
                            ?>
                            <tr>
                            <td rowspan="4" style="vertical-align: middle;font-size: 24px"><?php echo $student->profile->name;?></td>
                            <td rowspan="4" style="vertical-align: middle;font-size: 24px"><?php echo strtolower($student->profile->gender)=='female' ? "F" : "M";?></td>
                            <?php foreach($items as $k2 => $item):
                            ?>
                            <tr>

                                <td style="font-size: 24px"><?php echo $k2;?></td>
                                <?php
                                foreach ($item['scores'] as $k3 => $val):?>
                                    <td style="font-size: 24px"><?php echo $val;?></td>
                                <?php endforeach;?>
                                <td style="font-size: 24px"><?php echo $item['total']?></td>
                                <td style="font-size: 24px"><?php echo $item['average']?></td>
                                <td style="font-size: 24px"><?php echo $item['rank']?></td>
                            </tr>
                        <?php endforeach;?>
                          </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = 'Semester Ranking- Yearly Average';

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       0,
        filename:     name+'.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { dpi: 800, letterRendering: true},
        jsPDF:        { unit: 'in', format: 'A0', orientation: 'portrait' }
    };

    // New Promise-based usage:
    //  html2pdf().set(opt).from(element).save();

    // Old monolithic-style usage:
    html2pdf(element, opt)
        .then(res =>{
            console.log('finished')
            setTimeout(()=>{
                window.history.back();
            },2000)

        })

</script>


