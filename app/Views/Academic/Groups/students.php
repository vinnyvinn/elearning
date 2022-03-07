<?php
//d($students);
?>
<div class="card">
    <div class="card-body">
        <h4><?php echo $group->name; ?> Members</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Adm. Number</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($students as $student) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $student->student->admission_number; ?></td>
                        <td><?php echo $student->student->profile->name; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('checkbox').on('change', function(e){

    });
</script>