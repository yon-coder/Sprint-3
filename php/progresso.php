<?php
session_start();
include 'conectar.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

// Buscar dados antigos do usuário
$stmt = $conn->prepare("SELECT altura, peso FROM user WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->bind_result($altura_antiga, $peso_antigo);
$stmt->fetch();
$stmt->close();

// Valores atuais (padrão: antigos)
$altura_atual = $altura_antiga;
$peso_atual = $peso_antigo;
$imc_antigo = $imc_atual = $progresso = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $altura_atual = str_replace(',', '.', $_POST['altura_atual']);
    $peso_atual = str_replace(',', '.', $_POST['peso_atual']);
    $imc_antigo = $peso_antigo / ($altura_antiga * $altura_antiga);
    $imc_atual = $peso_atual / ($altura_atual * $altura_atual);
    $progresso = $peso_antigo != 0 ? (($peso_atual - $peso_antigo) / $peso_antigo) * 100 : 0;
}
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Progresso - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" type="image/png" href="../imgs/logoBYB.jpg">
</head>
<body>
    <header class="holder">
        <h1 style="font-size:var(--font-title);">Progresso</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="dieta.php">Dieta</a>
            <a href="treinos.php">Treinos</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main class="conteudo" style="max-width:var(--container-max-width);padding:var(--container-padding);">
        <h3 style="font-size:var(--font-subtitle);">Dados Antigos:</h3>
        <p>Altura: <?= isset($altura_antiga) ? $altura_antiga : '-' ?> m</p>
        <p>Peso: <?= isset($peso_antigo) ? $peso_antigo : '-' ?> kg</p>
        <form method="POST" style="max-width:400px;margin:0 auto;display:flex;flex-direction:column;gap:10px;">
            <h3>Dados Atuais:</h3>
            <label for="altura_atual">Altura (m):</label>
            <input type="text" name="altura_atual" required value="<?= htmlspecialchars($altura_atual) ?>">
            <label for="peso_atual">Peso (kg):</label>
            <input type="text" name="peso_atual" required value="<?= htmlspecialchars($peso_atual) ?>">
            <div style="display:flex;gap:10px;justify-content:center;">
                <button type="submit" name="calcular" style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);">Calcular Progresso</button>
                <button type="submit" name="atualizar" style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);color:#fff;font-weight:bold;">Atualizar Progresso</button>
            </div>
        </form>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <h3>Resultado:</h3>
            <p>IMC Antigo: <?= round($imc_antigo, 2) ?></p>
            <p>IMC Atual: <?= round($imc_atual, 2) ?></p>
            <p>Progresso de Peso: <?= ($progresso > 0 ? "+" : "") . round($progresso, 2) ?>%</p>
        <?php endif; ?>
        <?php
        if (isset($_POST['atualizar'])) {
            $altura_nova = str_replace(',', '.', $_POST['altura_atual']);
            $peso_novo = str_replace(',', '.', $_POST['peso_atual']);
            $stmt2 = $conn->prepare("UPDATE user SET altura = ?, peso = ? WHERE usuario = ?");
            $stmt2->bind_param("dds", $altura_nova, $peso_novo, $usuario);
            if ($stmt2->execute()) {
                echo '<p style="color:#43e97b;font-weight:bold;margin-top:10px;">Progresso atualizado com sucesso!</p>';
            } else {
                echo '<p style="color:#e74c3c;font-weight:bold;margin-top:10px;">Erro ao atualizar progresso.</p>';
            }
            $stmt2->close();
        }
        ?>
    </main>
        <footer class="holder" style="text-align:center;padding:20px 0;">
        <p style="font-size:var(--font-base);margin:0;">Site desenvolvido para pessoas em obesidade/sobre-peso, ou para quem deseja seguir uma vida mais saudável e ativa.</p>
        <p style="font-size:var(--font-base);">© 2025 BYB - Build Your Body. Todos os direitos reservados.</p>

</body>
</html>
