<?php

use App\Models\Students;

?>
<div class="card card-translucent">
    <div class="card-header">
        <h3 class="h3 d-inline-block">Student Groups</h3>
        <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target=".new_group">New Group
        </button>
    </div>
    <div class="modal fade new_group" tabindex="-1" role="dialog" aria-labelledby="modal-default"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <form class="ajaxForm" loader="true" method="post"
                      action="<?php echo site_url(route_to('admin.class.sections.groups.create', $section->id)); ?>">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">New Group</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sess">Group Name</label>
                            <input type="text" class="form-control" name="name"
                                   value="<?php echo old('name') ?>" required/>
                        </div>
                        <?php
                        $students = (new Students())->where('class', $section->class->id)->where('section', $section->id)->where('session', active_session())
                            ->whereNotIn('id', function (\CodeIgniter\Database\BaseBuilder $builder) {
                                return $builder->select('student')->from('section_groups');
                            })->findAll();
                        //d($students);
                        ?>
                        <div class="form-group">
                            <?php
                            if ($students && count($students) > 0) {
                                ?>
                                <label>Add Students to Group</label>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>##</th>
                                            <th>Adm. No.</th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($students as $student) {
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="students[]"
                                                           value="<?php echo $student->id; ?>"/></td>
                                                <td><?php echo $student->admission_number; ?></td>
                                                <td><?php echo $student->profile->name; ?></td>
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
                                <div class="alert alert-success">
                                    Looks like you have assigned all student to groups
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Create Group</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <?php
                    $groups = (new \App\Models\ClassGroups())->orderBy('id', 'DESC')->findAll();
                    //d($groups);
                    if ($groups && count($groups) > 0) {
                        ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $n = 0;
                                foreach ($groups as $group) {
                                    $n++;
                                    ?>
                                    <tr>
                                        <td><?php echo $n; ?></td>
                                        <td><?php echo $group->name; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-success viewGroup"
                                                    group-id="<?php echo $group->id; ?>" type="button">View
                                            </button>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target=".edit_group<?php echo $group->id; ?>">Manage
                                            </button>
                                    <?php if (isSuperAdmin()):?>
                                            <a class="btn btn-sm btn-danger send-to-server-click" href="#!"
                                               url="<?php echo site_url(route_to('admin.class.sections.groups.delete', $group->id)); ?>"
                                               data="action:delete|id:<?php echo $group->id; ?>"
                                               warning-title="Delete Group"
                                               warning-message="Are you sure you want to delete this group and all of its content?">Delete</a>
                                    <?php endif;?>
                                            <div class="modal fade edit_group<?php echo $group->id; ?>" tabindex="-1"
                                                 role="dialog" aria-labelledby="modal-default"
                                                 style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <form class="ajaxForm" loader="true" method="post"
                                                              action="<?php echo site_url(route_to('admin.class.sections.groups.create', $section->id)); ?>">
                                                            <input type="hidden" name="id"
                                                                   value="<?php echo $group->id; ?>"/>
                                                            <div class="modal-header">
                                                                <h6 class="modal-title" id="modal-title-default">Update
                                                                    Group</h6>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="sess">Group Name</label>
                                                                    <input type="text" class="form-control" name="name"
                                                                           value="<?php echo old('name', $group->name); ?>"
                                                                           required/>
                                                                </div>
                                                                <?php
                                                                $students = (new Students())->where('class', $section->class->id)->where('section', $section->id)
//                                                                    ->groupStart()
//                                                                    ->whereNotIn('id', function(\CodeIgniter\Database\BaseBuilder $builder) {
//                                                                        return $builder->select('student')->from('section_groups');
//                                                                    })
//                                                                    ->orWhereIn('id', function (\CodeIgniter\Database\BaseBuilder $builder){
//                                                                        return $builder->select('id')->from('section_groups');
//                                                                    })
//                                                                    ->groupEnd()
                                                                    ->findAll();
                                                                //d($students);
                                                                ?>
                                                                <div class="form-group">
                                                                    <?php
                                                                    if ($students && count($students) > 0) {
                                                                        ?>
                                                                        <label>Add Students to Group</label>
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>##</th>
                                                                                    <th>Adm. No.</th>
                                                                                    <th>Name</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                foreach ($students as $student) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td><input type="checkbox"
                                                                                                   name="students[]" <?php echo (new \App\Models\SectionGroups())->where('student', $student->id)->find() ? 'checked' : ''; ?>
                                                                                                   value="<?php echo $student->id; ?>"/>
                                                                                        </td>
                                                                                        <td><?php echo $student->admission_number; ?></td>
                                                                                        <td><?php echo $student->profile->name; ?></td>
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
                                                                        <div class="alert alert-success">
                                                                            Looks like you have assigned all students
                                                                            to groups
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Update
                                                                    Group
                                                                </button>
                                                                <button type="button" class="btn btn-link  ml-auto"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
                        <div class="card-body">
                            <div class="alert alert-danger">
                                No groups have been created for this class section
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div id="ajaxContent">
                    <div class="alert alert-warning">
                        Click "View" on any group to view its members
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.viewGroup').click(function (e) {
        e.preventDefault();
        var id = $(this).attr('group-id');
        fetchGroupMembers(id);
    })

    var fetchGroupMembers = function (id) {
        var e = {
            url: "<?php echo site_url(route_to('admin.class.sections.groups.members')); ?>",
            data: "id=" + id,
            loader: true
        }
        ajaxRequest(e, renderGroupMembers)
    }

    var renderGroupMembers = function (data) {
        $('#ajaxContent').html(data);
    }
</script>