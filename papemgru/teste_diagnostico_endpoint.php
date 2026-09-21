<?php
/**
 * TESTE ISOLADO - nao afeta pagtesouroControl.php nem PagTesouroClasse.php.
 * Objetivo: testar o payload real de servidorCivil() contra o host
 * pagtesouro.dgom.mb:3000, que confirmamos estar ativo (curl manual respondeu
 * HTTP 404 "Cannot GET /handle" para GET, e SSL ok), diferente do host que
 * PagTesouroClasse.php usa hoje (desenvolvimento.dgom.mb, ambiente="H" hardcoded).
 *
 * Como usar:
 *  https://SEU_HOST/papemgru/teste_diagnostico_endpoint.php?codigoServico=1541
 *  https://SEU_HOST/papemgru/teste_diagnostico_endpoint.php?codigoServico=11859
 *  https://SEU_HOST/papemgru/teste_diagnostico_endpoint.php?codigoServico=11860
 * Troque o token em $chave abaixo se quiser testar o token antigo vs o novo.
 */

// ============================================
// CONFIGURE AQUI
// ============================================
$url = 'https://pagtesouro.dgom.mb:3000/handle';

// Defina o token via variavel de ambiente (PAGTESOURO_BEARER_TOKEN) ou
// substitua manualmente o placeholder abaixo para diagnostico local.
$chave = getenv('PAGTESOURO_BEARER_TOKEN') ?: "******";

// Permite trocar o codigoServico direto na URL para comparar as 3 hipoteses:
// 1541 (o que o codigo usa hoje no branch H) | 11859 (pessoal, visto em outras copias) | 11860 (SISRES)
$codigoServico = isset($_GET['codigoServico']) ? (int)$_GET['codigoServico'] : 1541;

// ============================================
// PAYLOAD - mesma estrutura montada por servidorCivil() em PagTesouroClasse.php
// ============================================
$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
$partesVencimento = explode("-", $Vencimento);
$dataVencimento = $partesVencimento[2] . $partesVencimento[1] . $partesVencimento[0];
$competencia = date("mY");

$data = array(
  "cat" => "PAPEM",
  "codigoServico" => $codigoServico,
  "vencimento" => $dataVencimento,
  "competencia" => $competencia,
  "nomeContribuinte" => "TESTE DIAGNOSTICO PAPEM",
  "cnpjCpf" => "00000000000191",
  "valorDescontos" => 0,
  "valorOutrasDeducoes" => 0,
  "valorMulta" => 0,
  "valorJuros" => 0,
  "valorOutrosAcrescimos" => 0,
  "valorPrincipal" => "100.00",
  "nomeUG" => "TESTE UG",
  "cod_om" => "001",
  "cat_servico" => "PAPEM",
  "codRubrica" => "999",
  "nomeRubrica" => "TESTE RUBRICA",
  "motivo" => "Teste de diagnostico de endpoint",
  "tributavel" => 1,
  "nomeOC" => "TESTE OC",
  "cod_oc" => "001",
  "valorBrutoExercAnt" => 0,
  "codSiapeNip" => "0000000",
  "modoNavegacao" => "2",
  "tema" => "tema-light",
  "NatDev" => "Pagamento de Pessoal"
);
$data_string = json_encode($data);

// ============================================
// CHAMADA
// ============================================
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErrno = curl_errno($ch);
$curlError = curl_error($ch);
curl_close($ch);

// ============================================
// SAIDA
// ============================================
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="utf-8"><title>Diagnostico endpoint GRU</title></head>
<body style="font-family: monospace; padding: 20px;">
<h2>Teste isolado - nao afeta o fluxo real de geracao de GRU</h2>
<p><b>URL testada:</b> <?php echo htmlspecialchars($url); ?></p>
<p><b>codigoServico usado:</b> <?php echo htmlspecialchars($codigoServico); ?>
   (troque com <code>?codigoServico=11859</code> ou <code>?codigoServico=11860</code>)</p>
<p><b>curl_errno:</b> <?php echo $curlErrno; ?> |
   <b>curl_error:</b> <?php echo htmlspecialchars($curlError ?: '(nenhum)'); ?></p>
<p><b>HTTP code:</b> <?php echo $httpCode ?: '(sem resposta)'; ?></p>
<p><b>Payload enviado:</b></p>
<pre><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
<p><b>Resposta bruta:</b></p>
<pre><?php echo htmlspecialchars($result === false ? '(sem corpo - curl falhou antes de receber resposta)' : $result); ?></pre>
</body>
</html>
