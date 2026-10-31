<?php

require_once "../database/conexao.php";

$id = $_POST['id'];

$sql = "
DELETE FROM disponibilidade
WHERE id = :id
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(':id', $id);

if($stmt->execute()){

    echo json_encode([
        "success" => true,
        "message" => "Disponibilidade excluída com sucesso!"
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => "Erro ao excluir disponibilidade!"
    ]);

}

exit;