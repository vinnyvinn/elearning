<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Messages</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('teacher_messages_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">


<!--            <div class="">-->
<!--                --><?php
//                $messages = (new \App\Models\Messages())->groupBy('for_student')->orderBy('id', 'ASC')->findAll();
//                foreach ($messages as $message) {
//                    $parent = (new \App\Models\Parents())->find($message->parent);
//                    $student = (new \App\Models\Students())->find($message->for_student);
//                    ?>
<!--                    <a href="--><?php //echo site_url(route_to('admin.message.parent', $parent->id, $student->id, $message->teacher)); ?><!--">-->
<!--                        <div class="media media-comment">-->
<!--                            --><?php
//                            if ($parent->avatar) {
//                                ?>
<!--                                <img alt="--><?php //echo $parent->name; ?><!--"-->
<!--                                     class="avatar avatar-lg media-comment-avatar rounded-circle"-->
<!--                                     src="--><?php //echo $parent->avatar; ?><!--">-->
<!--                                --><?php
//                            }
//                            ?>
<!--                            <div class="media-body">-->
<!--                                <div class="media-comment-text">-->
<!--                                    <h4>--><?php //echo (new \App\Models\Teachers())->find($message->teacher)->profile->name; ?><!--</h4>-->
<!--                                    <h6 class="h5 mt-0">From --><?php //echo $parent->name; ?><!-- <small>parent to</small> --><?php //echo $student->profile->name.' ('.$student->admission_number.')'; ?><!--</h6>-->
<!--                                    <p class="text-sm lh-160">--><?php ////echo $message->message; ?><!--</p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                    --><?php
//                }
//                ?>
<!--            </div>-->
            <div class="table-responsive pt-2">
                <table class="table" id="messages-table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n=0;
                    $messages = (new \App\Models\Messages())->where('session',active_session())->groupBy('for_student')->orderBy('id', 'ASC')->limit(100)->get()->getResult();
                    //var_dump(count($messages));
                    //exit();
                    foreach ($messages as $message):
                        $parent = (new \App\Models\Parents())->find($message->parent);
                        $student = (new \App\Models\Students())->find($message->for_student);
                        if (!empty($parent) || !empty($student)):
                     $n++;
                    ?>
                    <tr>
                       <td><?php echo $n;?></td>
                       <td>
                           <?php  if (isset($message->parent)): ?>
                               <div class="media-body">
                                   <div class="media-comment-text">
                                       <h6 class="h5 mt-0"><?php echo $parent->name; ?> <small>parent to</small> <?php echo $student->profile->name.' ('.$student->admission_number.')'; ?></h6>
                                       <p class="text-sm lh-160"><?php //echo $message->message; ?></p>
                                   </div>
                               </div>
                           <?php else:?>
                               <div class="media-body">
                                   <div class="media-comment-text">
                                       <h6 class="h5 mt-0"><?php echo $student->profile->name; ?> <small>of class</small> <?php echo $student->profile->name.' ('.$student->admission_number.')'; ?></h6>
                                       <p class="text-sm lh-160"><?php //echo $message->message; ?></p>
                                   </div>
                               </div>
                           <?php endif;?>
                       </td>
                       <td>
                           <?php  if (isset($message->parent)): ?>
                           <a href="<?php echo site_url(route_to('admin.messages.parent', $parent->id, $student->id)); ?>" class="btn btn-info" title="View Conversation"><i class="fa fa-eye"></i></a>
                           <?php else:?>
                               <a href="<?php echo site_url(route_to('admin.messages.student',$student->id)); ?>" class="btn btn-info" title="View Conversation"><i class="fa fa-eye"></i></a>
                           <?php endif;?>
                       </td>
                    </tr>
                    <?php endif;endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#messages-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
            ],
        });
    })
</script>