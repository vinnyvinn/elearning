<?php namespace Config;

// Create a new instance of our RouteCollection class.
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('\App\Controllers\Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->add('/', '\App\Controllers\Home::index', ['as' => 'app.home']);
$routes->add('/c2b/confirmation/callback', '\App\Controllers\Home::confirmation', ['as' => 'app.confirmation']);
$routes->add('/c2b/validation/callback', '\App\Controllers\Home::validation', ['as' => 'app.validation']);
$routes->add('/trx-status/timeout/callback', '\App\Controllers\Home::timeout', ['as' => 'app.timeout']);
$routes->add('/trx-status/confirmation/callback', '\App\Controllers\Home::transactionStatus', ['as' => 'app.trx-status']);
$routes->add('/stk/confirmation/callback', '\App\Controllers\Home::stkConfirmation', ['as' => 'app.stk-confirmation']);
$routes->add('contact-us', '\App\Controllers\Home::contactUs', ['as' => 'app.contact_us']);
$routes->add('student-registration', '\App\Controllers\StudentRegistration::index', ['as' => 'app.student_registration']);
$routes->add('pre-student-registration', '\App\Controllers\StudentRegistration::pre_index', ['as' => 'app.pre_student_registration']);
$routes->add('teacher-recruitment', '\App\Controllers\TeacherRegistration::index', ['as' => 'app.teacher_registration']);
$routes->add('administration-recruitment', '\App\Controllers\TeacherRegistration::index_admin', ['as' => 'app.administration_registration']);
$routes->add('information', '\App\Controllers\TeacherRegistration::information', ['as' => 'app.information']);
$routes->get('notice-board', '\App\Controllers\Home::noticeBoard', ['as' => 'app.notice_board']);
//$routes->add('/', '\App\Controllers\Auth\Auth::index');

$routes->group('auth', ['namespace' => '\App\Controllers\Auth'], function ($routes) {
    $routes->add('/', 'Auth::index', ['as' => 'auth']);
    $routes->add('index', 'Auth::index', ['as' => 'auth.index']);
    $routes->add('login', 'Auth::login', ['as' => 'auth.login']);
    $routes->get('logout', 'Auth::logout', ['as' => 'auth.logout']);
    $routes->add('forgot-password', 'Auth::forgot_password', ['as' => 'auth.forgot_password']);
    $routes->add('reset-password/(:any)', 'Auth::reset_password/$1', ['as' => 'auth.reset_password']);
    $routes->add('activate/(:any)/(:any)', 'Auth::activate/$1/$2', ['as' => 'auth.activate']);
});

// Admin
$routes->group('admin', ['namespace' => '\App\Controllers\Admin'], function ($routes) {
    /** @var RouteCollection $routes */
    $routes->get('/', 'Dashboard::index', ['as' => 'admin.home']);
    $routes->get('dashboard', 'Dashboard::index', ['as' => 'admin.index']);
    $routes->post('notifications', 'Dashboard::getNotifications', ['as' => 'admin.notifications']);
    $routes->post('notifications/read', 'Dashboard::markRead', ['as' => 'admin.notifications.read']);
    $routes->get('dashboard/index', 'Dashboard::index', ['as' => 'admin.index.index']);

    // Users
    $routes->group('users', function ($routes) {
        /** @var RouteCollection $routes */
        // ADmins
        $routes->group('admins', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('', '\App\Controllers\Admin\Admins::index', ['as' => 'admin.admins.index']);
            $routes->get('create', '\App\Controllers\Admin\Admins::create', ['as' => 'admin.admins.create']);
            $routes->get('edit/(:num)', '\App\Controllers\Admin\Admins::edit/$1', ['as' => 'admin.admins.edit']);
            $routes->get('pdf', '\App\Controllers\Admin\Admins::pdf', ['as' => 'admin.admins.pdf']);
            $routes->get('export-excel', '\App\Controllers\Admin\Admins::exportExcel', ['as' => 'admin.admins.export-excel']);
            $routes->get('print-list', '\App\Controllers\Admin\Admins::print', ['as' => 'admin.admins.print-list']);
            $routes->post('save', '\App\Controllers\Admin\Admins::save', ['as' => 'admin.admins.save']);
            $routes->match(['GET', 'POST'], '(:num)/change-status', '\App\Controllers\Admin\Admins::delete/$1', ['as' => 'admin.admins.delete']);
            $routes->match(['GET', 'POST'], '(:num)/change-status', '\App\Controllers\Admin\Admins::delete/$1', ['as' => 'admin.admins.delete']);
        });

        // Students
        $routes->group('students', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->match(['GET','POST'],'/', '\App\Controllers\Students\Students::index', ['as' => 'admin.students.index']);
            $routes->match(['GET','POST'],'transcript', '\App\Controllers\Students\Students::transcript', ['as' => 'admin.students.transcript']);
            $routes->match(['GET','POST'],'transcript-years', '\App\Controllers\Students\Students::saveYears', ['as' => 'admin.students.transcript-years']);
            $routes->match(['GET','POST'],'departing', '\App\Controllers\Students\Students::departing', ['as' => 'admin.students.departing']);
            $routes->match(['GET','POST'],'depart-student', '\App\Controllers\Students\Students::departStd', ['as' => 'admin.students.depart.students']);
            $routes->match(['GET','POST'],'departure', '\App\Controllers\Students\Students::departure', ['as' => 'admin.students.departure']);
            $routes->post('update-letter/(:num)', '\App\Controllers\Students\Students::updateLetter/$1', ['as' => 'admin.students.update-letter']);
            $routes->post('update-transcript/(:num)', '\App\Controllers\Students\Students::updateTranscript/$1', ['as' => 'admin.students.update-transcript']);
            $routes->match(['GET', 'POST'], 'create', '\App\Controllers\Students\Students::create', ['as' => 'admin.students.create']);
            $routes->get('view/(:num)', '\App\Controllers\Students\Students::view/$1', ['as' => 'admin.students.view']);
            $routes->get('view/(:num)/exams', '\App\Controllers\Students\Students::exams/$1', ['as' => 'admin.students.view.exams']);
            $routes->get('view/(:num)/assignments', '\App\Controllers\Students\Students::assignments/$1', ['as' => 'admin.students.view.assignments']);
            $routes->get('view/(:num)/fees', '\App\Controllers\Students\Students::fees/$1', ['as' => 'admin.students.view.fees']);
            $routes->get('view/(:num)/results/(:num)', '\App\Controllers\Students\Students::results/$1/$2', ['as' => 'admin.students.view.results']);
            $routes->match(['GET', 'POST'], 'edit/(:num)', '\App\Controllers\Students\Students::edit/$1', ['as' => 'admin.students.edit']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Students\Students::delete/$1', ['as' => 'admin.students.delete']);
            $routes->match(['GET', 'POST'],'export', '\App\Controllers\Students\Students::export', ['as' => 'admin.students.export']);
            $routes->match(['GET', 'POST'],'print-list', '\App\Controllers\Students\Students::printList', ['as' => 'admin.students.print-list']);


            // Student Admission PDF
            $routes->get('(:num)/admission-form', '\App\Controllers\Students\Admission::admissionForm/$1', ['as' => 'admin.students.form.download']);
            $routes->get('(:num)/admission-id', '\App\Controllers\Students\Admission::admissionID/$1', ['as' => 'admin.students.form.download_id']);
            $routes->get('(:num)/admission-id-print', '\App\Controllers\Students\Admission::admissionIDPrint/$1', ['as' => 'admin.students.form.print_student_id']);
            $routes->get('admission-bulk-print/(:any)/(:any)', '\App\Controllers\Students\Admission::admissionIDPrintBulk/$1/$2 ', ['as' => 'admin.students.form.print_bulk_id']);
            $routes->get('admission-compact-form/(:any)/(:any)', '\App\Controllers\Students\Admission::admissionCompact/$1/$2 ', ['as' => 'admin.students.form.compact']);
            $routes->get('admission-bulk-pdf/(:any)/(:any)', '\App\Controllers\Students\Admission::admissionFormBulk/$1/$2 ', ['as' => 'admin.students.form.bulk_pdf']);
            $routes->get('student-list-pdf/(:any)/(:any)', '\App\Controllers\Students\Admission::studentListPdf/$1/$2 ', ['as' => 'admin.students.list.pdf']);
            $routes->get('admission-bulk-download/(:any)/(:any)', '\App\Controllers\Students\Admission::admissionIDPdfBulk/$1/$2 ', ['as' => 'admin.students.form.pdf_bulk_id']);
            $routes->get('print-transcript/(:any)/', '\App\Controllers\Students\Students::printTranscript/$1 ', ['as' => 'admin.students.print-transcript']);
            $routes->get('print-letter/(:any)/', '\App\Controllers\Students\Students::printLetter/$1 ', ['as' => 'admin.students.print-letter']);
        });
        // Teachers
        $routes->group('teachers', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Teachers\Teachers::index', ['as' => 'admin.teachers.index']);
            $routes->get('pdf', '\App\Controllers\Teachers\Teachers::pdf', ['as' => 'admin.teachers.pdf']);
            $routes->get('export-excel', '\App\Controllers\Teachers\Teachers::exportExcel', ['as' => 'admin.teachers.export-excel']);
            $routes->get('print-list', '\App\Controllers\Teachers\Teachers::print', ['as' => 'admin.teachers.print-list']);
            $routes->match(['GET', 'POST'], 'create', '\App\Controllers\Teachers\Teachers::create', ['as' => 'admin.teachers.create']);
            $routes->get('view/(:num)', '\App\Controllers\Teachers\Teachers::view/$1', ['as' => 'admin.teachers.view']);
            $routes->match(['GET', 'POST'], 'edit/(:num)', '\App\Controllers\Teachers\Teachers::edit/$1', ['as' => 'admin.teachers.edit']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Teachers\Teachers::delete/$1', ['as' => 'admin.teachers.delete']);

            $routes->post('(:num)/send-sms', '\App\Controllers\Teachers\Teachers::sendSMS/$1', ['as' => 'admin.teachers.send_sms']);
            $routes->post('add-subject/(:num)', '\App\Controllers\Teachers\Teachers::assignSubject/$1', ['as' => 'admin.teachers.assign_subject']);
        });
        // Parents
        $routes->group('parents', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Parents\Parents::index', ['as' => 'admin.parents.index']);
            $routes->get('pdf', '\App\Controllers\Parents\Parents::pdf', ['as' => 'admin.parents.pdf']);
            $routes->get('export-excel', '\App\Controllers\Parents\Parents::exportExcel', ['as' => 'admin.parents.export-excel']);
            $routes->get('print-list', '\App\Controllers\Parents\Parents::print', ['as' => 'admin.parents.print-list']);
            $routes->match(['GET', 'POST'], 'create', '\App\Controllers\Parents\Parents::create', ['as' => 'admin.parents.create']);
            $routes->get('view/(:num)', '\App\Controllers\Parents\Parents::view/$1', ['as' => 'admin.parents.view']);
            $routes->get('edit/(:num)', '\App\Controllers\Parents\Parents::edit/$1', ['as' => 'admin.parents.edit']);
            $routes->match(['GET','POST'],'delete/(:num)', '\App\Controllers\Parents\Parents::delete/$1', ['as' => 'admin.parents.delete']);

        });

        $routes->get('/', 'Users::index', ['as' => 'admin.users.index']);
        $routes->add('create', 'Users::create', ['as' => 'admin.users.create']);
        $routes->get('profile/(:num)', 'Users::view/$1', ['as' => 'admin.users.profile']);
        $routes->add('profile/(:num)/edit', 'Users::edit/$1', ['as' => 'admin.users.edit']);
        $routes->get('profile/(:num)/delete', 'Users::delete/$1', ['as' => 'admin.users.delete']);

        // Users.Roles
        $routes->get('roles', 'Roles::index', ['as' => 'admin.users.roles.index']);
        $routes->match(['GET', 'POST'], 'roles/(:num)/capabilities', 'Roles::capabilities/$1', ['as' => 'admin.users.roles.capabilities']);
        $routes->match(['GET', 'POST'], 'roles/create', 'Roles::create', ['as' => 'admin.users.roles.create']);
        $routes->match(['GET', 'POST'], 'roles/(:num)/edit', 'Roles::edit/$1', ['as' => 'admin.users.roles.edit']);
        $routes->add('roles/(:num)/delete', 'Roles::delete/$1', ['as' => 'admin.users.roles.delete']);
    });

    $routes->group('registration', function ($routes){
        /** @var RouteCollection $routes */
        $routes->match(['GET', 'POST'], 'teachers/create', '\App\Controllers\Teachers\Teachers::create', ['as' => 'admin.registration.teachers.create']);
        $routes->match(['GET', 'POST'], 'students/create', '\App\Controllers\Students\Students::create', ['as' => 'admin.registration.students.create']);
        $routes->get('groups-of-five', '\App\Controllers\Academic\Groups', ['as' => 'admin.registration.groups']);
        $routes->get('groups-of-five-print/(:num)', '\App\Controllers\Academic\Groups::print/$1', ['as' => 'admin.registration.groups.print']);
        $routes->get('groups-of-five-pdf/(:num)', '\App\Controllers\Academic\Groups::pdf/$1', ['as' => 'admin.registration.groups.pdf']);
        $routes->get('groups-of-five-excel/(:num)', '\App\Controllers\Academic\Groups::exportExcel/$1', ['as' => 'admin.registration.groups.excel']);
        $routes->post('get-groups', '\App\Controllers\Academic\Groups::getGroups', ['as' => 'admin.academic.groups.get']);

        $routes->get('class-registration', '\App\Controllers\School\Classes::index', ['as' => 'admin.registration.classes.index']);
        $routes->get('class-registration-print', '\App\Controllers\School\Classes::print', ['as' => 'admin.registration.classes.print']);
        $routes->get('class-registration-pdf', '\App\Controllers\School\Classes::pdf', ['as' => 'admin.registration.classes.pdf']);
        $routes->get('class-registration-excel', '\App\Controllers\School\Classes::exportExcel', ['as' => 'admin.registration.classes.excel']);
        $routes->get('subject-registration', '\App\Controllers\Classes\Subjects::index', ['as' => 'admin.registration.subjects.index']);
        $routes->get('subject-registration-pdf', '\App\Controllers\Classes\Subjects::pdf', ['as' => 'admin.registration.subjects.pdf']);
        $routes->get('subject-registration-excel', '\App\Controllers\Classes\Subjects::exportExcel', ['as' => 'admin.registration.subjects.excel']);
        $routes->get('subject-registration-print', '\App\Controllers\Classes\Subjects::print', ['as' => 'admin.registration.subjects.print']);

        $routes->group('departments', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Academic\Departments::index', ['as' => 'admin.registration.departments']);
            $routes->get('print', '\App\Controllers\Academic\Departments::print', ['as' => 'admin.registration.departments.print']);
            $routes->get('pdf', '\App\Controllers\Academic\Departments::pdf', ['as' => 'admin.registration.departments.pdf']);
            $routes->get('excel', '\App\Controllers\Academic\Departments::exportExcel', ['as' => 'admin.registration.departments.excel']);
            $routes->post('create', '\App\Controllers\Academic\Departments::create', ['as' => 'admin.registration.departments.create']);
            $routes->match(['GET', 'POST'], 'delete/(:num)', '\App\Controllers\Academic\Departments::delete/$1', ['as' => 'admin.registration.departments.delete']);
            $routes->post('ajax-dept-subjects', '\App\Controllers\Academic\Departments::getSubjects', ['as' => 'admin.registration.department.ajax_get_subjects']);
            $routes->post('manage-dept-subjects', '\App\Controllers\Academic\Departments::manageSubjects', ['as' => 'admin.registration.departments.manage_subjects']);
            $routes->match(['GET', 'POST'], 'remove-dept-subject/(:num)', '\App\Controllers\Academic\Departments::removeSubject/$1', ['as' => 'admin.registration.departments.remove_subject']);
        });

        $routes->group('online', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('teachers-recruitment', '\App\Controllers\OnlineRegistration\Teachers::index', ['as' => 'admin.registration.online.teacher']);
            $routes->get('teachers-recruitment-pdf', '\App\Controllers\OnlineRegistration\Teachers::pdf', ['as' => 'admin.teachers-recruitment.pdf']);
            $routes->get('teachers-recruitment-excel', '\App\Controllers\OnlineRegistration\Teachers::exportExcel', ['as' => 'admin.teachers-recruitment.export-excel']);
            $routes->get('teachers-recruitment-print', '\App\Controllers\OnlineRegistration\Teachers::print', ['as' => 'admin.teachers-recruitment.print']);
            $routes->get('admin-recruitment', '\App\Controllers\OnlineRegistration\Admin::index', ['as' => 'admin.registration.online.admin']);
            $routes->get('admin-recruitment-pdf', '\App\Controllers\OnlineRegistration\Admin::pdf', ['as' => 'admin.registration.online.admin.pdf']);
            $routes->get('admin-recruitment-excel', '\App\Controllers\OnlineRegistration\Admin::exportExcel', ['as' => 'admin.registration.online.admin.excel']);
            $routes->get('admin-recruitment-print', '\App\Controllers\OnlineRegistration\Admin::print', ['as' => 'admin.registration.online.admin.print']);
            $routes->match(['GET','POST'],'existing-students', '\App\Controllers\OnlineRegistration\Students::existing', ['as' => 'admin.registration.online.students.existing']);
            $routes->match(['GET','POST'],'new-students', '\App\Controllers\OnlineRegistration\Students::newStudents', ['as' => 'admin.registration.online.students.new_students']);
            $routes->get('new-students/(:num)/view', '\App\Controllers\OnlineRegistration\Students::viewNewStudent/$1', ['as' => 'admin.registration.online.students.new_students.view']);
            $routes->get('new-students-pdf', '\App\Controllers\OnlineRegistration\Students::pdf', ['as' => 'admin.registration.online.students.new_students.pdf']);
            $routes->get('new-students-print', '\App\Controllers\OnlineRegistration\Students::print', ['as' => 'admin.registration.online.students.new_students.print']);
            $routes->get('new-students-export-excel', '\App\Controllers\OnlineRegistration\Students::exportExcel', ['as' => 'admin.registration.online.students.new_students.export-excel']);
            $routes->get('existing-students-pdf', '\App\Controllers\OnlineRegistration\Students::pdfExisting', ['as' => 'admin.registration.online.students.existing_students.pdf']);
            $routes->get('existing-students-print', '\App\Controllers\OnlineRegistration\Students::printExisting', ['as' => 'admin.registration.online.students.existing_students.print']);
            $routes->get('existing-students-export-excel', '\App\Controllers\OnlineRegistration\Students::exportExcelExisting', ['as' => 'admin.registration.online.students.existing_students.export-excel']);
            $routes->get('new-students/(:num)/delete', '\App\Controllers\OnlineRegistration\Students::deleteNewStudent/$1', ['as' => 'admin.registration.online.students.new_students.delete']);
            $routes->post('new-students/(:num)/register', '\App\Controllers\OnlineRegistration\Students::registerStudent/$1', ['as' => 'admin.registration.online.students.new_students.register']);
            $routes->get('existing-students/(:num)/view', '\App\Controllers\OnlineRegistration\Students::viewExistingStudent/$1', ['as' => 'admin.registration.online.students.existing_students.view']);
            $routes->get('existing-students/(:num)/delete', '\App\Controllers\OnlineRegistration\Students::deleteExistingStudent/$1', ['as' => 'admin.registration.online.students.existing_students.delete']);
            $routes->get('teachers-recruitment/(:num)/view', '\App\Controllers\OnlineRegistration\Teachers::view/$1', ['as' => 'admin.registration.online.teachers.new_teachers.view']);
            $routes->get('admin-recruitment/(:num)/view', '\App\Controllers\OnlineRegistration\Admin::view/$1', ['as' => 'admin.registration.online.new_admin.view']);
            $routes->get('teachers-recruitment/(:num)/register', '\App\Controllers\OnlineRegistration\Teachers::registerTeacher/$1', ['as' => 'admin.registration.online.teachers.new_teachers.register']);
            $routes->get('teachers-recruitment/(:num)/delete', '\App\Controllers\OnlineRegistration\Teachers::deleteTeacher/$1', ['as' => 'admin.registration.online.teachers.new_teachers.delete']);
            $routes->get('admin-recruitment/(:num)/delete', '\App\Controllers\OnlineRegistration\Admin::deleteAdmin/$1', ['as' => 'admin.registration.online.new_admin.delete']);

            $routes->get('download-slip/(:num)', '\App\Controllers\OnlineRegistration\Students::downloadSlip/$1', ['as' => 'admin.registration.online.students.download_slip']);
        });
    });

    $routes->group('academic', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->group('schedules', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('regular-schedule', '\App\Controllers\Academic\Schedules::regular', ['as' => 'admin.academic.regular_schedule']);
            $routes->post('get-regular-schedule', '\App\Controllers\Academic\Schedules::getRegularSchedule', ['as' => 'admin.academic.get_regular_schedule']);
            $routes->get('(:num)/print-regular-schedule', '\App\Controllers\Academic\Schedules::print/$1', ['as' => 'admin.academic.print_regular_schedule']);
            $routes->get('(:num)/download-regular-schedule', '\App\Controllers\Academic\Schedules::pdf/$1', ['as' => 'admin.academic.download_regular_schedule']);

            $routes->get('asp-schedule', '\App\Controllers\Academic\Schedules::asp', ['as' => 'admin.academic.asp_schedule']);
            $routes->post('get-asp-schedule', '\App\Controllers\Academic\Schedules::getAspSchedule', ['as' => 'admin.academic.get_asp_schedule']);
            $routes->get('(:num)/print-asp-schedule', '\App\Controllers\Academic\Schedules::printAspSchedule/$1', ['as' => 'admin.academic.print_asp_schedule']);

            $routes->get('teachers-schedule', '\App\Controllers\Academic\TeachersSchedule::index', ['as' => 'admin.academic.teachers_schedule']);
            $routes->post('get-teachers-schedule', '\App\Controllers\Academic\TeachersSchedule::getSchedule', ['as' => 'admin.academic.get_teachers_schedule']);
            $routes->get('get-teachers-schedule-pdf/(:num)/(:num)', '\App\Controllers\Academic\TeachersSchedule::pdf/$1/$2', ['as' => 'admin.academic.get_teachers_schedule.pdf']);
            $routes->get('get-teachers-schedule-print/(:num)/(:num)', '\App\Controllers\Academic\TeachersSchedule::print/$1/$2', ['as' => 'admin.academic.get_teachers_schedule.print']);
            $routes->post('get-teachers-subjects', '\App\Controllers\Academic\TeachersSchedule::getSubjects', ['as' => 'admin.academic.get_teachers_subjects']);
            $routes->post('get-teacher-grades', '\App\Controllers\Academic\TeachersSchedule::getTeacherGrades', ['as' => 'admin.academic.get_teachers_grades']);

            $routes->get('teachers-asp-schedule', '\App\Controllers\Academic\TeachersSchedule::aspSchedule', ['as' => 'admin.academic.teachers_asp_schedule']);
            $routes->get('teachers-asp-schedule-pdf/(:num)', '\App\Controllers\Academic\TeachersSchedule::aspSchedulePdf/$1', ['as' => 'admin.academic.teachers_asp_schedule_pdf']);
            $routes->post('get-teachers-asp-schedule', '\App\Controllers\Academic\TeachersSchedule::getAspSchedule', ['as' => 'admin.academic.get_teachers_asp_schedule']);
        });

        $routes->group('semester', function ($routes) {
            $routes->get('ranking', '\App\Controllers\Academic\Ranking::index', ['as' => 'admin.academic.semester_ranking']);
            $routes->get('ranking-semester-pdf/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Ranking::semesterPdf/$1/$2/$3', ['as' => 'admin.academic.semester_ranking_pdf']);
            $routes->get('ranking-semester-print/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Ranking::semesterPrint/$1/$2/$3', ['as' => 'admin.academic.semester_ranking_print']);
            $routes->get('ranking-semester-average-pdf/(:num)', '\App\Controllers\Academic\Ranking::averagePdf/$1', ['as' => 'admin.academic.semester_average_pdf']);
            $routes->get('ranking-semester-average-print/(:num)', '\App\Controllers\Academic\Ranking::averagePrint/$1', ['as' => 'admin.academic.semester_average_print']);
            $routes->get('ranking-semester-average-kg-pdf/(:num)', '\App\Controllers\Academic\Ranking::averageKgPdf/$1', ['as' => 'admin.academic.semester_average_kg_pdf']);
            $routes->get('results/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Ranking::results/$1/$2/$3', ['as' => 'admin.academic.semester_results']);
            $routes->get('result-analysis', '\App\Controllers\Academic\Ranking::analysis', ['as' => 'admin.academic.semester_analysis']);
            $routes->get('result-analysis-others', '\App\Controllers\Academic\Ranking::analysisOthers', ['as' => 'admin.academic.semester_analysis_others']);
            $routes->post('ranking/get', '\App\Controllers\Academic\Ranking::get', ['as' => 'admin.academic.semester_ranking.get']);
            $routes->post('ranking/get-analysis', '\App\Controllers\Academic\Ranking::getAnalysis', ['as' => 'admin.academic.semester_analysis.get']);
            $routes->post('ranking/get-analysis-others', '\App\Controllers\Academic\Ranking::getAnalysisOthers', ['as' => 'admin.academic.semester_analysis_others.get']);
        });

        $routes->group('quarter', function ($routes) {
            $routes->get('ranking', '\App\Controllers\Academic\QuarterRanking::index', ['as' => 'admin.academic.quarter_ranking']);
            $routes->get('result-analysis', '\App\Controllers\Academic\QuarterRanking::analysis', ['as' => 'admin.academic.quarter_analysis']);
            $routes->post('ranking/get', '\App\Controllers\Academic\QuarterRanking::get', ['as' => 'admin.academic.quarter_ranking.get']);
            $routes->post('ranking/get-analysis', '\App\Controllers\Academic\QuarterRanking::getAnalysis', ['as' => 'admin.academic.quarter_analysis.get']);
        });


        $routes->group('certificate', function ($routes) {
            $routes->get('/', '\App\Controllers\Academic\Certificate::index', ['as' => 'admin.academic.yearly_certificate']);
            $routes->post('students', '\App\Controllers\Academic\Certificate::getStudents', ['as' => 'admin.academic.yearly_certificate.students']);
            $routes->get('(:num)/view', '\App\Controllers\Academic\Certificate::certificate/$1', ['as' => 'admin.academic.yearly_certificate.view']); //2
            $routes->get('(:num)/report-card', '\App\Controllers\Academic\Certificate::report/$1', ['as' => 'admin.academic.yearly_certificate.report-card']); //1
            $routes->get('(:num)/download-cert', '\App\Controllers\Academic\Certificate::downloadCert/$1', ['as' => 'admin.academic.yearly_certificate.download-cert']);
            $routes->get('(:num)/evaluation', '\App\Controllers\Academic\Certificate::evaluation/$1', ['as' => 'admin.academic.yearly_certificate.evaluation']); //3
            $routes->get('(:num)/evaluation-summary', '\App\Controllers\Academic\Certificate::evaluation_summary/$1', ['as' => 'admin.academic.yearly_certificate.evaluation_summary']); //4
            $routes->post('save_student_evaluation', '\App\Controllers\Academic\Certificate::save_evaluation', ['as' => 'admin.academic.yearly_certificate.save_student_evaluation']);
            $routes->post('save_student_evaluation_kg', '\App\Controllers\Academic\Certificate::save_kg_evaluation', ['as' => 'admin.academic.yearly_certificate.save_student_evaluation_kg']);
            $routes->post('save_homeroom_teacher_comment', '\App\Controllers\Academic\Certificate::save_homeroom_teacher/$1', ['as' => 'admin.academic.yearly_certificate.save_homeroom_teacher_comment']);
            $routes->post('director_sign', '\App\Controllers\Academic\Certificate::director_sign', ['as' => 'admin.academic.yearly_certificate.director_sign']);
            $routes->post('save_yearly_evaluation', '\App\Controllers\Academic\Certificate::save_yearly_evaluation', ['as' => 'admin.academic.yearly_certificate.save_yearly_evaluation']);
            $routes->get('(:num)/print', '\App\Controllers\Academic\Certificate::print/$1', ['as' => 'admin.academic.yearly_certificate.print']);
            //$routes->get('(:num)/print', '\App\Controllers\Teacher\Certificate::print/$1', ['as' => 'admin.academic.yearly_certificate.print']);
        });

        $routes->get('payment/(:num)/class/(:num)', '\App\Controllers\Academic\Payments::view/$1/$2', ['as' => 'admin.academic.view_payment']);
        $routes->get('payments', '\App\Controllers\Academic\Payments::index', ['as' => 'admin.academic.payments']);
        $routes->get('payments-pdf', '\App\Controllers\Academic\Payments::pdf', ['as' => 'admin.academic.pdf']);
        $routes->get('payments-excel', '\App\Controllers\Academic\Payments::exportExcel', ['as' => 'admin.academic.excel']);
        $routes->get('payments-print', '\App\Controllers\Academic\Payments::print', ['as' => 'admin.academic.print']);
        $routes->post('save-payment', '\App\Controllers\Academic\Payments::save', ['as' => 'admin.academic.save_payment']);
        $routes->match(['GET', 'POST'], 'delete-payment/(:num)', '\App\Controllers\Academic\Payments::delete/$1', ['as' => 'admin.academic.delete_payment']);
        $routes->match(['GET', 'POST'], '(:num)/clear-payment/(:num)', '\App\Controllers\Academic\Payments::clear_payment/$1/$2', ['as' => 'admin.payments.clear_payment']);
        $routes->match(['GET', 'POST'], 'payment/(:num)/download-slip', '\App\Controllers\Academic\Payments::download_slip/$1', ['as' => 'admin.payments.download_slip']);

        $routes->get('requirements', '\App\Controllers\Academic\Requirements::index', ['as' => 'admin.academic.requirements']);
        $routes->get('requirements-pdf', '\App\Controllers\Academic\Requirements::pdf', ['as' => 'admin.academic.requirements.pdf']);
        $routes->get('requirements-excel', '\App\Controllers\Academic\Requirements::exportExcel', ['as' => 'admin.academic.requirements.excel']);
        $routes->get('requirements-print', '\App\Controllers\Academic\Requirements::print', ['as' => 'admin.academic.requirements.print']);
        $routes->get('requirement/(:num)/class/(:num)', '\App\Controllers\Academic\Requirements::view/$1/$2', ['as' => 'admin.academic.view_requirement']);
        $routes->post('save-requirement', '\App\Controllers\Academic\Requirements::save', ['as' => 'admin.academic.save_requirement']);
        $routes->post('update-teacher-requirement', '\App\Controllers\Academic\Requirements::updateTeacherComment', ['as' => 'admin.academic.update_teacher_comment']);
        $routes->match(['GET', 'POST'], 'delete-requirement/(:num)', '\App\Controllers\Academic\Requirements::delete/$1', ['as' => 'admin.academic.delete_requirement']);

        $routes->get('lesson-plan', '\App\Controllers\Academic\LessonPlan::index', ['as' => 'admin.academic.lesson_plan']);
        $routes->post('get-lesson-plan', '\App\Controllers\Academic\LessonPlan::getLessonPlan', ['as' => 'admin.academic.get_lesson_plan']);

        $routes->group('exams', function($routes) {
            $routes->get('exam-list', '\App\Controllers\Academic\Exams::index', ['as' => 'admin.academic.exam_list']);
            $routes->get('exam-list-pdf', '\App\Controllers\Academic\Exams::pdf', ['as' => 'admin.academic.exam_list_pdf']);
            $routes->get('exam-list-excel', '\App\Controllers\Academic\Exams::exportExcel', ['as' => 'admin.academic.exam_list_excel']);
            $routes->get('exam-list-print', '\App\Controllers\Academic\Exams::print', ['as' => 'admin.academic.exam_list_print']);
            $routes->get('exam-schedule', '\App\Controllers\Academic\Exams::schedule', ['as' => 'admin.academic.exam_schedule']);
            $routes->post('get-exam-schedule', '\App\Controllers\Academic\Exams::getSchedule', ['as' => 'admin.academic.get_exam_schedule']);
            $routes->get('print-exam-schedule/(:num)/(:num)', '\App\Controllers\Academic\Exams::printSchedule/$1/$2', ['as' => 'admin.academic.exam_schedule.print']);
            $routes->get('pdf-exam-schedule/(:num)/(:num)', '\App\Controllers\Academic\Exams::pdfSchedule/$1/$2', ['as' => 'admin.academic.exam_schedule.pdf']);
            $routes->get('edit-exam-schedule/(:num)/(:num)', '\App\Controllers\Academic\Exams::editSchedule/$1/$2', ['as' => 'admin.academic.edit_exam_schedule']);
            $routes->post('save-time-slots', '\App\Controllers\Academic\Exams::saveTimeSlots', ['as' => 'admin.academic.save_time_slots']);

            $routes->get('exam-results', '\App\Controllers\Academic\Exams::results', ['as' => 'admin.academic.exam.results']);
            $routes->get('exam-results-pdf/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::resultsPdf/$1/$2/$3', ['as' => 'admin.academic.exam.results.pdf']);
            $routes->get('exam-results-print/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::resultsPrint/$1/$2/$3', ['as' => 'admin.academic.exam.results.print']);
            $routes->get('exam-results-top-three', '\App\Controllers\Academic\Exams::resultsTop3', ['as' => 'admin.academic.exam.results-top-three']);
            $routes->get('exam-results-top-five', '\App\Controllers\Academic\Exams::resultsTop5', ['as' => 'admin.academic.exam.results-top-five']);
            $routes->get('exam-results-top-ten', '\App\Controllers\Academic\Exams::resultsTop10', ['as' => 'admin.academic.exam.results-top-ten']);
            $routes->get('show-result/(:num)/(:num)', '\App\Controllers\Academic\Exams::show/$1/$2', ['as' => 'admin.academic.exam.show.result']);
            $routes->get('(:num)/print-student-results/(:num)', '\App\Controllers\Academic\Exams::printStudentResults/$1/$2', ['as' => 'admin.academic.exam.print_student_results']);
            $routes->post('get-exam-results', '\App\Controllers\Academic\Exams::getResults', ['as' => 'admin.academic.exam.get_results']);
            $routes->post('get-exam-results-top-three', '\App\Controllers\Academic\Exams::getResultsTop3', ['as' => 'admin.academic.exam.get_results_top_three']);
            $routes->post('get-exam-results-top-five', '\App\Controllers\Academic\Exams::getResultsTop5', ['as' => 'admin.academic.exam.get_results_top_five']);
            $routes->post('get-exam-results-top-ten', '\App\Controllers\Academic\Exams::getResultsTop10', ['as' => 'admin.academic.exam.get_results_top_ten']);
            $routes->get('print-results-top-three/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::printResultsTop3/$1/$2/$3', ['as' => 'admin.academic.exam.print_results_top_three']);
            $routes->get('download-results-top-three/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::downloadResultsTop3/$1/$2/$3', ['as' => 'admin.academic.exam.download_results_top_three']);
            $routes->get('download-results-top-five/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::downloadResultsTop5/$1/$2/$3', ['as' => 'admin.academic.exam.download_results_top_five']);
            $routes->get('download-results-top-ten/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::downloadResultsTop10/$1/$2/$3', ['as' => 'admin.academic.exam.download_results_top_ten']);
            $routes->get('print-results-top-five/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::printResultsTop5/$1/$2/$3', ['as' => 'admin.academic.exam.print_results_top_five']);
            $routes->get('print-results-top-ten/(:num)/(:num)/(:num)', '\App\Controllers\Academic\Exams::printResultsTop10/$1/$2/$3', ['as' => 'admin.academic.exam.print_results_top_ten']);
        });

        $routes->group('assessment', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Assessments\Assessments::index', ['as' => 'admin.academic.assessments']);
            $routes->post('get', '\App\Controllers\Assessments\Assessments::getAssessmentsAjax', ['as' => 'admin.academic.assessments.get']);
            $routes->post('save', '\App\Controllers\Assessments\Assessments::save', ['as' => 'admin.academic.assessments.save']);

            $routes->get('assessments', '\App\Controllers\Assessments\ManualAssessments::index', ['as' => 'admin.academic.assessments.manual.index']);
            $routes->post('assessments/get', '\App\Controllers\Assessments\ManualAssessments::get', ['as' => 'admin.academic.assessments.manual.get_the_assessments']);
            $routes->post('assessments/get-q', '\App\Controllers\Assessments\ManualAssessments::getQ', ['as' => 'admin.academic.assessments.manual.get_the_assessments_q']);
            $routes->post('assessments/save-ca', '\App\Controllers\Assessments\ManualAssessments::saveCA', ['as' => 'admin.academic.assessments.manual.save_cas']);
            $routes->post('assessments/save-ca-q', '\App\Controllers\Assessments\ManualAssessments::saveCAQ', ['as' => 'admin.academic.assessments.manual.save_cas_q']);
            $routes->post('assessments/save-ca-total', '\App\Controllers\Assessments\ManualAssessments::saveCATotal', ['as' => 'admin.academic.assessments.manual.save_cas_total']);
            $routes->post('assessments/save-ca-total-q', '\App\Controllers\Assessments\ManualAssessments::saveCATotalQ', ['as' => 'admin.academic.assessments.manual.save_cas_total_q']);
            $routes->post('assessments/maunal/save-results', '\App\Controllers\Assessments\ManualAssessments::saveResults', ['as' => 'admin.academics.assessments.manual.save_results']);
            $routes->post('assessments/maunal/save-results-q', '\App\Controllers\Assessments\ManualAssessments::saveResultsQ', ['as' => 'admin.academics.assessments.manual.save_results_q']);
        });

        $routes->group('notes', function ($routes){
            /** @var RouteCollection $routes */
            $routes->match(['GET', 'POST'], '/', '\App\Controllers\Academic\Notes::notes', ['as' => 'admin.academic.notes']);
            $routes->match(['GET', 'POST'], 'e-library', '\App\Controllers\Academic\Notes::elibraryNotes', ['as' => 'admin.academic.notes.elibrary']);
            $routes->match(['GET', 'POST'], 'get-notes', '\App\Controllers\Academic\Notes::getNotes', ['as' => 'admin.academic.get_notes']);
            $routes->match(['GET', 'POST'], 'get-notes-elibrary', '\App\Controllers\Academic\Notes::getNotesE', ['as' => 'admin.academic.get_notes_e']);
        });

        $routes->group('school/transport', function($routes){
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Admin\Transport::index', ['as' => 'admin.academic.transport.index']);
            $routes->post('save', '\App\Controllers\Admin\Transport::save', ['as' => 'admin.transport.save']);
            $routes->get('transport-pdf', '\App\Controllers\Admin\Transport::transportPdf', ['as' => 'admin.transport.pdf']);
            $routes->get('transport-print', '\App\Controllers\Admin\Transport::transportPrint', ['as' => 'admin.transport.print']);
            $routes->get('transport-excel', '\App\Controllers\Admin\Transport::exportExcel', ['as' => 'admin.transport.excel']);
            $routes->get('transport-view-pdf/(:num)', '\App\Controllers\Admin\Transport::transportViewPdf/$1', ['as' => 'admin.transport.view.pdf']);
            $routes->match(['GET', 'POST'], 'delete/(:num)', '\App\Controllers\Admin\Transport::delete/$1', ['as' => 'admin.transport.delete']);
            $routes->match(['GET', 'POST'], 'view/(:num)', '\App\Controllers\Admin\Transport::view/$1', ['as' => 'admin.transport.view']);
        });
    });

    $routes->group('home-school', function ($routes){
        /** @var RouteCollection $routes */
        $routes->group('assignments', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Academic\Assignments::index', ['as' => 'admin.academic.assignments']);
            $routes->get('writing', '\App\Controllers\Academic\Assignments::writing', ['as' => 'admin.academic.assignments.writing']);
            $routes->get('writing-submissions/(:num)/assignment/(:num)/class', '\App\Controllers\Academic\Assignments::writingSubmissions/$1/$2', ['as' => 'admin.academic.assignments.writing.submissions']);
            $routes->get('create-assignment', '\App\Controllers\Academic\Assignments::createAssignment', ['as' => 'admin.academic.assignments.create']);
            $routes->get('view/(:num)', '\App\Controllers\Academic\Assignments::view/$1', ['as' => 'admin.academic.assignments.view']);
            $routes->get('view/student/(:num)/assignment/(:num)', '\App\Controllers\Academic\Assignments::viewSubmitted/$1/$2', ['as' => 'admin.academic.assignments.view_submitted']);
            $routes->post('section-assignments', '\App\Controllers\Academic\Assignments::getAssignments', ['as' => 'admin.academic.assignments.get']);
            $routes->post('written-assignments', '\App\Controllers\Academic\Assignments::getWrAssignments', ['as' => 'admin.academic.assignments.written.get']);
            $routes->post('save', '\App\Controllers\Academic\Assignments::save', ['as' => 'admin.academic.new_assignment']);
            $routes->post('save-writing', '\App\Controllers\Academic\Assignments::saveWr', ['as' => 'admin.academic.new_assignment_writing']);
            $routes->post('mark-writing', '\App\Controllers\Academic\Assignments::markWr', ['as' => 'admin.academic.mark_writing']);
            $routes->get('download-assignment/(:num)', '\App\Controllers\Academic\Assignments::download/$1', ['as' => 'admin.academic.assignment.download']);

            $routes->get('(:num)/edit', '\App\Controllers\Academic\Assignments::edit/$1', ['as' => 'admin.academic.assignments.edit']);
            $routes->post('(:num)/update-assignment', '\App\Controllers\Academic\Assignments::saveEdit/$1', ['as' => 'admin.academic.assignments.save_edit']);
            $routes->post('(:num)/view-assignment', '\App\Controllers\Academic\Assignments::viewAssignment/$1', ['as' => 'admin.academic.assignments.view_assignment']);
            $routes->get('(:num)/print-assignment', '\App\Controllers\Academic\Assignments::printAssignment/$1', ['as' => 'admin.academic.assignments.print_assignment']);
            $routes->get('(:num)/publish', '\App\Controllers\Academic\Assignments::markPublished/$1', ['as' => 'admin.academic.assignments.publish']);
            $routes->get('(:num)/draft', '\App\Controllers\Academic\Assignments::markDraft/$1', ['as' => 'admin.academic.assignments.draft']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Academic\Assignments::delete/$1', ['as' => 'admin.academic.assignments.delete']);
        });

        $routes->group('classwork', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Assessments\Classwork::index', ['as' => 'admin.academic.assessments.class_work']);
            $routes->post('create-classwork', '\App\Controllers\Assessments\Classwork::create', ['as' => 'admin.academic.assessments.class_work.create']);
            $routes->post('(:num)/save-edit-classwork', '\App\Controllers\Assessments\Classwork::saveEditClasswork/$1', ['as' => 'admin.academic.assessments.class_work.save_edit']);
            $routes->get('new-classwork', '\App\Controllers\Assessments\Classwork::newClasswork', ['as' => 'admin.academic.assessments.class_work.new_classwork']);
            $routes->get('(:num)/view', '\App\Controllers\Assessments\Classwork::view/$1', ['as' => 'admin.academic.assessments.class_work.view']);
            $routes->get('(:num)/results', '\App\Controllers\Assessments\Classwork::results/$1', ['as' => 'admin.academic.assessments.class_work.results']);
            $routes->get('(:num)/edit', '\App\Controllers\Assessments\Classwork::editClasswork/$1', ['as' => 'admin.academic.assessments.class_work.edit']);
            $routes->post('(:num)/view-classwork', '\App\Controllers\Assessments\Classwork::viewClasswork/$1', ['as' => 'admin.academic.assessments.class_work.view_classwork']);
            $routes->get('(:num)/print-classwork', '\App\Controllers\Assessments\Classwork::printClasswork/$1', ['as' => 'admin.academic.assessments.class_work.print_classwork']);
            $routes->post('create-new-classwork', '\App\Controllers\Assessments\Classwork::newClassworkCreate', ['as' => 'admin.academic.assessments.class_work.new_classwork_create']);
            $routes->post('save-new-classwork', '\App\Controllers\Assessments\Classwork::saveNewClasswork', ['as' => 'admin.academic.assessments.class_work.new_classwork_save']);
            $routes->post('get-classwork', '\App\Controllers\Assessments\Classwork::get', ['as' => 'admin.academic.assessments.class_work.get']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Assessments\Classwork::delete/$1', ['as' => 'admin.academic.assessments.class_work.delete']);

            $routes->get('(:num)/publish', '\App\Controllers\Assessments\Classwork::markPublished/$1', ['as' => 'admin.academic.assessments.class_work.publish']);
            $routes->get('(:num)/draft', '\App\Controllers\Assessments\Classwork::markDraft/$1', ['as' => 'admin.academic.assessments.class_work.draft']);
        });

            $routes->group('quizes', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Assessments\Quizes::index', ['as' => 'admin.academic.assessments.quizes.index']);
            $routes->post('create-category', '\App\Controllers\Assessments\Quizes::create', ['as' => 'admin.academic.assessments.quizes.create']);
            $routes->post('get', '\App\Controllers\Assessments\Quizes::get', ['as' => 'admin.academic.assessments.quizes.get']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Assessments\Quizes::delete/$1', ['as' => 'admin.academic.assessments.quizes.delete']);
            $routes->get('create-quiz', '\App\Controllers\Assessments\Quizes::newQuiz', ['as' => 'admin.academic.assessments.quizes.new_quiz']);
            $routes->post('(:num)/create-new-quiz', '\App\Controllers\Assessments\Quizes::createNewQuiz/$1', ['as' => 'admin.academic.assessments.quizes.create_new_quiz']);
            $routes->post('save-new-quiz', '\App\Controllers\Assessments\Quizes::saveNewQuiz', ['as' => 'admin.academic.assessments.quizes.new_quiz_save']);
            $routes->get('(:num)/view', '\App\Controllers\Assessments\Quizes::view/$1', ['as' => 'admin.academic.assessments.quizes.view']);
            $routes->get('(:num)/results', '\App\Controllers\Assessments\Quizes::results/$1', ['as' => 'admin.academic.assessments.quizes.results']);
            $routes->get('(:num)/edit', '\App\Controllers\Assessments\Quizes::edit/$1', ['as' => 'admin.academic.assessments.quizes.edit_quiz']);
            $routes->post('(:num)/save-edit', '\App\Controllers\Assessments\Quizes::saveEditQuiz/$1', ['as' => 'admin.academic.assessments.quizes.save_edit_quiz']);
            $routes->post('(:num)/view-quiz', '\App\Controllers\Assessments\Quizes::viewQuiz/$1', ['as' => 'admin.academic.assessments.quizes.view_quiz']);
            $routes->get('(:num)/print-quiz', '\App\Controllers\Assessments\Quizes::printQuiz/$1', ['as' => 'admin.academic.assessments.quizes.print_quiz']);

            $routes->get('(:num)/publish', '\App\Controllers\Assessments\Quizes::markPublished/$1', ['as' => 'admin.academic.assessments.quizes.publish']);
            $routes->get('(:num)/draft', '\App\Controllers\Assessments\Quizes::markDraft/$1', ['as' => 'admin.academic.assessments.quizes.draft']);
        });

        $routes->group('exams', function ($routes){
            $routes->get('/', '\App\Controllers\Assessments\Exam::index', ['as' => 'admin.academic.assessments.exam']);
            //$routes->post('create-category', '\App\Controllers\Assessments\Exam::create', ['as' => 'admin.academic.assessments.exam.create']);
            $routes->post('get-exam', '\App\Controllers\Assessments\Exam::get', ['as' => 'admin.academic.assessments.exam.get']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Assessments\Exam::delete/$1', ['as' => 'admin.academic.assessments.exams.delete']);
            $routes->get('create-exam', '\App\Controllers\Assessments\Exam::newExam', ['as' => 'admin.academic.assessments.exams.new_exam']);
            //$routes->post('create-new-exam', '\App\Controllers\Assessments\Exam::createNewExam', ['as' => 'admin.academic.assessments.exams.create_new_exam']);
            $routes->post('save-new-exam', '\App\Controllers\Assessments\Exam::saveNewExam', ['as' => 'admin.academic.assessments.exams.new_exam_save']);
            $routes->get('(:num)/view-exam', '\App\Controllers\Assessments\Exam::view/$1', ['as' => 'admin.academic.assessments.exams.view']);
            $routes->get('(:num)/results', '\App\Controllers\Assessments\Exam::results/$1', ['as' => 'admin.academic.assessments.exams.results']);
            $routes->get('(:num)/edit', '\App\Controllers\Assessments\Exam::editExam/$1', ['as' => 'admin.academic.assessments.exams.edit']);
            $routes->post('(:num)/save-edit', '\App\Controllers\Assessments\Exam::saveEditExam/$1', ['as' => 'admin.academic.assessments.exams.save_edit']);
            $routes->post('(:num)/view-exam-item', '\App\Controllers\Assessments\Exam::viewExam/$1', ['as' => 'admin.academic.assessments.exams.view_exam']);
            $routes->get('(:num)/print-exam-item', '\App\Controllers\Assessments\Exam::printExam/$1', ['as' => 'admin.academic.assessments.exams.print_exam']);

            $routes->get('(:num)/publish', '\App\Controllers\Assessments\Exam::markPublished/$1', ['as' => 'admin.academic.assessments.exams.publish']);
            $routes->get('(:num)/draft', '\App\Controllers\Assessments\Exam::markDraft/$1', ['as' => 'admin.academic.assessments.exams.draft']);
        });

        $routes->group('calculate', function($routes) {
            $routes->get('continuous-assessment', '\App\Controllers\Assessments\CalculateCA::index', ['as' => 'admin.academic.assessments.calculate_ca']);
            $routes->match(['GET', 'POST'], 'get-cats', '\App\Controllers\Assessments\CalculateCA::getCats', ['as' => 'admin.academic.assessments.get_cats']);
            $routes->post('calculate', '\App\Controllers\Assessments\CalculateCA::calculate', ['as' => 'admin.academic.assessments.calculate_cats']);

            $routes->group('final-grade', function ($routes) {
                $routes->get('/', '\App\Controllers\Assessments\CalculateFG::index', ['as' => 'admin.academic.assessments.calculate_fg']);
                $routes->match(['GET', 'POST'], 'get-items', '\App\Controllers\Assessments\CalculateFG::getItems', ['as' => 'admin.academic.assessments.calculate_fg.items']);
                $routes->post('calculate', '\App\Controllers\Assessments\CalculateFG::calculate', ['as' => 'admin.academic.assessments.calculate_fg.calculate']);
            });
        });

        $routes->group('results', function ($routes) {
            $routes->get('/', '\App\Controllers\Assessments\Results::index', ['as' => 'admin.academic.assessments.home-school-results']);
            $routes->match(['GET', 'POST'], 'get', '\App\Controllers\Assessments\Results::getCats', ['as' => 'admin.academic.assessments.home-school-results.get_cats']);
        });

        $routes->group('rank', function ($routes) {
            $routes->get('/', '\App\Controllers\Assessments\Rank::index', ['as' => 'admin.academic.assessments.rank']);
            $routes->match(['GET', 'POST'], 'get-rank', '\App\Controllers\Assessments\Rank::getRank', ['as' => 'admin.academic.assessments.get_rank']);
        });
    });

    // System
    $routes->group('settings', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', 'Settings::index', ['as' => 'admin.settings.home']);
        $routes->get('index', 'Settings::index', ['as' => 'admin.settings.index']);
        $routes->get('student-evaluation', 'Settings::evaluation', ['as' => 'admin.settings.student-evaluation']);
        $routes->match(['GET', 'POST'],'background-image', 'Settings::background', ['as' => 'admin.settings.background-image']);
        $routes->get('kg-evaluation', 'Settings::kg_evaluation', ['as' => 'admin.settings.kg']);
        $routes->get('kg-categories', 'Settings::category', ['as' => 'admin.settings.kg-categories']);
        $routes->get('answer-options', 'Settings::answerOption', ['as' => 'admin.settings.answer-options']);
        $routes->get('kg-sub-categories', 'Settings::sub_category', ['as' => 'admin.settings.kg-sub-categories']);
        $routes->get('student-id', 'Settings::studentID', ['as' => 'admin.settings.student-id']);
        $routes->post('save_id', 'Settings::save_id', ['as' => 'admin.settings.save_id']);
        $routes->get('grading', 'Settings::grading', ['as' => 'admin.settings.grading']);
        $routes->post('save-grading', 'Settings::saveGrading', ['as' => 'admin.settings.save-grading']);
        $routes->get('promotion-rule', 'Settings::promotionRule', ['as' => 'admin.settings.promotion-rule']);
        $routes->post('save_promotion_rule', 'Settings::savePromotionRule', ['as' => 'admin.settings.save_promotion_rule']);
        $routes->get('no-of-exams', 'Settings::examsNo', ['as' => 'admin.settings.no-of-exams']);
        $routes->post('save_exams_no', 'Settings::saveExamsNo', ['as' => 'admin.settings.save_exams_no']);
        $routes->post('save_evaluation', 'Settings::save_evaluation', ['as' => 'admin.settings.save_evaluation']);
        $routes->post('save_kg_evaluation', 'Settings::save_kg_evaluation', ['as' => 'admin.settings.save_kg_evaluation']);
        $routes->post('save_category', 'Settings::save_category', ['as' => 'admin.settings.save_category']);
        $routes->post('save_option', 'Settings::save_option', ['as' => 'admin.settings.save_option']);
        $routes->post('save_sub_category', 'Settings::save_sub_category', ['as' => 'admin.settings.save_sub_category']);
        $routes->get('student-comment', 'Settings::comment', ['as' => 'admin.settings.student-comment']);
        $routes->post('save_comment', 'Settings::save_comment', ['as' => 'admin.settings.save_comment']);
        $routes->post('edit/(:num)', 'Settings::edit_evaluation/$1', ['as' => 'admin.settings.evaluation.update']);
        $routes->post('edit-kg/(:num)', 'Settings::edit_kg_evaluation/$1', ['as' => 'admin.settings.kg.update']);
        $routes->post('edit-category/(:num)', 'Settings::edit_kg_category/$1', ['as' => 'admin.settings.category.update']);
        $routes->post('edit-option/(:num)', 'Settings::edit_option/$1', ['as' => 'admin.settings.option.update']);
        $routes->post('edit-sub-category/(:num)', 'Settings::edit_kg_sub_category/$1', ['as' => 'admin.settings.sub_category.update']);
        $routes->post('edit/comment(:num)', 'Settings::edit_comment/$1', ['as' => 'admin.settings.comment.update']);
        $routes->match(['GET', 'POST'], 'delete-evaluation/(:num)', 'Settings::deleteEvaluation/$1', ['as' => 'admin.settings.evaluation.delete']);
        $routes->match(['GET', 'POST'], 'delete-option/(:num)', 'Settings::deleteOption/$1', ['as' => 'admin.settings.options.delete']);
        $routes->match(['GET', 'POST'], 'delete-kg-evaluation/(:num)', 'Settings::deleteKGEvaluation/$1', ['as' => 'admin.settings.kg.delete']);
        $routes->match(['GET', 'POST'], 'delete-category/(:num)', 'Settings::deleteCategory/$1', ['as' => 'admin.settings.category.delete']);
        $routes->match(['GET', 'POST'], 'delete-sub-category/(:num)', 'Settings::deleteSubCategory/$1', ['as' => 'admin.settings.sub_category.delete']);
        $routes->match(['GET', 'POST'], 'delete-comment/(:num)', 'Settings::deleteComment/$1', ['as' => 'admin.settings.comment.delete']);
        $routes->match(['GET', 'POST'], 'get-sub-categories', 'Settings::getSubCategories', ['as' => 'admin.settings.kg.sub-categories']);

        $routes->post('site', 'Settings::site', ['as' => 'admin.settings.site']);
        $routes->post('email', 'Settings::email', ['as' => 'admin.settings.email']);

        // SMS
        $routes->group('sms', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->match(['GET', 'POST'], '/', 'SmsSettings::index', ['as' => 'admin.settings.sms']);
            $routes->post('save', 'SmsSettings::save', ['as' => 'admin.settings.sms_settings_save']);
            $routes->post('test-gateway/(:any)', 'SmsSettings::testGateway/$1', ['as' => 'admin.settings.sms_test_gateway']);
            $routes->match(['GET', 'POST'],'activate-gateway/(:any)', 'SmsSettings::activateGateway/$1', ['as' => 'admin.settings.sms_activate_gateway']);
            $routes->match(['GET', 'POST'],'deactivate-gateway/(:any)', 'SmsSettings::deactivateGateway/$1', ['as' => 'admin.settings.sms_deactivate_gateway']);
        });

        // Modules
        $routes->get('modules', 'Modules::index', ['as' => 'admin.modules.index']);
        $routes->get('modules/activate/(:any)', 'Modules::activate/$1', ['as' => 'admin.modules.activate']);
        $routes->get('modules/deactivate/(:any)', 'Modules::deactivate/$1', ['as' => 'admin.modules.deactivate']);
        $routes->get('modules/delete/(:any)', 'Modules::delete/$1', ['as' => 'admin.modules.delete']);
        $routes->post('modules/upload', 'Modules::upload', ['as' => 'admin.modules.upload']);

        $routes->group('updates', function ($routes) {
            $routes->get('/', 'Updates::index', ['as' => 'admin.settings.updates']);
            $routes->get('run-update', 'Updates::runUpdates', ['as' => 'admin.settings.updates.run']);
        });
    });

    // School
    $routes->group('school', function ($routes) {
        /** @var RouteCollection $routes */
        // Sessions
        $routes->group('sessions', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\School\Sessions::index', ['as' => 'admin.sessions.index']);
            $routes->post('create', '\App\Controllers\School\Sessions::create', ['as' => 'admin.sessions.create']);
            $routes->post('update', '\App\Controllers\School\Sessions::update', ['as' => 'admin.sessions.update']);
            $routes->match(['GET', 'POST'], 'delete/(:num)', '\App\Controllers\School\Sessions::delete/$1', ['as' => 'admin.sessions.delete']);
            $routes->match(['GET', 'POST'], 'activate/(:num)', '\App\Controllers\School\Sessions::activate/$1', ['as' => 'admin.sessions.activate']);
        });

        // Classes
        $routes->group('classes', ['namespace' => '\App\Controllers\School'], function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', 'Classes::index', ['as' => 'admin.school.classes.index']);
            $routes->post('create', 'Classes::create', ['as' => 'admin.school.classes.create']);
            $routes->post('edit/(:num)', 'Classes::edit/$1', ['as' => 'admin.school.classes.update']);
            $routes->post('delete/(:num)', 'Classes::delete/$1', ['as' => 'admin.school.classes.delete']);
        });

        //Semester/Terms
        $routes->group('semesters', ['namespace' => '\App\Controllers\School'], function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', 'Semesters::index', ['as' => 'admin.school.semesters.index']);
            $routes->post('create', 'Semesters::create', ['as' => 'admin.school.semesters.create']);
            $routes->match(['GET', 'POST'], 'delete/(:num)', 'Semesters::delete/$1', ['as' => 'admin.school.semesters.delete']);
        });

        //Quarters/Terms
        $routes->group('quarters', ['namespace' => '\App\Controllers\School'], function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', 'Quarters::index', ['as' => 'admin.school.quarters.index']);
            $routes->post('create', 'Quarters::create', ['as' => 'admin.school.quarters.create']);
            $routes->get('semester/(:num)/session', 'Quarters::createSemester/$1', ['as' => 'admin.school.quarters.create_semester']);
            $routes->post('save-semester', 'Quarters::saveSemester', ['as' => 'admin.school.quarters.save_semester']);
            $routes->match(['GET', 'POST'], 'delete/(:num)', 'Quarters::delete/$1', ['as' => 'admin.school.quarters.delete']);
        });
    });

    // Classes management
    $routes->group('classes', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('index', '\App\Controllers\Classes\Classes::index', ['as' => 'admin.classes.index']);
        $routes->get('view/(:num)/overview', '\App\Controllers\Classes\Classes::view/$1', ['as' => 'admin.classes.view']);
        $routes->get('view/(:num)/notes', '\App\Controllers\Classes\Classes::notes/$1', ['as' => 'admin.classes.notes']);
        $routes->get('view/(:num)/students', '\App\Controllers\Classes\Classes::students/$1', ['as' => 'admin.classes.students']);
        $routes->get('view/(:num)/events', '\App\Controllers\Classes\Classes::events/$1', ['as' => 'admin.classes.events']);
        $routes->get('view/(:num)/exam-schedule', '\App\Controllers\Classes\Classes::examSchedule/$1', ['as' => 'admin.classes.exam_schedule']);
        $routes->get('view/(:num)/routines', '\App\Controllers\Classes\Classes::routines/$1', ['as' => 'admin.classes.routines']);
        $routes->get('view/(:num)/requirements', '\App\Controllers\Classes\Classes::requirements/$1', ['as' => 'admin.classes.requirements']);
        $routes->get('view/(:num)/assignments', '\App\Controllers\Classes\Classes::assignments/$1', ['as' => 'admin.classes.assignments']);

        $routes->post('view/(:num)/subjects/add', '\App\Controllers\Classes\Classes::addSubject/$1', ['as' => 'admin.classes.subjects.add_subject']);
        $routes->post('view/(:num)/subjects/edit', '\App\Controllers\Classes\Classes::editSubject/$1', ['as' => 'admin.classes.subjects.edit_subject']);
        $routes->post('view/(:num)/subjects/delete', '\App\Controllers\Classes\Classes::deleteSubject/$1', ['as' => 'admin.classes.subjects.delete_subject']);

        //Sections
        $routes->group('sections', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->post('create', '\App\Controllers\Classes\Sections::create', ['as' => 'admin.sections.create']);
            $routes->post('update/(:num)', '\App\Controllers\Classes\Sections::update/$1', ['as' => 'admin.sections.edit']);
            $routes->post('delete/(:num)', '\App\Controllers\Classes\Sections::delete/$1', ['as' => 'admin.sections.delete']);

            $routes->get('view/(:num)/overview', '\App\Controllers\Classes\Sections::view/$1', ['as' => 'admin.class.sections.view']);
            $routes->get('view/(:num)/students', '\App\Controllers\Classes\Sections::students/$1', ['as' => 'admin.class.sections.students']);
            $routes->get('view/(:num)/timetable', '\App\Controllers\Classes\Sections::timetable/$1', ['as' => 'admin.class.sections.timetable']);
            $routes->match(['GET', 'POST'], 'view/(:num)/create-timetable', '\App\Controllers\Classes\Sections::createTimetable/$1', ['as' => 'admin.class.sections.timetable_create']);
            $routes->match(['GET', 'POST'], 'view/(:num)/edit-timeslots', '\App\Controllers\Classes\Sections::createTimeslots/$1', ['as' => 'admin.class.sections.timeslots_create']);

            // After School Schedule
            $routes->get('view/(:num)/asp-schedule', '\App\Controllers\Classes\Sections::aspTimetable/$1', ['as' => 'admin.class.sections.asp_schedule']);
            $routes->match(['GET', 'POST'], 'view/(:num)/create-asp-schedule', '\App\Controllers\Classes\Sections::createAspTimetable/$1', ['as' => 'admin.class.sections.create_asp_schedule']);

            // Class Groups
            $routes->get('view/(:num)/class-groups', '\App\Controllers\Classes\Groups::view/$1', ['as' => 'admin.class.sections.groups']);
            $routes->post('view/(:num)/create-group', '\App\Controllers\Classes\Groups::createGroup/$1', ['as' => 'admin.class.sections.groups.create']);
            $routes->post('view/group/get-members', '\App\Controllers\Classes\Groups::getMembers', ['as' => 'admin.class.sections.groups.members']);
            $routes->post('view/(:num)/delete', '\App\Controllers\Classes\Groups::deleteGroup/$1', ['as' => 'admin.class.sections.groups.delete']);
        });

        // Assessments
        $routes->group('assessment', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Assessments\Assessments::index', ['as' => 'admin.classes.assessments']);
            $routes->post('get', '\App\Controllers\Assessments\Assessments::getAssessmentsAjax', ['as' => 'admin.classes.assessments.get']);
            $routes->post('save', '\App\Controllers\Assessments\Assessments::save', ['as' => 'admin.classes.assessments.save']);
        });

        // Subjects
        $routes->group('subjects', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('(:num)/section/(:num)/overview', '\App\Controllers\Classes\Subjects::view/$1/$2', ['as' => 'admin.subjects.view']);
            $routes->get('(:num)/unassign', '\App\Controllers\Classes\Subjects::unassignSubject/$1', ['as' => 'admin.subjects.unassign']);
            $routes->match(['GET', 'POST'], '(:num)/section/(:num)/notes', '\App\Controllers\Classes\Subjects::notes/$1/$2', ['as' => 'admin.subjects.notes']);
            $routes->match(['GET', 'POST'], '(:num)/section/(:num)/assignments', '\App\Controllers\Classes\Subjects::assignments/$1/$2', ['as' => 'admin.subjects.assignments']);

            //Create
            $routes->get('index', '\App\Controllers\Classes\Subjects::index', ['as' => 'admin.subjects.index']);
            $routes->get('/', '\App\Controllers\Classes\Subjects::index', ['as' => 'admin.subjects.index']);
            $routes->post('create', '\App\Controllers\Classes\Subjects::create', ['as' => 'admin.subjects.create']);
            $routes->post('update/(:num)', '\App\Controllers\Classes\Subjects::update/$1', ['as' => 'admin.subjects.edit']);
            $routes->post('delete/(:num)', '\App\Controllers\Classes\Subjects::delete/$1', ['as' => 'admin.subjects.delete']);

            $routes->match(['GET', 'POST'], 'notes/(:num)/delete', '\App\Controllers\Classes\Subjects::delete_note/$1', ['as' => 'admin.subject.notes.delete']);
            $routes->match(['GET', 'POST'], 'assignment/(:num)/delete', '\App\Controllers\Classes\Subjects::delete_assignment/$1', ['as' => 'admin.subject.assignments.delete']);

            //View and mark assignments
            $routes->match(['GET', 'POST'], '(:num)/assignments/(:num)', '\App\Controllers\Classes\Assignments::view/$1/$2', ['as' => 'admin.subjects.assignments.view']);
            $routes->match(['GET', 'POST'], '(:num)/assignments/(:num)/award-marks', '\App\Controllers\Classes\Assignments::award_marks/$1/$2', ['as' => 'admin.subjects.assignments.award_marks']);
            $routes->match(['GET', 'POST'], '(:num)/assignments/(:num)/delete-submission', '\App\Controllers\Classes\Assignments::delete_submission/$1/$2', ['as' => 'admin.subjects.assignments.delete_submission']);

            //Subject teacher
            $routes->match(['GET', 'POST'], '(:num)/add-teacher', '\App\Controllers\Classes\Subjects::add_teacher/$1', ['as' => 'admin.subjects.add_teacher']);

            // Lesson plans
            $routes->match(['GET', 'POST'], '(:num)/subject/(:num)/lesson-plan/create', '\App\Controllers\Classes\Subjects::createLessonPlan/$1/$2', ['as' => 'admin.subjects.lesson_plan.create']);
            $routes->match(['GET', 'POST'], '(:num)/subject/(:num)/lesson-plan/update/(:num)/(:num)', '\App\Controllers\Classes\Subjects::updateLessonPlan/$1/$2/$3/$4', ['as' => 'admin.subjects.lesson_plan.update']);
            $routes->match(['GET', 'POST'], '(:num)/subject/(:num)/lesson-plan/download/(:num)/(:num)', '\App\Controllers\Classes\Subjects::downloadLessonPlan/$1/$2/$3/$4', ['as' => 'admin.subjects.lesson_plan.download']);
        });

        // Promotion
        $routes->get('promotion', '\App\Controllers\Classes\Promotion::index', ['as' => 'admin.promotion']);
        $routes->get('move', '\App\Controllers\Classes\Promotion::move', ['as' => 'admin.move']);
        $routes->post('promotion/promote', '\App\Controllers\Classes\Promotion::promote', ['as' => 'admin.promotion.promote']);
        $routes->match(['GET','POST'],'move-student', '\App\Controllers\Classes\Promotion::moveStudent', ['as' => 'admin.classes.move-student']);
    });

    //Exams
    $routes->group('exams', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', '\App\Controllers\Exams\Exams::index', ['as' => 'admin.exams.index']);
        $routes->post('create', '\App\Controllers\Exams\Exams::create', ['as' => 'admin.exams.create']);
        $routes->match(['GET', 'POST'], 'delete/(:num)', '\App\Controllers\Exams\Exams::delete/$1', ['as' => 'admin.exams.delete']);

        $routes->get('(:num)/view', '\App\Controllers\Exams\View::index/$1', ['as' => 'admin.exams.view.index']);
        $routes->get('(:num)/results', '\App\Controllers\Exams\View::results/$1', ['as' => 'admin.exams.view.results']);

        $routes->add('(:num)/timetable/(:num)', '\App\Controllers\Exams\View::timetable/$1/$2', ['as' => 'admin.exams.view.timetables.ajax']);
        $routes->add('(:num)/results/(:num)', '\App\Controllers\Exams\View::classResults/$1/$2', ['as' => 'admin.exams.view.results.ajax']);

        $routes->add('(:num)/record-results', '\App\Controllers\Exams\Exams::recordResults/$1', ['as' => 'admin.exam.record_results']);
        $routes->post('(:num)/get-students', '\App\Controllers\Exams\Exams::getStudents/$1', ['as' => 'admin.exam.record_results.get_students']);
        $routes->post('(:num)/save-results', '\App\Controllers\Exams\Exams::saveResults/$1', ['as' => 'admin.exam.record.results.form']);

        // CATs
        $routes->group('cats', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Exams\Cats::index', ['as' => 'admin.exams.cats.index']);
            $routes->get('(:num)/view', '\App\Controllers\Exams\Cats::view/$1', ['as' => 'admin.exams.cats.view.index']);
            $routes->get('(:num)/results', '\App\Controllers\Exams\Cats::results/$1', ['as' => 'admin.exams.cats.view.results']);
            $routes->match(['GET', 'POST'], '(:num)/delete', '\App\Controllers\Exams\Cats::delete/$1', ['as' => 'admin.exams.cats.delete']);
        });
    });

    //Accounting
    $routes->group('accounting', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', '\App\Controllers\Accounting\Accounting::index', ['as' => 'admin.accounting.index']);
        $routes->post('create', '\App\Controllers\Accounting\Accounting::create', ['as' => 'admin.accounting.fee.create']);
        $routes->post('delete/(:num)', '\App\Controllers\Accounting\Accounting::delete/$1', ['as' => 'admin.accounting.fee.delete']);
        $routes->get('information', '\App\Controllers\Accounting\Accounting::history', ['as' => 'admin.accounting.fee.history']);
        $routes->get('collect', '\App\Controllers\Accounting\Accounting::collect', ['as' => 'admin.accounting.fee.collect']);
        $routes->post('collect/student/(:num)', '\App\Controllers\Accounting\Accounting::addCollection/$1', ['as' => 'admin.accounting.fee.add_collection']);
    });

    // Attendance
    $routes->group('attendance', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('students', '\App\Controllers\Attendance\Attendance::students', ['as' => 'admin.attendance.students']);
        $routes->get('students-pdf', '\App\Controllers\Attendance\Attendance::studentsPdf', ['as' => 'admin.attendance.students.pdf']);
        $routes->get('students-print', '\App\Controllers\Attendance\Attendance::studentsPrint', ['as' => 'admin.attendance.students.print']);
        $routes->get('teachers-pdf', '\App\Controllers\Attendance\Attendance::teachersPdf', ['as' => 'admin.attendance.teachers.pdf']);
        $routes->get('teachers-print', '\App\Controllers\Attendance\Attendance::teachersPrint', ['as' => 'admin.attendance.teachers.print']);
        $routes->get('students-monthly', '\App\Controllers\Attendance\Attendance::studentsMonthly', ['as' => 'admin.attendance.students_monthly']);
        $routes->get('students-monthly-pdf/(:num)/(:num)/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::studentsMonthlyPdf/$1/$2/$3/$4', ['as' => 'admin.attendance.students_monthly_pdf']);
        $routes->get('students-monthly-print/(:num)/(:num)/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::studentsMonthlyPrint/$1/$2/$3/$4', ['as' => 'admin.attendance.students_monthly_print']);
        $routes->get('teachers-monthly-excel/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::exportExcelTeachersMonthly/$1/$2', ['as' => 'admin.attendance.teachers_monthly_excel']);
        $routes->get('teachers-monthly-pdf/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::teachersMonthlyPdf/$1/$2', ['as' => 'admin.attendance.teachers_monthly_pdf']);
        $routes->get('teachers-monthly-print/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::teachersMonthlyPrint/$1/$2', ['as' => 'admin.attendance.teachers_monthly_print']);
        $routes->get('students-monthly-excel/(:num)/(:num)/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::exportExcelStudentsMonthly/$1/$2/$3/$4', ['as' => 'admin.attendance.students_monthly_excel']);
        $routes->get('show-attendance/(:num)', '\App\Controllers\Attendance\Attendance::show/$1', ['as' => 'admin.attendance.show']);
        $routes->get('students/record', '\App\Controllers\Attendance\Attendance::recordStudents', ['as' => 'admin.attendance.record_students']);
        $routes->get('teachers', '\App\Controllers\Attendance\Attendance::teachers', ['as' => 'admin.attendance.teachers']);
        $routes->get('teachers-monthly', '\App\Controllers\Attendance\Attendance::teachersMonthly', ['as' => 'admin.attendance.teachers_monthly']);

        $routes->post('students/get-students-ajax', '\App\Controllers\Attendance\Attendance::getStudentsAjax', ['as' => 'admin.attendance.students.get_ajax']);
        $routes->post('students/get-attendance-ajax', '\App\Controllers\Attendance\Attendance::getAttendanceAjax', ['as' => 'admin.attendance.students.get_ajax_attendance']);
        $routes->post('students/get-teacher-attendance-ajax', '\App\Controllers\Attendance\Attendance::getTeacherAttendanceAjax', ['as' => 'admin.attendance.students.get_ajax_teacher_attendance']);
        $routes->post('students/post-students-ajax', '\App\Controllers\Attendance\Attendance::postStudentsAjax', ['as' => 'admin.attendance.students.post_ajax']);
        $routes->post('students/post-teachers-ajax', '\App\Controllers\Attendance\Attendance::postTeachersAjax', ['as' => 'admin.attendance.students.post_teacher_ajax']);
        $routes->post('students/save-student', '\App\Controllers\Attendance\Attendance::saveStudent', ['as' => 'admin.attendance.saveStudent']);

        //Teachers
        $routes->post('teachers/get-teachers', '\App\Controllers\Attendance\Attendance::getTeachers', ['as' => 'admin.attendance.get_teachers.ajax']);
        $routes->post('teachers/get-teachers-monthly', '\App\Controllers\Attendance\Attendance::getTeachersMonthly', ['as' => 'admin.attendance.get_teachers.ajax_monthly']);
        $routes->get('teachers/record', '\App\Controllers\Attendance\Attendance::recordTeachers', ['as' => 'admin.attendance.record_teachers']);
        $routes->post('students/save-teacher', '\App\Controllers\Attendance\Attendance::saveTeacher', ['as' => 'admin.attendance.saveTeacher']);
        $routes->post('students/get-teachers-ajax', '\App\Controllers\Attendance\Attendance::getTeachersAjax', ['as' => 'admin.attendance.teachers.get_ajax']);
    });

    //Events
    $routes->group('events', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', '\App\Controllers\Events\Events::index', ['as' => 'admin.events.index']);
        $routes->get('calendar', '\App\Controllers\Events\Events::calendar', ['as' => 'admin.events.calendar']);

        $routes->get('create-event', '\App\Controllers\Events\Events::createNew', ['as' => 'admin.events.create-event']);
        $routes->get('pdf', '\App\Controllers\Events\Events::pdf', ['as' => 'admin.events.pdf']);
        $routes->get('export-excel', '\App\Controllers\Events\Events::exportExcel', ['as' => 'admin.events.excel']);
        $routes->get('print', '\App\Controllers\Events\Events::print', ['as' => 'admin.events.print']);
        $routes->post('create', '\App\Controllers\Events\Events::create', ['as' => 'admin.events.create']);
        $routes->post('edit', '\App\Controllers\Events\Events::edit', ['as' => 'admin.events.edit']);
        $routes->post('delete/(:num)', '\App\Controllers\Events\Events::delete/$1', ['as' => 'admin.events.delete']);
    });

    //Transport
    $routes->group('school/transport', function($routes){
        /** @var RouteCollection $routes */
        $routes->get('/', '\App\Controllers\Admin\Transport::index', ['as' => 'admin.transport.index']);
        $routes->post('save', '\App\Controllers\Admin\Transport::save', ['as' => 'admin.transport.save']);
        $routes->match(['GET', 'POST'], 'delete/(:num)', '\App\Controllers\Admin\Transport::delete/$1', ['as' => 'admin.transport.delete']);
    });

    //Messages
    $routes->group('messages', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('','\App\Controllers\Admin\Messages::index', ['as' => 'admin.messages.index']);
        $routes->get('index_parent','\App\Controllers\Admin\Messages::index_parent', ['as' => 'admin.messages.index_parent']);
        $routes->get('parent/(:num)/(:num)','\App\Controllers\Admin\Messages::parent/$1/$2', ['as' => 'admin.messages.parent']);
        $routes->get('student/(:num)','\App\Controllers\Admin\Messages::student/$1', ['as' => 'admin.messages.student']);
        $routes->get('messages/(:num)/(:num)/(:num)', '\App\Controllers\Admin\Messages::conversation/$1/$2/$3', ['as' => 'admin.message.parent']);
        $routes->post('messages/send', '\App\Controllers\Admin\Messages::sendMessage', ['as' => 'admin.message.send']);
        $routes->post('messages/ajax-fetch', '\App\Controllers\Admin\Messages::ajaxGetMessage', ['as' => 'admin.message.ajax_fetch']);
        $routes->post('messages/ajax-fetch-student', 'Messages::ajaxGetStudentMessage', ['as' => 'admin.message.ajax_fetch_student']);

        $routes->group('sms', function ($routes){
            $routes->match(['GET', 'POST'], '/', '\App\Controllers\Admin\SMS::index', ['as' => 'admin.messages.sms']);
            $routes->post('send-sms', '\App\Controllers\Admin\SMS::sendSms', ['as' => 'admin.messages.sms_send']);
        });
    });

    //Frontend
    $routes->group('frontend', function ($routes){
        /** @var RouteCollection $routes */
        $routes->get('/', '\App\Controllers\Admin\Frontend::index', ['as' => 'admin.frontend.index']);
        $routes->post('save-general', '\App\Controllers\Admin\Frontend::saveGeneral', ['as' => 'admin.frontend.save_general']);
        $routes->post('remove-picture', '\App\Controllers\Admin\Frontend::removePicture', ['as' => 'admin.frontend.remove-picture']);
        $routes->post('remove-info-picture', '\App\Controllers\Admin\Frontend::removeInfoPicture', ['as' => 'admin.frontend.remove-info-picture']);
        $routes->post('remove-admin-picture', '\App\Controllers\Admin\Frontend::removeAdminPicture', ['as' => 'admin.frontend.remove-admin-picture']);
        $routes->post('remove-teacher-picture', '\App\Controllers\Admin\Frontend::removeTeacherPicture', ['as' => 'admin.frontend.remove-teacher-picture']);
        $routes->post('remove-logo', '\App\Controllers\Admin\Frontend::removeLogo', ['as' => 'admin.frontend.remove-logo']);
        $routes->post('remove-student-doc', '\App\Controllers\Admin\Frontend::removeStudentDoc', ['as' => 'admin.frontend.remove-student-doc']);
        $routes->get('homepage', '\App\Controllers\Admin\Frontend::homeSettings', ['as' => 'admin.frontend.homepage']);
        $routes->get('edit-homepage', '\App\Controllers\Admin\Frontend::editHomeSettings', ['as' => 'admin.frontend.edit-homepage']);
        $routes->get('homepage-index', '\App\Controllers\Admin\Frontend::homeIndex', ['as' => 'admin.frontend.homepage_index']);
        $routes->get('mission', '\App\Controllers\Admin\Frontend::missionSettings', ['as' => 'admin.frontend.mission']);
        $routes->post('save-mission', '\App\Controllers\Admin\Frontend::saveMission', ['as' => 'admin.frontend.save_mission']);
        $routes->post('save-slides', '\App\Controllers\Admin\Frontend::saveSlides', ['as' => 'admin.frontend.save_slide']);
        $routes->post('update-slides', '\App\Controllers\Admin\Frontend::updateSlides', ['as' => 'admin.frontend.update_slide']);
        $routes->match(['GET', 'POST'], 'delete-slide/(:any)', '\App\Controllers\Admin\Frontend::deleteSlide/$1', ['as' => 'admin.frontend.delete_slide']);
        $routes->get('events', '\App\Controllers\Admin\Frontend::events', ['as' => 'admin.frontend.events']);
        $routes->post('events', '\App\Controllers\Admin\Frontend::saveEvents', ['as' => 'admin.frontend.save_event']);
        $routes->post('update-events/(:num)', '\App\Controllers\Admin\Frontend::updateEvents/$1', ['as' => 'admin.frontend.update_event']);
        $routes->get('notice-board', '\App\Controllers\Admin\Frontend::noticeBoard', ['as' => 'admin.frontend.notice_board']);
        $routes->get('notice-board-new', '\App\Controllers\Admin\Frontend::noticeBoardNew', ['as' => 'admin.frontend.notice_board_new']);
        $routes->get('notice-board-edit/(:num)', '\App\Controllers\Admin\Frontend::noticeBoardEdit/$1', ['as' => 'admin.frontend.notice_board_edit']);
        $routes->post('edit-notice/(:num)', '\App\Controllers\Admin\Frontend::editNotice/$1', ['as' => 'admin.frontend.notice_board.update']);
        $routes->post('save-notice', '\App\Controllers\Admin\Frontend::saveNotice', ['as' => 'admin.frontend.save_notice']);
        $routes->match(['GET', 'POST'], 'delete-notice/(:num)', '\App\Controllers\Admin\Frontend::deleteNotice/$1', ['as' => 'admin.frontend.notice.delete']);
        $routes->match(['GET', 'POST'], 'hide-notice/(:num)', '\App\Controllers\Admin\Frontend::hideNotice/$1', ['as' => 'admin.frontend.notice.hide']);
        $routes->match(['GET', 'POST'], 'show-notice/(:num)', '\App\Controllers\Admin\Frontend::showNotice/$1', ['as' => 'admin.frontend.notice.show']);
        $routes->match(['GET', 'POST'], 'delete-video/(:num)', '\App\Controllers\Admin\Frontend::deleteVideo/$1', ['as' => 'admin.frontend.video.delete']);
        $routes->match(['GET', 'POST'], 'delete-event/(:num)', '\App\Controllers\Admin\Frontend::deleteEvent/$1', ['as' => 'admin.frontend.event.delete']);
    });
});

//Students
$routes->group('students', ['namespace' => '\App\Controllers\Student'], function ($routes) {
    /** @var RouteCollection $routes */
    $routes->get('profile', 'Profile::index', ['as' => 'student.index']);
    $routes->get('dashboard', 'Profile::dashboard', ['as' => 'student.dashboard']);
    $routes->get('profile/calendar', 'Profile::calendar', ['as' => 'student.calendar']);
    $routes->get('attendance', 'Profile::attendance', ['as' => 'student.attendance']);
    $routes->get('requirements', 'Profile::requirements', ['as' => 'student.requirements']);
    $routes->get('payments', 'Profile::payments', ['as' => 'student.payments']);
    $routes->post('deposit-slip/upload/(:num)', 'Profile::upload_slip/$1', ['as' => 'student.payments.upload_slip']);
    $routes->get('deposit-slip/download/(:num)', 'Profile::download_slip/$1', ['as' => 'student.payments.download_slip']);
    $routes->post('student-attendance', 'Attendance::ajaxAttendance', ['as' => 'student.attendance.ajax']);

    //Certificate
    $routes->post('view-cert', 'Certificate::certificate', ['as' => 'student.certificate.view']); //2
    $routes->post('student-report-card', 'Certificate::report', ['as' => 'student.report-card']); //1
    $routes->get('(:num)/download-cert', 'Certificate::downloadCert/$1', ['as' => 'student.certificate.download-cert']);
    $routes->get('(:num)/print-cert', 'Certificate::print/$1', ['as' => 'student.certificate.print']);
    $routes->post('evaluation', 'Certificate::evaluation', ['as' => 'student.certificate.evaluation']); //3
    $routes->post('evaluation-summary', 'Certificate::evaluation_summary', ['as' => 'student.certificate.evaluation_summary']); //4


    //$routes->get('assessments', 'Assessment::results', ['as' => 'student.assessment_results']);
    $routes->get('assessment-result', 'Assessment::results/$1', ['as' => 'student.assessment.results']);
    $routes->post('assessment-result', 'Assessment::results/$1', ['as' => 'student.assessment.results']);

    $routes->get('certificate', 'Certificate::index', ['as' => 'student.certificate']);
    $routes->get('print-certificate', 'Certificate::print', ['as' => 'student.certificate.print']);

    $routes->group('class', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('timetable', 'Classes::timetable', ['as' => 'students.class.timetable']);
        $routes->get('asp', 'Classes::asp', ['as' => 'students.class.asp']);
        $routes->get('notes', 'Classes::notes', ['as' => 'students.class.notes']);
        $routes->get('download-notes/(:num)', 'Classes::download_notes/$1', ['as' => 'students.class.download_notes']);
        $routes->get('subjects', 'Classes::subjects', ['as' => 'students.class.subjects']);
    });

    $routes->group('exams', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', 'Exam::index', ['as' => 'student.exams.index']);
        $routes->get('(:num)/schedule', 'Exam::view/$1', ['as' => 'student.exam.view.index']);
        $routes->get('results', 'Exam::results', ['as' => 'student.exam.results']);
        $routes->get('student-exam-results', 'Exam::studentExamResults', ['as' => 'student.exam-results']);
        $routes->post('exam-results', 'Exam::ajaxResults', ['as' => 'student.exams.ajax_results']);
        $routes->get('(:num)/view/results', 'Exam::viewResults/$1', ['as' => 'student.exam.view.results']);
        $routes->post('schedule', 'Exam::ajaxSchedule', ['as' => 'student.exams.schedule']);
        $routes->post('exam', 'Exam::ajaxExams', ['as' => 'student.exams.ajax_exam']);
        $routes->post('get-exam', 'Exam::getExam', ['as' => 'student.exams.get_exam']);
        $routes->post('get-results', 'Exam::ajaxMainResults', ['as' => 'student.exams.results.get']);
    });

    $routes->group('accounting', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', 'Accounting::index', ['as' => 'student.accounting.index']);
        $routes->get('log', 'Accounting::log', ['as' => 'student.accounting.log']);
    });

    $routes->group('assessment', function($routes){
        /** @var RouteCollection $routes */
        $routes->get('/', 'Assessment::index', ['as' => 'student.assessment.index']);

        $routes->post('show-assessment', 'Assessment::getAssessments', ['as' => 'student.classes.assessments.get']);
    });

    $routes->group('home-school', function ($routes){
        /** @var RouteCollection $routes */
        $routes->get('assignments', 'Assignments::index', ['as' => 'student.assignments']);
        $routes->post('assignments/submit/(:num)', 'Assignments::submit/$1', ['as' => 'student.assignments.submit']);
        $routes->get('download-assignment/(:num)', 'Assignments::download/$1', ['as' => 'student.assignments.download']);
        $routes->get('download-submission/(:num)', 'Assignments::download_submission/$1', ['as' => 'student.assignments.download_submission']);
        $routes->get('(:num)/submit/(:num)', 'Assignments::submitAssignment/$1/$2', ['as' => 'student.assignments.submit_assignment']);
        $routes->post('save-writing', 'Assignments::saveWr', ['as' => 'student.assignments.assignment_writing.save']);

        $routes->group('classwork', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Student\Assessments\ClassWork::index', ['as' => 'student.assessments.classwork.index']);
            $routes->get('(:num)/view', '\App\Controllers\Student\Assessments\ClassWork::view/$1', ['as' => 'student.assessments.classwork.view']);
            $routes->get('(:num)/do-classwork/(:num)', '\App\Controllers\Student\Assessments\ClassWork::do/$1/$2', ['as' => 'student.assessments.classwork.do_classwork']);
            $routes->post('(:num)/submit/(:num)', '\App\Controllers\Student\Assessments\ClassWork::submit/$1/$2', ['as' => 'student.assessments.classwork.submit']);

            $routes->get('(:num)/results', '\App\Controllers\Student\Assessments\ClassWork::results/$1', ['as' => 'student.assessments.classwork.results']);
        });

        $routes->group('quizes', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Student\Assessments\Quizes::index', ['as' => 'student.assessments.quizes.index']);
            $routes->get('(:num)/view', '\App\Controllers\Student\Assessments\Quizes::view/$1', ['as' => 'student.assessments.quizes.view']);
            $routes->get('(:num)/results', '\App\Controllers\Student\Assessments\Quizes::results/$1', ['as' => 'student.assessments.quizes.results']);
            $routes->get('(:num)/do-quiz/(:num)', '\App\Controllers\Student\Assessments\Quizes::do/$1/$2', ['as' => 'student.assessments.quizes.do_quiz']);
            $routes->post('(:num)/submit/(:num)', '\App\Controllers\Student\Assessments\Quizes::submit/$1/$2', ['as' => 'student.assessments.quizes.submit']);
        });
        //$routes->get('quizes', '\App\Controllers\Student\Assessments\Quizes::index', ['as' => 'student.assessments.quizes.index']);
        $routes->group('exams', function ($routes) {
            $routes->get('/', '\App\Controllers\Student\Assessments\Exam::index', ['as' => 'student.assessments.exams.index']);
            $routes->get('(:num)/view', '\App\Controllers\Student\Assessments\Exam::view/$1', ['as' => 'student.assessments.exams.view']);
            $routes->get('(:num)/results', '\App\Controllers\Student\Assessments\Exam::results/$1', ['as' => 'student.assessments.exams.results']);
            $routes->get('(:num)/do-exam/(:num)', '\App\Controllers\Student\Assessments\Exam::do/$1/$2', ['as' => 'student.assessments.exam.do_exam']);
            $routes->post('(:num)/submit/(:num)', '\App\Controllers\Student\Assessments\Exam::submit/$1/$2', ['as' => 'student.assessments.exam.submit']);
        });

        $routes->group('final-result', function ($routes) {
            $routes->get('/', '\App\Controllers\Student\Assessments\FinalGrade::index', ['as' => 'student.assessments.final_grade.index']);
            $routes->post('get-results', '\App\Controllers\Student\Assessments\FinalGrade::getResults', ['as' => 'student.assessments.final_grade.get_results']);
        });
    });

    $routes->group('messages', function ($routes) {
        $routes->get('/', 'Messages::index', ['as' => 'student.messages']);
        $routes->get('chat/(:num)', 'Messages::message/$1', ['as' => 'student.messages.chat']);
        $routes->post('send/(:num)', 'Messages::send/$1', ['as' => 'student.message.send']);
        $routes->post('fetch', 'Messages::ajaxFetch', ['as' => 'student.message.ajax_fetch']);
    });
});

//parents
$routes->group('parents', ['namespace' => '\App\Controllers\Parent'], function ($routes) {
    /** @var RouteCollection $routes */
    $routes->get('profile', 'Profile::index', ['as' => 'parent.index']);

    $routes->get('requirements', 'Profile::requirements', ['as' => 'parent.requirements']);
    $routes->get('payments', 'Profile::payments', ['as' => 'parent.payments']);
    $routes->get('calendar', 'Profile::calendar', ['as' => 'parent.calendar']);
    $routes->get('attendance', 'Profile::attendance', ['as' => 'parent.attendance']);
    $routes->post('requirements/mark-checked/(:any)', 'Profile::mark_checked/$1', ['as' => 'parent.requirements.mark_checked']);
    $routes->post('deposit-slip/upload/(:num)', 'Profile::upload_slip/$1', ['as' => 'parent.payments.upload_slip']);
    $routes->get('deposit-slip/download/(:num)', 'Profile::download_slip/$1', ['as' => 'parent.payments.download_slip']);

    $routes->get('assessment-results', 'Assessment::results/$1', ['as' => 'parent.assessment.results']);
    $routes->post('assessment-results', 'Assessment::results/$1', ['as' => 'parent.assessment.results']);

    $routes->get('student-exam-results', 'Exams::studentExamResults', ['as' => 'parent.student-exam-results']);
    $routes->post('exam-results', 'Exams::ajaxResults', ['as' => 'parent.exams.ajax_results']);
    $routes->post('exam', 'Exams::ajaxExam', ['as' => 'parent.exams.ajax_exam']);
    $routes->post('student', 'Exams::ajaxStudent', ['as' => 'parent.exams.ajax_student']);
    $routes->post('student-exams', 'Exams::ajaxExams', ['as' => 'parent.exams.ajax_exams']);

    $routes->group('exams', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('', 'Exams::index', ['as' => 'parent.exams.index']);
        $routes->post('schedule', 'Exams::ajaxSchedule', ['as' => 'parent.exams.schedule']);
        $routes->get('results', 'Exams::results', ['as' => 'parent.exams.results']); //parent.exam.results
        $routes->post('get-results', 'Exams::ajaxMainResults', ['as' => 'parent.exams.results.get']); //parent.exam.results

        $routes->get('continuous-assessments', 'Assessment::index', ['as' => 'parent.exams.continuous_assessment']);
        $routes->post('class-subjects', 'Assessment::getStudentSubjects', ['as' => 'parent.classes.assessments.get_student_subjects_ajax']);
        $routes->post('show-assessment', 'Assessment::getAssessments', ['as' => 'parent.classes.assessments.get']);
    });

    $routes->group('schedules', function ($routes) {
        $routes->get('regular', 'Schedules::regular', ['as' => 'parent.schedules.regular']);
        $routes->post('get-student-regular', 'Schedules::getStudentRegularSchedule', ['as' => 'parent.schedules.student.get_regular']);
        $routes->get('asp', 'Schedules::asp', ['as' => 'parent.schedules.asp']);
        $routes->post('get-student-asp', 'Schedules::getAspSchedule', ['as' => 'parent.schedules.student.get_asp']);
    });

    $routes->group('rank', function($routes){
        /** @var RouteCollection $routes */
        $routes->get('/', 'Rank::index', ['as' => 'parent.rank.index']);
        $routes->post('get-rank-ajax', 'Rank::rankAjax', ['as' => 'parent.rank.get_ajax']);
    });

    $routes->group('home-school', function($routes){
        /** @var RouteCollection $routes */
        $routes->group('classwork', function($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', 'Classwork::index', ['as' => 'parent.continuous_assessments.classwork']);
            $routes->post('view', 'Classwork::view', ['as' => 'parent.continuous_assessments.view_classwork']);
            $routes->get('(:num)/results/(:num)', 'Classwork::results/$1/$2', ['as' => 'parent.continuous_assessments.classwork_results']);
        });

        $routes->group('quiz', function ($routes) {
            $routes->get('/', 'Quiz::index', ['as' => 'parent.continuous_assessments.quiz']);
            $routes->post('view', 'Quiz::view', ['as' => 'parent.continuous_assessments.view_quiz']);
            $routes->get('(:num)/results/(:num)', 'Quiz::results/$1/$2', ['as' => 'parent.continuous_assessments.quiz_results']);

        });

        $routes->group('exam', function ($routes) {
            $routes->get('/', 'Exam::index', ['as' => 'parent.continuous_assessments.exam']);
            $routes->post('view', 'Exam::view', ['as' => 'parent.continuous_assessments.view_exam']);
            $routes->get('(:num)/results/(:num)', 'Exam::results/$1/$2', ['as' => 'parent.continuous_assessments.exam_results']);
        });

        $routes->group('assignment', function ($routes) {
            $routes->get('/', 'Assignment::index', ['as' => 'parent.continuous_assessments.assignment']);
            $routes->post('view', 'Assignment::view', ['as' => 'parent.continuous_assessments.view_assignment']);
            $routes->get('(:num)/results/(:num)', 'Assignment::results/$1/$2', ['as' => 'parent.continuous_assessments.assignment_results']);
        });

        $routes->group('final-result', function ($routes) {
            $routes->get('/', 'FinalResult::index', ['as' => 'parent.continuous_assessments.final_result']);
            $routes->post('/', 'FinalResult::view', ['as' => 'parent.continuous_assessments.view_final_result']);
        });
    });

    $routes->get('student/certificate', 'Certificate::index', ['as' => 'parent.student.certificate']);
    $routes->get('student/certificate/(:num)/print', 'Certificate::print/$1', ['as' => 'parent.academic.yearly_certificate.print']);
    $routes->post('student/certificate/view', 'Certificate::view', ['as' => 'parent.student.yearly_certificate']);

    $routes->post('view-cert', 'Certificate::certificate', ['as' => 'parent.student.certificate.view']); //2
    $routes->post('student-report-card', 'Certificate::report', ['as' => 'parent.student.report-card']); //1
    $routes->get('(:num)/download-cert', 'Certificate::downloadCert/$1', ['as' => 'parent.student.certificate.download-cert']);
    $routes->get('(:num)/print', 'Certificate::print/$1', ['as' => 'parent.student.certificate.print']);
    $routes->post('evaluation', 'Certificate::evaluation', ['as' => 'parent.student.certificate.evaluation']); //3
    $routes->post('evaluation-summary', 'Certificate::evaluation_summary', ['as' => 'parent.student.certificate.evaluation_summary']); //4

    $routes->get('transport-route', 'TransportRoutes::index', ['as' => 'parent.transport.route']);
    $routes->post('transport/student-route', 'TransportRoutes::ajaxStudentRoute', ['as' => 'parent.transport.student_routes']);
    $routes->post('transport/student/set-route', 'TransportRoutes::ajaxSetStudentRoute', ['as' => 'parent.transport.set_route']);

    $routes->post('student-attendance', 'Attendance::ajaxAttendance', ['as' => 'parent.attendance.students.ajax']);
    $routes->post('student-details', 'Attendance::ajaxDetails', ['as' => 'parent.attendance.students-details.ajax']);

    $routes->get('messages', 'Messages::index', ['as' => 'parent.messages']);
    $routes->get('messages/(:num)/(:num)', 'Messages::message/$1/$2', ['as' => 'parent.message.teacher']);
    $routes->post('messages/send', 'Messages::sendMessage', ['as' => 'parent.message.send']);
    $routes->post('messages/ajax-fetch', 'Messages::ajaxGetMessage', ['as' => 'parent.message.ajax_fetch']);
});

//Teachers
$routes->group('teachers', ['namespace' => '\App\Controllers\Teacher'], function ($routes) {
    /** @var RouteCollection $routes */
    $routes->get('profile', 'Profile::index', ['as' => 'teacher.index']);
    $routes->get('dashboard', 'Profile::dashboard', ['as' => 'teacher.dashboard']);
    $routes->get('assignments', 'Assignments::index', ['as' => 'teacher.assignments']);
    $routes->get('calendar', 'Profile::calendar', ['as' => 'teacher.calendar']);
    $routes->post('assignments/submit/(:num)', 'Assignments::submit/$1', ['as' => 'teachers.assignments.submit']);
    $routes->post('student-attendance', 'Attendance::ajaxAttendance', ['as' => 'teacher.attendance.students.ajax']);

    $routes->group("requirements",function ($routes){
        $routes->get('', 'Requirements::index', ['as' => 'teacher.requirements.index']);
        $routes->get('view/(:num)/(:num)', 'Requirements::view/$1/$2', ['as' => 'teacher.requirements.view']);
        $routes->get('view/(:num)/(:num)/(:num)', 'Requirements::viewSection/$1/$2/$3', ['as' => 'teacher.requirements.view_requirement']);
        $routes->post('save', 'Requirements::save', ['as' => 'teacher.requirements.save']);
        $routes->post('mark_checked/(:num)', 'Requirements::mark_checked/$1', ['as' => 'teacher.requirements.mark_checked']);
    });
    $routes->group('schedules', function ($routes){
        /** @var RouteCollection $routes */
        $routes->get('student-regular', 'Schedules::studentRegular', ['as' => 'teacher.schedules.student.regular']);
        $routes->post('get-student-regular', 'Schedules::getStudentRegularSchedule', ['as' => 'teacher.schedules.student.get_regular']);
        $routes->get('student-asp', 'Schedules::studentASP', ['as' => 'teacher.schedules.student.asp']);
        $routes->post('get-student-asp', 'Schedules::getAspSchedule', ['as' => 'teacher.schedules.student.get_asp']);

        $routes->get('teacher-regular', 'TeachersSchedule::index', ['as' => 'teacher.schedules.teacher.regular']);
        $routes->post('get-teacher-regular', 'TeachersSchedule::getSchedule', ['as' => 'teacher.schedules.teacher.get_regular']);
        $routes->get('teacher-asp', 'TeachersSchedule::aspSchedule', ['as' => 'teacher.schedules.teacher.asp']);
        $routes->post('get-teacher-asp', 'TeachersSchedule::getAspSchedule', ['as' => 'teacher.schedules.teacher.get_asp']);
    });

     $routes->group('certificate', function ($routes) {
        $routes->get('/', '\App\Controllers\Teacher\Certificate::index', ['as' => 'teacher.academic.yearly_certificate']);
        $routes->post('students', '\App\Controllers\Teacher\Certificate::getStudents', ['as' => 'teacher.academic.yearly_certificate.students']);
        $routes->post('save_yearly_evaluation', '\App\Controllers\Teacher\Certificate::save_yearly_evaluation', ['as' => 'teacher.academic.yearly_certificate.save_yearly_evaluation']);
        $routes->post('save_homeroom_teacher_comment', '\App\Controllers\Teacher\Certificate::save_homeroom_teacher/$1', ['as' => 'teacher.academic.yearly_certificate.save_homeroom_teacher_comment']);
        $routes->post('homeroom_sign', '\App\Controllers\Teacher\Certificate::homeroom_sign', ['as' => 'teacher.academic.yearly_certificate.homeroom_sign']);
        $routes->get('(:num)/view', '\App\Controllers\Teacher\Certificate::certificate/$1', ['as' => 'teacher.academic.yearly_certificate.view']); //2
        $routes->get('(:num)/report-card', '\App\Controllers\Teacher\Certificate::report/$1', ['as' => 'teacher.academic.yearly_certificate.report-card']); //1
        $routes->get('(:num)/download-cert', '\App\Controllers\Teacher\Certificate::downloadCert/$1', ['as' => 'teacher.academic.yearly_certificate.download-cert']);
        $routes->get('(:num)/evaluation', '\App\Controllers\Teacher\Certificate::evaluation/$1', ['as' => 'teacher.academic.yearly_certificate.evaluation']); //3
        $routes->get('(:num)/evaluation-summary', '\App\Controllers\Teacher\Certificate::evaluation_summary/$1', ['as' => 'teacher.academic.yearly_certificate.evaluation_summary']); //4
        $routes->get('(:num)/print', '\App\Controllers\Teacher\Certificate::print/$1', ['as' => 'teacher.academic.yearly_certificate.print']);

    });

    $routes->group('semester', function ($routes) {
        $routes->get('ranking', '\App\Controllers\Teacher\Ranking::index', ['as' => 'teacher.academic.semester_ranking']);
        $routes->get('results/(:num)/(:num)/(:num)', '\App\Controllers\Teacher\Ranking::results/$1/$2/$3', ['as' => 'teacher.academic.semester_results']);
        $routes->get('result-analysis', '\App\Controllers\Teacher\Ranking::analysis', ['as' => 'teacher.academic.semester_analysis']);
        $routes->get('result-analysis-others', '\App\Controllers\Teacher\Ranking::analysisOthers', ['as' => 'teacher.academic.semester_analysis_others']);
        $routes->post('ranking/get', '\App\Controllers\Teacher\Ranking::get', ['as' => 'teacher.academic.semester_ranking.get']);
        $routes->post('ranking/get-analysis', '\App\Controllers\Teacher\Ranking::getAnalysis', ['as' => 'teacher.academic.semester_analysis.get']);
        $routes->post('ranking/get-analysis-others', '\App\Controllers\Teacher\Ranking::getAnalysisOthers', ['as' => 'teacher.academic.semester_analysis_others.get']);
    });

    $routes->group('quarter', function ($routes) {
        $routes->get('ranking', '\App\Controllers\Teacher\QuarterRanking::index', ['as' => 'teacher.academic.quarter_ranking']);
        $routes->get('result-analysis', '\App\Controllers\Teacher\QuarterRanking::analysis', ['as' => 'teacher.academic.quarter_analysis']);
        $routes->post('ranking/get', '\App\Controllers\Teacher\QuarterRanking::get', ['as' => 'teacher.academic.quarter_ranking.get']);
        $routes->post('ranking/get-analysis', '\App\Controllers\Teacher\QuarterRanking::getAnalysis', ['as' => 'teacher.academic.quarter_analysis.get']);
    });


    $routes->get('lesson-plan', 'LessonPlan::index', ['as' => 'teacher.lesson_plan.index']);
    $routes->post('get-lesson-plan', 'LessonPlan::getLessonPlan', ['as' => 'teacher.lesson_plan.get_plan']);
    $routes->match(['GET', 'POST'], 'lesson-plan/(:num)/subject/(:num)/download/(:num)/(:num)', 'LessonPlan::downloadLessonPlan/$1/$2/$3/$4', ['as' => 'teacher.subjects.lesson_plan.download']);

    $routes->match(['GET', 'POST'], '(:num)/subject/(:num)/lesson-plan/create', 'LessonPlan::createLessonPlan/$1/$2', ['as' => 'teacher.subjects.lesson_plan.create']);
    $routes->match(['GET', 'POST'], '(:num)/subject/(:num)/lesson-plan/update/(:num)/(:num)', 'LessonPlan::updateLessonPlan/$1/$2/$3/$4', ['as' => 'teacher.subjects.lesson_plan.update']);

    $routes->group('class', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', 'Classes::index', ['as' => 'teacher.classes']);
        
        //TEST
        $routes->get('index', 'Classes::index', ['as' => 'teacher.classes.index']);
        $routes->get('view/(:num)/overview', 'Classes::view/$1', ['as' => 'teacher.classes.view']);
        $routes->get('view/(:num)/notes', 'Classes::notes/$1', ['as' => 'teacher.classes.notes']);
        $routes->get('view/(:num)/students', 'Classes::students/$1', ['as' => 'teacher.classes.students']);
        $routes->get('view/(:num)/events', 'Classes::events/$1', ['as' => 'teacher.classes.events']);
        $routes->get('view/(:num)/routines', 'Classes::routines/$1', ['as' => 'teacher.classes.routines']);
        $routes->get('view/(:num)/requirements', 'Classes::requirements/$1', ['as' => 'teacher.classes.requirements']);
        $routes->get('view/(:num)/subjects', 'Classes::subjects/$1', ['as' => 'teacher.classes.subjects']);

        $routes->post('view/(:num)/subjects/add', 'Classes::addSubject/$1', ['as' => 'teacher.classes.subjects.add_subject']);
        $routes->post('view/(:num)/subjects/edit', 'Classes::editSubject/$1', ['as' => 'teacher.classes.subjects.edit_subject']);
        $routes->post('view/(:num)/subjects/delete', 'Classes::deleteSubject/$1', ['as' => 'teacher.classes.subjects.delete_subject']);

        //Sections
        $routes->group('sections', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->post('create', 'Sections::create', ['as' => 'teacher.sections.create']);
            $routes->post('update/(:num)', 'Sections::update/$1', ['as' => 'teacher.sections.edit']);
            $routes->post('delete/(:num)', 'Sections::delete/$1', ['as' => 'teacher.sections.delete']);

            $routes->get('view/(:num)/overview', 'Sections::view/$1', ['as' => 'teacher.class.sections.view']);
            $routes->get('view/(:num)/students', 'Sections::students/$1', ['as' => 'teacher.class.sections.students']);
            $routes->get('view/(:num)/timetable', 'Sections::timetable/$1', ['as' => 'teacher.class.sections.timetable']);
            $routes->match(['GET', 'POST'], 'view/(:num)/create-timetable', 'Sections::createTimetable/$1', ['as' => 'teacher.class.sections.timetable_create']);

            // After School Schedule
            $routes->get('view/(:num)/asp-schedule', 'Sections::aspTimetable/$1', ['as' => 'teacher.class.sections.asp_schedule']);
            $routes->match(['GET', 'POST'], 'view/(:num)/create-asp-schedule', 'Sections::createAspTimetable/$1', ['as' => 'teacher.class.sections.create_asp_schedule']);

            // Class Groups
            $routes->get('view/(:num)/class-groups', 'Groups::view/$1', ['as' => 'teacher.class.sections.groups']);
            $routes->post('view/(:num)/create-group', 'Groups::createGroup/$1', ['as' => 'teacher.class.sections.groups.create']);
            $routes->post('view/group/get-members', 'Groups::getMembers', ['as' => 'teacher.class.sections.groups.members']);
            $routes->post('view/(:num)/delete', 'Groups::deleteGroup/$1', ['as' => 'teacher.class.sections.groups.delete']);
        });
        //END TEST

        $routes->get('timetable', 'Classes::timetable', ['as' => 'teacher.class.timetable']);
        $routes->get('notes', 'Classes::notes', ['as' => 'teacher.class.notes']);

        $routes->group('subjects', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', 'Classes::subjects', ['as' => 'teacher.class.subjects']);
            $routes->get('(:num)/section/(:num)/overview', 'Subjects::view/$1/$2', ['as' => 'teacher.subjects.view']);
            $routes->match(['GET', 'POST'], '(:num)/section/(:num)/notes', 'Subjects::notes/$1/$2', ['as' => 'teacher.subjects.notes']);
            $routes->match(['GET', 'POST'], '(:num)/section/(:num)/assignments', 'Subjects::assignments/$1/$2', ['as' => 'teacher.subjects.assignments']);

            $routes->match(['GET', 'POST'], 'notes/(:num)/delete', 'Subjects::delete_note/$1', ['as' => 'teacher.subject.teacher.subject.notes.delete']);
            $routes->match(['GET', 'POST'], 'assignment/(:num)/delete', 'Subjects::delete_assignment/$1', ['as' => 'teacher.subject.assignments.delete']);

            //View and mark assignments
            $routes->match(['GET', 'POST'], '(:num)/assignments/(:num)', 'Assignments::view/$1/$2', ['as' => 'teacher.subjects.assignments.view']);
            $routes->match(['GET', 'POST'], '(:num)/assignments/(:num)/award-marks', 'Assignments::award_marks/$1/$2', ['as' => 'teacher.subjects.assignments.award_marks']);
            $routes->match(['GET', 'POST'], '(:num)/assignments/(:num)/delete-submission', 'Assignments::delete_submission/$1/$2', ['as' => 'teacher.subjects.assignments.delete_submission']);
        });
    });

    //Exams
    $routes->group('exams', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', 'Exams::index', ['as' => 'teacher.exams.index']);
        $routes->get('schedule', 'Exams::index_schedule', ['as' => 'teacher.exams.schedule']);
        $routes->post('create', 'Exams::create', ['as' => 'teacher.exams.create']);
        $routes->post('ajax-schedule', 'Exams::ajaxSchedule', ['as' => 'teacher.exams.schedule-ajax']);
        $routes->match(['GET', 'POST'], 'delete/(:num)', 'Exams::delete/$1', ['as' => 'teacher.exams.delete']);

        $routes->get('(:num)/view', 'View::index/$1', ['as' => 'teacher.exams.view.index']);
        $routes->get('(:num)/results', 'View::results/$1', ['as' => 'teacher.exams.view.results']);
        $routes->get('exam-results', 'Exams::examResults', ['as' => 'teacher.exams.results']);

        $routes->add('(:num)/timetable/(:num)', 'View::timetable/$1/$2', ['as' => 'teacher.exams.view.timetables.ajax']);
        $routes->add('(:num)/results/(:num)', 'View::classResults/$1/$2', ['as' => 'teacher.exams.view.results.ajax']);

        $routes->add('(:num)/record-results', 'Exams::recordResults/$1', ['as' => 'teacher.exam.record_results']);
        $routes->post('(:num)/get-students', 'Exams::getStudents/$1', ['as' => 'teacher.exam.record_results.get_students']);
        $routes->post('(:num)/save-results', 'Exams::saveResults/$1', ['as' => 'teacher.exam.record.results.form']);

        $routes->post('get-exam-results', 'View::getResults', ['as' => 'teacher.exam.get_results']);

        // CATs
//        $routes->group('cats', function ($routes) {
//            $routes->get('/', 'Cats::index', ['as' => 'teacher.exams.cats.index']);
//            $routes->get('(:num)/view', 'Cats::view/$1', ['as' => 'teacher.exams.cats.view.index']);
//            $routes->get('(:num)/results', 'Cats::results/$1', ['as' => 'teacher.exams.cats.view.results']);
//            $routes->match(['GET', 'POST'], '(:num)/delete', 'Cats::delete/$1', ['as' => 'teacher.exams.cats.delete']);
//        });
    });

    // CATs
//    $routes->group('cats', function ($routes) {
//        $routes->get('/', 'Cats::index', ['as' => 'teacher.exams.cats.index']);
//        $routes->get('(:num)/view', 'Cats::view/$1', ['as' => 'teacher.exams.cats.view.index']);
//        $routes->get('(:num)/results', 'Cats::results/$1', ['as' => 'teacher.exams.cats.view.results']);
//        $routes->match(['GET', 'POST'], '(:num)/delete', 'Cats::delete/$1', ['as' => 'teacher.exams.cats.delete']);
//    });

    // Assessments
    $routes->group('assessment', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('/', 'Assessments::index', ['as' => 'teacher.exams.cats.index']);
        $routes->post('get', 'Assessments::getAssessmentsAjax', ['as' => 'teacher.classes.assessments.get']);
        $routes->post('save', 'Assessments::save', ['as' => 'teacher.classes.assessments.save']);

        $routes->get('assessments', '\App\Controllers\Teacher\Assessments\ManualAssessments::index', ['as' => 'teacher.academic.assessments.manual.index']);
        $routes->post('assessments/get', '\App\Controllers\Teacher\Assessments\ManualAssessments::get', ['as' => 'teacher.academic.assessments.manual.get_the_assessments']);
        $routes->post('assessments/save-ca', '\App\Controllers\Teacher\Assessments\ManualAssessments::saveCA', ['as' => 'teacher.academic.assessments.manual.save_cas']);
        $routes->post('assessments/maunal/save-results', '\App\Controllers\Teacher\Assessments\ManualAssessments::saveResults', ['as' => 'teacher.academics.assessments.manual.save_results']);
        $routes->post('save-ca-total', '\App\Controllers\Teacher\Assessments\ManualAssessments::saveCATotal', ['as' => 'teacher.assessments.manual.save_cas_total']);
    });

    $routes->group('home-school', function ($routes){
        /** @var RouteCollection $routes */
        $routes->group('assignments', function ($routes){
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Teacher\Academic\Assignments::index', ['as' => 'teacher.academic.assignments']);
            $routes->post('section-assignments', '\App\Controllers\Teacher\Academic\Assignments::getAssignments', ['as' => 'teacher.academic.assignments.get']);
            $routes->post('save', '\App\Controllers\Teacher\Academic\Assignments::save', ['as' => 'teacher.academic.new_assignment']);
        });

        $routes->group('classwork', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Teacher\Assessments\Classwork::index', ['as' => 'teacher.academic.assessments.class_work']);
            $routes->post('create-classwork', '\App\Controllers\Teacher\Assessments\Classwork::create', ['as' => 'teacher.academic.assessments.class_work.create']);
            $routes->post('(:num)/save-edit-classwork', '\App\Controllers\Teacher\Assessments\Classwork::saveEditClasswork/$1', ['as' => 'teacher.academic.assessments.class_work.save_edit']);
            $routes->get('new-classwork', '\App\Controllers\Teacher\Assessments\Classwork::newClasswork', ['as' => 'teacher.academic.assessments.class_work.new_classwork']);
            $routes->get('(:num)/view', '\App\Controllers\Teacher\Assessments\Classwork::view/$1', ['as' => 'teacher.academic.assessments.class_work.view']);
            $routes->get('(:num)/results', '\App\Controllers\Teacher\Assessments\Classwork::results/$1', ['as' => 'teacher.academic.assessments.class_work.results']);
            $routes->get('(:num)/edit', '\App\Controllers\Teacher\Assessments\Classwork::editClasswork/$1', ['as' => 'teacher.academic.assessments.class_work.edit']);
            $routes->post('(:num)/view-classwork', '\App\Controllers\Teacher\Assessments\Classwork::viewClasswork/$1', ['as' => 'teacher.academic.assessments.class_work.view_classwork']);
            $routes->get('(:num)/print-classwork', '\App\Controllers\Teacher\Assessments\Classwork::printClasswork/$1', ['as' => 'teacher.academic.assessments.class_work.print_classwork']);
            $routes->post('create-new-classwork', '\App\Controllers\Teacher\Assessments\Classwork::newClassworkCreate', ['as' => 'teacher.academic.assessments.class_work.new_classwork_create']);
            $routes->post('save-new-classwork', '\App\Controllers\Teacher\Assessments\Classwork::saveNewClasswork', ['as' => 'teacher.academic.assessments.class_work.new_classwork_save']);
            $routes->post('get-classwork', '\App\Controllers\Teacher\Assessments\Classwork::get', ['as' => 'teacher.academic.assessments.class_work.get']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Teacher\Assessments\Classwork::delete/$1', ['as' => 'teacher.academic.assessments.class_work.delete']);
        });

        $routes->group('quizes', function ($routes) {
            /** @var RouteCollection $routes */
            $routes->get('/', '\App\Controllers\Teacher\Assessments\Quizes::index', ['as' => 'teacher.academic.assessments.quizes.index']);
            $routes->post('create-category', '\App\Controllers\Teacher\Assessments\Quizes::create', ['as' => 'teacher.academic.assessments.quizes.create']);
            $routes->post('get', '\App\Controllers\Teacher\Assessments\Quizes::get', ['as' => 'teacher.academic.assessments.quizes.get']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Teacher\Assessments\Quizes::delete/$1', ['as' => 'teacher.academic.assessments.quizes.delete']);
            $routes->get('create-quiz', '\App\Controllers\Teacher\Assessments\Quizes::newQuiz', ['as' => 'teacher.academic.assessments.quizes.new_quiz']);
            $routes->post('(:num)/create-new-quiz', '\App\Controllers\Teacher\Assessments\Quizes::createNewQuiz/$1', ['as' => 'teacher.academic.assessments.quizes.create_new_quiz']);
            $routes->post('save-new-quiz', '\App\Controllers\Teacher\Assessments\Quizes::saveNewQuiz', ['as' => 'teacher.academic.assessments.quizes.new_quiz_save']);
            $routes->get('(:num)/view', '\App\Controllers\Teacher\Assessments\Quizes::view/$1', ['as' => 'teacher.academic.assessments.quizes.view']);
            $routes->get('(:num)/results', '\App\Controllers\Teacher\Assessments\Quizes::results/$1', ['as' => 'teacher.academic.assessments.quizes.results']);
            $routes->get('(:num)/edit', '\App\Controllers\Teacher\Assessments\Quizes::edit/$1', ['as' => 'teacher.academic.assessments.quizes.edit_quiz']);
            $routes->post('(:num)/save-edit', '\App\Controllers\Teacher\Assessments\Quizes::saveEditQuiz/$1', ['as' => 'teacher.academic.assessments.quizes.save_edit_quiz']);
            $routes->post('(:num)/view-quiz', '\App\Controllers\Teacher\Assessments\Quizes::viewQuiz/$1', ['as' => 'teacher.academic.assessments.quizes.view_quiz']);
            $routes->get('(:num)/print-quiz', '\App\Controllers\Teacher\Assessments\Quizes::printQuiz/$1', ['as' => 'teacher.academic.assessments.quizes.print_quiz']);
        });

        $routes->group('exams', function ($routes){
            $routes->get('/', '\App\Controllers\Teacher\Assessments\Exam::index', ['as' => 'teacher.academic.assessments.exam']);
            //$routes->post('create-category', '\App\Controllers\Teacher\Assessments\Exam::create', ['as' => 'teacher.academic.assessments.exam.create']);
            $routes->post('get-exam', '\App\Controllers\Teacher\Assessments\Exam::get', ['as' => 'teacher.academic.assessments.exam.get']);
            $routes->match(['GET', 'POST'],'delete/(:num)', '\App\Controllers\Teacher\Assessments\Exam::delete/$1', ['as' => 'teacher.academic.assessments.exams.delete']);
            $routes->get('create-exam', '\App\Controllers\Teacher\Assessments\Exam::newExam', ['as' => 'teacher.academic.assessments.exams.new_exam']);
            //$routes->post('create-new-exam', '\App\Controllers\Teacher\Assessments\Exam::createNewExam', ['as' => 'teacher.academic.assessments.exams.create_new_exam']);
            $routes->post('save-new-exam', '\App\Controllers\Teacher\Assessments\Exam::saveNewExam', ['as' => 'teacher.academic.assessments.exams.new_exam_save']);
            $routes->get('(:num)/view-exam', '\App\Controllers\Teacher\Assessments\Exam::view/$1', ['as' => 'teacher.academic.assessments.exams.view']);
            $routes->get('(:num)/edit', '\App\Controllers\Teacher\Assessments\Exam::editExam/$1', ['as' => 'teacher.academic.assessments.exams.edit']);
            $routes->post('(:num)/save-edit', '\App\Controllers\Teacher\Assessments\Exam::saveEditExam/$1', ['as' => 'teacher.academic.assessments.exams.save_edit']);
            $routes->post('(:num)/view-exam-item', '\App\Controllers\Teacher\Assessments\Exam::viewExam/$1', ['as' => 'teacher.academic.assessments.exams.view_exam']);
            $routes->get('(:num)/print-exam-item', '\App\Controllers\Teacher\Assessments\Exam::printExam/$1', ['as' => 'teacher.academic.assessments.exams.print_exam']);
        });

        $routes->group('calculate', function($routes) {
            $routes->get('continuous-assessment', '\App\Controllers\Teacher\Assessments\CalculateCA::index', ['as' => 'teacher.academic.assessments.calculate_ca']);
            $routes->match(['GET', 'POST'], 'get-cats', '\App\Controllers\Teacher\Assessments\CalculateCA::getCats', ['as' => 'teacher.academic.assessments.get_cats']);
            $routes->post('calculate', '\App\Controllers\Teacher\Assessments\CalculateCA::calculate', ['as' => 'teacher.academic.assessments.calculate_cats']);

            $routes->group('final-grade', function ($routes) {
                $routes->get('/', '\App\Controllers\Teacher\Assessments\CalculateFG::index', ['as' => 'teacher.academic.assessments.calculate_fg']);
                $routes->match(['GET', 'POST'], 'get-items', '\App\Controllers\Teacher\Assessments\CalculateFG::getItems', ['as' => 'teacher.academic.assessments.calculate_fg.items']);
                $routes->post('calculate', '\App\Controllers\Teacher\Assessments\CalculateFG::calculate', ['as' => 'teacher.academic.assessments.calculate_fg.calculate']);
            });
        });
        $routes->group('rank', function ($routes) {
            $routes->get('/', '\App\Controllers\Teacher\Assessments\Rank::index', ['as' => 'teacher.academic.assessments.rank']);
            $routes->match(['GET', 'POST'], 'get-rank', '\App\Controllers\Teacher\Assessments\Rank::getRank', ['as' => 'teacher.academic.assessments.get_rank']);
        });
    });

    // Attendance
    $routes->group('attendance', function ($routes) {
        /** @var RouteCollection $routes */
        $routes->get('students', 'Attendance::students', ['as' => 'teacher.attendance.students']);
        $routes->get('students/record', 'Attendance::recordStudents', ['as' => 'teacher.attendance.record_students']);
        $routes->get('teachers', 'Attendance::teachers', ['as' => 'teacher.attendance.teachers']);

        $routes->post('students/get-students-ajax', 'Attendance::getStudentsAjax', ['as' => 'teacher.attendance.students.get_ajax']);
        $routes->post('students/save-student', 'Attendance::saveStudent', ['as' => 'teacher.attendance.saveStudent']);

        //Teachers
        $routes->post('teachers/get-teachers', 'Attendance::getTeachers', ['as' => 'teacher.attendance.get_teachers.ajax']);
        $routes->get('teachers/record', 'Attendance::recordTeachers', ['as' => 'teacher.attendance.record_teachers']);
        $routes->post('students/save-teacher', 'Attendance::saveTeacher', ['as' => 'teacher.attendance.saveTeacher']);
        $routes->post('students/get-teachers-ajax', 'Attendance::getTeachersAjax', ['as' => 'teacher.attendance.teachers.get_ajax']);
        $routes->post('students/get-attendance-ajax', 'Attendance::getAttendanceAjax', ['as' => 'teacher.attendance.students.get_ajax_attendance']);
        $routes->post('students/post-students-ajax', 'Attendance::postStudentsAjax', ['as' => 'teacher.attendance.students.post_ajax']);
    });

    //Messages
    $routes->get('messages', 'Messages::index', ['as' => 'teacher.messages']);
    $routes->get('messages/(:num)/(:num)', 'Messages::message/$1/$2', ['as' => 'teacher.message.parent']);
    $routes->get('messages/(:num)/student', 'Messages::messageStudent/$1', ['as' => 'teacher.message.student']);
    $routes->post('messages/send', 'Messages::sendMessage', ['as' => 'teacher.message.send']);
    $routes->post('messages/send-to-student', 'Messages::sendStudent', ['as' => 'teacher.message.send_student']);
    $routes->post('messages/ajax-fetch', 'Messages::ajaxGetMessage', ['as' => 'teacher.message.ajax_fetch']);
    $routes->post('messages/ajax-fetch-student', 'Messages::ajaxGetStudentMessage', ['as' => 'teacher.message.ajax_fetch_student']);
});

//Profile
$routes->group('profile', function ($routes){
    $routes->get('change-password', '\App\Controllers\Profile::changePassword', ['as' => 'profile.password.change_password']);
    $routes->post('post-change-password', '\App\Controllers\Profile::postChangePassword', ['as' => 'profile.change_password.post']);
});
/**************************************************************************************************************
 *
 *                                  DO NOT CHANGE ANYTHING BELOW HERE
 *
 *                        The routes below have been hardcoded in the application
 *                              Changing them may break some functionality
 *
 **************************************************************************************************************/
//Do not change the ajax requests
//Ajax, none-write none-delete operations
$routes->group('ajax', function ($routes) {
    /** @var RouteCollection $routes */
    $routes->add('class/(:num)/sections', '\App\Controllers\Ajax::sections/$1', ['as' => 'ajax.get.sections.select']);
    $routes->add('class/(:num)/subjects', '\App\Controllers\Ajax::subjects/$1', ['as' => 'ajax.get.subjects.select']);
    $routes->add('class/(:num)/subjects-new', '\App\Controllers\Ajax::subjectsNew/$1', ['as' => 'ajax.get.subjects.select-new']);
    $routes->add('class/(:num)/subjects/(:num)', '\App\Controllers\Ajax::subjectsTeacher/$1/$2', ['as' => 'ajax.get.subjects.select-subjects']);
    $routes->add('section/(:num)/subjects-section/(:num)', '\App\Controllers\Ajax::subjectsSection/$1/$2', ['as' => 'ajax.get.subjects.select-section']);
    $routes->add('section/(:num)/subjects', '\App\Controllers\Ajax::classSubjects/$1', ['as' => 'ajax.get.subjects.select-section']);
    $routes->add('class/(:num)/section', '\App\Controllers\Ajax::classSection/$1', ['as' => 'ajax.get.section']);
    $routes->add('session/(:num)/classes', '\App\Controllers\Ajax::sessionClasses/$1', ['as' => 'ajax.get.session.classes.select']);
    $routes->add('session/(:num)/semesters', '\App\Controllers\Ajax::sessionSemesters/$1', ['as' => 'ajax.get.session.semesters.select']);
    $routes->add('students/(:num)/class/(:num)/section/(:num)', '\App\Controllers\Ajax::sessionClassStudent/$1/$2/$3', ['as' => 'ajax.get.session.class.section.students']);
    $routes->add('students/section/(:num)', '\App\Controllers\Ajax::sectionStudents/$1', ['as' => 'ajax.get.section.students']);
    $routes->add('students/(:num)/classid/(:num)/sectionid/(:num)', '\App\Controllers\Ajax::sessionClassStudentMove/$1/$2/$3', ['as' => 'ajax.get.session.class.section.move']);
    $routes->add('students/departures/(:num)', '\App\Controllers\Ajax::getDepartures/$1', ['as' => 'ajax.get.session.departures']);
    $routes->add('students/(:num)/class_depart/(:num)/section_depart/(:num)', '\App\Controllers\Ajax::sessionClassStudentDepart/$1/$2/$3', ['as' => 'ajax.get.session.class.section.departing']);
    $routes->add('collect/(:num)/class/(:num)/section/(:num)', '\App\Controllers\Ajax::sessionClassStudentFeeCollection/$1/$2/$3', ['as' => 'ajax.get.session.class.section.collect_table']);
    $routes->add('fee-information/(:num)/class/(:num)/section/(:num)', '\App\Controllers\Ajax::feeInformation/$1/$2/$3', ['as' => 'ajax.get.session.class.section.fee_information']);
    $routes->add('semester/(:num)/quarter', '\App\Controllers\Ajax::getSemester/$1', ['as' => 'ajax.get.semester']);
    $routes->add('remove-semester', '\App\Controllers\Ajax::removeSemester', ['as' => 'ajax.get.semester-remove']);

    $routes->add('attendance/students/(:num)/section/(:num)/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::getStudents/$1/$2/$3/$4');
    $routes->add('attendance/students-monthly/(:num)/section/(:num)/(:num)/(:num)', '\App\Controllers\Attendance\Attendance::getStudentsMonthly/$1/$2/$3/$4');
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
