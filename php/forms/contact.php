<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Requisição inválida.');
}

$nome = trim($_POST['client_name'] ?? '');
$celular = trim($_POST['client_number'] ?? '');
$endereço = trim($_POST['client_address'] ?? '');
$mensagem = trim($_POST['task_description'] ?? '');

if ($nome === '' || $celular === '' || $endereço === '' || $mensagem === '') {
    die('Por favor, preencha todos os campos obrigatórios.');
}


if (
    empty($nome) || 
    empty($celular) ||
    empty($endereço) ||
    empty($mensagem)
) {
    exit('Por favor, preencha todos os campos.');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = $mailConfig['host'];
    $mail->Username = $mailConfig['username'];
    $mail->Password = $mailConfig['password'];

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $mailConfig['port'];

    $mail->CharSet = 'UTF-8';

    $mail->setForm(
        'contato@betimnotebook.com.br',
        'Betim Notebook'
    );

    $mail->addAddress(
        'contato@betimnotebook.com.br',
        'Betim Notebook'
    );

    $mail->Subject = 'Novo pedido de manutenção em domicilio';

    $mail->isHTML(true);

    $mail->Body = '
        <h2>Novo pedido de manutenção em domicílio</h2>

        <p><strong>Nome:</strong> '. htmlspecialchars($nome) . '</p>

        <p><strong>WhatsApp:</strong> ' . htmlespecialchars($celular) . '</p>
        
        <p><strong>Endereço:</strong> ' . htmlespecialchars($endereço) . '</p>

        <p><strong>Descrição:</strong>

        <p>' . nl2b(htmlespecialchars($mensagem)) . ' </p>

        <hr>

         <p>
            Solicitação enviada pelo site
            <strong>Betim Notebook</strong>.
        </p>
    
    ';


    $mail->AltBody = 
        "Novo pedido de manutenção de notebook\n\n" .
        "WhatsApp: $celular\n" .
        "Endereço: $endereco\n\n" .
        "Descrição:\n$mensagem"; 


        $mail->send();

        echo 'success';


} catch (Exception $e) {
    http_response_code(500);

    echo 'Não foi possível enviar sua solicitação';
}