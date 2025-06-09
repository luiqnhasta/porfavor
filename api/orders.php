<?php
require_once 'config.php';

$db = getDatabase()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Buscar pedido específico
                $stmt = $db->prepare('SELECT * FROM orders WHERE id = ?');
                $stmt->execute([$_GET['id']]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($order) {
                    $order['items'] = json_decode($order['items'], true);
                    sendResponse($order);
                } else {
                    sendError('Pedido não encontrado', 404);
                }
            } else {
                // Buscar todos os pedidos
                $stmt = $db->query('SELECT * FROM orders ORDER BY created_at DESC');
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($orders as &$order) {
                    $order['items'] = json_decode($order['items'], true);
                }
                
                sendResponse($orders);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['customer_name']) || !isset($input['customer_phone']) || 
                !isset($input['customer_address']) || !isset($input['items']) || !isset($input['total'])) {
                sendError('Dados obrigatórios não fornecidos', 400);
            }
            
            $stmt = $db->prepare('INSERT INTO orders (customer_name, customer_phone, customer_address, items, total, delivery_fee) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $input['customer_name'],
                $input['customer_phone'],
                $input['customer_address'],
                json_encode($input['items']),
                $input['total'],
                $input['delivery_fee'] ?? 5.00
            ]);
            
            sendResponse(['id' => $db->lastInsertId(), 'message' => 'Pedido criado com sucesso'], 201);
            break;
            
        case 'PUT':
            if (!isset($_GET['id'])) {
                sendError('ID do pedido não fornecido', 400);
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['status'])) {
                sendError('Status não fornecido', 400);
            }
            
            $stmt = $db->prepare('UPDATE orders SET status = ? WHERE id = ?');
            $stmt->execute([$input['status'], $_GET['id']]);
            
            sendResponse(['message' => 'Status do pedido atualizado']);
            break;
            
        default:
            sendError('Método não permitido', 405);
    }
} catch (Exception $e) {
    sendError('Erro interno: ' . $e->getMessage());
}
?>