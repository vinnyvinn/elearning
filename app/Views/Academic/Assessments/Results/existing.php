<?php




?>
<div>
    <div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($existing as $item) {
                    if(!empty($item->student)) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $item->student->profile->name; ?></td>
                            <td><?php echo $item->score; ?></td>
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
<script>
    function getDeleteCATS() {
        var classes = $('#class_id').val();
        var subject = $('#subject_id').val();
        var semester = $('#semester_id').val();
        if(classes == '' || subject == '' || semester == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('admin.academic.assessments.get_cats')); ?>",
                loader: true,
                data: "class=" + classes + "&subject="+subject+"&semester="+semester+"&delete=1"
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>