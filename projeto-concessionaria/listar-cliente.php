<h1>Listar clientes</h1>

<?php
    // Seleciona todos os clientes
    $sql = "SELECT * FROM cliente";

    // Assume-se que $conn é um objeto de conexão
    $res = $conn->query($sql);

    // Conta a quantidade de resultados
    $qtd = $res->num_rows;

    if ($qtd > 0) {
        print "<p>Encontrou <b>$qtd</b> resultado(s)</p>";
        
        // Inicia a Tabela
        print "<table class='table table-bordered table-striped table-hover'>";
        
        // Cabeçalho da Tabela (<thead>)
        print "<thead>";
        print "<tr>";
        print "<th>#</th>";
        print "<th>Nome</th>";
        print "<th>CPF</th>";
        print "<th>E-mail</th>";
        print "<th>Telefone</th>";
        print "<th>Endereço</th>";         
        print "<th>Dt. Nasc.</th>";        
        print "<th>Ações</th>";           
        print "</tr>";
        print "</thead>";

        // Corpo da Tabela 
        print "<tbody>";
        
        // Itera sobre os resultados
        while ($row = $res->fetch_object()) {
            print "<tr>";
            print "<td>" . $row->id_cliente . "</td>";
            print "<td>" . $row->nome_cliente . "</td>";
            print "<td>" . $row->cpf_cliente . "</td>";
            print "<td>" . $row->email_cliente . "</td>";
            print "<td>" . $row->telefone_cliente . "</td>";
            print "<td>" . $row->endereco_cliente . "</td>"; 
            
            // Formata a data para um formato mais legível (opcional)
            $dt_formatada = date('d/m/Y', strtotime($row->dt_nasc_cliente));
            print "<td>" . $dt_formatada . "</td>";          // Exibe Data de Nascimento
            
            // Adicionando botões de Ação com links
            print "<td>";
            
            // Botão Editar: Redireciona para o formulário de edição
            print "<button class='btn btn-success' onclick=\"location.href='?page=editar-cliente&id_cliente={$row->id_cliente}';\">Editar</button>";
            
            // Botão Excluir
            print "<button class='btn btn-danger' onclick=\"if(confirm('Tem certeza que deseja excluir?')){location.href='?page=salvar-cliente&acao=excluir&id_cliente={$row->id_cliente}';}else{false;}\">Excluir</button>";
            print "</td>";
            print "</tr>";
        }
        print "</tbody>";
        
        print "</table>";
        print"<a href='?page=index.php' class='btn btn-secondary'> Voltar </a>";
    } else {
        // Alerta mensagem de informação
        print "<div class='alert alert-info' role='alert'>Não encontrou resultado.</div>";
        print"<a href='?page=index.php' class='btn btn-secondary'> Voltar </a>";
    }
?>