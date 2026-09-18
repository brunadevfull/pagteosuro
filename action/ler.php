<?php

function Leitura(string $arquivo) {
    $fp = fopen($arquivo, "r");
    if ($fp === false) {
        return "";
    }
    $texto = fread($fp, filesize($arquivo));
    fclose($fp);
    return $texto;
}

?>