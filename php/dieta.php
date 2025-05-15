<?php
// Iniciar a sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conectar ao banco de dados
include 'conectar.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Obter o usuário logado
$usuario = $_SESSION['usuario'];

// Consultar os dados do usuário no banco
$stmt = $conn->prepare("SELECT peso, altura, idade, sexo, objetivo FROM user WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $peso = $row['peso'];
    $altura = $row['altura'];
    $idade = $row['idade'];
    $sexo = $row['sexo'];
    $objetivo = $row['objetivo'];

    // Funções para gerar dieta e treino
    function calcular_tmb($peso, $altura, $idade, $sexo) {
        if ($sexo === 'masculino') {
            return 88.36 + (13.4 * $peso) + (4.8 * $altura * 100) - (5.7 * $idade);
        } else {
            return 447.6 + (9.2 * $peso) + (3.1 * $altura * 100) - (4.3 * $idade);
        }
    }

    function calcular_calorias($tmb, $objetivo) {
        if ($objetivo === 'ganhar') {
            return (int)($tmb * 1.2);
        } elseif ($objetivo === 'perder') {
            return (int)($tmb * 0.8);
        } else {
            return (int)($tmb * 1.0);
        }
    }

    function distribuir_macronutrientes($calorias, $peso, $objetivo) {
        if ($objetivo === 'ganhar') {
            $proteinas = 2.2 * $peso;
            $carboidratos = 5 * $peso;
            $gorduras = 1 * $peso;
        } elseif ($objetivo === 'perder') {
            $proteinas = 2.5 * $peso;
            $carboidratos = 2.5 * $peso;
            $gorduras = 0.8 * $peso;
        } else {
            $proteinas = 2 * $peso;
            $carboidratos = 3.5 * $peso;
            $gorduras = 1 * $peso;
        }
        return [
            'proteinas' => round($proteinas),
            'carboidratos' => round($carboidratos),
            'gorduras' => round($gorduras),
        ];
    }

    $tmb = calcular_tmb($peso, $altura, $idade, $sexo);
    $calorias = calcular_calorias($tmb, $objetivo);
    $macros = distribuir_macronutrientes($calorias, $peso, $objetivo);

    $dieta = [
        'calorias' => $calorias,
        'proteinas' => $macros['proteinas'],
        'carboidratos' => $macros['carboidratos'],
        'gorduras' => $macros['gorduras'],
    ];
} else {
    echo "Erro: Usuário não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dieta - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header class="holder">
        <h1>Dieta Personalizada</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="treinos.php">Treinos</a>
            <a href="progresso.php">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main class="conteudo">
        <h2>Sua Dieta</h2>
        <p>Calorias diárias: <?= $dieta['calorias'] ?> kcal</p>
        <p>Proteínas: <?= $dieta['proteinas'] ?> g</p>
        <p>Carboidratos: <?= $dieta['carboidratos'] ?> g</p>
        <p>Gorduras: <?= $dieta['gorduras'] ?> g</p>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
