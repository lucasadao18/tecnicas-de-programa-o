<?php
session_start();
if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - TechCorp Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container {
            max-width: 400px;
            margin-top: 100px;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container mx-auto">
            <h2 class="text-center text-primary mb-4">Acesso Restrito - RH</h2>
            
            <?php
            if (isset($_GET['erro']) && $_GET['erro'] == 1) {
                echo '<div class="alert alert-danger">Usuário ou senha inválidos.</div>';
            }
            if (isset($_GET['nao_autorizado']) && $_GET['nao_autorizado'] == 1) {
                echo '<div class="alert alert-warning">Acesso negado. Por favor, faça login.</div>';
            }
            ?>

            <form action="verificar_login.php" method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário (Email):</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>