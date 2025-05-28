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
        ['exercicio' => 'Cadeira Extensora', 'series' => '3x15', 'dica' => 'Concentre-se na contração máxima.'],
        ['exercicio' => 'Cadeira Flexora', 'series' => '3x15', 'dica' => 'Mantenha o movimento controlado.'],
        ['exercicio' => 'Mesa Flexora', 'series' => '3x15', 'dica' => 'Evite movimentos bruscos e foque na amplitude.'],
        ['exercicio' => 'Leg Press', 'series' => '3x15', 'dica' => 'Ajuste a carga para manter a execução perfeita.'],
        ['exercicio' => 'Agachamento na Barra Guiada', 'series' => '3x12', 'dica' => 'Mantenha a postura e desça até 90 graus.'],
        ['exercicio' => 'Panturrilha em Pé com Halteres', 'series' => '4x10', 'dica' => 'Faça o movimento completo e segure no topo.'],
        ['exercicio' => 'Cardio (mínimo 30 minutos)', 'series' => '-', 'dica' => 'Escolha entre esteira, bicicleta, escada ou elíptico. Mantenha intensidade moderada.'],
    ],
    'Costas/Bíceps' => [
        ['exercicio' => 'Remador Triângulo', 'series' => '4x10', 'dica' => 'Mantenha a postura reta durante o movimento e puxe o triângulo até o abdômen.'],
        ['exercicio' => 'Pulley Frente', 'series' => '3x12', 'dica' => 'Progressão leve a cada série.'],
        ['exercicio' => 'Pulley Frente com Triângulo', 'series' => '3x12', 'dica' => 'Use o triângulo para maior ativação do centro das costas. Mantenha o tronco estável.'],
        ['exercicio' => 'Rosca Direta no Cross Over', 'series' => '3x15', 'dica' => 'Mantenha os cotovelos fixos e concentre o esforço nos bíceps. Use o cross para maior controle e amplitude.'],
        ['exercicio' => 'Rosca Alternada com Halter', 'series' => '3x12', 'dica' => 'Execute alternando os braços, sem balanço do corpo, utilizando halteres.'],
        ['exercicio' => 'Cardio (mínimo 30 minutos)', 'series' => '-', 'dica' => 'Escolha entre esteira, bicicleta, escada ou elíptico. Mantenha intensidade moderada.'],
    ],
    'Peito/Ombro/Tríceps' => [
        ['exercicio' => 'Supino Inclinado', 'series' => '3x10', 'dica' => 'Progrida na última série se sentir conforto.'],
        ['exercicio' => 'Desenvolvimento com Halteres', 'series' => '4x12', 'dica' => 'Amplitude total para melhor ativação.'],
        ['exercicio' => 'Crucifixo', 'series' => '3x15', 'dica' => 'A carga deve permitir um movimento amplo.'],
        ['exercicio' => 'Fly Pec Deck', 'series' => '3x12', 'dica' => 'Movimento lento e controlado.'],
        ['exercicio' => 'Elevação Lateral', 'series' => '3x12', 'dica' => 'Evite movimentos bruscos.'],
        ['exercicio' => 'Tríceps Pulley', 'series' => '3x12', 'dica' => 'Movimento completo para máximo estímulo.'],
        ['exercicio' => 'Tríceps Corda', 'series' => '3x12', 'dica' => 'Mantenha os cotovelos próximos ao corpo e faça a extensão completa do tríceps.'],
        ['exercicio' => 'Cardio (mínimo 30 minutos)', 'series' => '-', 'dica' => 'Escolha entre esteira, bicicleta, escada ou elíptico. Mantenha intensidade moderada.'],
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Treinos Personalizados - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css" />
    <link rel="icon" type="image/jpg" href="../imgs/logoBYB.jpg">
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
            display: block;
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f0f8ff;
            /* Animação de slide */
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            will-change: max-height, opacity;
        }
        .treino-conteudo.ativo {
            display: block;
            max-height: 1000px;
            opacity: 1;
            transition: max-height 1.1s cubic-bezier(.77,0,.18,1), opacity 0.7s;
        }
        .treino-conteudo.saindo {
            max-height: 0 !important;
            opacity: 0 !important;
            transition: max-height 1.1s cubic-bezier(.77,0,.18,1), opacity 0.7s;
        }
        .treino-conteudo-inner {
            opacity: 0;
            transform: translateY(-32px);
            transition: opacity 0.7s, transform 0.7s;
        }
        .treino-conteudo.ativo .treino-conteudo-inner {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.7s 0.2s, transform 0.7s 0.2s;
        }
        .treino-conteudo.saindo .treino-conteudo-inner {
            opacity: 0;
            transform: translateY(-32px);
            transition: opacity 0.5s, transform 0.5s;
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
    <main class="conteudo" style="max-width:var(--container-max-width);padding:var(--container-padding);min-height:auto;">
        <h2 style="font-size:var(--font-subtitle);letter-spacing:1px;margin-bottom:24px;text-shadow:0 2px 8px #43e97b99;">15 primeiros dias - adaptação:</h2>
        <div id="botoes-treino" style="display:flex;flex-wrap:wrap;gap:18px 24px;justify-content:center;margin-bottom:32px;">
        <?php $i=0; foreach (
            $treinos as $grupo => $exercicios): ?>
            <button class="treino-titulo" data-idx="<?php echo $i; ?>" style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);color:#fff;font-weight:bold;min-width:180px;letter-spacing:1px;">
                <?php echo $grupo; ?>
            </button>
        <?php $i++; endforeach; ?>
        </div>
        <?php $i=0; foreach ($treinos as $grupo => $exercicios): ?>
            <div class="treino-conteudo" data-idx="<?php echo $i; ?>" style="background:rgba(255,255,255,0.25);backdrop-filter:blur(6px);border-radius:18px;box-shadow:0 4px 24px #43e97b22;margin-bottom:28px;padding:0;max-width:600px;margin-left:auto;margin-right:auto;display:none;">
                <div class="treino-conteudo-inner" style="padding:28px 24px 18px 24px;">
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
            </div>
        <?php $i++; endforeach; ?>
        <h2 style="font-size:var(--font-subtitle);letter-spacing:1px;margin:40px 0 24px 0;text-shadow:0 2px 8px #43e97b99;">Objetivo: <b><?php echo ucfirst($objetivo); ?></b></h2>
        <?php
        // Função para ajustar séries/reps e dicas conforme objetivo
        function ajustarTreinoPorObjetivo($treinos, $objetivo) {
            $novoTreino = [];
            $dicaObjetivo = '';
            $objetivo = strtolower($objetivo);
            if ($objetivo === 'ganho de massa' || $objetivo === 'hipertrofia') {
                $dicaObjetivo = 'Já que quer ganhar massa, busque sempre colocar mais peso, mantendo a execução correta.';
                $series = '4x8-10';
                $cardio = 'Cardio leve, 15-20 minutos após o treino.';
            } elseif ($objetivo === 'emagrecimento' || $objetivo === 'perda de peso') {
                $dicaObjetivo = 'Foque em intensidade, pouco descanso e mantenha o ritmo elevado. Cardio é fundamental!';
                $series = '3x15-20';
                $cardio = 'Cardio intenso, 30-40 minutos após o treino.';
            } else {
                $dicaObjetivo = 'Mantenha regularidade e busque sempre evoluir nas cargas e execução.';
                $series = '3x12-15';
                $cardio = 'Cardio moderado, 20-30 minutos após o treino.';
            }
            foreach ($treinos as $grupo => $exercicios) {
                $novoExs = [];
                foreach ($exercicios as $ex) {
                    if (stripos($ex['exercicio'], 'Cardio') !== false) {
                        $novoExs[] = [
                            'exercicio' => $ex['exercicio'],
                            'series' => '-',
                            'dica' => $cardio
                        ];
                    } else {
                        $novoExs[] = [
                            'exercicio' => $ex['exercicio'],
                            'series' => $series,
                            'dica' => $ex['dica'] . ' ' . $dicaObjetivo
                        ];
                    }
                }
                $novoTreino[$grupo] = $novoExs;
            }
            return $novoTreino;
        }
        $treinosObjetivo = ajustarTreinoPorObjetivo($treinos, $objetivo);
        ?>
        <div id="botoes-treino-objetivo" style="display:flex;flex-wrap:wrap;gap:18px 24px;justify-content:center;margin-bottom:32px;">
        <?php $j=0; foreach ($treinosObjetivo as $grupo => $exercicios): ?>
            <button class="treino-titulo" data-idx="obj<?php echo $j; ?>" style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);color:#fff;font-weight:bold;min-width:180px;letter-spacing:1px;">
                <?php echo $grupo; ?>
            </button>
        <?php $j++; endforeach; ?>
        </div>
        <?php $j=0; foreach ($treinosObjetivo as $grupo => $exercicios): ?>
            <div class="treino-conteudo" data-idx="obj<?php echo $j; ?>" style="background:rgba(255,255,255,0.25);backdrop-filter:blur(6px);border-radius:18px;box-shadow:0 4px 24px #43e97b22;margin-bottom:28px;padding:0;max-width:600px;margin-left:auto;margin-right:auto;display:none;">
                <div class="treino-conteudo-inner" style="padding:28px 24px 18px 24px;">
                    <h3 style="font-size:1.25em;color:#43e97b;margin-bottom:18px;text-align:center;text-shadow:0 2px 8px #38f9d799;letter-spacing:1px;">Treino de <?php echo $grupo; ?> (Objetivo)</h3>
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
            </div>
        <?php $j++; endforeach; ?>
    </main>
    <script>
        // Corrigido: ao clicar, mostra/oculta apenas o treino correspondente, mantendo os botões visíveis
        const botoes = document.querySelectorAll('.treino-titulo');
        const conteudos = document.querySelectorAll('.treino-conteudo');
        // Separar botões e conteúdos de adaptação e objetivo
        const botoesAdap = document.querySelectorAll('#botoes-treino .treino-titulo');
        const conteudosAdap = document.querySelectorAll('.treino-conteudo[data-idx]:not([data-idx^="obj"])');
        const botoesObj = document.querySelectorAll('#botoes-treino-objetivo .treino-titulo');
        const conteudosObj = document.querySelectorAll('.treino-conteudo[data-idx^="obj"]');

        // Função para ativar/ocultar treinos
        function ativaTreino(btns, conteudos, idx) {
            const content = conteudos[idx];
            const btn = btns[idx];
            // Se já está ativo, anima saída
            if (content.classList.contains('ativo')) {
                content.classList.remove('ativo');
                content.classList.add('saindo');
                btn.classList.remove('ativo');
                btn.style.color = '#fff';
                setTimeout(() => {
                    content.classList.remove('saindo');
                    content.style.display = 'none';
                }, 800); // tempo igual ao transition do CSS
                return;
            }
            // Fecha todos antes de abrir o novo
            conteudos.forEach((c, i) => {
                if (c.classList.contains('ativo')) {
                    c.classList.remove('ativo');
                    c.classList.add('saindo');
                    btns[i].classList.remove('ativo');
                    btns[i].style.color = '#fff';
                    setTimeout(() => {
                        c.classList.remove('saindo');
                        c.style.display = 'none';
                    }, 800);
                } else {
                    c.classList.remove('saindo');
                    c.style.display = 'none';
                }
            });
            // Abre o selecionado
            content.style.display = 'block';
            setTimeout(()=>{content.classList.add('ativo');}, 10);
            btn.classList.add('ativo');
            btn.style.color = '#267f4a';
        }

        botoesAdap.forEach((btn, idx) => {
            btn.addEventListener('click', function() {
                ativaTreino(botoesAdap, conteudosAdap, idx);
            });
            btn.addEventListener('mouseover', function(){
                btn.style.background = 'linear-gradient(90deg,#38f9d7 0%,#43e97b 100%)';
                if (!btn.classList.contains('ativo')) btn.style.color = '#267f4a';
                btn.style.transform = 'scale(1.04)';
            });
            btn.addEventListener('mouseout', function(){
                btn.style.background = btn.classList.contains('ativo') ? 'linear-gradient(90deg,#38f9d7 0%,#43e97b 100%)' : 'linear-gradient(90deg,#43e97b 0%,#38f9d7 100%)';
                btn.style.color = btn.classList.contains('ativo') ? '#267f4a' : '#fff';
                btn.style.transform = 'scale(1)';
            });
        });
        botoesObj.forEach((btn, idx) => {
            btn.addEventListener('click', function() {
                ativaTreino(botoesObj, conteudosObj, idx);
            });
            btn.addEventListener('mouseover', function(){
                btn.style.background = 'linear-gradient(90deg,#38f9d7 0%,#43e97b 100%)';
                if (!btn.classList.contains('ativo')) btn.style.color = '#267f4a';
                btn.style.transform = 'scale(1.04)';
            });
            btn.addEventListener('mouseout', function(){
                btn.style.background = btn.classList.contains('ativo') ? 'linear-gradient(90deg,#38f9d7 0%,#43e97b 100%)' : 'linear-gradient(90deg,#43e97b 0%,#38f9d7 100%)';
                btn.style.color = btn.classList.contains('ativo') ? '#267f4a' : '#fff';
                btn.style.transform = 'scale(1)';
            });
        });
    </script>
        <footer class="holder" style="text-align:center;padding:20px 0;">
        <p style="font-size:var(--font-base);margin:0;">Site desenvolvido para pessoas em obesidade/sobre-peso, ou para quem deseja seguir uma vida mais saudável e ativa.</p>
        <p style="font-size:var(--font-base);">© 2025 BYB - Build Your Body. Todos os direitos reservados.</p>

</body>
</html>

<?php
$conn->close();
?>
