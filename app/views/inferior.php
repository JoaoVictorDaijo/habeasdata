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

foreach($assuntos as $key => $values)
    $names[] = $values['_id'];
    
#Coloca comarcas em ordem alfabética
function compareASCII($a, $b) {
    $at = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
    $bt = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
    return strcmp($at, $bt);
}

usort($names, 'compareASCII');

$section = $process->getSecao();

?>

<div class="container-fluid sem-pad-right">
    <div class="row sem-pad-right">
        <div class="col-12 sem-pad-right">
            <div class="footer">
              
            <div class="col-3">
            <label class="pad-sup" for="filtro-assunto">Filtro de assunto</label>
                <form class="form-inline" method="post" action="<?php echo $section; ?>">
                <select name="assunto_filtro" id="assunto_filtro" class="form-control pad-right">
                    <option value="Todos" selected>Todos</option>
                    <?php
                    foreach($names as $row) {
                        echo "<option value=".$row.">".$row."</option>";
                        } 
                    ?>
                </select>
                <input class="form-control ponteiro" type="submit" id="filtrar" value="Filtrar"/>
                </form>    
            </div>                     


            <h7 class="centralizado">Desenvolvido por <a id="link-linkedin" target="_blank" href="https://www.linkedin.com/in/jo%C3%A3o-victor-daij%C3%B3-374ab2116/">João Victor Daijó</a></h7>
            <img class="float-right logo-habeas-inf" src="<?=$this->config['url'];?>/assets/img/logo-footer.png"/>
            </div>
        </div>
</div>