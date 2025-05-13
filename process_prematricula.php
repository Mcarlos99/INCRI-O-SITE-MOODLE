<?php
// Incluir arquivo de configuração dos polos
require_once('polo_config.php');

// Configurações do banco de dados - ajuste conforme seu ambiente
$db_host = 'localhost';
$db_name = 'inscricao';
$db_user = 'inscricao';
$db_pass = 'EHl20R5ahRvwF7yOP0Uv';

// Receber dados do formulário
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$zipCode = $_POST['zipCode'] ?? '';
$educationLevel = $_POST['educationLevel'] ?? '';
$categoryId = (int)$_POST['categoryId'] ?? 0;
$categoryName = $_POST['categoryName'] ?? '';
$poloId = $_POST['poloId'] ?? '';
$poloName = $_POST['poloName'] ?? '';

// Validação básica
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || 
    empty($cpf) || empty($categoryId) || empty($poloId)) {
    sendResponse(false, 'Campos obrigatórios não preenchidos');
    exit;
}

// Verificar se o polo existe na configuração
if (!isset($POLO_CONFIG[$poloId])) {
    sendResponse(false, 'Polo não encontrado na configuração');
    exit;
}

// Obter configuração do polo selecionado
$poloConfig = $POLO_CONFIG[$poloId];
$MOODLE_URL = $poloConfig['moodle_url'];

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Email inválido');
    exit;
}

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar se já existe uma pré-matrícula para este email e curso
    $stmt = $pdo->prepare("SELECT * FROM prematriculas WHERE email = ? AND category_id = ?");
    $stmt->execute([$email, $categoryId]);
    $existingPrematricula = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingPrematricula) {
        // Se já existe e está pendente, atualizar os dados
        if ($existingPrematricula['status'] === 'pending') {
            $stmt = $pdo->prepare("
                UPDATE prematriculas SET 
                    first_name = ?,
                    last_name = ?,
                    phone = ?,
                    cpf = ?,
                    address = ?,
                    city = ?,
                    state = ?,
                    zipcode = ?,
                    education_level = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $firstName,
                $lastName,
                $phone,
                $cpf,
                $address,
                $city,
                $state,
                $zipCode,
                $educationLevel,
                $existingPrematricula['id']
            ]);
            
            // Enviar email de atualização da pré-matrícula
            sendPreMatriculaEmail($email, $firstName, $categoryName, $poloName, 'update');
            
            sendResponse(true, 'Pré-matrícula atualizada com sucesso', [
                'prematricula_id' => $existingPrematricula['id']
            ]);
        } elseif ($existingPrematricula['status'] === 'approved') {
            // Se já está aprovada, informar o usuário
            sendResponse(false, 'Você já está matriculado neste curso. Por favor, entre em contato com o suporte se precisar de ajuda para acessar sua conta.');
        } else {
            // Se foi rejeitada, permitir nova solicitação
            $stmt = $pdo->prepare("
                UPDATE prematriculas SET 
                    first_name = ?,
                    last_name = ?,
                    phone = ?,
                    cpf = ?,
                    address = ?,
                    city = ?,
                    state = ?,
                    zipcode = ?,
                    education_level = ?,
                    status = 'pending',
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $firstName,
                $lastName,
                $phone,
                $cpf,
                $address,
                $city,
                $state,
                $zipCode,
                $educationLevel,
                $existingPrematricula['id']
            ]);
            
            // Enviar email de nova solicitação de pré-matrícula
            sendPreMatriculaEmail($email, $firstName, $categoryName, $poloName, 'renew');
            
            sendResponse(true, 'Nova solicitação de pré-matrícula enviada com sucesso', [
                'prematricula_id' => $existingPrematricula['id']
            ]);
        }
    } else {
        // Inserir nova pré-matrícula
        $stmt = $pdo->prepare("
            INSERT INTO prematriculas (
                polo_id, polo_name, category_id, category_name, 
                first_name, last_name, email, phone, cpf,
                address, city, state, zipcode, education_level,
                status, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, 
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                'pending', NOW(), NOW()
            )
        ");
        
        $stmt->execute([
            $poloId,
            $poloName,
            $categoryId,
            $categoryName,
            $firstName,
            $lastName,
            $email,
            $phone,
            $cpf,
            $address,
            $city,
            $state,
            $zipCode,
            $educationLevel
        ]);
        
        $prematriculaId = $pdo->lastInsertId();
        
        // Enviar email de confirmação da pré-matrícula
        sendPreMatriculaEmail($email, $firstName, $categoryName, $poloName, 'new');
        
        // Enviar email de notificação para o administrador
        sendAdminNotificationEmail($firstName, $lastName, $email, $phone, $categoryName, $poloName, $prematriculaId);
        
        sendResponse(true, 'Pré-matrícula enviada com sucesso', [
            'prematricula_id' => $prematriculaId
        ]);
    }
    
} catch (PDOException $e) {
    sendResponse(false, 'Erro ao processar pré-matrícula: ' . $e->getMessage());
}

/**
 * Função para enviar e-mail de pré-matrícula para o aluno
 */
function sendPreMatriculaEmail($email, $name, $categoryName, $poloName, $type = 'new') {
    switch ($type) {
        case 'update':
            $subject = 'Atualização de Pré-matrícula - ' . $categoryName . ' - Polo ' . $poloName;
            $title = 'Pré-matrícula Atualizada!';
            $message = "Sua pré-matrícula foi atualizada com sucesso. Nossos atendentes entrarão em contato com você em breve para discutir os detalhes de pagamento e finalizar o processo de matrícula.";
            break;
            
        case 'renew':
            $subject = 'Nova Solicitação de Pré-matrícula - ' . $categoryName . ' - Polo ' . $poloName;
            $title = 'Nova Solicitação Enviada!';
            $message = "Sua nova solicitação de pré-matrícula foi enviada com sucesso. Nossos atendentes entrarão em contato com você em breve para discutir os detalhes de pagamento e finalizar o processo de matrícula.";
            break;
            
        default: // new
            $subject = 'Confirmação de Pré-matrícula - ' . $categoryName . ' - Polo ' . $poloName;
            $title = 'Pré-matrícula Recebida!';
            $message = "Sua pré-matrícula foi recebida com sucesso. Nossos atendentes entrarão em contato com você em breve para discutir os detalhes de pagamento e finalizar o processo de matrícula.";
    }
    
    $htmlMessage = "
    <html>
    <head>
        <title>Confirmação de Pré-matrícula</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #3498db; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; }
            .course-info { background-color: #e8f4fc; padding: 15px; margin: 20px 0; }
            .next-steps { background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #3498db; }
            .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #888; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>{$title}</h2>
            </div>
            <div class='content'>
                <p>Olá <strong>{$name}</strong>,</p>
                <p>{$message}</p>
                
                <div class='course-info'>
                    <h3>Informações da Pré-matrícula:</h3>
                    <p><strong>Polo:</strong> {$poloName}</p>
                    <p><strong>Curso:</strong> {$categoryName}</p>
                </div>
                
                <div class='next-steps'>
                    <h3>Próximos Passos:</h3>
                    <ol>
                        <li>Nossa equipe entrará em contato com você em até 48 horas úteis.</li>
                        <li>Você poderá escolher a forma de pagamento e discutir os valores com nosso atendente.</li>
                        <li>Após confirmação do pagamento, sua matrícula será ativada.</li>
                        <li>Você receberá um email com as credenciais de acesso à plataforma.</li>
                    </ol>
                </div>
                
                <p>Caso tenha alguma dúvida, sinta-se à vontade para entrar em contato pelo telefone (91) 3456-7890 ou responder a este email.</p>
                
                <p>
                Atenciosamente,<br>
                Equipe de Matrículas - Polo {$poloName}
                </p>
            </div>
            <div class='footer'>
                <p>Este é um email automático enviado após sua solicitação de pré-matrícula.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Headers para enviar e-mail em formato HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: matriculas@imepedu.com.br" . "\r\n";
    
    // Enviar email
    return mail($email, $subject, $htmlMessage, $headers);
}

/**
 * Função para enviar e-mail de notificação para o administrador
 */
function sendAdminNotificationEmail($firstName, $lastName, $email, $phone, $categoryName, $poloName, $prematriculaId) {
    // Email do administrador - ajuste conforme necessário
    $adminEmail = 'admin@imepedu.com.br';
    
    $subject = 'Nova Pré-matrícula: ' . $firstName . ' ' . $lastName . ' - ' . $categoryName;
    
    $htmlMessage = "
    <html>
    <head>
        <title>Nova Pré-matrícula</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #3498db; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; }
            .student-info { background-color: #f9f9f9; padding: 15px; margin: 20px 0; }
            .action-button { display: block; width: 200px; margin: 20px auto; padding: 10px; background-color: #3498db; color: white; text-align: center; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Nova Pré-matrícula Recebida</h2>
            </div>
            <div class='content'>
                <p>Uma nova solicitação de pré-matrícula foi recebida.</p>
                
                <div class='student-info'>
                    <h3>Informações do Aluno:</h3>
                    <p><strong>Nome:</strong> {$firstName} {$lastName}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Telefone:</strong> {$phone}</p>
                    <p><strong>Curso:</strong> {$categoryName}</p>
                    <p><strong>Polo:</strong> {$poloName}</p>
                    <p><strong>ID da Pré-matrícula:</strong> {$prematriculaId}</p>
                </div>
                
                <p>Por favor, entre em contato com o aluno para discutir os detalhes de pagamento e finalizar o processo de matrícula.</p>
                
                <a href='admin/prematriculas.php' class='action-button'>Gerenciar Pré-matrículas</a>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Headers para enviar e-mail em formato HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: sistema@imepedu.com.br" . "\r\n";
    
    // Enviar email
    return mail($adminEmail, $subject, $htmlMessage, $headers);
}

/**
 * Enviar resposta em formato JSON
 */
function sendResponse($success, $message, $data = []) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>