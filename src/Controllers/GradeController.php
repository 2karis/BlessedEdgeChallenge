<?php 
namespace GradeController;
use src\Models\grade;
class GradeController{

	protected $container;

	public function __construct($container) {
        $this->container = $container;
    }

    public function __get($property){
        if($this->container->{$property}){
            return $this->container->{$property};
        }
    }

    public function newGrade($request, $response, $args) {
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
    }

	public function getEditGrade($request, $response, $args) {
		$id = $args['grade_id'];

		$grade = $this->db->table('grade')->where('id',$id)->first();
		$data =[
			'grade'=>$grade
		];
	    return $this->view->render($response, 'edit.html', $data);
	}

	public function postEditGrade($request, $response, $args) {
		$id = $args['grade_id'];

		$course = $request->getParam('course');
		$grade = $request->getParam('grade');
		$student_id = $request->getParam('student_id');

		grade::where('id', $id)
	    ->update([
	    	'course'=>$course,
	    	'grade'=>$grade,
	    ]);
		
		return $response->withRedirect($this->router->pathFor('student.view', ['student_id' => $student_id]));
	}

	public function postDeleteGrade($request, $response, $args) {
		$student_id = $args['student_id'];
		$id = $args['grade_id'];

		grade::where('id', $id)
	    ->delete();
		
		return $response->withRedirect($this->router->pathFor('student.view', ['student_id' => $student_id]));
	}
}
