<!--
|
|  Seção que mostra as sentenças detalhadamente
|
-->

<?php

    #Pega a Comarca da sessão
    include_once('app/controllers/process.php');
    $process = new Process();
    $data_session = $process->verificaSessao();
    //PERMITIDO USAR A PLATAFORMA
    $comarca = $data_session['comarca'];
    $collection = $data_session['collection'];

    #Chama as classes de BD
    include_once('app/models/database.php');
    $mongo = new Mongo();
    $sql = new Database();
    $conn = $sql->connect();

    #Carrega a classe de gráficos (apenas para usar a função que parseia os resultados)
    include_once('app/controllers/graph.php');
    $graph = new Graphs();

    #Pega as varas salvas no CACHE
    if (mysqli_num_rows($result = mysqli_query($conn, "SELECT `vara` FROM `mongodata` WHERE `comarca`='$collection'")) != 0) {
        $result = str_replace('|', '\\', mysqli_fetch_row($result));
        $varas = array_keys(json_decode($result[0], true));
    } else {
        echo "
                <script>
                    alert('Dados não encontrados, por favo vá para a página de Home para recarregar os processos');
                </script>
            ";
    }

    #Pega os assuntos
    $result = $mongo->list_assunto($collection);
    foreach ($result as $row)
        $assuntos[] = $row['_id'];

    #Coloca os assuntos em ordem alfabética
    function compareASCII($a, $b)
    {
        $at = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
        $bt = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
        return strcmp($at, $bt);
    }
    uasort($assuntos, 'compareASCII');

    #Cria um array com os possíveis resultados
    $resultados = array('homologado', 'procedente', 'parcialmente_procedente', 'improcedente', 'sem_merito', 'extincao_do_processo', 'nc');

    // PEGA OS FILTROS POR POST
    
    #Pega o filtro de VARA do usuário
    if (isset($_POST['vara']) and $_POST['vara'] != null and $_POST['vara'] != '(Tudo)') {
        //$vara_param = ' "Dados_Fórum" => [\'$regex\' => "'.$_POST['vara'].'" ],';
        $vara_param = $_POST['vara'];
        $exibe_erro = false;
    } else {
        unset($_POST['vara']);
        $vara_param = '.*';
        $exibe_erro = true;
    }

    #Pega o filtro de ASSUNTO do usuário
    if (isset($_POST['assunto']) and $_POST['assunto'] != null and $_POST['assunto'] != '(Tudo)') {
        #$assunto_param = ' "Assunto" => [\'$regex\' => "'.$_POST['assunto'].'" ],';
        $assunto_param = $_POST['assunto'];
        $exibe_erro = false;
    } else {
        unset($_POST['assunto']);
        $assunto_param = '.*';
        $exibe_erro = true;
    }

    #Pega o filtro de RESULTADO do usuário
    if (isset($_POST['resultado']) and $_POST['resultado'] != null and $_POST['resultado'] != '(Tudo)') {
        //$resultado_param = ' "Sentenca.0.Situacao" => [\'$regex\' => "'.$_POST['resultado'].'" ],';
        $resultado_param = $_POST['resultado'];
        $exibe_erro = false;
    } else {
        unset($_POST['resultado']);
        $resultado_param = '.*';
        $exibe_erro = true;
    }

    #Quando o botão FILTRAR for clicado faz a consulta pelos documentos no Mongo
    if (isset($_POST['filtrar'])) {

        $filter = $vara_param . $resultado_param . $assunto_param;
        $busca = $mongo->consulta($collection, $vara_param, $assunto_param, $resultado_param);

    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3">
            <form method="post" action="conteudo">
                <label class="pad-sup" for="vara">Vara</label>
                <select list="vara" value="1ª VARA" name="vara" id="vara" class="form-control">
                    <option value="(Tudo)" selected>Todos</option>
                    <?php
                    foreach ($varas as $vara) {
                        echo "<option value=" . $vara . ">" . $vara . "</option>";
                    }
                    ?>
                </select>
        </div>

        <div class="col-sm-3">
            <label class="pad-sup" for="assunto">Assunto</label>
            <select list="assunto" value="(Tudo)" name="assunto" id="assunto" class="form-control">
                <option value="(Tudo)" selected>Todos</option>
                <?php
                foreach ($assuntos as $assunto) {
                    echo "<option value=" . $assunto . ">" . $assunto . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-sm-3">
            <label class="pad-sup" for="resultado">Resultado</label>
            <select list="resultado" value="(Tudo)" name="resultado" id="resultado" class="form-control">
                <option value="(Tudo)" selected>Todos</option>
                <?php
                foreach ($resultados as $resultado) {
                    echo "<option value=" . $resultado . ">" . $resultado . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-sm-1">
            <input class="form-control mar-top" type="submit" name="filtrar" id="filtrar" value="Filtrar" />
        </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <hr />
        <div class="rolagem-maior texto-tabela">
            <table class="table table-striped table-responsive-lg">
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
                    if (isset($_POST['filtrar'])) {
                        foreach ($busca as $doc) {
                            echo "<tr>";

                            echo "<td> " . $doc['vara'] . " </td>";
                            echo "<td> " . $doc['assunto'] . " </td>";
                            echo "<td> " . $doc['processo'] . " </td>";
                            echo "<td> " . $doc['data'] . " </td>";
                            echo "<td> " . $doc['conteudo'] . " </td>";
                            echo "<td> " . $graph->word_parser($doc['resultado']) . " </td>";

                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>