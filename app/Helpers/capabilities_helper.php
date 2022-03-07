<?php
function current_user_can($permission) {
    $user = new \App\Entities\User();
    $ionAuth = new \App\Libraries\IonAuth();
    $user->id = $ionAuth->user()->row()->id;

    if($ionAuth->isAdmin($user->id)){
        return true;
    }
    return $user->can($permission);
}

/**
 * Required system permissions
 * Applied as filters in BaseController::class line ~47
 *
 * @param array $existing
 * @return array
 */
function system_permissions($existing = array()) {
    $perms = [
        [
            'name'  => 'Users Management',
            'capabilities'  => [
                'create_user'   => 'Create user',
                'view_users'    => 'View users',
                'update_user'   => 'Edit/Update user profile',
                'delete_user'   => 'Delete users',
            ]
        ],
        [
            'name'  => 'System Settings',
            'capabilities'  => [
                'update_system_settings'   => 'Update System Settings',
                'manage_modules'    => 'Manage System Modules',
            ]
        ],
        [
            'name'  => 'Exams and Continuous Assessment Tests',
            'capabilities'  => [
                'create_exam'               => 'Create and Update Exam',
                'delete_exam'               => 'Delete Exam/CAT',
                'create_exam_timetable'     => 'Create and Update Exam Timetable',
                'create_exam_results'       => 'Record and update exam results',
                'record_cats'               => 'Records Continuous Assessments'
            ]
        ]
    ];

    return array_merge($existing, $perms);
}