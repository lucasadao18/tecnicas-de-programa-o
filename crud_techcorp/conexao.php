// conexao.php
<?php
$host = 'localhost'; 
$usuario = 'root';  
$senha = '';        
$banco = 'meu_crud'; 

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {

    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

$conexao->set_charset("utf8");

?>