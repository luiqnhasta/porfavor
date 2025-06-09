<?php
require_once 'config.php';

$db = getDatabase()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['key'])) {
                // Buscar configuração específica
                $stmt = $db->prepare('SELECT value FROM config WHERE key = ?');
                $stmt->execute([$_GET['key']]);
                $config = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($config) {
                    sendResponse(['key' => $_GET['key'], 'value' => $config['value']]);
                } else {
                    sendError('Configuração não encontrada', 404);
                }
            } else {
                // Buscar todas as configurações
                $stmt = $db->query('SELECT * FROM config');
                $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $result = [];
                foreach ($configs as $config) {
                    $result[$config['key']] = $config['value'];
                }
                
                sendResponse($result);
            }
            break;
            
        case 'POST':
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['key']) || !isset($input['value'])) {
                sendError('Key e value são obrigatórios', 400);
            }
            
            $stmt = $db->prepare('INSERT OR REPLACE INTO config (key, value) VALUES (?, ?)');
            $stmt->execute([$input['key'], $input['value']]);
            
            sendResponse(['message' => 'Configuração salva com sucesso']);
            break;
            
        default:
            sendError('Método não permitido', 405);
    }
} catch (Exception $e) {
    sendError('Erro interno: ' . $e->getMessage());
}
?>