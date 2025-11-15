<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index', [
    'as' => 'login.view',
    'filter' => 'guest'
]);

$routes->post('/', 'Login::authenticate', [
    'as' => 'login.auth',
    'filter' => ['guest', 'throttle:2']
]);

$routes->group('lupa-kata-sandi', ['filter' => 'guest'], static function ($routes) {
    $routes->get('/', 'ForgotPassword::index', ['as' => 'forgot-password.index']);
    $routes->post('/', 'ForgotPassword::sendResetLink', [
        'as' => 'forgot-password.send',
        'filter' => 'throttle:2'
    ]);
});

$routes->group('atur-ulang-kata-sandi', ['filter' => 'guest'], static function ($routes) {
    $routes->get('/', 'PasswordReset::index', ['as' => 'password-reset.index']);
    $routes->post('/', 'PasswordReset::save', ['as' => 'password-reset.save']);
});

$routes->group('admin', ['filter' => 'rolecheck:admin'], static function ($routes) {
    $routes->post('logout', 'Logout::deleteSession', ['as' => 'admin.logout']);

    $routes->get('dashboard', 'Admin\Dashboard::index', ['as' => 'admin.dashboard.index']);

    $routes->group('profil', static function ($routes) {
        $routes->get('/', 'Admin\Profile::index', ['as' => 'admin.profile.index']);
        $routes->get('ubah', 'Admin\Profile::edit', ['as' => 'admin.profile.edit']);
        $routes->post('ubah', 'Admin\Profile::update', ['as' => 'admin.profile.update']);
    });

    $routes->group('ubah-kata-sandi', static function ($routes) {
        $routes->get('/', 'Admin\ChangePassword::edit', ['as' => 'admin.change-password.edit']);
        $routes->post('/', 'Admin\ChangePassword::update', ['as' => 'admin.change-password.update']);
    });

    $routes->group('aplikasi', static function ($routes) {
        $routes->get('/', 'Admin\Application::index', ['as' => 'admin.application.index']);
        $routes->get('ubah', 'Admin\Application::edit', ['as' => 'admin.application.edit']);
        $routes->post('ubah', 'Admin\Application::update', ['as' => 'admin.application.update']);
    });

    $routes->group('pengguna', static function ($routes) {
        $routes->get('/', 'Admin\User::index', ['as' => 'admin.users.index']);
        $routes->get('tambah', 'Admin\User::create', ['as' => 'admin.users.create']);
        $routes->post('tambah', 'Admin\User::store', ['as' => 'admin.users.store']);
        $routes->get('(:segment)/ubah', 'Admin\User::edit/$1', ['as' => 'admin.users.edit']);
        $routes->post('(:segment)/ubah', 'Admin\User::update/$1', ['as' => 'admin.users.update']);
        $routes->post('(:segment)/hapus', 'Admin\User::delete/$1', ['as' => 'admin.users.delete']);
    });

    $routes->group('ujian', static function ($routes) {
        $routes->get('/', 'Admin\Exam::index', ['as' => 'admin.exams.index']);
        $routes->get('tambah', 'Admin\Exam::create', ['as' => 'admin.exams.create']);
        $routes->post('tambah', 'Admin\Exam::store', ['as' => 'admin.exams.store']);
        $routes->get('(:segment)/ubah', 'Admin\Exam::edit/$1', ['as' => 'admin.exams.edit']);
        $routes->post('(:segment)/ubah', 'Admin\Exam::update/$1', ['as' => 'admin.exams.update']);
        $routes->post('(:segment)/hapus', 'Admin\Exam::delete/$1', ['as' => 'admin.exams.delete']);
    });
});

$routes->group('guru', ['filter' => 'rolecheck:teacher'], static function ($routes) {
    $routes->post('logout', 'Logout::deleteSession', ['as' => 'teacher.logout']);

    $routes->get('dashboard', 'Teacher\Dashboard::index', ['as' => 'teacher.dashboard.index']);

     $routes->group('profil', static function ($routes) {
        $routes->get('/', 'Teacher\Profile::index', ['as' => 'teacher.profile.index']);
        $routes->get('ubah', 'Teacher\Profile::edit', ['as' => 'teacher.profile.edit']);
        $routes->post('ubah', 'Teacher\Profile::update', ['as' => 'teacher.profile.update']);
    });

    $routes->group('ubah-kata-sandi', static function ($routes) {
        $routes->get('/', 'Teacher\ChangePassword::edit', ['as' => 'teacher.change-password.edit']);
        $routes->post('/', 'Teacher\ChangePassword::update', ['as' => 'teacher.change-password.update']);
    });

    $routes->group('soal', static function ($routes) {
        $routes->get('/', 'Teacher\Question::index', ['as' => 'teacher.questions.index']);
        $routes->get('list', 'Teacher\Question::list', ['as' => 'teacher.questions.list']);
        $routes->get('tambah', 'Teacher\Question::create', ['as' => 'teacher.questions.create']);
        $routes->post('tambah', 'Teacher\Question::store', ['as' => 'teacher.questions.store']);
        $routes->get('(:segment)/ubah', 'Teacher\Question::edit/$1', ['as' => 'teacher.questions.edit']);
        $routes->post('(:segment)/ubah', 'Teacher\Question::update/$1', ['as' => 'teacher.questions.update']);
        $routes->post('(:segment)/hapus', 'Teacher\Question::delete/$1', ['as' => 'teacher.questions.delete']);
    });
});

$routes->group('murid', ['filter' => 'rolecheck:student'], static function ($routes) {
    $routes->post('logout', 'Logout::deleteSession', ['as' => 'student.logout']);

    $routes->get('beranda', 'Student\Home::index', ['as' => 'student.home.index']);

    $routes->group('profil', static function ($routes) {
        $routes->get('/', 'Student\Profile::index', ['as' => 'student.profile.index']);
        $routes->get('ubah', 'Student\Profile::edit', ['as' => 'student.profile.edit']);
        $routes->post('ubah', 'Student\Profile::update', ['as' => 'student.profile.update']);
    });

    $routes->group('ubah-kata-sandi', static function ($routes) {
        $routes->get('/', 'Student\ChangePassword::edit', ['as' => 'student.change-password.edit']);
        $routes->post('/', 'Student\ChangePassword::update', ['as' => 'student.change-password.update']);
    });

    $routes->group('mulai-ujian', static function ($routes) {
        $routes->get('/', 'Student\StartExam::index', ['as' => 'student.start-exam.index']);
        $routes->get('(:segment)/(:segment)', 'Student\StartExam::create/$1/$2', ['as' => 'student.start-exam.create']);
        $routes->post('(:segment)/(:segment)', 'Student\StartExam::store/$1/$2', ['as' => 'student.start-exam.store']);
        $routes->post('(:segment)', 'Student\StartExam:Finish/$1', ['as' => 'student.start-exam.finish']);
    });
});

$routes->set404Override('App\Controllers\Error::show404');