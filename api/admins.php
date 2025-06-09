<?php
require_once 'config.php';

$db = getDatabase()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Buscar todos os administradores (sem senha)
            $stmt = $db->query('SELECT id, username, name, created_at FROM admins');
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendResponse($admins);
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Verificar se é login
            if (isset($input['action']) && $input['action'] === 'login') {
                if (!isset($input['username']) || !isset($input['password'])) {
                    sendError('Username e password são obrigatórios', 400);
                }
                
                $stmt = $db->prepare('SELECT * FROM admins WHERE username = ? AND password = ?');
                $stmt->execute([$input['username'], $input['password']]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($admin) {
                    unset($admin['password']); // Remover senha da resposta
                    sendResponse(['success' => true, 'admin' => $admin]);
                } else {
                    sendError('Credenciais inválidas', 401);
                }
            } else {
                // Criar novo administrador
                if (!isset($input['username']) || !isset($input['password']) || !isset($input['name'])) {
                    sendError('Dados obrigatórios não fornecidos', 400);
                }
                
                $stmt = $db->prepare('INSERT INTO admins (username, password, name) VALUES (?, ?, ?)');
                $stmt->execute([$input['username'], $input['password'], $input['name']]);
                
                sendResponse(['id' => $db->lastInsertId(), 'message' => 'Administrador criado com sucesso'], 201);
            }
            break;
            
        case 'DELETE':
            if (!isset($_GET['id'])) {
                sendError('ID do administrador não fornecido', 400);
            }
            
            $stmt = $db->prepare('DELETE FROM admins WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            
            sendResponse(['message' => 'Administrador removido com sucesso']);
            break;
            
        default:
            sendError('Método não permitido', 405);
    }
} catch (Exception $e) {
    sendError('Erro interno: ' . $e->getMessage());
}
?>