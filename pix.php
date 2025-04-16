<?php
// Substitua os valores abaixo com suas credenciais e endpoint reais
$secretKey = 'd2ff7bd819cc03e0b9902b1694b97922';
$endpoint = 'https://crassbrasil.github.io/pix/'; // edite para o endpoint real

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$amount = isset($input['amount']) ? $input['amount'] : null;

if (!$amount) {
  echo json_encode(['error' => 'Valor inválido']);
  exit;
}

$data = [
  'value' => $amount,
  'currency' => 'BRL',
  'description' => 'Doação via Pix'
];

$options = [
  'http' => [
    'header'  => "Content-type: application/json
" .
                 "Authorization: Bearer $secretKey
",
    'method'  => 'POST',
    'content' => json_encode($data),
  ]
];

$context = stream_context_create($options);
$response = file_get_contents($endpoint, false, $context);

if ($response === FALSE) {
  echo json_encode(['error' => 'Erro ao conectar à API']);
  exit;
}

// Supondo que a resposta contenha o campo 'pix' com o código copia e cola
echo $response;
?>
