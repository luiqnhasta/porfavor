<?php
require_once 'config.php';

$db = getDatabase()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Buscar produto específico
                $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
                $stmt->execute([$_GET['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    sendResponse($product);
                } else {
                    sendError('Produto não encontrado', 404);
                }
            } else {
                // Buscar todos os produtos
                $stmt = $db->query('SELECT * FROM products WHERE available = 1 ORDER BY category, name');
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($products);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['name']) || !isset($input['price']) || !isset($input['category'])) {
                sendError('Dados obrigatórios não fornecidos', 400);
            }
            
            $stmt = $db->prepare('INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $input['name'],
                $input['description'] ?? '',
                $input['price'],
                $input['category'],
                $input['image'] ?? ''
            ]);
            
            sendResponse(['id' => $db->lastInsertId(), 'message' => 'Produto criado com sucesso'], 201);
            break;
            
        case 'PUT':
            if (!isset($_GET['id'])) {
                sendError('ID do produto não fornecido', 400);
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                sendError('Dados não fornecidos', 400);
            }
            
            $stmt = $db->prepare('UPDATE products SET name = ?, description = ?, price = ?, category = ?, image = ?, available = ? WHERE id = ?');
            $stmt->execute([
                $input['name'],
                $input['description'] ?? '',
                $input['price'],
                $input['category'],
                $input['image'] ?? '',
                $input['available'] ?? 1,
                $_GET['id']
            ]);
            
            sendResponse(['message' => 'Produto atualizado com sucesso']);
            break;
            
        case 'DELETE':
            if (!isset($_GET['id'])) {
                sendError('ID do produto não fornecido', 400);
            }
            
            $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            
            sendResponse(['message' => 'Produto removido com sucesso']);
            break;
            
        default:
            sendError('Método não permitido', 405);
    }
} catch (Exception $e) {
    sendError('Erro interno: ' . $e->getMessage());
}
?>