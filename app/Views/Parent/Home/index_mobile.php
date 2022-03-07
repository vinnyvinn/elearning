<link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
<!-- Page content -->
<div class="container-fluid mt-2">
<style>
    .col-md-2{
        width: 30% !important;
    }
    .pt5{
       padding-top: 5% !important;
       margin-right: 15% !important;
    }
    .mr10{
      margin-right: 15% !important;
    }
</style>

    <div class="row">
      <div class="card" style="width: 100%">
          <div class="card-body">
              <div id="theNavMenu">
                  <div class="p-3">
                      <a class="" href="<?php echo site_url(route_to('parent.calendar')); ?>">
                          <?php $file = get_option('website_logo', FALSE); ?>
                          <img src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>" class="navbar-brand-img logo" alt="...">
                      </a>
                      <div class="row mb10">
                          <div class="col-md-2 col-sm-4 col-xs-6 mr10">
                              <a href="<?php echo site_url(route_to('parent.calendar')); ?>"><img title="events calendar" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/calender.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 mr10">
                              <a href="<?php echo site_url(route_to('parent.requirements')); ?>"><img title="requirements" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/check list.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 mr10">
                              <a href="<?php echo site_url(route_to('parent.attendance')); ?>"><img title="attendance" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/attendance.png') ?>" /> </a>
                          </div>

                          </div>
                      <div class="row mb10">
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.payments')); ?>"><img title="payments" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/payment.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.schedules.regular')); ?>"><img title="regular class-schedule" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/class schedule.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.exams.index')); ?>"><img title="exams" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/exam schedule.png') ?>" /> </a>
                          </div>
                      </div>
                      <div class="row mb10">
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.student-exam-results')); ?>"><img title="exam results" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/exam result.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.assessment.results')); ?>"><img title="assessment results" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/continuous-improvement.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.student.certificate')); ?>"><img title="certificate" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/certificate.png') ?>" /> </a>
                          </div>
                      </div>
                      <div class="row mb10">
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.messages')); ?>"><img title="messages" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/message.png') ?>" /></a>
                          </div>
                      </div>
                      <hr/>

                      <h1 style="font-size: 35px">E-Learning</h1>
                      <div class="row mb10">
                          <div class="col-md-2 col-sm-4 col-xs-6 mr10">
                              <a href="<?php echo site_url(route_to('parent.continuous_assessments.assignment')); ?>"><img title="assignments" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/assignment.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 mr10">
                              <a href="<?php echo site_url(route_to('parent.continuous_assessments.classwork')); ?>"><img title="classwork" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/class work.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 mr10">
                              <a href="<?php echo site_url(route_to('parent.continuous_assessments.quiz')); ?>"><img title="quiz" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/quiz.png') ?>" /> </a>
                          </div>
                      </div>
                      <div class="row mb10">
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.continuous_assessments.exam')); ?>"><img title="exams" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/exam.png') ?>" /> </a>
                          </div>
                          <div class="col-md-2 col-sm-4 col-xs-6 pt5">
                              <a href="<?php echo site_url(route_to('parent.continuous_assessments.final_result')); ?>"><img title="final grade" class="img" style="max-height: 200px" src="<?php echo base_url('assets/img/menu/result.png') ?>" /> </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
