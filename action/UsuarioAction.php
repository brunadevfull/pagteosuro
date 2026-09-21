<?php

$req = filter_input(INPUT_GET, "req", FILTER_SANITIZE_NUMBER_INT);
//$arquivo = 'G:\AppsWeb\Formulario\arquivos\usuario.txt';
$arquivo = '.\..\arquivos\usuario.txt';

/*
/txt: significa que o arquivo .txt está na pasta raiz do sistema;

./txt: significa que o arquivo .txt está na mesma pasta que o script está rodando;

../txt: significa que o arquivo .txt está na pasta imediatamente acima da pasta em que o script PHP está rodando.
*/


switch($req){
    case 1:
        $nome = trim(strip_tags(filter_input(INPUT_POST, "txtNome") ?? ''));
        $email = trim(strip_tags(filter_input(INPUT_POST, "txtEmail") ?? ''));
        $telefone = trim(strip_tags(filter_input(INPUT_POST, "txtTelefone") ?? ''));
		$cep = trim(strip_tags(filter_input(INPUT_POST, "txtCep") ?? ''));
		$rua = trim(strip_tags(filter_input(INPUT_POST, "txtRua") ?? ''));
		$bairro = trim(strip_tags(filter_input(INPUT_POST, "txtBairro") ?? ''));
		$cidade = trim(strip_tags(filter_input(INPUT_POST, "txtCidade") ?? ''));
		$estado = trim(strip_tags(filter_input(INPUT_POST, "txtEstado") ?? ''));

        $str = "Nome: {$nome} | E-mail: {$email} | Telefone: {$telefone} | CEP: {$cep} | Rua: {$rua} | Bairro: {$bairro} | Cidade: {$cidade} | Estado: {$estado}";

        $fp = fopen($arquivo, "a+");
        if($fp === false){
            echo "0";
            break;
        }
        if(fwrite($fp, "\r\n{$str}\r\n")){
            echo "1";
        }else{
            echo "0";
        }
        fclose($fp);
    break;

    case 2:
        $fp = fopen($arquivo, "r+");
        if($fp === false){
            echo "";
            break;
        }
        $texto = fread($fp, filesize($arquivo));
        fclose($fp);
        echo $texto;


    break;

    default:
        echo "nada";
    break;
	
}