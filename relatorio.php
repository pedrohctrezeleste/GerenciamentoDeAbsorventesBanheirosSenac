
<?php

session_start();

include_once("./conexao.php");


/* =========================================
   FILTROS
========================================= */

$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim    = $_GET['data_fim'] ?? '';
$andar       = $_GET['andar'] ?? '';
$tipo        = $_GET['tipo'] ?? '';


/* =========================================
   CONSULTA
========================================= */

$sql = "
    SELECT
        id_historico,
        andar,
        tipo,
        quantidade_movimentada,
        quantidade_anterior,
        quantidade_nova,
        data_hora
    FROM historico
    WHERE andar IN (
        'Térreo',
        '1º Andar',
        '2º Andar',
        '3º Andar'
    )
";


/* =========================================
   FILTRO DATA INICIAL
========================================= */

if ($data_inicio != '') {

    $sql .= "
        AND DATE(data_hora) >= '$data_inicio'
    ";

}


/* =========================================
   FILTRO DATA FINAL
========================================= */

if ($data_fim != '') {

    $sql .= "
        AND DATE(data_hora) <= '$data_fim'
    ";

}


/* =========================================
   FILTRO ANDAR
========================================= */

if ($andar != '') {

    $sql .= "
        AND andar = '$andar'
    ";

}


/* =========================================
   FILTRO TIPO
========================================= */

if ($tipo != '') {

    $sql .= "
        AND tipo = '$tipo'
    ";

}


/* =========================================
   ORDEM
========================================= */

$sql .= "
    ORDER BY data_hora DESC
";


$resultado = mysqli_query($conn, $sql);


if (!$resultado) {

    die(
        "Erro na consulta: " .
        mysqli_error($conn)
    );

}

?>


<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Relatório de Movimentações</title>


    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- =====================================================
         CSS DO SISTEMA
    ====================================================== -->

    <link
        rel="stylesheet"
        href="style.css"
    >


    <!-- =====================================================
         AJUSTES EXCLUSIVOS DO RELATÓRIO
    ====================================================== -->

    <style>

        .relatorio-container {

            width: 100% !important;

            max-width: 1200px !important;

            margin-left: auto !important;

            margin-right: auto !important;

        }


        .relatorio-container .card {

            width: 100% !important;

            max-width: 100% !important;

        }


        .relatorio-container .filtro-form {

            width: 100% !important;

            max-width: none !important;

            margin: 0 !important;

            padding: 0 !important;

            background: transparent !important;

            box-shadow: none !important;

            backdrop-filter: none !important;

        }


        .relatorio-container .form-label {

            display: block;

            width: 100%;

            text-align: left;

        }


        .relatorio-container .form-control,

        .relatorio-container .form-select {

            width: 100% !important;

            min-width: 0 !important;

        }


        .relatorio-container .historico-card {

            width: 100% !important;

            max-width: 100% !important;

            padding: 25px !important;

        }


        .relatorio-container
        .historico-card
        .table-responsive {

            width: 100% !important;

            overflow-x: auto;

        }


        .relatorio-container
        .historico-card
        table {

            width: 100% !important;

            min-width: 900px;

            margin-bottom: 0;

        }


        .relatorio-container
        .historico-card
        thead {

            color: white;

        }


        .relatorio-container
        .historico-card
        th,

        .relatorio-container
        .historico-card
        td {

            padding: 12px 15px;

            white-space: nowrap;

            vertical-align: middle;

        }


        .relatorio-container .resumo-card {

            width: 100% !important;

            max-width: 100% !important;

            min-height: 150px;

            display: flex;

            justify-content: center;

            align-items: center;

        }


        .relatorio-container h1 {

            white-space: nowrap;

        }


        .relatorio-container
        .sem-resultados {

            padding: 30px !important;

            color: #8a5a7a;

        }


        @media (max-width: 768px) {

            .relatorio-container {

                width: 95% !important;

                max-width: 95% !important;

            }


            .relatorio-container h1 {

                white-space: normal;

                font-size: 1.8rem;

            }


            .relatorio-container
            .historico-card {

                padding: 15px !important;

            }

        }

    </style>

</head>


<body>


<div class="container relatorio-container mt-4 mb-4">


    <!-- =====================================================
         TÍTULO
    ====================================================== -->

    <div class="text-center mb-4">

        <h1>

            <i class="bi bi-clipboard-data"></i>

            RELATÓRIO DE MOVIMENTAÇÕES

        </h1>


        <p>

            Controle de entradas, retiradas e atualizações

        </p>

    </div>


    <hr>



    <!-- =====================================================
         FILTROS
    ====================================================== -->

    <div class="card p-4 mb-4">


        <h2 class="text-center">

            <i class="bi bi-funnel"></i>

            Filtros

        </h2>


        <form
            method="GET"
            action="relatorio.php"
            class="filtro-form"
        >


            <div class="row g-3">


                <!-- DATA INICIAL -->

                <div class="col-md-3">

                    <label
                        for="data_inicio"
                        class="form-label"
                    >

                        Data inicial

                    </label>


                    <input
                        type="date"
                        name="data_inicio"
                        id="data_inicio"
                        class="form-control"
                        value="<?php echo htmlspecialchars($data_inicio); ?>"
                    >

                </div>



                <!-- DATA FINAL -->

                <div class="col-md-3">

                    <label
                        for="data_fim"
                        class="form-label"
                    >

                        Data final

                    </label>


                    <input
                        type="date"
                        name="data_fim"
                        id="data_fim"
                        class="form-control"
                        value="<?php echo htmlspecialchars($data_fim); ?>"
                    >

                </div>



                <!-- ANDAR -->

                <div class="col-md-3">

                    <label
                        for="andar"
                        class="form-label"
                    >

                        Andar

                    </label>


                    <select
                        name="andar"
                        id="andar"
                        class="form-select"
                    >

                        <option value="">
                            Todos
                        </option>


                        <option
                            value="Térreo"

                            <?php

                            if ($andar == 'Térreo') {

                                echo 'selected';

                            }

                            ?>
                        >

                            Térreo

                        </option>


                        <option
                            value="1º Andar"

                            <?php

                            if ($andar == '1º Andar') {

                                echo 'selected';

                            }

                            ?>
                        >

                            1º Andar

                        </option>


                        <option
                            value="2º Andar"

                            <?php

                            if ($andar == '2º Andar') {

                                echo 'selected';

                            }

                            ?>
                        >

                            2º Andar

                        </option>


                        <option
                            value="3º Andar"

                            <?php

                            if ($andar == '3º Andar') {

                                echo 'selected';

                            }

                            ?>
                        >

                            3º Andar

                        </option>

                    </select>

                </div>



                <!-- TIPO -->

                <div class="col-md-3">

                    <label
                        for="tipo"
                        class="form-label"
                    >

                        Movimentação

                    </label>


                    <select
                        name="tipo"
                        id="tipo"
                        class="form-select"
                    >

                        <option value="">
                            Todas
                        </option>


                        <option
                            value="ENTRADA"

                            <?php

                            if ($tipo == 'ENTRADA') {

                                echo 'selected';

                            }

                            ?>
                        >

                            Entrada

                        </option>


                        <option
                            value="RETIRADA"

                            <?php

                            if ($tipo == 'RETIRADA') {

                                echo 'selected';

                            }

                            ?>
                        >

                            Retirada

                        </option>


                        <option
                            value="ATUALIZAÇÃO"

                            <?php

                            if ($tipo == 'ATUALIZAÇÃO') {

                                echo 'selected';

                            }

                            ?>
                        >

                            Atualização

                        </option>


                    </select>

                </div>



                <!-- BOTÕES -->

                <div class="col-12 text-center mt-4">


                    <button
                        type="submit"
                        class="botao-administrador"
                    >

                        <i class="bi bi-search"></i>

                        FILTRAR

                    </button>


                    <a
                        href="relatorio.php"
                        class="botao-adminpainel"
                    >

                        <i class="bi bi-x-circle"></i>

                        LIMPAR

                    </a>


                </div>


            </div>


        </form>

    </div>



    <!-- =====================================================
         TABELA
    ====================================================== -->

    <div class="card p-3 historico-card">


        <h2 class="text-center mb-4">

            <i class="bi bi-clock-history"></i>

            Histórico de movimentações

        </h2>


        <div class="table-responsive">


            <table
                class="table table-bordered table-hover align-middle"
            >


                <thead
                    style="
                        background: linear-gradient(
                            135deg,
                            #f783ac,
                            #cc5de8
                        );
                        color: white;
                    "
                >

                    <tr>

                        <th class="text-center">
                            Data
                        </th>


                        <th class="text-center">
                            Hora
                        </th>


                        <th class="text-center">
                            Andar
                        </th>


                        <th class="text-center">
                            Tipo
                        </th>


                        <th class="text-center">
                            Quantidade
                        </th>


                        <th class="text-center">
                            Estoque anterior
                        </th>


                        <th class="text-center">
                            Estoque atual
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php


                /* =========================================
                   TOTAIS
                ========================================== */

                $total_entrada = 0;

                $total_retirada = 0;


                /* =========================================
                   RESULTADOS
                ========================================== */

                if (mysqli_num_rows($resultado) > 0) {


                    while (
                        $row = mysqli_fetch_assoc($resultado)
                    ) {


                        /* DATA */

                        $data = date(
                            'd/m/Y',
                            strtotime(
                                $row['data_hora']
                            )
                        );


                        /* HORA */

                        $hora = date(
                            'H:i',
                            strtotime(
                                $row['data_hora']
                            )
                        );


                        /* =================================
                           TOTAL ENTRADAS
                        ================================= */

                        if (
                            $row['tipo'] == 'ENTRADA'
                        ) {

                            $total_entrada +=
                                (int)
                                $row[
                                    'quantidade_movimentada'
                                ];

                        }


                        /* =================================
                           TOTAL RETIRADAS
                        ================================= */

                        if (
                            $row['tipo'] == 'RETIRADA'
                        ) {

                            $total_retirada +=
                                (int)
                                $row[
                                    'quantidade_movimentada'
                                ];

                        }


                ?>


                    <tr>


                        <!-- DATA -->

                        <td class="text-center">

                            <?php

                            echo $data;

                            ?>

                        </td>


                        <!-- HORA -->

                        <td class="text-center">

                            <?php

                            echo $hora;

                            ?>

                        </td>


                        <!-- ANDAR -->

                        <td class="text-center">

                            <?php

                            echo htmlspecialchars(
                                $row['andar']
                            );

                            ?>

                        </td>


                        <!-- TIPO -->

                        <td class="text-center">


                            <?php


                            /* =================================
                               ENTRADA
                            ================================= */

                            if (
                                $row['tipo'] == 'ENTRADA'
                            ) {


                                echo '

                                    <span
                                        class="badge rounded-pill"
                                        style="
                                            background: #d3f9d8;
                                            color: #087f5b;
                                        "
                                    >

                                        <i
                                            class="bi bi-box-arrow-in-down"
                                        ></i>

                                        ENTRADA

                                    </span>

                                ';


                            }


                            /* =================================
                               RETIRADA
                            ================================= */

                            elseif (
                                $row['tipo'] == 'RETIRADA'
                            ) {


                                echo '

                                    <span
                                        class="badge rounded-pill"
                                        style="
                                            background: #ffe3ea;
                                            color: #c2255c;
                                        "
                                    >

                                        <i
                                            class="bi bi-box-arrow-up"
                                        ></i>

                                        RETIRADA

                                    </span>

                                ';


                            }


                            /* =================================
                               ATUALIZAÇÃO
                            ================================= */

                            elseif (
                                $row['tipo'] == 'ATUALIZAÇÃO'
                            ) {


                                echo '

                                    <span
                                        class="badge rounded-pill"
                                        style="
                                            background: #e5dbff;
                                            color: #6741d9;
                                        "
                                    >

                                        <i
                                            class="bi bi-arrow-repeat"
                                        ></i>

                                        ATUALIZAÇÃO

                                    </span>

                                ';


                            }


                            /* =================================
                               OUTRO TIPO
                            ================================= */

                            else {


                                echo '

                                    <span
                                        class="badge rounded-pill"
                                        style="
                                            background: #e9ecef;
                                            color: #495057;
                                        "
                                    >

                                        <i
                                            class="bi bi-question-circle"
                                        ></i>

                                        ' .
                                        htmlspecialchars(
                                            $row['tipo']
                                        ) .
                                        '

                                    </span>

                                ';

                            }


                            ?>

                        </td>


                        <!-- QUANTIDADE -->

                        <td class="text-center">

                            <strong>

                                <?php

                                echo (int)
                                    $row[
                                        'quantidade_movimentada'
                                    ];

                                ?>

                            </strong>

                        </td>


                        <!-- ESTOQUE ANTERIOR -->

                        <td class="text-center">

                            <?php

                            echo (int)
                                $row[
                                    'quantidade_anterior'
                                ];

                            ?>

                        </td>


                        <!-- ESTOQUE ATUAL -->

                        <td class="text-center">

                            <?php

                            echo (int)
                                $row[
                                    'quantidade_nova'
                                ];

                            ?>

                        </td>


                    </tr>


                <?php


                    }


                } else {


                ?>


                    <tr>

                        <td
                            colspan="7"
                            class="text-center sem-resultados"
                        >

                            <i
                                class="bi bi-info-circle"
                            ></i>

                            <br>

                            Nenhuma movimentação encontrada.

                        </td>

                    </tr>


                <?php

                }


                ?>


                </tbody>


            </table>


        </div>

    </div>



    <!-- =====================================================
         RESUMO
    ====================================================== -->

    <div class="row mt-4 g-3">


        <!-- ================================================
             ENTRADAS
        ================================================= -->

        <div class="col-md-6">


            <div
                class="card text-center p-3 h-100 resumo-card"
                style="
                    border-left: 6px solid #087f5b;
                "
            >


                <i
                    class="bi bi-box-arrow-in-down"
                    style="
                        font-size: 2rem;
                        color: #087f5b;
                    "
                ></i>


                <strong
                    style="
                        color: #087f5b;
                    "
                >

                    TOTAL DE ENTRADAS

                </strong>


                <span
                    style="
                        font-size: 28px;
                        font-weight: 700;
                        color: #087f5b;
                    "
                >

                    <?php

                    echo $total_entrada;

                    ?>

                </span>


                <span
                    style="
                        color: #8a5a7a;
                    "
                >

                    absorventes

                </span>


            </div>


        </div>



        <!-- ================================================
             RETIRADAS
        ================================================= -->

        <div class="col-md-6">


            <div
                class="card text-center p-3 h-100 resumo-card"
                style="
                    border-left: 6px solid #c2255c;
                "
            >


                <i
                    class="bi bi-box-arrow-up"
                    style="
                        font-size: 2rem;
                        color: #c2255c;
                    "
                ></i>


                <strong
                    style="
                        color: #c2255c;
                    "
                >

                    TOTAL DE RETIRADAS

                </strong>


                <span
                    style="
                        font-size: 28px;
                        font-weight: 700;
                        color: #c2255c;
                    "
                >

                    <?php

                    echo $total_retirada;

                    ?>

                </span>


                <span
                    style="
                        color: #8a5a7a;
                    "
                >

                    absorventes

                </span>


            </div>


        </div>


    </div>



    <!-- =====================================================
         VOLTAR
    ====================================================== -->

    <div class="text-center mt-4 mb-4">


        <a
            href="painel_admin.php"
            class="botao-administrador"
        >

            <i class="bi bi-arrow-left"></i>

            VOLTAR

        </a>


    </div>


</div>



<!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
