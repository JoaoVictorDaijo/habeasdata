<?php
/*
|----------------------------------------
| ROUTING SYSTEM
|----------------------------------------
| You can define valid routes to be accessed by Router.php
| @usage:   'name/youwant' => 'controller/method';
|
| @warning: Do not use more than two/routenames */

$this->routes=array(

	'index' => 'home/index',
	'home' => 'home/inicio', 
	'home/page' => 'home/inicio', 
	'conteudo' => 'home/conteudo',
	'busca' => 'home/busca',
	'process' => 'home/process',
	'sessao' => 'home/sessao',
	'juiz' => 'home/juiz',
	'pessoa' => 'home/pessoa',
	'genero_autor' => 'home/genero_autor',
	'genero_reu' => 'home/genero_reu'
);
