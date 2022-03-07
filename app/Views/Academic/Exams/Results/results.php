<?php

use App\Models\ExamResults;
use CodeIgniter\Model;

$class = (new \App\Models\Classes())->find($class);
$exam = (new \App\Models\Exams())->find($exam);
$section = (new \App\Models\Sections())->find($section);
if($exam && $section) {
    $subjects = $class->subjects();
    $students = $section->students;
    $std = (new \App\Models\Students())->find(1948);
    //echo '<pre>';
   // var_dump($std->class->subjects);


    $students_arr = array();
    foreach ($students as $student) {
        foreach ($subjects as $subject) {
            $result = (new ExamResults())->select('SUM(mark) as subtotal')->where('student', $student->id)
                ->where('subject', $subject->id)->where('exam', $exam->id)->get()->getRowObject();

            // $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $class->id])->get()->getLastRow();
            if (!empty($result)) {
                if (!isset($students_arr[$student->id])) {
                    $students_arr[$student->id] = $result->subtotal;
                } else {
                    $students_arr[$student->id] += $result->subtotal;
                }
            }
        }
    }
    $rank_students = array_rank($students_arr);
    ?>
    <div class="">
        <a class="btn btn-sm btn-success" href="<?php echo site_url(route_to('admin.exam.record_results', $exam->id)); ?>"><i
                    class="fa fa-plus"></i> Record Results
        </a>
        <a class="btn btn-sm btn-danger" target="_blank" href="<?php echo site_url(route_to('admin.academic.exam.results.pdf', $exam->id,$class->id,$section->id)); ?>"><i
                    class="fa fa-download"></i> Pdf
        </a>
        <a class="btn btn-sm btn-danger" target="_blank" href="<?php echo site_url(route_to('admin.academic.exam.results.print', $exam->id,$class->id,$section->id)); ?>"><i
                    class="fa fa-print"></i> Print
        </a>
    </div>
    <?php

    if($students && count($students) > 0) {
        ?>
        <div class="table-responsive pt-2">
            <table class="table" id="results_table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Section</th>
                    <?php
                  //  $subjects = $class->subjects();
                    foreach ($subjects as $subject) {
                       // if ($subject->optional !=1){
                            ?>
                            <th><?php echo $subject->name; ?></th>
                            <?php
                       // }
                    }
                    ?>
                    <th>Average</th>
                    <th>Total</th>
                    <th>Rank</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($rank_students as $student => $rank) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo getStudent($student); ?></td>
                        <td><?php echo getSection($student); ?></td>
                        <?php
                        $i = 0;
                        $tt = 0;
                        $model = new \App\Models\ExamResults();
                        $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student])->get()->getRowObject();
                        $tt = $big->tt;
                        $total_marks = 0;
                        foreach ($subjects as $subject) {
                         //  if ($subject->optional !=1) {
                                $rs = $model->where(['exam' => $exam->id, 'student' => $student, 'subject' => $subject->id])->get()->getRowObject();
                                ?>
                                <td><?php echo ($rs && !empty($rs->mark)) ? $rs->mark : '0'; ?></td>
                                <?php
                               if ($subject->optional !=1) {
                                   $i++;
                                   $total_marks += (!empty($rs->mark) && is_numeric($rs->mark)) ? $rs->mark : 0;
                               }
                          //}
                        }
                        ?>
                        <td>
                            <?php
                            echo number_format(($total_marks/$i), 2);
                            ?>
                        </td>
                        <td><?php echo  number_format($total_marks, 2); ?></td>
                        <td><?php echo $rank; ?></td>
                        <td>
                            <a target="_blank" href="<?php echo site_url(route_to('admin.academic.exam.print_student_results', $exam->id, $student)) ?>">Print</a>
                        </td>
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
        <div class="alert alert-warning">
            This class has no students
        </div>
        <?php
    }
    ?>
    <script>
        $(document).ready(function () {
            $('#results_table').dataTable({
                dom: 'Bfrtip',
                colReorder: true,
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    // {
                    //     extend: 'pdf',
                    //     exportOptions: {
                    //         columns: 'th:not(:last-child)'
                    //     }
                    // },
                    // {
                    //     extend: 'print',
                    //     exportOptions: {
                    //         columns: 'th:not(:last-child)'
                    //     }
                    // },
                ],
            });
        })
    </script>
    <?php
} else {
    ?>
    <div class="alert alert-danger">
        Invalid class section or exam selected
    </div>
    <?php
}