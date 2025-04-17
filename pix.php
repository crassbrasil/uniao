<?php
// Define o cabeçalho para resposta JSON
header('Content-Type: application/json');

// Recebe os dados enviados via JSON do frontend
$input = json_decode(file_get_contents('php://input'), true);
$amount = isset($input['amount']) ? $input['amount'] : null;

// Valida o valor
if (!$amount || !is_numeric($amount)) {
  echo json_encode(['error' => 'Valor inválido']);
  exit;
}

// Prepara os dados que serão enviados para sua API personalizada
$data = [
  'value' => $amount
];

// Configura a requisição POST para sua API (Railway)
$options = [
  'http' => [
    'header'  => "Content-type: application/json\r\n",
    'method'  => 'POST',
    'content' => json_encode($data),
    'ignore_errors' => true // permite ver resposta mesmo em caso de erro HTTP
  ]
];

// Envia a requisição para sua API personalizada
$context = stream_context_create($options);
$response = file_get_contents('https://unido-production.up.railway.app/pix', false, $context);

// Verifica se houve erro na conexão
if ($response === FALSE) {
  echo json_encode(['error' => 'Erro ao conectar à API Nexus personalizada']);
  exit;
}

// Retorna a resposta da API para o frontend
echo $response;
?>
