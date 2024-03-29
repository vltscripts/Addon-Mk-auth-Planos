<?php
// INCLUE FUNCOES DE ADDONS -----------------------------------------------------------------------
include('addons.class.php');

// VERIFICA SE O USUARIO ESTA LOGADO --------------------------------------------------------------
session_name('mka');
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['MKA_Logado'])) exit('Acesso negado... <a href="/admin/">Fazer Login</a>');
// VERIFICA SE O USUARIO ESTA LOGADO --------------------------------------------------------------

// Assuming $Manifest is defined somewhere before this code
$manifestTitle = isset($Manifest->{'name'}) ? $Manifest->{'name'} : '';
$manifestVersion = isset($Manifest->{'version'}) ? $Manifest->{'version'} : '';
?>

<!DOCTYPE html>
<?php
if (isset($_SESSION['MM_Usuario'])) {
    echo '<html lang="pt-BR">'; // Fix versão antiga MK-AUTH
} else {
    echo '<html lang="pt-BR" class="has-navbar-fixed-top">';
}
?>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>MK - AUTH :: <?php echo $Manifest->{'name'} . " - V " . $Manifest->{'version'};  ?></title>

    <link href="../../estilos/mk-auth.css" rel="stylesheet" type="text/css" />
    <link href="../../estilos/font-awesome.css" rel="stylesheet" type="text/css" />

    <script src="../../scripts/jquery.js"></script>
    <script src="../../scripts/mk-auth.js"></script>
    <link href="../../estilos/bi-icons.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/css.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        /* Estilos CSS personalizados */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        form,
        .table-container,
        .client-count-container {
            width: 80%;
            margin: 0 auto;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 2px;
            text-align: left;
        }

        table th {
            background-color: #0d6cea;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        h1 {
            color: #4caf50;
        }

        .client-count-container {
            text-align: center;
            margin-top: 10px;
        }

        .client-count {
            color: #4caf50;
            font-weight: bold;
        }

        .client-count.blue {
            color: #2196F3;
        }

        .nome_cliente a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        .nome_cliente a:hover {
            text-decoration: underline;
        }

        .nome_cliente td {
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .nome_cliente:nth-child(odd) {
            background-color: #FFFF99;
        }

        /* Adiciona uma classe para identificar as células do plano clicáveis */
        .plan-name {
            cursor: pointer;
            color: blue;
            font-weight: bold;
        }
		    /* Estilo para o botão Limpar */
        .button-clear {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        background-color: #f44336; /* Cor de fundo vermelha */
        color: white;
        font-weight: bold;
        cursor: pointer;
        }
    </style>

<script type="text/javascript">
    function clearSearch() {
        // Limpa o campo de busca
        document.getElementById('search').value = '';

        // Reseta o título do documento
        document.title = 'MK - AUTH';

        // Submete automaticamente o formulário
        document.forms['searchForm'].submit();
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Adiciona um ouvinte de eventos de clique a todas as células na coluna "Plano"
        var cells = document.querySelectorAll('.table-container tbody td.plan-name');
        cells.forEach(function (cell) {
            cell.addEventListener('click', function () {
                // Obtém o valor do "Plano" clicado
                var planName = this.innerText;

                // Atualiza o campo de busca com o valor clicado
                document.getElementById('search').value = planName;

                // Adiciona o título específico ao painel
                document.title = 'Painel: ' + planName;

                // Submete automaticamente o formulário
                document.forms['searchForm'].submit();
            });
        });
    });
</script>


</head>

<body>
    <?php include('../../topo.php'); ?>

    <nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
        <ul>
            <li><a href="#"> ADDON</a></li>
            <li class="is-active">
                <a href="#" aria-current="page"> <?php echo htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?> </a>
            </li>
        </ul>
    </nav>

    <?php include('config.php'); ?>

    <?php
    if ($acesso_permitido) {
        // Formulário Atualizado com Funcionalidade de Busca
        ?>
<form id="searchForm" method="GET">
    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div style="width: 80%; margin-right: 10px;">
            <label for="search" style="font-weight: bold; margin-bottom: 5px;">Buscar Plano:</label>
            <input type="text" id="search" name="search" placeholder="Digite o Nome do Plano " value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc;">
        </div>
        <div style="display: flex; align-items: flex-end; flex-direction: row;">
            <input type="submit" value="Buscar" style="padding: 10px; border: 1px solid #ccc; background-color: #4caf50; color: white; font-weight: bold; cursor: pointer; border-radius: 5px;">
            <button type="button" class="button-clear" onclick="clearSearch()" style="margin-left: 10px; padding: 10px; border: 1px solid #ccc; background-color: #f44336; color: white; font-weight: bold; cursor: pointer; border-radius: 5px;">Limpar</button>
        </div>
    </div>
</form>

        <div class="table-container">
            <table style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 2px solid #ddd;">Plano</th>
                        <th style="border: 2px solid #ddd;">Valor</th>
                        <th style="border: 2px solid #ddd;">Upload</th>
                        <th style="border: 2px solid #ddd;">Download</th>
						<th style="border: 2px solid #ddd;">Alterar Plano</th>
                    </tr>
                </thead>
                <tbody>
        <?php
        // Adicione a condição de busca, se houver
        $searchCondition = '';
        $search = '';
        if (isset($_GET['search'])) {
        $search = '%' . mysqli_real_escape_string($link, $_GET['search']) . '%';
        $searchCondition = " AND (p.nome LIKE ? OR p.valor LIKE ?)";
        }

        $query = "SELECT p.uuid_plano, p.nome, p.valor, p.velup, p.veldown
          FROM sis_plano p
          WHERE p.oculto = 'nao'"
        . $searchCondition .
        " ORDER BY p.valor DESC";

        $stmt = mysqli_prepare($link, $query);

        if (!empty($search)) {
        mysqli_stmt_bind_param($stmt, "ss", $search, $search);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Resto do código para processar o resultado...

        $total_boletos_ = 0; // Inicialize a variável de contagem

        // Loop através dos resultados da consulta e exibir os dados na tabela
        if ($result) {
        $rowNumber = 0;
        while ($row = mysqli_fetch_assoc($result)) {
        $nome_por_num_titulo = "Nome do Cliente: " . $row['nome'];
        $rowNumber++;

        // Incrementar o contador de boletos
        $total_boletos_++;

        $nomeClienteClass = ($rowNumber % 2 == 0) ? 'nome_cliente' : 'nome_cliente highlight';

        echo "<tr class='$nomeClienteClass'>";
        // Plano 
        echo "<td class='plan-name' style='border: 1px solid #ddd; position: relative;'>";
        echo "<img src='img/plano.png' alt='Ícone de Nome' width='25' height='25' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
        echo "<span style='color: blue; font-weight: bold; cursor: pointer;'>" . $row['nome'] . "</span>";
        echo "</td>";

        // Valor
        echo "<td style='text-align: center; color: #283fda; font-weight: bold; border: 1px solid #ddd; position: relative;'>";
        echo "<img src='img/valor.png' alt='Ícone de Valor' width='20' height='20' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
        echo $row['valor'];
        echo "</td>";

        // Upload
        echo "<td style='text-align: center; color: #da6a28; font-weight: bold; border: 1px solid #ddd; position: relative;'>";
        echo "<img src='img/upload.png' alt='Ícone de Upload' width='20' height='20' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
        echo $row['velup'];
        echo "</td>";

        // Download
        echo "<td style='text-align: center; color: #da6a28; font-weight: bold; border-left: 1px solid #ddd; position: relative;'>";
        echo "<img src='img/download.png' alt='Ícone de Download' width='20' height='20' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
        echo $row['veldown'];
        echo "</td>";

        // Alterar Valor
        echo "<td style='text-align: center; color: blue; font-weight: bold; border: 1px solid #ddd; position: relative;'>";
        echo "<img src='img/alterar.png' alt='Ícone de Alterar' width='20' height='20' style='position: absolute; left: 0; top: 50%; transform: translateY(-50%);'> ";
        echo "<a href='/admin/planos_alt.hhvm?uuid=" . $row['uuid_plano'] . "' style='color: blue; font-weight: bold; cursor: pointer;'>Alterar</a>";
        echo "</td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Erro na consulta: " . mysqli_error($link) . "</td></tr>";
}

// Exibe o total de boletos
echo "<div style='text-align: center; font-weight: bold; color: blue;'>Total de Planos: " . $total_boletos_ . "</div>";

                    ?>
                </tbody>
            </table>
        </div>

    <?php
    } else {
        echo "Acesso não permitido!";
    }
    ?>

    <?php include('../../baixo.php'); ?>

    <script src="../../menu.js.php"></script>
    <?php include('../../rodape.php'); ?>
</body>

</html>
