<?php
require '../vendor/autoload.php';


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


//student

$app->get('/student/{student_id}', function ($request, $response, $args) {
    $id = $args['student_id'];
    $student = $this->db
    ->table('student')
    ->where('student.id', $id)
    ->first();
    $grades = $this->db
    ->table('grade')
    ->where('grade.student_id', $id)
    ->get();
    $data = [
		'student'=>$student,
		'grades'=>$grades
	];
    return $this->view->render($response, 'student.html', $data);
    
})->setname('student.view');


$app->post('/new-student', function ($request, $response, $args) {
	$f_name = $request->getParam('f_name');
	$l_name = $request->getParam('l_name');
	$major = $request->getParam('major');

    $students = $this->db
    ->table('student')
    ->insert([
    	'FirstName'=>$f_name,
    	'LastName'=>$l_name,
    	'Major'=>$major
    ]);
    return $response->withRedirect($this->router->pathFor('home'));
});

//grade

$app->post('/student/{student_id}/new-grade', function ($request, $response, $args) {
	$id = $args['student_id'];

	$course = $request->getParam('course');
	$grade = $request->getParam('grade');

    $students = $this->db
    ->table('grade')
    ->insert([
    	'student_id'=>$id,
    	'course'=>$course,
    	'grade'=>$grade,
    ]);
    return $response->withRedirect($this->router->pathFor('student.view', ['student_id' => $id]));
});


//edit grade

$app->get('/grade/{grade_id}/edit-grade', function ($request, $response, $args) {
	$id = $args['grade_id'];

	$grade = $this->db->table('grade')->where('id',$id)->first();
	$data =[
		'grade'=>$grade
	];
    return $this->view->render($response, 'edit.html', $data);
})->setname('grade.get');


$app->post('/grade/{grade_id}/post-grade', function ($request, $response, $args) {
	$id = $args['student_id'];

	$course = $request->getParam('course');
	$grade = $request->getParam('grade');
	$student_id = $request->getParam('student_id');

    $this->db
    ->table('grade')
    ->where('id', $id)
    ->update([
    	'course'=>$course,
    	'grade'=>$grade,
    ]);
    return $response->withRedirect($this->router->pathFor('student.view', ['student_id' => $student_id]));
})->setname('grade.post');


//home page

$app->get('/', function ($request, $response, $args) {
	$students = $this->db->table('student')->get();
	$data =[
		'students'=>$students
	];
    return $this->view->render($response, 'home.html', $data);
})->setname('home');

$app->run();

