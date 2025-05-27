<?php
session_start();
include 'conectar.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);
$sql = "SELECT objetivo, sexo FROM user WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo "Erro ao carregar dados do usuário.";
    exit();
}

$row = $result->fetch_assoc();
$objetivo = $row['objetivo'];
$sexo = strtolower($row['sexo']);

$treinos = [
    'Perna' => [
        ['exercicio' => 'Agachamento Livre', 'series' => '4x12', 'dica' => 'Mantenha a carga constante para focar na execução.'],
        ['exercicio' => 'Leg Press', 'series' => '4x10', 'dica' => 'Progressão de carga a cada série.'],
        ['exercicio' => 'Cadeira Extensora', 'series' => '3x15', 'dica' => 'Concentre-se na contração máxima.'],
        ['exercicio' => 'Levantamento Terra', 'series' => '4x8', 'dica' => 'Aumente a carga gradativamente.'],
        ['exercicio' => 'Afundo com Halteres', 'series' => '3x12', 'dica' => 'Priorize o equilíbrio e a amplitude.'],
        ['exercicio' => 'Stiff', 'series' => '4x10', 'dica' => 'Foque na descida controlada.'],
        ['exercicio' => 'Leg Curl', 'series' => '3x12', 'dica' => 'Mantenha a carga moderada para evitar lesões.'],
        ['exercicio' => 'Cadeira Adutora', 'series' => '3x15', 'dica' => 'Movimento lento e controlado.'],
        ['exercicio' => 'Panturrilha no Leg Press', 'series' => '4x15', 'dica' => 'Faça o movimento completo para melhor ativação.'],
    ],
    'Costas/Antebraço/Bíceps' => [
        ['exercicio' => 'Puxada na Barra Fixa', 'series' => '3x8', 'dica' => 'Foque na execução correta.'],
        ['exercicio' => 'Remada Curvada', 'series' => '4x10', 'dica' => 'Mantenha a postura reta durante o movimento.'],
        ['exercicio' => 'Pulley Frente', 'series' => '3x12', 'dica' => 'Progressão leve a cada série.'],
        ['exercicio' => 'Rosca Direta', 'series' => '3x15', 'dica' => 'Use carga moderada para manter o controle.'],
        ['exercicio' => 'Rosca Martelo', 'series' => '3x12', 'dica' => 'Evite balanço do corpo.'],
        ['exercicio' => 'Rosca Inversa', 'series' => '3x12', 'dica' => 'Controle a descida para maior eficiência.'],
        ['exercicio' => 'Rosca Concentrada', 'series' => '3x10', 'dica' => 'Use carga leve para manter a forma.'],
    ],
    'Peito/Ombro/Tríceps' => [
        ['exercicio' => 'Supino Reto', 'series' => '4x8', 'dica' => 'Mantenha a carga estável em todas as séries.'],
        ['exercicio' => 'Supino Inclinado', 'series' => '3x10', 'dica' => 'Progrida na última série se sentir conforto.'],
        ['exercicio' => 'Desenvolvimento com Halteres', 'series' => '4x12', 'dica' => 'Amplitude total para melhor ativação.'],
        ['exercicio' => 'Crucifixo Inclinado', 'series' => '3x15', 'dica' => 'A carga deve permitir um movimento amplo.'],
        ['exercicio' => 'Fly Pec Deck', 'series' => '3x12', 'dica' => 'Movimento lento e controlado.'],
        ['exercicio' => 'Elevação Lateral', 'series' => '3x12', 'dica' => 'Evite movimentos bruscos.'],
        ['exercicio' => 'Desenvolvimento Militar', 'series' => '4x8', 'dica' => 'Aumente a carga nas últimas séries.'],
        ['exercicio' => 'Flexão de Braço', 'series' => '3x15', 'dica' => 'Foque na postura correta.'],
        ['exercicio' => 'Tríceps Pulley', 'series' => '3x12', 'dica' => 'Movimento completo para máximo estímulo.'],
        ['exercicio' => 'Tríceps Testa', 'series' => '3x10', 'dica' => 'A carga deve ser leve para proteger as articulações.'],
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Treinos Personalizados - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css" />
    <link rel=icon type="image/png" href="../imgs/green_pokeball_by_jormxdos_dfgb82o-fullview.png">
    <style>
        .treino-titulo {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            user-select: none;
        }
        .treino-conteudo {
            display: none;
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f0f8ff;
        }
        .exercicio {
            margin-bottom: 5px;
        }
        .dica {
            color: #555;
        }
    </style>
</head>
<body>
    <header class="holder">
        <h1 style="font-size:var(--font-title);">Treinos</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="dieta.php">Dieta</a>
            <a href="progresso.php">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main class="conteudo" style="max-width:var(--container-max-width);padding:var(--container-padding);">
        <h2 style="font-size:var(--font-subtitle);letter-spacing:1px;margin-bottom:24px;text-shadow:0 2px 8px #43e97b99;">Objetivo: <b><?php echo ucfirst($objetivo); ?></b></h2>
        <div id="botoes-treino" style="display:flex;flex-wrap:wrap;gap:18px 24px;justify-content:center;margin-bottom:32px;">
        <?php $i=0; foreach ($treinos as $grupo => $exercicios): ?>
            <button class="treino-titulo" data-idx="<?php echo $i; ?>" style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);color:#fff;font-weight:bold;min-width:180px;letter-spacing:1px;">
                <?php echo $grupo; ?>
            </button>
        <?php $i++; endforeach; ?>
        </div>
        <?php $i=0; foreach ($treinos as $grupo => $exercicios): ?>
            <div class="treino-conteudo" data-idx="<?php echo $i; ?>" style="display:none;background:rgba(255,255,255,0.25);backdrop-filter:blur(6px);border-radius:18px;box-shadow:0 4px 24px #43e97b22;margin-bottom:28px;padding:28px 24px 18px 24px;max-width:600px;margin-left:auto;margin-right:auto;">
                <h3 style="font-size:1.25em;color:#43e97b;margin-bottom:18px;text-align:center;text-shadow:0 2px 8px #38f9d799;letter-spacing:1px;">Treino de <?php echo $grupo; ?></h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px 24px;">
                <?php foreach ($exercicios as $ex): ?>
                    <div class="exercicio" style="background:rgba(255,255,255,0.55);border-radius:12px;padding:14px 12px 10px 16px;box-shadow:0 2px 8px #43e97b22;display:flex;flex-direction:column;align-items:flex-start;">
                        <span style="font-weight:bold;font-size:1.08em;color:#267f4a;letter-spacing:0.5px;">
                            <?php echo $ex['exercicio']; ?> <span style="color:#145c36;font-size:0.98em;">(<?php echo $ex['series']; ?>)</span>
                        </span>
                        <span class="dica" style="color:#267f4a;font-size:0.97em;margin-top:4px;opacity:0.85;">💡 <?php echo $ex['dica']; ?></span>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        <?php $i++; endforeach; ?>
    </main>
    <script>
        // Corrigido: ao clicar, mostra/oculta apenas o treino correspondente, mantendo os botões visíveis
        const botoes = document.querySelectorAll('.treino-titulo');
        const conteudos = document.querySelectorAll('.treino-conteudo');
        botoes.forEach((btn, idx) => {
            btn.addEventListener('click', function() {
                const content = document.querySelector('.treino-conteudo[data-idx="'+idx+'"]');
                if(content.style.display === 'block') {
                    content.style.display = 'none';
                    btn.classList.remove('ativo');
                } else {
                    conteudos.forEach((c, i) => {
                        c.style.display = 'none';
                        botoes[i].classList.remove('ativo');
                    });
                    content.style.display = 'block';
                    btn.classList.add('ativo');
                }
            });
            btn.addEventListener('mouseover', function(){
                btn.style.background = 'linear-gradient(90deg,#38f9d7 0%,#43e97b 100%)';
                btn.style.color = '#267f4a';
                btn.style.transform = 'scale(1.04)';
            });
            btn.addEventListener('mouseout', function(){
                btn.style.background = btn.classList.contains('ativo') ? 'linear-gradient(90deg,#38f9d7 0%,#43e97b 100%)' : 'linear-gradient(90deg,#43e97b 0%,#38f9d7 100%)';
                btn.style.color = btn.classList.contains('ativo') ? '#267f4a' : '#fff';
                btn.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
