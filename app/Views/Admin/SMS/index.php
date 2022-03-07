<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SMS</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('sms_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="h4 mb-0">Send SMS</h4>
                        </div>
                        <div class="card-body">
                            <form class="XajaxForm" loader="true" method="POST" action="<?php echo site_url(route_to('admin.messages.sms_send')) ?>">
                                <div class="form-group">
                                    <h4>Message<i class="text-danger">*</i></h4>
                                    <textarea class="form-control" name="message" rows="4" required><?php echo old('message') ?></textarea>
                                </div>
                                <h4>Recipients</h4>
                                <hr class="mt-0"/>
                                <div class="form-group">
                                    <label>Session</label>
                                    <select class="form-control select2" name="session" id="session" data-toggle="select2" onchange="getClass()">
                                        <option value="">Select School Session</option>
                                        <?php
                                        $sessions = (new \App\Models\Sessions())->findAll();
                                        foreach ($sessions as $session) {
                                            ?>
                                            <option <?php echo old('session', active_session()) == $session->id ? 'selected' : ''; ?> value="<?php echo $session->id; ?>"><?php echo $session->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <h5 class="d-block">Select Audience</h5>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><input type="checkbox" name="send_to_teachers" <?php echo old('send_to_teachers') == 1 ? 'checked' : ''; ?> value="1"/> Teachers</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><input type="checkbox" name="send_to_parents" <?php echo old('send_to_parents') == 1 ? 'checked' : ''; ?> value="1"/> Parents</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                                <div class="form-group">
                                    <label>Class (<small class="text-danger">Leave unchanged to send to all Classes</small>)</label>
                                    <select name="class" class="form-control select2" data-toggle="select2" id="class" onchange="getSections()">
                                        <option>Select Class</option>
                                        <option value="all">All Classes</option>
                                        <?php
                                        $classes = getSession()->classes->findAll();
                                        foreach ($classes as $class) {
                                            ?>
                                            <option <?php echo old('class') == $class->id ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Section (<small class="text-danger">Leave unchanged to send to all sections</small>)</label>
                                    <select name="section" class="form-control select2" data-toggle="select2" id="section">
                                        <option>All Sections</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><input type="checkbox" name="include_contacts" value="1"> Include Emergency Contacts</label>
                                </div>
                                <button class="btn btn-success btn-block" type="submit">Send SMS</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="h4 mb-0">Previous SMSes</h4>
                        </div>
                        <div class="card-body">
                            A list of previously sent SMSes<br/>
                            [-- PENDING --]
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var getClass = function () {
        $('#class').html('');
        $('#section').html('');
        var session = $('#session').val();
        if (session == '') {
            toast('Error', 'Please select a Session', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/session/') ?>" + session + "/classes",
                data: "session=" + session,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#class').html(data);
            });
        }
    };

    var getSections = function () {
        var classId = $('#class').val();
        if (classId == '') {
            //toast('Error', 'Please select a class', 'error');
        } else {
            if(classId != 'all') {
                var data = {
                    url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                    data: "session=" + classId,
                    loader: true
                };
                ajaxRequest(data, function (data) {
                    $('#section').html(data);
                });
            }
        }
    };
</script>