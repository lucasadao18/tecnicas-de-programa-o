<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lê o campo 'usuario' do formulário (que armazena o email)
    $email_digitado = $_POST['usuario']; 
    $senha_digitada = $_POST['senha'];

    // Query busca pelo 'email' na coluna 'email'
    $sql = "SELECT id, email, senha, nome_completo FROM usuarios WHERE email = ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email_digitado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $linha = $resultado->fetch_assoc();
        $hash_armazenado = $linha['senha'];

        if (password_verify($senha_digitada, $hash_armazenado)) {
            
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $linha['id'];
            $_SESSION['usuario_nome'] = $linha['nome_completo'];

            $stmt->close();
            $conexao->close();
            header("Location: index.php");
            exit();
            
        } else {
            header("Location: login.php?erro=1");
            exit();
        }
    } else {
        header("Location: login.php?erro=1");
        exit();
    }
    
    $stmt->close();
    $conexao->close();
    
} else {
    header("Location: login.php");
    exit();
}
?>