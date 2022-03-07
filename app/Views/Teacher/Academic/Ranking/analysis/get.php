<?php
$class = (new \App\Models\Classes())->find($class);
?>
<style>
  table,th,td{
   border: 1px solid black !important;
  }
</style>
<h4 class="text-center">የክፍል ደረጃ <span style="text-decoration: underline"><?php echo $class->name;?></span></h4>
<div class="table-responsive">
    <table class="table" id="datatable">
        <thead>
        <tr>
            <th colspan="7" class="text-center">የተማሪው የፈተና ውጤት ከመቶ</th>
        </tr>
        <tr>
          <th rowspan="2" class="text-center" style="vertical-align: middle">የትምህርት አይነት</th>
          <th colspan="3" class="text-center">ከ50 በታች ያገኙ</th>
          <th colspan="3" class="text-center">50 እና ከዛ በላይ ያገኙ</th>
        </tr>
        <tr>
          <th>ወ</th>
          <th>ሴ</th>
          <th>ድ</th>
          <th>ወ</th>
          <th>ሴ</th>
          <th>ድ</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $n = 0;
        $i = 0;

        foreach ($subjects as $subject) {

            $n++;
            ?>
            <tr>
                <td><?php echo $subject->name; ?></td>
                <td><?php echo isset($male_subjects_above_50[$subject->id]) ? $male_subjects_above_50[$subject->id] : 0; ?></td>
                <td><?php echo isset($female_subjects_above_50[$subject->id]) ? $female_subjects_above_50[$subject->id] : 0; ?></td>
                <td><?php echo (isset($female_subjects_above_50[$subject->id]) ? $female_subjects_above_50[$subject->id] : 0) + (isset($male_subjects_above_50[$subject->id]) ? $male_subjects_above_50[$subject->id] :0); ?></td>
                <td><?php echo isset($male_subjects_below_50[$subject->id]) ? $male_subjects_below_50[$subject->id] : 0; ?></td>
                <td><?php echo isset($female_subjects_below_50[$subject->id]) ? $female_subjects_below_50[$subject->id] : 0; ?></td>
                <td><?php echo (isset($female_subjects_below_50[$subject->id]) ? $female_subjects_below_50[$subject->id] : 0) +(isset($male_subjects_below_50[$subject->id]) ? $male_subjects_below_50[$subject->id] : 0); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'pdf',
                },
                {
                    extend: 'print',
                },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1 ] }
            ]
        });
    })
</script>