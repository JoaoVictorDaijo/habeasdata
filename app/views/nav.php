<!--
|
| Navbar da dashboard
|
-->
<?php 

session_start();

include_once('app/controllers/process.php');
$process = new Process();
$data_session = $process->verificaSessao();

//PERMITIDO USAR A PLATAFORMA

$comarca = $data_session['comarca'];
$collection = $data_session['collection'];

?>

<div id="cabecalho" class="container-fluid">
        <p class="titulo">Sentenças em <?php echo "$comarca"?></p>
        <a href="<?=$this->config['url'];?>/home"><img class="float-left logo-hd" src="<?=$this->config['url'];?>/assets/img/logo-hd.png"/></a>
        <img class="float-right logo-fea" src="<?=$this->config['url'];?>/assets/img/logo-fea.png"/>
        <img class="float-right logo-usp" src="<?=$this->config['url'];?>/assets/img/logo-usp.png"/>
</div>