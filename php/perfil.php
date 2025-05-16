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
</head>
<body>
    <header class="holder">
        <h1>Perfil</h1>
        <nav>
            <a href="dieta.php">Dieta</a>
            <a href="treinos.php">Treinos</a>
            <a href="progresso.php">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main class="conteudo">
        <h2>Seus Dados:</h2>
        <div id="dados-perfil">
            <?php
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>Nome: " . htmlspecialchars($row['nome']) . "</p>";
                echo "<p>Idade: " . htmlspecialchars($row['idade']) . "</p>";
                echo "<p>Peso: " . htmlspecialchars($row['peso']) . " kg</p>";
                echo "<p>Altura: " . htmlspecialchars($row['altura']) . " m</p>";
                echo "<p>Objetivo: " . htmlspecialchars($row['objetivo']) . "</p>";
                echo "<p>Sexo: " . htmlspecialchars($row['sexo']) . "</p>";
            } else {
                echo "<p>Erro ao carregar perfil.</p>";
            }
            ?>
        </div>

        <button onclick="mostrarFormulario()">Atualizar Objetivo</button>

        <div id="form-objetivo" style="display: none; margin-top: 10px;">
            <form method="POST" action="">
                <label for="objetivo">Escolha o novo objetivo:</label>
                <select name="objetivo" id="objetivo" required>
                    <option value="ganhar">Ganhar Massa</option>
                    <option value="perder">Perder Peso</option>
                    <option value="manter">Manter Peso</option>
                </select>
                <button type="submit">Atualizar</button>
            </form>
        </div>
    </main>

    <script>
        function mostrarFormulario() {
            var form = document.getElementById("form-objetivo");
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

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
