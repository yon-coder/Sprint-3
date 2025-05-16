<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Progresso - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header class="holder">
        <h1>Progresso</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="dieta.php">Dieta</a>
            <a href="treinos.php">Treinos</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conectar.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];

// Puxa os dados antigos do banco de dados
$stmt = $conn->prepare("SELECT peso, altura FROM user WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Usuário não encontrado.";
    exit;
}

$row = $result->fetch_assoc();
$peso_antigo = $row['peso'];
$altura_antiga = $row['altura'];

// Função para calcular IMC
function calcular_imc($peso, $altura) {
    return $peso / ($altura * $altura);
}

// Função para calcular progresso em porcentagem
function calcular_progresso($peso_antigo, $peso_atual) {
    $diferenca = $peso_atual - $peso_antigo;
    $percentual = ($diferenca / $peso_antigo) * 100;
    return round($percentual, 2);
}

$peso_atual = $altura_atual = $imc_antigo = $imc_atual = $progresso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peso_atual = floatval($_POST['peso_atual']);
    $altura_atual = floatval($_POST['altura_atual']);

    // Atualiza os dados no banco
    $stmt = $conn->prepare("UPDATE user SET peso = ?, altura = ? WHERE usuario = ?");
    $stmt->bind_param("dds", $peso_atual, $altura_atual, $usuario);
    $stmt->execute();

    // Calcula IMC antigo e atual
    $imc_antigo = calcular_imc($peso_antigo, $altura_antiga);
    $imc_atual = calcular_imc($peso_atual, $altura_atual);

    // Calcula a taxa de progresso
    $progresso = calcular_progresso($peso_antigo, $peso_atual);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Progresso - BYB</title>
</head>
<body>
    

    <h3>Dados Antigos:</h3>
    <p>Altura: <?= $altura_antiga ?> m</p>
    <p>Peso: <?= $peso_antigo ?> kg</p>

    <form method="POST">
        <h3>Dados Atuais:</h3>
        <label for="altura_atual">Altura (m):</label>
        <input type="text" name="altura_atual" required value="<?= $altura_atual ?>"><br>

        <label for="peso_atual">Peso (kg):</label>
        <input type="text" name="peso_atual" required value="<?= $peso_atual ?>"><br>

        <button type="submit">Calcular Progresso</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h3>Resultado:</h3>
        <p>IMC Antigo: <?= round($imc_antigo, 2) ?></p>
        <p>IMC Atual: <?= round($imc_atual, 2) ?></p>
        <p>Progresso de Peso: <?= ($progresso > 0 ? "+" : "") . $progresso ?>%</p>
    <?php endif; ?>
</body>
</html>
