<?php


?>
<?php
//d($classwork);

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $classwork->name; ?> Results</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="table">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Submitted On</th>
                            <th>Score</th>
                            <th>Out Of</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $items = $classwork->items;
                    if($items && count($items) > 0) {
                        $n = 0;
                        foreach ($items as $item) {
                            $n++;
                            $submission = $item->getSubmission($student->id);
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $item->subject->name; ?></td>
                                <td>
                                    <?php
                                    if($submission) {
                                        echo $submission->submitted_on;
                                    } else {
                                        ?> <span class="badge badge-danger">Not Submitted</span> <?php
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    if($submission) {
                                        echo $submission->score;
                                    } else {
                                        echo '-';
                                    }
                                    ?></td>
                                <td><?php echo $item->out_of; ?></td>
                                <td><?php echo '-'; ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
