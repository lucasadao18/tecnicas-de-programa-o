<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $cargo = $conexao->real_escape_string($_POST['cargo']);
    $salario = str_replace(',', '.', $conexao->real_escape_string($_POST['salario']));
    $contratacao = $conexao->real_escape_string($_POST['contratacao']);

    $sql = "INSERT INTO funcionarios (nome, setor_id, salario, contratacao) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    
    $stmt->bind_param("ssds", $nome, $cargo, $salario, $contratacao); 

    if ($stmt->execute()) {
        header("Location: index.php?msg=sucesso_cadastro");
        exit();
    } else {
        $erro = "Erro ao cadastrar: " . $conexao->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cadastrar Novo Funcionário</h2>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="adicionar.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" id="cargo" name="cargo" required>
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salário (R$)</label>
                <input type="number" step="0.01" class="form-control" id="salario" name="salario" required>
            </div>
            <div class="mb-3">
                <label for="contratacao" class="form-label">Data de Contratação</label>
                <input type="date" class="form-control" id="contratacao" name="contratacao" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
<?php $conexao->close(); ?> 