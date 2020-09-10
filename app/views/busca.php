<!--
|
|  Seção para buscar uma sentença
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

    $mongo = new Mongo();

    if (isset($_POST['nro_processo']) and $_POST['nro_processo'] != null) {

        // echo $_POST['nro_processo'];
        $nro_process = trim($_POST['nro_processo']);

        $busca = $mongo->busca($collection, $nro_process);

        $exibe_erro = false;
    } else {
        unset($_POST['nro_processo']);

        $exibe_erro = true;
    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3">
            <label class="pad-sup" for="vara">Digite o nº do Processo</label>

            <form method="post" action="busca">
                <input list="text" name="nro_processo" id="nro_processo" class="form-control" />

                <!-- <input list="vara" value="0000230213354672320232" class="form-control"> -->
                <datalist id="nro_processo"> </datalist>
        </div>

        <div class="col-sm-1">
            <input class="form-control mar-top" type="submit" value="Busca"></input>
        </div>
        </form>
        <!-- <input type="text" name="txt" value="<?php if (isset($message)) {
                                                        echo $message;
                                                    } ?>" > -->

        <!-- <form  method="post">
                    <input class="form-control mar-top" type="submit" name="filtrar" value="Filtrar">
                    <input type="text" name="txt" value="<?php if (isset($message)) {
                                                                echo $message;
                                                            } ?>" >
                </form> -->
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <hr />
        <div class="texto-tabela">
            <table class="table table-responsive-lg table-striped">
                <thead>
                    <tr>
                        <th>Vara</th>
                        <th>Assunto</th>
                        <th>Processo</th>
                        <th>Data</th>
                        <th>Conteúdo</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_POST['nro_processo'])) {
                        foreach ($busca as $arr) {

                            for ($i = 0; $i < count($busca['conteudo']); $i++) {
                                $data = substr($busca['data'][$i], 0, 4) . '/' . substr($busca['data'][$i], 4, 2) . '/' . substr($busca['data'][$i], 6, 2);

                                echo "<tr>";
                                if (isset($busca['vara'][$i]))
                                    echo "<td> " . $busca['vara'] . " </td>";
                                else
                                    echo "<td> </td>";

                                if (isset($busca['assunto'][$i]))
                                    echo "<td> " . $busca['assunto'][$i] . " </td>";
                                else
                                    echo "<td> </td>";

                                if (isset($busca['processo'][$i]))
                                    echo "<td> " . $busca['processo'][$i] . " </td>";
                                else
                                    echo "<td> </td>";

                                echo "<td> " . $data . " </td>";
                                echo "<td> " . $busca['conteudo'][$i] . " </td>";
                                echo "<td> " . $busca['situacao'][$i] . " </td>";

                                echo "</tr>";
                            }
                        }
                        unset($_POST['nro_processo']);
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>