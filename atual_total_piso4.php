<?php

session_start();

include_once("conexao.php");


/* =========================================
   VERIFICAR SE O CAMPO FOI ENVIADO
========================================= */

if (!isset($_POST['estoque'])) {

    die("O campo estoque não foi enviado.");

}


$ESTOQUE = (int) $_POST['estoque'];


/* =========================================
   VALIDAR QUANTIDADE
========================================= */

if ($ESTOQUE < 0) {

    $_SESSION['msg'] = "Informe uma quantidade válida.";

    header("Location: atualizar_piso4.php");

    exit;

}


/* =========================================
   BUSCAR ESTOQUE ATUAL
========================================= */

$sql_estoque = "
    SELECT estoque
    FROM piso4
    WHERE id = 1
";

$resultado_estoque = mysqli_query(
    $conn,
    $sql_estoque
);


if (!$resultado_estoque) {

    die(
        "Erro ao consultar estoque: "
        . mysqli_error($conn)
    );

}


$dados_estoque = mysqli_fetch_assoc(
    $resultado_estoque
);


if (!$dados_estoque) {

    die("Registro do estoque não encontrado.");

}


$estoque_anterior =
    (int) $dados_estoque['estoque'];


/* =========================================
   VERIFICAR SE HOUVE ALTERAÇÃO
========================================= */

if ($ESTOQUE == $estoque_anterior) {

    $_SESSION['msg'] =
        "O estoque já possui essa quantidade.";

    header("Location: atualizar_piso4.php");

    exit;

}


/* =========================================
   ATUALIZAR ESTOQUE TOTAL
========================================= */

$sql = "
    UPDATE piso4
    SET estoque = $ESTOQUE
    WHERE id = 1
";

$resultado = mysqli_query(
    $conn,
    $sql
);


if (!$resultado) {

    die(
        "Erro ao atualizar estoque: "
        . mysqli_error($conn)
    );

}


/* =========================================
   REGISTRAR ATUALIZAÇÃO NO HISTÓRICO
========================================= */

$sql_historico = "
    INSERT INTO historico
    (
        andar,
        tipo,
        quantidade_movimentada,
        quantidade_anterior,
        quantidade_nova
    )
    VALUES
    (
        '3º Andar',
        'ATUALIZAÇÃO',
        0,
        $estoque_anterior,
        $ESTOQUE
    )
";


$resultado_historico = mysqli_query(
    $conn,
    $sql_historico
);


if (!$resultado_historico) {

    die(
        "Estoque atualizado, mas ocorreu um erro "
        . "ao registrar o histórico: "
        . mysqli_error($conn)
    );

}


/* =========================================
   MENSAGEM
========================================= */

$_SESSION['msg'] =
    "ESTOQUE TOTAL ATUALIZADO COM SUCESSO!";


/* =========================================
   VOLTAR
========================================= */

header("Location: atualizar_piso4.php");

exit;

?>