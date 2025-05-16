<html>
<head>
    <meta charset="UTF-8">
    <title>Sua Dieta - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header class="holder">
        <h1>Dieta do Usuário</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="html/treinos.html">Treinos</a>
            <a href="html/progresso.html">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <button onclick="window.location.href='dieta.php'">Voltar</button>
</body>
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

$stmt = $conn->prepare("SELECT peso, altura, idade, sexo, objetivo FROM user WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Usuário não encontrado.";
    exit;
}

$row = $result->fetch_assoc();
$peso = $row['peso'];
$altura = $row['altura'];
$idade = $row['idade'];
$sexo = $row['sexo'];
$objetivo = $row['objetivo'];

$dietaEscolhida = $_POST['dieta'] ?? '1';
if (!in_array($dietaEscolhida, ['1', '2', '3'])) {
    $dietaEscolhida = '1';
}

function calcular_tmb($peso, $altura, $idade, $sexo) {
    return $sexo === 'masculino' ? 88.36 + (13.4 * $peso) + (4.8 * ($altura * 100)) - (5.7 * $idade) : 447.6 + (9.2 * $peso) + (3.1 * ($altura * 100)) - (4.3 * $idade);
}

function ajustar_calorias($tmb, $objetivo) {
    return $objetivo === 'ganhar' ? $tmb * 1.2 : ($objetivo === 'perder' ? $tmb * 0.8 : $tmb);
}

function classificar_alimento($valores) {
    if ($valores['proteina'] >= 15) return "bom fornecedor de proteínas";
    if ($valores['carboidrato'] >= 15) return "bom fornecedor de carboidratos";
    if ($valores['gordura'] >= 15) return "bom fornecedor de gorduras";
    return "equilibrado";
}

$alimentos = [
    'Peito de Frango' => ['proteina' => 31, 'carboidrato' => 0, 'gordura' => 3.6],
    'Ovos' => ['proteina' => 13, 'carboidrato' => 1.1, 'gordura' => 10],
    'Tofu' => ['proteina' => 8, 'carboidrato' => 1.9, 'gordura' => 4.8],
    'Salmão' => ['proteina' => 20, 'carboidrato' => 0, 'gordura' => 13],
    'Carne Vermelha' => ['proteina' => 26, 'carboidrato' => 0, 'gordura' => 15],
    'Arroz Integral' => ['proteina' => 2.6, 'carboidrato' => 23, 'gordura' => 1],
    'Batata Doce' => ['proteina' => 1.6, 'carboidrato' => 20, 'gordura' => 0.1],
    'Quinoa' => ['proteina' => 4.4, 'carboidrato' => 21, 'gordura' => 1.9],
    'Azeite' => ['proteina' => 0, 'carboidrato' => 0, 'gordura' => 100],
    'Abacate' => ['proteina' => 2, 'carboidrato' => 9, 'gordura' => 15],
    'Amêndoas' => ['proteina' => 21, 'carboidrato' => 22, 'gordura' => 49]
];

switch ($dietaEscolhida) {
    case '1': unset($alimentos['Salmão']); break;
    case '2': unset($alimentos['Carne Vermelha']); break;
}

$tmb = calcular_tmb($peso, $altura, $idade, $sexo);
$calorias = ajustar_calorias($tmb, $objetivo);

echo "<h2>Dieta: {$dietaEscolhida}</h2><p>Objetivo: {$objetivo}</p><p>Calorias: ".round($calorias,1)." kcal</p>";
echo "<h3>Alimentos sugeridos:</h3><ul>";
foreach ($alimentos as $alimento => $valores) {
    $descricao = classificar_alimento($valores);
    echo "<li>{$alimento} -> {$descricao}</li>";
}
echo "</ul>";
// ... seu código atual até o final da listagem dos alimentos

echo "</ul>";

// Sugestão de refeições do dia baseada na dieta escolhida
echo "<h3>Sugestão de Refeições para o Dia:</h3>";

switch ($dietaEscolhida) {
    case '1': // sem peixe, com carne vermelha
        echo "<p><strong>Almoço:</strong> Carne Vermelha grelhada, Arroz Integral e Salada de Abacate com Azeite.</p>";
        echo "<p><strong>Jantar:</strong> Peito de Frango assado com Batata Doce e legumes.</p>";
        break;

    case '2': // sem carne vermelha, com peixe
        echo "<p><strong>Almoço:</strong> Salmão grelhado, Quinoa e Salada de Amêndoas e Abacate.</p>";
        echo "<p><strong>Jantar:</strong> Tofu grelhado com Arroz Integral e legumes.</p>";
        break;

    case '3': // com ambos
        echo "<p><strong>Almoço:</strong> Salmão e Carne Vermelha grelhados, Arroz Integral e Salada de Abacate com Azeite.</p>";
        echo "<p><strong>Jantar:</strong> Peito de Frango e Tofu com Batata Doce e legumes.</p>";
        break;
}

?>
