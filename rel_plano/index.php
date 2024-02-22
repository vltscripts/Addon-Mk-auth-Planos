<?php
include('addons.class.php');

session_name('mka');
session_start();

if (!isset($_SESSION['MKA_Logado'])) {
    exit('Acesso negado... <a href="/admin/">Fazer Login</a>');
}

// Assuming $Manifest is defined somewhere before this code
$manifestTitle = isset($Manifest->{'name'}) ? $Manifest->{'name'} : '';
$manifestVersion = isset($Manifest->{'version'}) ? $Manifest->{'version'} : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>MK - AUTH :: <?php echo htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?></title>

    <link href="../../estilos/mk-auth.css" rel="stylesheet" type="text/css" />
    <link href="../../estilos/font-awesome.css" rel="stylesheet" type="text/css" />

    <script src="../../scripts/jquery.js"></script>
    <script src="../../scripts/mk-auth.js"></script>

    <style type="text/css">
        /* Estilos CSS personalizados */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
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
            background-color: #4caf50;
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
    <label for="search">Buscar Cliente:</label>
    <input type="text" id="search" name="search" placeholder="Digite o nome do cliente" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <input type="submit" value="Buscar">
    <button type="button" class="button-clear" onclick="clearSearch()">Limpar</button>
    </form>




        <?php
        // Dados de conexão com o banco de dados já estão em config.php
        $searchCondition = '';
        $search = '%' . mysqli_real_escape_string($link, $_GET['search']) . '%';

        if (!empty($_GET['search'])) {
            $searchCondition = " WHERE p.nome LIKE ? OR p.valor LIKE ?";
        }

        $countQuery = "SELECT COUNT(*) AS client_count FROM sis_plano WHERE oculto = 'nao'";

        if (!empty($_GET['search'])) {
            $countQuery .= " AND (nome LIKE ? OR valor LIKE ?)";
        }

        $stmt = mysqli_prepare($link, $countQuery);

        if (!empty($_GET['search'])) {
            $search = '%' . mysqli_real_escape_string($link, $_GET['search']) . '%';
            mysqli_stmt_bind_param($stmt, "ss", $search, $search);
        }

        mysqli_stmt_execute($stmt);
        $countResult = mysqli_stmt_get_result($stmt);

        if ($countResult) {
            $countRow = mysqli_fetch_assoc($countResult);
            $clientCount = $countRow['client_count'];

            echo "<div class='client-count-container'><p class='client-count blue'>Quantidade de Planos: $clientCount</p></div>";
        } else {
            echo "<div class='client-count-container'><p class='client-count blue'>Erro ao obter a quantidade de planos</p></div>";
        }

        ?>

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
                    if (!empty($_GET['search'])) {
                        $search = mysqli_real_escape_string($link, $_GET['search']);
                        $searchCondition = " AND (p.nome LIKE '%$search%' OR p.valor LIKE '%$search%')";
                    }

                    $searchCondition = '';
                    $search = '%' . mysqli_real_escape_string($link, $_GET['search']) . '%';

                    if (!empty($_GET['search'])) {
                        $searchCondition = " AND (p.nome LIKE ? OR p.valor LIKE ?) AND p.oculto = 'nao'";
                    }

                    $query = "SELECT p.uuid_plano, p.nome, p.valor, p.velup, p.veldown
                            FROM sis_plano p
                            WHERE p.oculto = 'nao'"
                        . $searchCondition .
                        " ORDER BY p.valor DESC";

                    $stmt = mysqli_prepare($link, $query);
                    mysqli_stmt_bind_param($stmt, "ss", $search, $search);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result) {
                        $rowNumber = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $nome_por_num_titulo = "Nome do Cliente: " . $row['nome'];
                            $rowNumber++;

                            $nomeClienteClass = ($rowNumber % 2 == 0) ? 'nome_cliente' : 'nome_cliente highlight';

        echo "<tr class='$nomeClienteClass'>";
        echo "<td class='plan-name' style='border: 1px solid #ddd;'><span style='color: blue; font-weight: bold; cursor: pointer;'>" . $row['nome'] . "</span></td>";
        echo "<td style='text-align: center; color: #283fda; font-weight: bold; border: 1px solid #ddd;'>" . $row['valor'] . "</td>";

        // Adiciona a cor verde (#4caf50) e borda à direita e à esquerda nos resultados da coluna "Upload"
        echo "<td style='text-align: center; color: #da6a28; font-weight: bold; border: 1px solid #ddd;'>" . $row['velup'] . "</td>";

        // Adiciona a cor verde (#4caf50) e borda à esquerda nos resultados da coluna "Download"
        echo "<td style='text-align: center; color: #da6a28; font-weight: bold; border-left: 1px solid #ddd;'>" . $row['veldown'] . "</td>";
        echo "<td style='text-align: center; color: blue; font-weight: bold; border: 1px solid #ddd;'><a href='/admin/planos_alt.hhvm?uuid=" . $row['uuid_plano'] . "' style='color: blue; font-weight: bold; cursor: pointer;'>Alterar</a></td>";
        echo "</tr>";
    }
                    } else {
                        echo "<tr><td colspan='4'>Erro na consulta: " . mysqli_error($link) . "</td></tr>";
                    }
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
