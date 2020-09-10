<!--
|
| Lista de assuntos (parte esquerda da dashboard)
|
-->
<?php

    include_once('app/controllers/process.php');
    $process = new Process();
    $data_session = $process->verificaSessao();

    //PERMITIDO USAR A PLATAFORMA
    $comarca = $data_session['comarca'];
    $collection = $data_session['collection'];

    // CONECTA NO MONGO E PEGA OS ASSUNTOS
    include_once('app/models/database.php');
    $mongo = new Mongo();
    $assuntos = $mongo->list_assunto($collection);
    $section = $process->getSecao();

    if (isset($_POST['assunto_filtro'])) {
        $assunto_filtro = $_POST['assunto_filtro'];
    } else {
        $assunto_filtro = "Todos";
    }

?>

<div class="row">
    <div class="col-sm-3">
        <div class="pad-interno">
            <h5>Assunto: <?php echo $assunto_filtro; ?></h5>
            <hr />
            <div class="rolagem">
                <?php
                    $form_n = 1;
                    echo "<form id='" . $form_n . "' action='" . $section . "' method='post'><div class='assunto' onclick='submitform(" . $form_n . ")'><a>Todos</a></div><input id='invisible' name='assunto_filtro' type='text' value='Todos'></form>";
                    foreach ($assuntos as $row) {
                        $form_n++;
                        echo "<form id='" . $form_n . "' action='" . $section . "' method='post'><div class='assunto' onclick='submitform(" . $form_n . ")'><a>" . $row['_id'] . ": " . $row['count'] . "</a></div><input id='invisible' name='assunto_filtro' type='text' value='" . $row['_id'] . "'></form>";
                    }
                ?>
            </div>
        </div>
    </div>
    