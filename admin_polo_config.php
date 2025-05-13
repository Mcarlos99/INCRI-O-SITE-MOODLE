<?php
// admin_polo_config.php
// Página de administração para gerenciar configurações de polos

// Verificar autenticação de administrador aqui...

// Carregar configuração atual
$configFile = 'polo_config.php';
$configExists = file_exists($configFile);

// Processar formulário se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_config'])) {
    // Validar e processar dados do formulário
    $polos = [];
    
    foreach ($_POST['polo'] as $poloId => $poloData) {
        if (!empty($poloData['name']) && !empty($poloData['moodle_url']) && !empty($poloData['api_token'])) {
            $polos[$poloId] = [
                'name' => $poloData['name'],
                'moodle_url' => $poloData['moodle_url'],
                'api_token' => $poloData['api_token'],
                'description' => $poloData['description'] ?? '',
                'address' => $poloData['address'] ?? ''
            ];
        }
    }
    
    // Adicionar novo polo se dados fornecidos
    if (!empty($_POST['new_polo_id']) && !empty($_POST['new_polo_name']) && 
        !empty($_POST['new_polo_moodle_url']) && !empty($_POST['new_polo_api_token'])) {
        
        $newPoloId = preg_replace('/[^a-z0-9-]/', '', strtolower($_POST['new_polo_id']));
        
        if (!empty($newPoloId)) {
            $polos[$newPoloId] = [
                'name' => $_POST['new_polo_name'],
                'moodle_url' => $_POST['new_polo_moodle_url'],
                'api_token' => $_POST['new_polo_api_token'],
                'description' => $_POST['new_polo_description'] ?? '',
                'address' => $_POST['new_polo_address'] ?? ''
            ];
        }
    }
    
    // Gerar arquivo de configuração
    $configContent = "<?php\n// Configuração dos polos e suas respectivas instâncias Moodle\n";
    $configContent .= "\$POLO_CONFIG = " . var_export($polos, true) . ";\n?>";
    
    if (file_put_contents($configFile, $configContent)) {
        $successMessage = 'Configuração salva com sucesso!';
        $configExists = true;
    } else {
        $errorMessage = 'Erro ao salvar configuração. Verifique as permissões de escrita.';
    }
}

// Carregar configuração atual se existir
$POLO_CONFIG = [];
if ($configExists) {
    include($configFile);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Configuração de Polos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Administração - Configuração de Polos</h1>
        
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Gerenciar Polos e Instâncias Moodle
            </div>
            <div class="card-body">
                <form method="post">
                    <?php if (!empty($POLO_CONFIG)): ?>
                        <h5 class="mb-3">Polos Existentes</h5>
                        
                        <?php foreach ($POLO_CONFIG as $poloId => $polo): ?>
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="m-0"><?php echo $polo['name']; ?> (<?php echo $poloId; ?>)</h6>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removePolo('<?php echo $poloId; ?>')">
                                        <i class="fas fa-trash"></i> Remover
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nome do Polo:</label>
                                            <input type="text" class="form-control" name="polo[<?php echo $poloId; ?>][name]" value="<?php echo $polo['name']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
										<label class="form-label">ID do Polo:</label>
                                            <input type="text" class="form-control" value="<?php echo $poloId; ?>" disabled>
                                            <small class="text-muted">O ID não pode ser alterado</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">URL do Moodle:</label>
                                            <input type="url" class="form-control" name="polo[<?php echo $poloId; ?>][moodle_url]" value="<?php echo $polo['moodle_url']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Token de API:</label>
                                            <input type="text" class="form-control" name="polo[<?php echo $poloId; ?>][api_token]" value="<?php echo $polo['api_token']; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Descrição:</label>
                                        <input type="text" class="form-control" name="polo[<?php echo $poloId; ?>][description]" value="<?php echo $polo['description'] ?? ''; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Endereço:</label>
                                        <input type="text" class="form-control" name="polo[<?php echo $poloId; ?>][address]" value="<?php echo $polo['address'] ?? ''; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="testConnection('<?php echo $poloId; ?>', '<?php echo $polo['moodle_url']; ?>', '<?php echo $polo['api_token']; ?>')">
                                            <i class="fas fa-plug"></i> Testar Conexão
                                        </button>
                                        <span id="connection-status-<?php echo $poloId; ?>"></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Adicionar Novo Polo</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nome do Polo:</label>
                                    <input type="text" class="form-control" name="new_polo_name" placeholder="Ex: Nova Cidade">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ID do Polo (somente letras, números e hífen):</label>
                                    <input type="text" class="form-control" name="new_polo_id" placeholder="Ex: nova-cidade">
                                    <small class="text-muted">Este ID será usado nas URLs e no sistema</small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">URL do Moodle:</label>
                                    <input type="url" class="form-control" name="new_polo_moodle_url" placeholder="https://novacidade.imepedu.com.br">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Token de API:</label>
                                    <input type="text" class="form-control" name="new_polo_api_token" placeholder="Token de API do Moodle">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descrição:</label>
                                <input type="text" class="form-control" name="new_polo_description" placeholder="Polo de Educação Superior de Nova Cidade">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Endereço:</label>
                                <input type="text" class="form-control" name="new_polo_address" placeholder="Rua Principal, 123 - Centro, Nova Cidade - PA">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" name="save_config" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para remover polo (apenas na interface, confirmação necessária)
        function removePolo(poloId) {
            if (confirm('Tem certeza que deseja remover este polo? Esta ação não pode ser desfeita.')) {
                const poloElement = document.querySelector(`.card-header:contains('${poloId}')`).closest('.card');
                if (poloElement) {
                    poloElement.remove();
                }
            }
        }
        
        // Função para testar conexão com o Moodle
        function testConnection(poloId, moodleUrl, apiToken) {
            const statusElement = document.getElementById(`connection-status-${poloId}`);
            statusElement.innerHTML = '<span class="text-warning">Testando conexão...</span>';
            
            // Criar um formulário temporário para enviar a solicitação
            const formData = new FormData();
            formData.append('moodle_url', moodleUrl);
            formData.append('api_token', apiToken);
            
            fetch('test_moodle_connection.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusElement.innerHTML = '<span class="text-success">Conexão bem-sucedida!</span>';
                } else {
                    statusElement.innerHTML = `<span class="text-danger">Falha na conexão: ${data.message}</span>`;
                }
            })
            .catch(error => {
                statusElement.innerHTML = '<span class="text-danger">Erro ao testar conexão</span>';
                console.error('Erro:', error);
            });
        }
        
        // Polyfill para seletor :contains
        if (!Element.prototype.matches) {
            Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
        }
        
        if (!Element.prototype.closest) {
            Element.prototype.closest = function(s) {
                var el = this;
                do {
                    if (el.matches(s)) return el;
                    el = el.parentElement || el.parentNode;
                } while (el !== null && el.nodeType === 1);
                return null;
            };
        }
        
        jQuery.expr[':'].contains = function(a, i, m) {
            return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };
    </script>
</body>
</html>