<!--
|
|  Legenda (parte direita da dashboard)
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

<div class="col-sm-2 ">
    <div class="pad-interno">
        <h5>Legenda</h5>
        <hr/>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-hom"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">Homologado</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-pro"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">Procedente</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-par"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">Parcialmente Procedente</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-imp"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">Improcedente</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-sem"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">Sem Mérito</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-ext"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">Extinção do Processo</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="legenda-nc"></div>
            </div>
            <div class="col-sm-10 sem-pad-left">
                <p class="legenda-texto">NC (Não Computado)</p>
            </div>
        </div>
        <hr/>

        <div class="row">
            <div class="col-sm-12">
            <h5>Sobre</h5>
            <p class='text-justify'>Esta plataforma tem o objetivo de apresentar para o usuário um conjunto de indicadores estatísticos relativos aos processos da comarca selecionada.</p>
            <p class='text-justify'>As informações sobre a situação do processo são agrupadas por cinco categorias: Vara, Juiz, Pessoa (Autor), Gênero (Autor) e Gênero (Réu).</p>
            <p class='text-justify'>A plataforma também conta com duas ferramentas de consulta: Conteúdo - para filtrar e apresentar o conteúdo dos procesos da comarca selecionada - e Busca - permite ao usuário buscar um processo no banco por meio de seu identificador.</p>
            </div>    
        </div>

        <!-- <div class="row">
            <div class="col-sm-12">
                <label class="pad-sup" for="filtro-assunto">Filtro de assunto</label>
                <form method="post" action="<?php echo $section; ?>">
                <select name="assunto_filtro" id="assunto_filtro" class="form-control">
                    <option value="Todos" selected>Todos</option>
                    <?php
                    /*foreach($assuntos as $row) {
                        echo "<option value=".$row['_id'].">".$row['_id']."</option>";
                        } */
                    ?>
                </select>
                <input class="form-control mar-top-sm mar-bot" type="submit" id="filtrar" value="Filtrar"/>
                </form>
            </div>
        </div> -->
    </div>
</div>