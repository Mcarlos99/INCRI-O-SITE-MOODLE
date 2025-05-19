<?php
/**
 * Helper para envio de emails via SMTP
 * Requer a biblioteca PHPMailer
 */

// Verifique se ainda não existe uma função sendEmail
if (!function_exists('sendEmail')) {
    /**
     * Envia email usando PHPMailer via SMTP
     * 
     * @param string $to Email do destinatário
     * @param string $subject Assunto do email
     * @param string $htmlMessage Corpo do email em HTML
     * @param string $fromEmail Email do remetente (opcional)
     * @param string $fromName Nome do remetente (opcional)
     * @return bool Retorna true se o email foi enviado com sucesso
     */
    function sendEmail($to, $subject, $htmlMessage, $fromEmail = 'noreply@imepedu.com.br', $fromName = 'IMEPE EAD') {
        // Verificar se PHPMailer está disponível
        $phpmailerAvailable = class_exists('PHPMailer\PHPMailer\PHPMailer');
        
        // Se PHPMailer não estiver disponível, tentar usar a função mail() nativa
        if (!$phpmailerAvailable) {
            return sendNativeMail($to, $subject, $htmlMessage, $fromEmail, $fromName);
        }
        
        // Carregar configurações do email
        $emailConfig = getEmailConfig();
        
        try {
            // Criar instância do PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = $emailConfig['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $emailConfig['smtp_username'];
            $mail->Password = $emailConfig['smtp_password'];
            $mail->SMTPSecure = $emailConfig['smtp_secure'];
            $mail->Port = $emailConfig['smtp_port'];
            $mail->CharSet = 'UTF-8';
            
            // Configurações do remetente e destinatário
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);
            
            // Configuração do email
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlMessage));
            
            // Enviar email
            $result = $mail->send();
            
            // Registrar o envio em log
            logEmailSend($to, $subject, $result);
            
            return $result;
        } catch (Exception $e) {
            // Registrar erro em log
            logEmailError($to, $subject, $e->getMessage());
            
            // Tentar usar a função mail() nativa como fallback
            return sendNativeMail($to, $subject, $htmlMessage, $fromEmail, $fromName);
        }
    }
    
    /**
     * Enviar email usando a função mail() nativa do PHP
     */
    function sendNativeMail($to, $subject, $htmlMessage, $fromEmail, $fromName) {
        // Headers para enviar e-mail em formato HTML
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$fromName} <{$fromEmail}>" . "\r\n";
        
        // Enviar email
        $result = mail($to, $subject, $htmlMessage, $headers);
        
        // Registrar o envio em log
        logEmailSend($to, $subject, $result, 'native');
        
        return $result;
    }
    
    /**
     * Obter configurações de email do arquivo config.php ou usar valores padrão
     */
    function getEmailConfig() {
        // Verificar se existe arquivo config.php com configurações de email
        $configFile = __DIR__ . '/email_config.php';
        if (file_exists($configFile)) {
            include $configFile;
            if (isset($EMAIL_CONFIG) && is_array($EMAIL_CONFIG)) {
                return $EMAIL_CONFIG;
            }
        }
        
        // Configurações padrão
        return [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_username' => 'seu_email@gmail.com',
            'smtp_password' => 'sua_senha_app',
            'smtp_secure' => 'tls',
            'smtp_port' => 587
        ];
    }
    
    /**
     * Registrar em log o envio de email
     */
    function logEmailSend($to, $subject, $result, $method = 'phpmailer') {
        $logFile = __DIR__ . '/email_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $status = $result ? 'SUCCESS' : 'FAILED';
        $logMessage = "[{$timestamp}] [{$status}] [{$method}] To: {$to} | Subject: {$subject}\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    /**
     * Registrar em log erros de envio de email
     */
    function logEmailError($to, $subject, $errorMessage) {
        $logFile = __DIR__ . '/email_error_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] To: {$to} | Subject: {$subject} | Error: {$errorMessage}\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
?>