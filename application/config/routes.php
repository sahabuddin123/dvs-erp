<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

spl_autoload_register(function($className) {
    if ( substr($className, -6) == "_Addon" ) {
        $file = APPPATH . 'core/' . $className . '.php';
        if ( file_exists($file) && is_file($file) ) {
            @include_once( $file );
        }
    }
});

$routes_path = APPPATH . 'config/my_routes/';
if (is_dir($routes_path)) {
	$routes = scandir($routes_path);
	foreach ($routes as $r_file)
	{
	    if ($r_file === '.' || $r_file === '..' || $r_file === 'index.html') {
	        continue;
	    }
        $route_path = $routes_path . $r_file;
        if (file_exists($route_path)) {
            @include_once $route_path; 
        }
	} 
}

$route['(:any)/authentication'] = 'authentication/index/$1';
$route['(:any)/forgot'] = 'authentication/forgot/$1';
$route['teachers'] = 'home/teachers';
$route['(:any)/teachers'] = 'home/teachers';
$route['(:any)/events'] = 'home/events';
$route['(:any)/news'] = 'home/news/';
$route['lang/(:any)'] = 'home/lang/$1';
$route['locale/(:any)'] = 'home/lang/$1';
$route['history'] = 'home/history';
$route['(:any)/history'] = 'home/history';
$route['governing-body'] = 'home/governing_body';
$route['governing_body'] = 'home/governing_body';
$route['(:any)/governing-body'] = 'home/governing_body';
$route['(:any)/governing_body'] = 'home/governing_body';
$route['message-founder/(:any)'] = 'home/message_founder/$1';
$route['(:any)/message-founder/(:any)'] = 'home/message_founder/$2';
$route['message-chairman'] = 'home/message_founder/1';
$route['(:any)/message-chairman'] = 'home/message_founder/1';
$route['staff'] = 'home/staff';
$route['(:any)/staff'] = 'home/staff';
$route['about'] = 'home/about';
$route['faq'] = 'home/faq';
$route['admission'] = 'home/admission';
$route['admission_confirmation/(:any)'] = 'home/admission_confirmation/$1';
$route['gallery'] = 'home/gallery';
$route['contact'] = 'home/contact';
$route['admit_card'] = 'home/admit_card';
$route['exam_results'] = 'home/exam_results';
$route['certificates'] = 'home/certificates';
$route['events'] = 'home/events';
$route['news'] = 'home/news';
$route['notice'] = 'home/news';
$route['(:any)/notice'] = 'home/news';
$route['students'] = 'home/students';
$route['(:any)/students'] = 'home/students';
$route['student/(:any)'] = 'home/student_department/$1';
$route['(:any)/student/(:any)'] = 'home/student_department/$2';
$route['calendar-school'] = 'home/calendar_school';
$route['(:any)/calendar-school'] = 'home/calendar_school';
$route['result-school'] = 'home/exam_results';
$route['(:any)/result-school'] = 'home/exam_results';
$route['class-routine'] = 'home/about';
$route['(:any)/class-routine'] = 'home/about';
$route['notice-details/(:any)'] = 'home/news_view/$1';
$route['(:any)/notice-details/(:any)'] = 'home/news_view/$2';
$route['gallery-details/(:any)'] = 'home/gallery_view/$1';
$route['(:any)/gallery-details/(:any)'] = 'home/gallery_view/$2';
$route['page/(:any)'] = 'home/page/$1';
$route['gallery_view/(:any)'] = 'home/gallery_view/$1';
$route['event_view/(:num)'] = 'home/event_view/$1';
$route['news_view/(:any)'] = 'home/news_view/$1';
$route['(:any)/about'] = 'home/about';
$route['(:any)/faq'] = 'home/faq';
$route['(:any)/admission'] = 'home/admission';
$route['(:any)/gallery'] = 'home/gallery';
$route['(:any)/contact'] = 'home/contact';
$route['(:any)/admit_card'] = 'home/admit_card';
$route['(:any)/exam_results'] = 'home/exam_results';
$route['(:any)/certificates'] = 'home/certificates';
$route['(:any)/page/(:any)'] = 'home/page/$2';
$route['(:any)/gallery_view/(:any)'] = 'home/gallery_view/$2';
$route['(:any)/event_view/(:num)'] = 'home/event_view/$2';
$route['(:any)/news_view/(:any)'] = 'home/news_view/$2';

$route['dashboard'] = 'dashboard/index';
$route['branch'] = 'branch/index';
$route['attachments'] = 'attachments/index';
$route['homework'] = 'homework/index';
$route['onlineexam'] = 'onlineexam/index';
$route['hostels'] = 'hostels/index';
$route['event'] = 'event/index';
$route['accounting'] = 'accounting/index';
$route['school_settings'] = 'school_settings/index';
$route['role'] = 'role/index';
$route['sessions'] = 'sessions/index';
$route['translations'] = 'translations/index';
$route['cron_api'] = 'cron_api/index';
$route['modules'] = 'modules/index';
$route['system_student_field'] = 'system_student_field/index';
$route['custom_field'] = 'custom_field/index';
$route['backup'] = 'backup/index';
$route['advance_salary'] = 'advance_salary/index';
$route['system_update'] = 'system_update/index';
$route['certificate'] = 'certificate/index';
$route['payroll'] = 'payroll/index';
$route['leave'] = 'leave/index';
$route['award'] = 'award/index';
$route['classes'] = 'classes/index';
$route['student_promotion'] = 'student_promotion/index';
$route['live_class'] = 'live_class/index';
$route['exam'] = 'exam/index';
$route['profile'] = 'profile/index';
$route['sections'] = 'sections/index';
$route['biometric'] = 'biometric/index';
$route['biometric/(:any)'] = 'biometric/$1';
$route['biometric/(:any)/(:any)'] = 'biometric/$1/$2';

$route['authentication'] = 'authentication/index';
$route['home'] = 'home/index';
$route['404_override'] = 'errors';
if (!empty($saas_default) && $saas_default == true) {
	$route['default_controller'] = 'saas_website/index';
} else {
	$route['default_controller'] = 'home';
}
$route['(:any)'] = 'home/index/$1';
$route['translate_uri_dashes'] = FALSE;