<?php

class Process {

    public function verificaSessao(){
        if (!(isset($_SESSION['comarca-parsed']))){
            unset($_SESSION['comarca-parsed']);
            unset($_SESSION['comarca-non-parsed']);
            header('location:index');
        }
        else {
            $comarca = $_SESSION['comarca-parsed'];
            $collection = $_SESSION['comarca-non-parsed'];
            $resultado = array();
            
            $resultado['comarca'] = $comarca;
            $resultado['collection'] = $collection;

            return $resultado;
        }
    }

    public function getSecao(){
        $arr = explode('habeasdata/',$_SERVER['REQUEST_URI']);
        return $arr[1];
    }
}

?>