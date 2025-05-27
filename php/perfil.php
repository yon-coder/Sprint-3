<?php
session_set_cookie_params(30 * 24 * 60 * 60);
session_start();

include 'conectar.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);
$sql = "SELECT * FROM user WHERE usuario='$usuario'";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_objetivo = $_POST['objetivo'];

    $stmt = $conn->prepare("UPDATE user SET objetivo = ? WHERE usuario = ?");
    $stmt->bind_param("ss", $novo_objetivo, $usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Objetivo atualizado com sucesso para: " . ucfirst($novo_objetivo);
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar o objetivo.";
    }

    // Redireciona para evitar reenvio do formulário ao dar refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel=icon type="image/png" href="../imgs/green_pokeball_by_jormxdos_dfgb82o-fullview.png">
</head>
<body>
    <header class="holder">
        <h1 style="font-size:var(--font-title);">Perfil</h1>
        <nav>
            <a href="dieta.php">Dieta</a>
            <a href="treinos.php">Treinos</a>
            <a href="progresso.php">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main class="conteudo" style="max-width:var(--container-max-width);padding:var(--container-padding);">
        <h2 style="font-size:var(--font-subtitle);letter-spacing:1px;margin-bottom:18px;text-shadow:0 2px 8px #fbc2eb99;">Seus Dados</h2>
        <?php
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<p>Nome: <strong>" . htmlspecialchars($row['nome']) . "</strong></p>";
            echo "<p>Idade: <strong>" . htmlspecialchars($row['idade']) . "</strong></p>";
            echo "<p>Peso: <strong>" . htmlspecialchars($row['peso']) . " kg</strong></p>";
            echo "<p>Altura: <strong>" . htmlspecialchars($row['altura']) . " m</strong></p>";
            echo "<p>Objetivo: <strong>" . htmlspecialchars($row['objetivo']) . "</strong></p>";
            echo "<p>Sexo: <strong>" . htmlspecialchars($row['sexo']) . "</strong></p>";
        } else {
            echo "<p>Erro ao carregar perfil.</p>";
        }
        ?>
        <div style="display:flex;justify-content:center;gap:10px;">
            <button id="btn-atualizar" style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);">Atualizar Objetivo</button>
        </div>
        <form id="form-objetivo" method="POST" action="" style="display:none;max-width:350px;margin:10px auto 0 auto;flex-direction:column;gap:10px;align-items:center;background:rgba(255,255,255,0.4);border-radius:10px;padding:18px 16px;box-shadow:0 2px 8px #a1c4fd22;">
            <label for="objetivo">Escolha o novo objetivo:</label>
            <select name="objetivo" id="objetivo" required style="max-width:200px;">
                <option value="ganhar">Ganhar Massa</option>
                <option value="perder">Perder Peso</option>
                <option value="manter">Manter Peso</option>
            </select>
            <button type="submit" style="align-self:center;font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);">Atualizar</button>
        </form>
    </main>

    <script>
        document.getElementById('btn-atualizar').onclick = function() {
            var form = document.getElementById("form-objetivo");
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        };
        window.addEventListener('load', function() {
            <?php if (isset($_SESSION['mensagem'])): ?>
                if (!sessionStorage.getItem('reloadDone')) {
                    alert('<?php echo addslashes($_SESSION['mensagem']); ?>');
                    sessionStorage.setItem('reloadDone', 'true');
                    location.reload();
                } else {
                    sessionStorage.removeItem('reloadDone');
                }
            <?php unset($_SESSION['mensagem']); endif; ?>
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
