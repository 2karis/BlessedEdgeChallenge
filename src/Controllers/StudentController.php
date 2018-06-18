<?php 
namespace StudentController;

class StudentController{

    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __get($property){
        if($this->container->{$property}){
            return $this->container->{$property};
        }
    }

    public function viewStudent($request, $response, $args) {
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
        
    }

    public function newStudent($request, $response, $args) {
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
    }


}