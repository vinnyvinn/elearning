<?php
//d($student->annualRank);
$rank = $student->annualRank;
if($rank && is_array($rank) && count($rank) > 0) {
    $ranks = [];
    $months = [];
    foreach ($rank as $r) {
        $dateObj   = DateTime::createFromFormat('!m', $r->month);
        $monthName = $dateObj->format('F'); // March
        $months[] = $monthName;
        $ranks[] = (int) $r->rank;
    }
    ?>
    <script src="<?php echo base_url('assets/js/highcharts.js'); ?>" ></script>
    <div id="container" style="height: 400px"></div>
    <script>
        Highcharts.chart('container', {
            title: {
                text: 'Monthly Rank'
            },
            xAxis: {
                categories: <?php echo json_encode($months); ?>,
                reversed: false,
                title: {
                    text: 'Months'
                }
            },
            yAxis: {
                reversed: true,
                floor: 0,
                ceiling: 45,
                showLastLabel: true,
                title: {
                    text: 'Student Monthly Rank'
                }
            },
            series: [{
                name: "Student Rank",
                data: <?php echo json_encode($ranks); ?>
            }]
        });
    </script>
    <?php
} else {
    ?>
    <div class="card-body">
        <div class="alert alert-warning">
            This student has no assessments added
        </div>
    </div>
    <?php
}
?>