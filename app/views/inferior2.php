<!--
|
| Inferior da dashboard
|
-->
<?php 

include_once('app/controllers/process.php');
$process = new Process();
$data_session = $process->verificaSessao();

//PERMITIDO USAR A PLATAFORMA

$comarca = $data_session['comarca'];
$collection = $data_session['collection'];

include_once('app/models/database.php');
$database = new Database();
$mongo = new Mongo();

$conn = $database->connect();
$assuntos = $database->query($conn,'assunto');
$assuntos = $mongo->list_assunto($collection);

$section = $process->getSecao();

?>

<div class="container-fluid sem-pad-right">
    <div class="row sem-pad-right">
        <div class="col-12 sem-pad-right pad-left-0">
            <div class="footer2">
            <h7 class="centralizado">Desenvolvido por <a id="link-linkedin" target="_blank" href="https://www.linkedin.com/in/jo%C3%A3o-victor-daij%C3%B3-374ab2116/">João Victor Daijó</a></h7>
            <img class="float-right logo-habeas-inf2" src="<?=$this->config['url'];?>/assets/img/logo-footer.png"/>
            </div>
        </div>
</div>
