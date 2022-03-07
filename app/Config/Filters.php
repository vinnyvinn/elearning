<?php namespace Config;

use App\Filters\AdminFilter;
use App\Filters\LoggedInFilter;
use App\Filters\ParentFilter;
use App\Filters\StudentFilter;
use App\Filters\TeacherFilter;
use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,

        'isLoggedIn' => LoggedInFilter::class,
        'isAdmin' => AdminFilter::class,
        'isStudent' => StudentFilter::class,
        'isTeacher' => TeacherFilter::class,
        'isParent' => ParentFilter::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			//'honeypot'
			// 'csrf',
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
	    //'isLoggedIn' => ['before' => ['account/*', 'admin/*', 'profile/*', 'account', 'admin', 'profile', 'parent*', 'teacher*', 'student*']],
	    'isAdmin' => ['before' => ['admin/*', 'admin']],
	    'isTeacher' => ['before' => ['teachers/*', 'teachers']],
	    'isStudent' => ['before' => ['students/*', 'students']],
	    'isParent' => ['before' => ['parents/*', 'parents']],
    ];
}
