<?php

if (isset($_GET['id'])) {
 
    $json_tarefas = file_get_contents('tarefas.json');
    $tarefas = json_decode($json_tarefas, true);

  
    $id = $_GET['id'];
    if (isset($tarefas[$id])) {
        $tarefa = $tarefas[$id];
    } else {
        echo "Tarefa não encontrada!";
        exit;
    }
} else {
    echo "ID não fornecido!";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $tarefas[$id]['titulo'] = $_POST['titulo'];
    $tarefas[$id]['descricao'] = $_POST['descricao'];
    $tarefas[$id]['data_vencimento'] = $_POST['data_vencimento'];


    file_put_contents('tarefas.json', json_encode($tarefas, JSON_PRETTY_PRINT));


    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>

        <form action="editar_tarefa.php?id=<?= $id ?>" method="POST" class="formulario-tarefa">
            <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required>
            <textarea name="descricao" placeholder="Descrição..."><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
            <input type="datetime-local" name="data_vencimento" value="<?= date('Y-m-d\TH:i', strtotime($tarefa['data_vencimento'])) ?>" required>
            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="index.php">Voltar para a lista de tarefas</a>
    </div>
</body>
</html>
