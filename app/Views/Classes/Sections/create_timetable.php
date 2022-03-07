<?php
$framework = get_option('timetable_framework_'.$section->class->id, get_option('timetable_framework', FALSE));

$framework = json_decode($framework, TRUE);
$builder = new \App\Models\Timetable();
$class = $section->class->id;
?>
<div class="card">
    <!-- Modal stuff -->
    <div class="modal fade edit_timeslots" tabindex="-1"
         role="dialog" aria-labelledby="modal-default"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" data-parsley-validate id="timeSlotForm"
                      action="#!">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Subject Teacher</h6>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="the-holder">
                            <?php
                            if($framework) {
                                foreach ($framework as $item) {
                                    ?>
                                    <div class="row" id="item">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input type="text" name="time" class="form-control" value="<?php echo $item['time']; ?>" required />
                                                <label><input type="checkbox" name="break" onchange="this.checked ? $(this).parent().parent().find('input[name=label]').show() : $(this).parent().parent().find('input[name=label]').hide()" value="1" <?php echo $item['break'] ? 'checked' : ''; ?> /> Break </label>
                                                <input type="text" name="label" <?php echo $item['break'] ? '' : 'style="display: none"'; ?> placeholder="Label" value="<?php echo $item['break'] ? $item['label'] : ''; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="rm" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="row" id="item">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="text" name="time" class="form-control" required />
                                            <label><input type="checkbox" name="break" onchange="this.checked ? $(this).parent().parent().find('input[name=label]').show() : $(this).parent().parent().find('input[name=label]').hide()" value="1" /> Break </label>
                                            <input type="text" name="label" style="display: none" placeholder="Label" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="rm" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-success" onclick="addTimeSlot()"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-link  ml-auto"
                                data-dismiss="modal">Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="xxHolder" style="display: none">
        <div class="row" id="item">
            <div class="col-md-10">
                <div class="form-group">
                    <input type="text" name="time" class="form-control" required />
                    <label><input type="checkbox" name="break" onchange="this.checked ? $(this).parent().parent().find('input[name=label]').show() : $(this).parent().parent().find('input[name=label]').hide()" value="1" /> Break </label>
                    <input type="text" name="label" style="display: none" placeholder="Label" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" id="rm" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    </div>
    <script>
        $('#timeSlotForm').submit(function (e) {
            e.preventDefault();
            var index = 1;
            var exam = [];
            $('#the-holder #item').each(function () {
                var row = {};

                $(this).find('input,select,textarea').each(function () {
                    if ($(this).is(':checkbox')) {
                        row[$(this).attr('name')] = $(this).is(':checked');
                    } else {
                        if($(this).attr('type') == 'file') {
                            row['image'] = $(this).attr('src');
                        } else {
                            row[$(this).attr('name')] = $(this).val();
                        }
                    }
                });
                row['index'] = index;
                index++;
                exam.push(row);
            });

            var data = {
                slots: exam,
                dummy: 'noting'
            };
            console.log(data);
            var dd = {
                data: JSON.stringify(data),
                loader: true,
                url: "<?php echo site_url(route_to('admin.class.sections.timeslots_create', $section->class->id)); ?>"
            }

            //Use fd as you would any form data.
            // Check /home/ben/HTML/Perfex/perfex_crm/modules/hrm/views/new.php:527 for an example
            server(dd);
        })

        function addTimeSlot() {
            $('#the-holder').append($('#xxHolder').html());
        }
    </script>
    <!-- End Modal stuff -->
    <form method="post" action="<?php echo current_url(); ?>" data-parsley-validate="">
        <input type="hidden" name="class" value="<?php echo $section->class->id; ?>">
        <input type="hidden" name="section" value="<?php echo $section->id; ?>">
        <div class="card-header clearfix">
            <button class="btn btn-sm btn-success pull-right float-right clearfix m-1" type="submit"> <i class="fa fa-plus-square"></i> Save Timetable</button>
            <button class="btn btn-sm btn-warning pull-right float-right clearfix m-1" type="button" data-toggle="modal" data-target=".edit_timeslots"> <i class="fa fa-edit"></i> Edit Time Slots</button>
        </div>
        <div class="table-responsive">
            <?php
            if($framework && count($framework) > 0) {
                ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Day \ Time</th>
                        <?php
                        foreach($framework as $time) {
                            ?>
                            <th><?php echo $time['time']; ?></th>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $days = json_decode(get_option('school_days', json_encode(['Mon', 'Tue', 'Wed', 'Thur', 'Fri'])), true);
                    foreach ($days as $day) {
                        ?>
                        <tr>
                            <th><?php echo $day; ?></th>
                            <?php
                            foreach ($framework as $time) {
                                ?>
                                <td>
                                    <?php
                                    if(isset($time['break']) && $time['break'] == true) {
                                        echo $time['label'];
                                    } else {
                                        $sub = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time['time']])->first();
                                        ?>
                                        <div class="form-group">
                                            <select name="timetable[<?php echo $day; ?>][<?php echo $time['time']; ?>]" class="form-control form-control-sm" >
                                                <option value="">Please select</option>
                                                <?php
                                                $subjects = $section->class->subjects();
                                                foreach ($subjects as $subject) {
                                                    ?>
                                                    <option <?php echo @$sub->subject->id == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <div class="alert alert-warning">
                    Time slots have not been created
                </div>
                <?php
            }
            ?>
        </div>
    </form>
</div>