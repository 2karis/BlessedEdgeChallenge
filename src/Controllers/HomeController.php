<?php 
namespace HomeController;

class HomeController{

	protected $container;

	public function __construct($container) {
        $this->container = $container;
    }

    public function __get($property){
        if($this->container->{$property}){
            return $this->container->{$property};
        }
    }

    public function home($request, $response, $args) {
		$students = $this->db->table('student')->get();
		$data =[
			'students'=>$students
		];
	    return $this->view->render($response, 'home.html', $data);
	}

}