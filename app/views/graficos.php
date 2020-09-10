<!--
|
| Lista de assuntos (parte central da dashboard)
|
-->

<?php

    include_once('app/models/database.php');
    include_once('app/controllers/process.php');
    $process = new Process();
    $data_session = $process->verificaSessao();

    $sql = new Database();
    $conn = $sql->connect();
    $mongo = new Mongo();

    //PERMITIDO USAR A PLATAFORMA
    $comarca = $data_session['comarca'];
    $collection = $data_session['collection'];

    set_time_limit(500);

    if (isset($_POST['assunto_filtro'])) {
        $assunto_filtro = $_POST['assunto_filtro'];
    } else {
        $assunto_filtro = "Todos";
    }

    // CASO NÃO HAJA FILTRO
    if ($assunto_filtro == 'Todos') {

        // TENTA PEGAR OS DADOS DO CACHE
        if (mysqli_num_rows($result = mysqli_query($conn, "SELECT `vara` FROM `mongodata` WHERE `comarca`='$collection'")) != 0) {

            $tipo = "CACHE";
            $s = microtime(true);
            #Converte o resultado da query num array
            while ($assoc = mysqli_fetch_row($result)) {

                $value = $assoc[0];
                #Substitui o dummy | em \
                $value = str_replace('|', '\\', $value);
                #Converte o JSON em um array associativo
                $varas = json_decode($value, true);
            }
            $e = microtime(true);
        } 
        
        // PEGA OS DADOS REMOTAMENTE DO MONGO
        else {

            $s = microtime(true);
            $varas = $mongo->vara($collection);
            $e = microtime(true);

            uasort($varas, function ($a, $b) {
                return array_sum($b) - array_sum($a);
            });

            #Limpa o banco cache caso > 50 comarcas estejam armazenadas
            $sql->clean($conn);
            #Codifica os dados do MongoDB em JSON
            $json = json_encode(mb_convert_encoding($varas, 'UTF-8', 'UTF-8'), true);
            #O banco cache deleta o simbolo /, troca ele por um dummy |
            $json = str_replace('\\', '|', $json);

            // CASO A TUPLA NO BD NÃO EXISTA, INSERE ELA
            if (mysqli_num_rows($result = mysqli_query($conn, "SELECT `vara` FROM `mongodata` WHERE `comarca`='$collection'")) == 0) {
                $tipo = "INSERT";
                #Gera o SQL para inserir no banco
                $sql = "INSERT INTO `mongodata` (`id`, `comarca`, `vara`) VALUES (NULL, '$collection', '$json')";
                //mysqli_query($conn, $sql);
                mysqli_query($conn, $sql)
                    or die(mysqli_error($conn));
            } 
            
            // CASO A TUPLA NO BD EXISTA, ATUALIZA ELA
            else {
                $tipo = "UPDATE";
                #Gera o SQL para inserir no banco
                $sql = "UPDATE `mongodata` SET `vara`='$json' WHERE `comarca`='$collection'";
                //mysqli_query($conn, $sql);
                mysqli_query($conn, $sql)
                    or die(mysqli_error($conn));
            }
        }
    } 
    
    // CASO UM ASSUNTO ESPECIFICO TENHA SIDO ESCOLHIDO
    else {

        $tipo = "FILTRO";
        $s = microtime(true);
        $varas = $mongo->vara($collection, $assunto_filtro);
        $e = microtime(true);
    }

    // echo '<pre>';
    // print_r($varas);
    // echo  '</pre>';

?>

<!-- CRIA TABELA CENTRAL E ALOCA OS CANVAS -->
<div class="col-sm-7">
    <div class="pad-interno">
        <h5>Gráficos :<?php echo '|' . $tipo . '| MICROTEMPO : ', ($e - $s); ?></h5>
        <hr />
        <div class="rolagem texto-tabela">
            <table class="table table-bordered table-responsive-lg">
                <thead>
                    <tr>
                        <th id="tmenor"> Vara </th>
                        <th> Situação </th>
                    </tr>
                </thead>
                <?php
                $id = 0;
                foreach ($varas as $key => $value) {
                    if ($key != 'lista') {
                        echo "  
                        <tr> 
                            <td id='tmenor'> $key </td>
                            <td> <div class='container-fluid'><canvas id='chart_$id'></canvas></div></td>
                        </tr>";
                        $id += 1;
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

<?php
    // CRIA OS GRÁFICOS E ASSOCIA COM OS CANVAS CRIADOS ACIMA
    include_once('app/controllers/graph.php');
    $graph = new Graphs();
    $id = 0;
    foreach ($varas as $key => $value) {
        if ($key != 'lista') {
            $graph->plot_js($value, $id);
            $id += 1;
        }
    }
?>