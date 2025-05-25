<?php
header('Content-Type: application/json');

// Recebe o JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['respostas']) || count($data['respostas']) < 22) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos ou inválidos']);
    exit;
}

$respostas = $data['respostas'];

// Conectar ao banco
$host = 'localhost';
$user = 'root';
$senha = ''; // Altere se necessário
$banco = 'sustentabilidade_db';

$conn = new mysqli($host, $user, $senha, $banco);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro de conexão com o banco']);
    exit;
}

// Inserir na tabela respostas_pessoais (primeiras 7 respostas)
$stmtPessoal = $conn->prepare("INSERT INTO respostas_pessoais (genero, zona, pessoas, idade, escolaridade, estudo_tipo, trabalho) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmtPessoal->bind_param(
    "sssssss",
    $respostas[0], // gênero
    $respostas[1], // zona
    $respostas[2], // pessoas
    $respostas[3], // idade
    $respostas[4], // escolaridade
    $respostas[5], // estudo_tipo
    $respostas[6]  // trabalho
);

if (!$stmtPessoal->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar dados pessoais']);
    exit;
}

$respostaId = $stmtPessoal->insert_id;
$stmtPessoal->close();

// Inserir o restante na tabela respostas_sustentabilidade
$stmtSust = $conn->prepare("INSERT INTO respostas_sustentabilidade (resposta_id, pergunta_numero, resposta) VALUES (?, ?, ?)");

for ($i = 7; $i < count($respostas); $i++) {
    $numeroPergunta = $i + 1;
    $respostaTexto = $respostas[$i];
    $stmtSust->bind_param("iis", $respostaId, $numeroPergunta, $respostaTexto);
    if (!$stmtSust->execute()) {
        echo json_encode(['status' => 'error', 'message' => "Erro ao salvar resposta da pergunta $numeroPergunta"]);
        exit;
    }
}
$stmtSust->close();
$conn->close();

// Sucesso
echo json_encode(['status' => 'success', 'message' => 'Respostas salvas com sucesso']);
?>
