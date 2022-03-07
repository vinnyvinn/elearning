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
</style>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="fullcalendar-title h2 d-inline-block mb-0">Events Calendar</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
<!--                    <a class="btn btn-sm btn-warning" href="--><?php //echo site_url(route_to('admin.events.index')); ?><!--"> <i class="fa fa-wrench"></i> Manage Events</a>-->
                    <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">Month</a>
                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">Week</a>
                    <a href="#" class="btn btn-sm btn-neutral fc-today-button active" data-calendar-view="basicDay">Day</a>
                    <?php do_action('admin_events_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$events = (new \App\Models\Events())->calendarEvents();
?>
<script>
 var schoolEvents = <?php echo json_encode($events); ?>
</script>
<div class="container-fluid mt--6">
    <div class="card card-calendar">
        <div class="card-header">
            <h6 class="h3 mb-0">Calendar</h6>
        </div>
        <div class="card-body p-0">
            <div class="calendar" data-toggle="calendar" id="calendar"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-secondary" role="document">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <form class="ajaxForm" loader="true" method="post"
                      action="<?php echo site_url(route_to('admin.events.edit')); ?>">
                    <div class="form-group">
                        <label class="form-control-label">Event title</label>
                        <input type="text" name="name" class="form-control form-control-alternative edit-event--title" placeholder="Event Title">
                        <input type="hidden" class="form-control form-control-alternative edit-event--id" name="id">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Starting Date</label>
                        <input type="text" name="starting_date" class="form-control form-control-alternative edit-event--start datepicker" placeholder="Event Start" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Ending Date</label>
                        <input type="text" class="form-control form-control-alternative edit-event--end datepicker" placeholder="Event End" name="ending_date">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Class</label>
                        <input type="text" name="class" class="form-control form-control-alternative edit-event--class" placeholder="Class">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Section</label>
                        <input type="text"  name="section" class="form-control form-control-alternative edit-event--section" placeholder="Section">
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
                    <input type="hidden" class="edit-event--id">
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button class="btn btn-link ml-auto" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

<script>
$(".color-palet span").click(function () {
    $(".color-palet").find(".active").removeClass("active");
    $(this).addClass("active");
    $("#color").val($(this).attr("data-color"));
});

$(function (){

    $('.fc-today-button').removeAttr('disabled');
    $('.fc-today-button').removeClass('fc-state-disabled');
})




</script>