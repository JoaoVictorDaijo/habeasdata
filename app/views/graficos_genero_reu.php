<!--
|
| Lista de assuntos (parte central da dashboard)
|
-->

<?php 

if (!(isset($_SESSION['comarca-parsed']))){
        header('location:index');
}
else {
       $comarca = $_SESSION['comarca-parsed'];
       $collection = $_SESSION['comarca-non-parsed'];
}

set_time_limit(500);

include_once('app/models/database.php');
$sql = new Database();
$conn = $sql->connect();
$mongo = new Mongo();

if (isset($_POST['assunto_filtro'])) {
    $assunto_filtro = $_POST['assunto_filtro'];
}
else {
    $assunto_filtro = "Todos";
}

// CASO NÃO HAJA FILTRO
if($assunto_filtro == 'Todos') {

    // TENTA PEGAR OS DADOS DO CACHE
    $result = mysqli_fetch_row( mysqli_query($conn, "SELECT `genero_reu` FROM `mongodata` WHERE `comarca`='$collection'") );
    
    if($result[0] != NULL) {

        $tipo= "CACHE";
        $s = microtime(true);
        $value = $result[0];
        #Substitui o dummy | em \
        $value= str_replace('|', '\\', $value);
        #Converte o JSON em um array associativo
        $varas = json_decode($value, true);
        $e = microtime(true);
    }

    // PEGA OS DADOS REMOTAMENTE DO MONGO
    else {

        $s = microtime(true);
        $varas = $mongo->genero_reu($collection);
        $e = microtime(true);
        
        #Ordena o array pela quantidade de ocorrencias
        function cmp($a, $b)
        {    
            $a = array_sum($a['masculino']) + array_sum($a['feminino']);
            $b = array_sum($b['masculino']) + array_sum($b['feminino']);

            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        }

        uasort($varas, "cmp");
        
        #Codifica os dados do MongoDB em JSON
        $json = json_encode(mb_convert_encoding($varas, 'UTF-8', 'UTF-8'), true);
        #O banco cache deleta o simbolo /, troca ele por um dummy |
        $json = str_replace('\\', '|', $json);

        // CASO A TUPLA NO BD NÃO EXISTA, INSERE ELA
        if(mysqli_num_rows($result = mysqli_query($conn, "SELECT `vara` FROM `mongodata` WHERE `comarca`='$collection'")) == 0) {
            $tipo = "INSERT";
            #Gera o SQL para inserir no banco
            $sql = "INSERT INTO `mongodata` (`id`, `comarca`, `genero_reu`) VALUES (NULL, '$collection', '$json')";
            //mysqli_query($conn, $sql);
            mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
        }

        // CASO A TUPLA NO BD EXISTA, ATUALIZA ELA
        else {
            $tipo = "UPDATE";
            #Gera o SQL para inserir no banco
            $sql = "UPDATE `mongodata` SET `genero_reu`='$json' WHERE `comarca`='$collection'";
            //mysqli_query($conn, $sql);
            mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
        }
    }
}

// CASO FILTRO DE ASSUNTO SEJA USADO
else {

    $tipo = "FILTRO";
    $s = microtime(true);
    $varas = $mongo->genero_reu($collection, $assunto_filtro);
    $e = microtime(true);

}

// echo "<pre>";
//    print_r($varas);
// echo "</pre>";

// foreach ($varas as $key => $value) {
//     echo "$key <br>"; 
//     for($i=0; $i<count($value['situacao']);$i++) {
//         echo"".$value['situacao'][$i].": ";
//         echo"".$value['count'][$i]." <br>";
//     }
//     echo "<br>";
// }

?>
<!-- CRIA TABELA CENTRAL E ALOCA OS CANVAS -->
<div class="col-sm-7">
            <div class="pad-interno">
                <h5>Gráficos: <?php echo '|'.$tipo . '| MICROTEMPO : ' , ($e-$s); ?></h5>
                <hr/>
                <div class="rolagem texto-tabela">
                    <table class="table table-bordered table-responsive-lg">
                        <thead>
                            <tr>
                                <th id="tum"> Vara </th>
                                <th id="tdois"> Masculino </th>
                                <th id="ttres"> Feminino </th>
                            </tr>
                        </thead>
                        <?php 
                            $id=0;
                            foreach ($varas as $key => $value) {
                                if($key != 'lista') {
                                    echo "  <tr> 
                                                <td  id='tum'> $key </td>
                                                <td id='tdois'>";
                                    echo"<div class='container-fluid'><canvas id='chart_$id'></canvas></div></td>";
                                    $id+=1;
                                    echo "<td id='ttres'>";
                                    echo"<div class='container-fluid'><canvas id='chart_$id'></canvas></div></td>";
                                    echo    "</tr>";
                                    $id+=1;
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
    $id=0;
    foreach ($varas as $key => $value) {
        if($key != 'lista') {
            $graph->plot_js($value['masculino'], $id);
            $id+=1;
            $graph->plot_js($value['feminino'], $id);
            $id+=1;
        }
    }
?>