<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Configuração do banco de dados SQLite
class Database {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO('sqlite:' . __DIR__ . '/database.db');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
            $this->insertInitialData();
        } catch(PDOException $e) {
            die(json_encode(['error' => 'Erro na conexão: ' . $e->getMessage()]));
        }
    }
    
    public function getConnection() {
        return $this->db;
    }
    
    private function createTables() {
        // Tabela de produtos
        $this->db->exec("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            price REAL NOT NULL,
            category TEXT NOT NULL,
            image TEXT,
            available BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Tabela de pedidos
        $this->db->exec("CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name TEXT NOT NULL,
            customer_phone TEXT NOT NULL,
            customer_address TEXT NOT NULL,
            items TEXT NOT NULL,
            total REAL NOT NULL,
            delivery_fee REAL DEFAULT 5.00,
            status TEXT DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Tabela de administradores
        $this->db->exec("CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Tabela de configurações
        $this->db->exec("CREATE TABLE IF NOT EXISTS config (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL
        )");
    }
    
    private function insertInitialData() {
        // Verificar se já existem dados
        $stmt = $this->db->query("SELECT COUNT(*) FROM products");
        if ($stmt->fetchColumn() > 0) {
            return; // Dados já existem
        }
        
        // Inserir produtos iniciais
        $products = [
            ['Pizza Margherita', 'Pizza tradicional com molho de tomate, mussarela e manjericão', 35.90, 'pizza', 'https://images.pexels.com/photos/315755/pexels-photo-315755.jpeg'],
            ['Pizza Pepperoni', 'Pizza com molho de tomate, mussarela e pepperoni', 42.90, 'pizza', 'https://images.pexels.com/photos/708587/pexels-photo-708587.jpeg'],
            ['Hambúrguer Clássico', 'Hambúrguer com carne, alface, tomate e queijo', 28.90, 'hamburguer', 'https://images.pexels.com/photos/1639557/pexels-photo-1639557.jpeg'],
            ['Hambúrguer Bacon', 'Hambúrguer com carne, bacon, queijo e molho especial', 32.90, 'hamburguer', 'https://images.pexels.com/photos/1556909/pexels-photo-1556909.jpeg'],
            ['Refrigerante Lata', 'Refrigerante gelado 350ml', 5.90, 'bebida', 'https://images.pexels.com/photos/50593/coca-cola-cold-drink-soft-drink-coke-50593.jpeg'],
            ['Suco Natural', 'Suco natural de frutas 500ml', 8.90, 'bebida', 'https://images.pexels.com/photos/96974/pexels-photo-96974.jpeg']
        ];
        
        $stmt = $this->db->prepare('INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)');
        foreach ($products as $product) {
            $stmt->execute($product);
        }
        
        // Inserir admin padrão
        $this->db->exec("INSERT OR IGNORE INTO admins (username, password, name) VALUES ('admin', 'admin123', 'Administrador')");
        
        // Inserir configurações
        $this->db->exec("INSERT OR IGNORE INTO config (key, value) VALUES ('delivery_fee', '5.00')");
        $this->db->exec("INSERT OR IGNORE INTO config (key, value) VALUES ('restaurant_name', 'Delivery Express')");
    }
}

function getDatabase() {
    static $database = null;
    if ($database === null) {
        $database = new Database();
    }
    return $database;
}

function sendResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function sendError($message, $status = 500) {
    http_response_code($status);
    echo json_encode(['error' => $message]);
    exit;
}
?>