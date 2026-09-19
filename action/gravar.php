<?php

function Gravar(string $texto, string $arquivo) {
    $fp = fopen($arquivo, "a+");
    if ($fp === false) {
        return false;
    }
    fwrite($fp, "{$texto} <br>\r\n");
    fclose($fp);
    return true;
}

?>