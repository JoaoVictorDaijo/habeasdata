<?php


// SETA A COMARCA SELECIONADA NO MENU INICIAL COMO UMA $_SESSION
if (isset($_POST['comarcas'])){
    session_start();

    list($parsed,$_SESSION['comarca-non-parsed']) = explode('|||', $_POST['comarcas']);
    $_SESSION['comarca-parsed'] = str_replace('_',' ',$parsed);

    header("location:home");
}
else {
    unset($_SESSION['comarca-parsed']);
    unset($_SESSION['comarca-non-parsed']);
    header("location:index");
}

?>