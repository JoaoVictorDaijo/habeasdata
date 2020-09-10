<!--
|
|  Seção inicial que mostra as comarcas
|
-->
<?php

    session_start();

    unset($_SESSION['comarca-parsed']);
    unset($_SESSION['comarca-non-parsed']);

    include_once('app/models/database.php');
    $mongo = new Mongo();
    $comarca = $mongo->list_collection();

?>

<div class="box">
    <div class="top-bar">
        <p>Comarcas</p>
    </div>
    <p id="texto-comarca">Selecione uma comarca:</p>
    <div class="marg-input">
        <form method="post" action="sessao" name="selecaoComarca" onSubmit="return selecionarComarca();">
            <select name="comarcas" id="comarcas" class="form-control">
                <option value="none" selected>Selecione</option>
                <?php
                foreach ($comarca as $arr) {
                    if (strpos($arr['parsed2'], $arr['parsed']) and strpos($arr['parsed3'], $arr['parsed']))
                        echo "<option value=" . str_replace(' ', '_', $arr['parsed']) . "|||" . $arr['non-parsed'] . ">" . $arr['parsed'] . "</option>";
                    else
                        echo "<option value=" . str_replace(' ', '_', $arr['parsed']) . "|||" . $arr['non-parsed'] . ">" . $arr['parsed'] . " (" . trim($arr['parsed3']) . ") </option>";
                }
                ?>
            </select>
    </div>
    <div class="marg-submit">
        <input type="submit" class="btn btn-botoes submit-comarca" value="Entrar">
    </div>
    </form>
</div>
</div>

<img class="logo-footer" src="assets/img/logo-footer.png">