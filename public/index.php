<?php

require '../vendor/autoload.php';
include  '../src/controllers/HomeController.php';
include  '../src/controllers/GradeController.php';
include  '../src/controllers/StudentController.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['username']   = 'root';
$config['db']['password']   = '';
$config['db']['database'] = 'school';
$config['db']['driver'] = 'mysql';


$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['view'] =function ($container) {

 $view = new \Slim\Views\Twig('../src/Views/');
 $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
 	return $view;
};


$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

$container['HomeController'] = function($container) {
    return new HomeController\HomeController($container);
};
$container['GradeController'] = function($container) {
    return new GradeController\GradeController($container);
};
$container['StudentController'] = function($container) {
    return new StudentController\StudentController($container);
};

$app->get('/student/{student_id}', \StudentController::class . ':viewStudent')->setname('student.view');

$app->post('/new-student', \StudentController::class . ':newStudent' );

$app->post('/student/{student_id}/new-grade', \GradeController::class . ':newGrade' );

$app->get('/grade/{grade_id}/edit-grade', \GradeController::class . ':getEditGrade')->setname('grade.get');

$app->post('/grade/{grade_id}/post-grade', \GradeController::class . ':postEditGrade')->setname('grade.post');

$app->get('/', \HomeController::class . ':home')->setname('home');

$app->run();

