<?php
// Incluir arquivo de configuração dos polos
require_once('polo_config.php');

header('Content-Type: application/json');

// Receber polo selecionado
$polo = isset($_GET['polo']) ? $_GET['polo'] : '';

if (empty($polo)) {
    echo json_encode(['success' => false, 'message' => 'Polo não especificado']);
    exit;
}

// Verificar se o polo existe na configuração
if (!isset($POLO_CONFIG[$polo])) {
    echo json_encode(['success' => false, 'message' => 'Polo não encontrado na configuração']);
    exit;
}

// Obter configuração do polo selecionado
$poloConfig = $POLO_CONFIG[$polo];

try {
    // Chamar API do Moodle para obter categorias
    $moodleUrl = $poloConfig['moodle_url'];
    $apiToken = $poloConfig['api_token'];
    
    $serverurl = $moodleUrl . '/webservice/rest/server.php';
    $params = [
        'wstoken' => $apiToken,
        'wsfunction' => 'core_course_get_categories',
        'moodlewsrestformat' => 'json'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serverurl . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        throw new Exception('Erro de conexão: ' . curl_error($ch));
    }
    
    curl_close($ch);
    
    $categories = json_decode($response, true);
    
    if (isset($categories['exception'])) {
        throw new Exception($categories['message']);
    }
    
    // Filtrar apenas categorias principais (parent = 0) e visíveis
    $mainCategories = [];
    foreach ($categories as $category) {
        if ($category['parent'] == 0 && $category['visible'] == 1) {
            $mainCategories[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'description' => strip_tags($category['description'] ?? ''),
                'coursecount' => $category['coursecount']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'polo' => $polo,
        'polo_name' => $poloConfig['name'],
        'moodle_url' => $moodleUrl,
        'categories' => $mainCategories
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>