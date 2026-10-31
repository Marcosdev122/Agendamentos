<?php

session_start();

require_once "../database/conexao.php";

$user_id = $_SESSION['user_id'];

$profissional = $_POST['profissional_id'];
$data = $_POST['data'];
$horario = $_POST['horario'];

// VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
if (
    empty($_POST['profissional_id']) ||
    empty($_POST['data']) ||
    empty($_POST['horario'])
) {
    echo json_encode([
        'success' => false,
        'message' => 'Preencha todos os campos.'
    ]);
    exit;
}

try {
    // Inicia a transação
    $conn->beginTransaction();

    // =============================================
    // 1. VERIFICA SE AINDA TEM VAGAS DISPONÍVEIS
    // =============================================
    $sqlVerificaVagas = "
        SELECT vagas 
        FROM disponibilidade 
        WHERE profissional_id = :profissional 
        AND data = :data 
        AND horario = :horario
        FOR UPDATE
    ";

    $stmtVagas = $conn->prepare($sqlVerificaVagas);
    $stmtVagas->bindValue(":profissional", $profissional);
    $stmtVagas->bindValue(":data", $data);
    $stmtVagas->bindValue(":horario", $horario);
    $stmtVagas->execute();

    $vagasDisponiveis = $stmtVagas->fetch(PDO::FETCH_ASSOC);

    if (!$vagasDisponiveis) {
        throw new Exception("Este horário não está disponível para agendamento.");
    }

    // Conta quantos agendamentos já existem para este horário
    $sqlContaAgendamentos = "
        SELECT COUNT(*) as total 
        FROM agendamentos 
        WHERE profissional_id = :profissional 
        AND data = :data 
        AND horario = :horario
    ";

    $stmtConta = $conn->prepare($sqlContaAgendamentos);
    $stmtConta->bindValue(":profissional", $profissional);
    $stmtConta->bindValue(":data", $data);
    $stmtConta->bindValue(":horario", $horario);
    $stmtConta->execute();

    $totalAgendamentos = $stmtConta->fetch(PDO::FETCH_ASSOC);
    
    $vagasRestantes = $vagasDisponiveis['vagas'] - $totalAgendamentos['total'];
    
    if ($vagasRestantes <= 0) {
        throw new Exception("Não há mais vagas disponíveis para este horário.");
    }

    // =============================================
    // 2. VERIFICA INTERVALO DE 90 MINUTOS 
    //    SOMENTE PARA O MESMO USUÁRIO
    // =============================================
    $sqlVerificaIntervalo = "
        SELECT horario 
        FROM agendamentos 
        WHERE user_id = :user_id 
        AND data = :data
    ";

    $stmtIntervalo = $conn->prepare($sqlVerificaIntervalo);
    $stmtIntervalo->bindValue(":user_id", $user_id);
    $stmtIntervalo->bindValue(":data", $data);
    $stmtIntervalo->execute();

    $novoHorario = strtotime($horario);
    $horariosExistentes = $stmtIntervalo->fetchAll(PDO::FETCH_ASSOC);

    foreach ($horariosExistentes as $row) {
        $horarioExistente = strtotime($row['horario']);
        $diferenca = abs($novoHorario - $horarioExistente) / 60;

        if ($diferenca < 90) {
            $horarioFormatado = date('H:i', $horarioExistente);
            throw new Exception("Você já tem um agendamento às {$horarioFormatado} neste dia. É necessário respeitar o intervalo mínimo de 1h30 entre agendamentos.");
        }
    }

    // =============================================
    // 3. VERIFICA SE O USUÁRIO JÁ AGENDOU 
    //    EXATAMENTE NESTE HORÁRIO
    // =============================================
    $sqlVerificaDuplicata = "
        SELECT id 
        FROM agendamentos 
        WHERE user_id = :user_id 
        AND profissional_id = :profissional 
        AND data = :data 
        AND horario = :horario
    ";

    $stmtDuplicata = $conn->prepare($sqlVerificaDuplicata);
    $stmtDuplicata->bindValue(":user_id", $user_id);
    $stmtDuplicata->bindValue(":profissional", $profissional);
    $stmtDuplicata->bindValue(":data", $data);
    $stmtDuplicata->bindValue(":horario", $horario);
    $stmtDuplicata->execute();

    if ($stmtDuplicata->rowCount() > 0) {
        throw new Exception("Você já tem um agendamento neste dia e horário.");
    }

    // =============================================
    // 4. INSERE O AGENDAMENTO
    // =============================================
    $sql = "
    INSERT INTO agendamentos
    (
        user_id,
        profissional_id,
        data,
        horario
    )
    VALUES
    (
        :user_id,
        :profissional,
        :data,
        :horario
    )
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":profissional", $profissional);
    $stmt->bindValue(":data", $data);
    $stmt->bindValue(":horario", $horario);
    $stmt->execute();

    // Confirma a transação
    $conn->commit();

    echo json_encode([
        "success" => true,
        "message" => "Agendamento realizado com sucesso! Vagas restantes: " . ($vagasRestantes - 1)
    ]);

} catch (Exception $e) {
    // Desfaz a transação em caso de erro
    $conn->rollBack();
    
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}