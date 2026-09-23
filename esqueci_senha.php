<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include('conexao.php');

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Verificar se o e-mail existe na base de dados
    $sql = "SELECT id, nome FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Gerar token seguro e validade de 1 hora
        $token = bin2hex(random_bytes(32));
        $validade = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token e validade na base de dados
        $sql_update = "UPDATE usuarios SET token_recuperacao = ?, token_validade = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sss", $token, $validade, $email);
        $stmt_update->execute();

        // Configuração do PHPMailer para envio real
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'gabrielvarela707@gmail'; // <--- SUBSTITUI PELO TEU E-MAIL DO GMAIL
            $mail->Password   = 'zkklekheoqwtqjfz*'; // <--- SUBSTITUI PELA SENHA DE APLICAÇÃO DO GMAIL
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('gabrielvarela707@gmail.com', 'Suporte AdaTech');
            $mail->addAddress($email, $usuario['nome']);

            // Link dinâmico apontando para o teu servidor AWS / DuckDNS
            $link_redefinicao = "http://adatech.duckdns.org/redefinir_senha.php?token=" . $token;

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - AdaTech';
            $mail->Body    = "Olá, <b>{$usuario['nome']}</b>.<br><br>Recebemos um pedido para redefinir a sua senha no sistema AdaTech.<br>Clique no botão abaixo para criar uma nova senha:<br><br><a href='{$link_redefinicao}' style='background: #0284c7; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Redefinir Senha</a><br><br>Este link é válido por 1 hora.<br><br>Se não solicitou esta alteração, ignore esta mensagem.";

            $mail->send();
            $mensagem = "<div style='background: #dcfce7; color: #16a34a; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>E-mail de recuperação enviado com sucesso! Verifique a sua caixa de entrada.</div>";
        } catch (Exception $e) {
            $mensagem = "<div style='background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>Erro ao enviar o e-mail. Tente novamente mais tarde.</div>";
        }
    } else {
        // Mensagem neutra por segurança para evitar enumeração de contas
        $mensagem = "<div style='background: #dcfce7; color: #16a34a; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>Se o e-mail estiver registado, receberá as instruções em breve.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0f172a; margin: 0; font-family: sans-serif;">

    <div style="background: #1e293b; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); color: #ffffff;">
        <h2 style="margin-top: 0; color: #38bdf8; text-align: center;">Recuperar Senha</h2>
        <p style="color: #94a3b8; font-size: 14px; text-align: center; margin-bottom: 20px;">Digite seu E-mail Cadastrado</p>
        
        <?php echo $mensagem; ?>

        <form method="POST" action="esqueci_senha.php" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <input type="email" name="email" placeholder="gabriel@gmail.com" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <button type="submit" style="background: #0284c7; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">Avançar</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="login.php" style="color: #38bdf8; text-decoration: none;">&larr; Voltar para o Login</a>
        </p>
    </div>

</body>
</html>