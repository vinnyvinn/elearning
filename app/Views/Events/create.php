<style>
    .color-tag {
        display: inline-block;
        width: 13px;
        height: 13px;
        margin: 2px 10px 0 0;
        transition: all 300ms ease;
        padding: 2%;
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
    .calendars-cmd-clear,.calendars-cmd-close,.calendars-cmd-next,.calendars-cmd-today,.calendars-cmd-prev{
        padding-top: 0%;
    }
    .calendars-month{
        width: 500px !important;
    }
    .popupDatepicker{
    width: 100%;
    height: 45px;
    border: 1px solid #dee2e6;
    }
</style>

<link type="text/css" href="<?php echo base_url('assets/vendor/css/calendar/redmond.calendars.picker.css')?>" rel="stylesheet" />

    <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/calendar/jquery.plugin.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/calendar/jquery.calendars.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/calendar/jquery.calendars.plus.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/calendar/jquery.calendars.picker.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/calendar/jquery.calendars.ethiopian.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/vendor/js/calendar/jquery.calendars.ethiopian-am.js')?>"></script>



<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <a href="<?php echo site_url(route_to('admin.events.index')); ?>">
                        <h6 class="h2 text-white d-inline-block mb-0">Events</h6>
                    </a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('admin_events_calendar_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header pb-0">
            <h3>New Event</h3>
        </div>
        <div class="card-body">
            <form class="ajaxForm" loader="true" method="post"
                  action="<?php echo site_url(route_to('admin.events.create')); ?>">
                    <div class="form-group">
                        <label for="sess">Event Name</label>
                        <input type="text" class="form-control" name="name"
                               value="<?php echo old('name'); ?>" required/>
                    </div>
                    <div class="form-group">
                        <label for="sess">Starting Date </label>
                        <input type="text" class="form-control popupDatepicker" name="starting_date"
                               required />
                    </div>
                    <div class="form-group">
                        <label for="sess">Ending Date</label>
                        <input type="text" class="form-control popupDatepicker" name="ending_date"
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

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
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
    $(function () {
        var calendar = $.calendars.instance('ethiopian','am');
        $('.popupDatepicker').calendarsPicker({calendar: calendar});
        $('.inlineDatepicker').calendarsPicker({calendar: calendar, onSelect: showDate});
    });

    function showDate(date) {
        alert('The date chosen is ' + date);
    }
</script>