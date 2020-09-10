<?php

/*
|---------------------------------------------
| Example of a Home controller
|---------------------------------------------
| Loading view pages using the view method on Controller.php class
| @usage:   $this->view('nameofviewfile');
| 
| @warning: View files extension are set as .php, if you want to use .hmtl
| or other extension, please change the view method on Controller.php */

class Home extends Controller{

	public function index(){
		$this->view('templates/head');
		$this->view('comarca');
		$this->view('templates/footer');
	}

	public function inicio(){
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('assuntos');
		$this->view('graficos');
		$this->view('legenda');
		$this->view('inferior');
		$this->view('templates/footer');
	}

	public function juiz(){
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('assuntos');
		$this->view('graficos_juiz');
		$this->view('legenda');
		$this->view('inferior');
		$this->view('templates/footer');
	}

	public function pessoa(){
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('assuntos');
		$this->view('graficos_pessoa');
		$this->view('legenda');
		$this->view('inferior');
		$this->view('templates/footer');
	}

	public function genero_autor(){
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('assuntos');
		$this->view('graficos_genero_autor');
		$this->view('legenda');
		$this->view('inferior');
		$this->view('templates/footer');
	}

	public function genero_reu(){
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('assuntos');
		$this->view('graficos_genero_reu');
		$this->view('legenda');
		$this->view('inferior');
		$this->view('templates/footer');
	}

	public function conteudo(){
		
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('conteudo');
		$this->view('inferior2');
		$this->view('templates/footer');
	}

	public function busca() {
		$this->view('templates/head');
		$this->view('nav');
		$this->view('menu');
		$this->view('busca');
		$this->view('inferior2');
		$this->view('templates/footer');
	}

	public function process() {
		$this->controller('process');
	}

	public function sessao() {
		$this->controller('sessao');
	}
}
