<style>
    .color-tag {
        display: inline-block;
        width: 13px;
        height: 13px;
        margin: 2px 10px 0 0;
        transition: all 300ms ease;
        padding: 3%;
    }
    .clickable {
        cursor: pointer;
    }
    .mr15 {
        margin-right: 12px !important;
    }
    .color-tag.active {
        border-radius: 50%;
    }
    .section
    {
        margin:5px;
        border:solid 1px #ccc;
        border-radius:4px;
    }
    .shaded
    {
        background:#eee;
    }
</style>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Events</h6>
                    <a href="<?php echo base_url("admin/events/export-excel?month=$event_month");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo base_url("admin/events/pdf?month=$event_month"); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo base_url("admin/events/print?month=$event_month");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_event"><i
                                class="fa fa-plus-square"></i> New Event
                    </button>

                    <?php do_action('admin_events_calendar_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_event" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post"
                                  action="<?php echo site_url(route_to('admin.events.create')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Event</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Event Name</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name'); ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Starting Date </label>
                                        <input type="text" class="form-control datepicker" name="starting_date"
                                                required />
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Ending Date</label>
                                        <input type="text" class="form-control datepicker" name="ending_date"
                                               value="<?php echo old('ending_date'); ?>"/>
                                    </div>

                                    <div class="form-group">
                                        <label for="sess">Class</label>
                                        <select class="form-control select2" name="class" id="class"
                                                onchange="getSections()">
                                            <option value=""> -- Please select --</option>
                                            <?php
                                            $classes = getSession()->classes->findAll();
                                            if ($classes && count($classes) > 0) {
                                                foreach ($classes as $class) {
                                                    ?>
                                                    <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Section</label>
                                        <select class="form-control select2" name="section" id="section">
                                            <option value=""> -- Please select --</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <h3>Color</h3>
                                        <div class="color-palet col-md-9">
                                            <?php
                                            $selected_color = "bg-primary";
                                            $colors = array("bg-primary", "bg-secondary", "bg-success", "bg-danger", "bg-warning", "bg-info", "bg-light", "bg-dark", "bg-white");
                                            foreach ($colors as $color) {
                                                $active_class = "";
                                                if ($selected_color === $color) {
                                                    $active_class = "active";
                                                }
                                                echo "<span class='color-tag clickable mr15 " .$color." ". $active_class . "' data-color='" . $color . "'></span>";
                                            }
                                            ?>
                                            <input id="color" type="hidden" name="className" value="<?php echo $selected_color; ?>"/>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header pb-0">
            <h3>Events</h3>
        </div>
        <div class="card-body">
            <form method="get">
            <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control select2" data-toggle="select2" name="month" id="month">
                                <option value=""> -- Month --</option>
                                <option <?php echo $event_month == '01' ? 'selected' : ''; ?> value='01'>January</option>
                                <option <?php echo $event_month == '02' ? 'selected' : ''; ?> value='02'>February</option>
                                <option <?php echo $event_month == '03' ? 'selected' : ''; ?> value='03'>March</option>
                                <option <?php echo $event_month == '04' ? 'selected' : ''; ?> value='04'>April</option>
                                <option <?php echo $event_month == '05' ? 'selected' : ''; ?> value='05'>May</option>
                                <option <?php echo $event_month == '06' ? 'selected' : ''; ?> value='06'>June</option>
                                <option <?php echo $event_month == '07' ? 'selected' : ''; ?> value='07'>July</option>
                                <option <?php echo $event_month == '08' ? 'selected' : ''; ?> value='08'>August</option>
                                <option <?php echo $event_month == '09' ? 'selected' : ''; ?> value='09'>September</option>
                                <option <?php echo $event_month == '10' ? 'selected' : ''; ?> value='10'>October</option>
                                <option <?php echo $event_month == '11' ? 'selected' : ''; ?> value='11'>November</option>
                                <option <?php echo $event_month == '12' ? 'selected' : ''; ?> value='12'>December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-sm btn-primary btn-block">Filter</button>
                    </div>
            </div>
            </form>
            <div class="table-responsive">
                <table class="table" id="events-basic">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;

                    $events = (new \App\Models\Events())
                            ->groupStart()
                            ->where("MONTH(ending_date) =",$event_month)
                           ->orWhere("MONTH(starting_date) =",$event_month)
                            ->groupEnd()
                          ->where('session',active_session())
                        ->orderBy('starting_date', 'ASC')->findAll();
                    foreach ($events as $event) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $event->name; ?></td>
                            <td><?php echo $event->starting_date->format('d/m/Y'); ?></td>
                            <td><?php echo $event->ending_date ? $event->ending_date->format('d/m/Y') : ''; ?></td>
                            <td><?php echo $event->class ? $event->class->name : ''; ?></td>
                            <td><?php echo $event->section ? $event->section->name : ''; ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target=".edit_event_<?php echo $event->id;?>"><i
                                            class="fa fa-pencil"></i> Edit
                                </button>
                                <div class="modal fade edit_event_<?php echo $event->id?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post"
                                                  action="<?php echo site_url(route_to('admin.events.edit')); ?>">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Edit Event</h6>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $event->id?>">
                                                    <div class="form-group">
                                                        <label for="sess">Event Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                               value="<?php echo $event->name; ?>" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sess">Starting Date </label>
                                                        <input type="text" class="form-control datepicker" name="starting_date"
                                                               required value="<?php echo $event->starting_date?>"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sess">Ending Date</label>
                                                        <input type="text" class="form-control datepicker" name="ending_date"
                                                               value="<?php echo $event->ending_date?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="sess">Class</label>
                                                        <select class="form-control select2" name="class" id="class"
                                                                onchange="getSections()">
                                                            <option value=""> -- Please select --</option>
                                                            <?php
                                                            $classes = getSession()->classes->findAll();
                                                            if ($classes && count($classes) > 0) {
                                                                foreach ($classes as $class) {
                                                                    ?>
                                                                    <option value="<?php echo $class->id; ?>" <?php if (isset($event->class->id) && $class->id == $event->class->id):?> selected <?php endif;?>><?php echo $class->name; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sess">Section</label>
                                                        <select class="form-control select2" name="section" id="section">
                                                            <option value=""> -- Please select --</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <h3>Color</h3>
                                                        <div class="color-palet col-md-9">
                                                            <?php
                                                            $selected_color = "bg-primary";
                                                            $colors = array("bg-primary", "bg-secondary", "bg-success", "bg-danger", "bg-warning", "bg-info", "bg-light", "bg-dark", "bg-white");
                                                            foreach ($colors as $color) {
                                                                $active_class = "";
                                                                if ($selected_color === $color) {
                                                                    $active_class = "active";
                                                                }
                                                                echo "<span class='color-tag clickable mr15 " .$color." ". $active_class . "' data-color='" . $color . "'></span>";
                                                            }
                                                            ?>
                                                            <input id="color" type="hidden" name="className" value="<?php echo $selected_color; ?>"/>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-sm btn-danger send-to-server-click"
                                   data="action:delete|id:<?php echo $event->id; ?>"
                                   url="<?php echo site_url(route_to('admin.events.delete', $event->id)); ?>"
                                   warning-title="Delete Event"
                                   warning-message="Are you sure you want to delete this event?" loader="true"
                                   href="<?php echo site_url(route_to('admin.events.delete', $event->id)); ?>">Delete</a>
                        <?php endif;?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var getSections = function () {
        var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section').html(data);
            });
        }
    };
</script>
<script>
    $(document).ready(function () {
        $('#events-basic').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5]
                    }
                },
                // {
                //     extend: 'excel',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3,4,5 ]
                //     }
                // },
                // {
                //     extend: 'pdf',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3,4,5 ]
                //     }
                // },
                // {
                //     extend: 'print',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3,4,5 ]
                //     }
                // },
            ],
        });
    })

    $(".color-palet span").click(function () {
        $(".color-palet").find(".active").removeClass("active");
        $(this).addClass("active");
        $("#color").val($(this).attr("data-color"));
    });
    //
    //
    // $(function () {
    //     // Ethiopia calendar
    //     $(".datepicker_eth").ethcal_datepicker();
    // });
</script>