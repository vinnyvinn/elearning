<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Notes</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <?php
    $class = $student->class->id;
    $section = $student->section;
    $notes = $section->notes;
    ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Uploaded On</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($notes as $note) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n ?></td>
                        <td><?php echo $note->subject->name; ?></td>
                        <td><?php echo $note->name; ?></td>
                        <td><?php echo $note->description; ?></td>
                        <td><a href="<?php echo $note->file; ?>">Download Notes</a></td>
                        <td><?php echo $note->created_at->format('d/m/Y'); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>