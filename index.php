<?php
// Verifica se o arquivo de tarefas existe
if (file_exists('tarefas.json')) {
    // Lê o conteúdo do arquivo JSON
    $json_tarefas = file_get_contents('tarefas.json');
    // Converte o JSON em um array PHP
    $tarefas = json_decode($json_tarefas, true);
} else {
    // Se o arquivo não existir, cria um array vazio
    $tarefas = [];
    // $tarefas é um array que armazena todas as tarefas
}

// Verifica se há um filtro ativo (todas, pendentes ou concluídas)
$filtro = 'todas'; // Filtro padrão
if (isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
}
// isset verifica se a variável está definida
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>📝 Minha Lista de Tarefas</h1>

        <!-- Formulário para adicionar tarefas -->
        <form action="salvar_tarefa.php" method="POST" class="formulario-tarefa">
            <input type="text" name="titulo" placeholder="Título (ex: Academia)" required>
            <textarea name="descricao" placeholder="Descrição..."></textarea>
            <input type="datetime-local" name="data_vencimento" required>
            <button type="submit">Adicionar Tarefa</button>
        </form>

        <!-- Filtros -->
        <div class="filtros">
            <a href="?filtro=todas" class="<?= $filtro === 'todas' ? 'ativo' : '' ?>">Todas</a>
            <!-- Código verifica se o filtro está ativo -->
            <a href="?filtro=pendentes" class="<?= $filtro === 'pendentes' ? 'ativo' : '' ?>">Pendentes</a>
            <a href="?filtro=concluidas" class="<?= $filtro === 'concluidas' ? 'ativo' : '' ?>">Concluídas</a>
        </div>

        <!-- Lista de tarefas -->
        <div class="tarefas">
            <?php if (empty($tarefas)) : ?>
                <p class="vazio">Nenhuma tarefa encontrada. 🎉</p>
            <?php else : ?>
                <?php foreach ($tarefas as $id => $tarefa) : ?>
                    
                    <?php 
                    // Aplica filtro
                    if (
                        ($filtro === 'pendentes' && $tarefa['concluida']) ||
                        ($filtro === 'concluidas' && !$tarefa['concluida'])
                    ) continue;
                    ?>
                    
                    <div class="tarefa <?= $tarefa['concluida'] ? 'concluida' : '' ?>">
                        <div class="cabecalho-tarefa">
                            <h3><?= htmlspecialchars($tarefa['titulo']) ?></h3>
                            <span class="data-vencimento"><?= date('d/m/Y H:i', strtotime($tarefa['data_vencimento'])) ?></span>
                        </div>
                        
                        <p class="descricao"><?= htmlspecialchars($tarefa['descricao']) ?></p>
                        
                        <div class="acoes">
                            <a href="alterar_status.php?id=<?= $id ?>" class="status">
                                <?= $tarefa['concluida'] ? '✅ Concluído' : '🕒 Pendente' ?>
                            </a>
                            <a href="editar_tarefa.php?id=<?= $id ?>" class="editar">✏️ Editar</a>
                            <a href="excluir_tarefa.php?id=<?= $id ?>" class="excluir">🗑️ Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>