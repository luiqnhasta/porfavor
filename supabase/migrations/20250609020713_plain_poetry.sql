-- Salgados da Sara Database Schema
-- PostgreSQL Database Schema

-- Create database (run this separately)
-- CREATE DATABASE salgados_da_sara;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    address VARCHAR(255) NOT NULL,
    number VARCHAR(20) NOT NULL,
    complement VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    is_portioned BOOLEAN DEFAULT FALSE,
    is_custom BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INTEGER REFERENCES users(id),
    customer_data JSONB NOT NULL,
    items JSONB NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    is_delivery BOOLEAN DEFAULT FALSE,
    payment_method VARCHAR(50) DEFAULT 'cash',
    status VARCHAR(50) DEFAULT 'pending',
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order status history table
CREATE TABLE IF NOT EXISTS order_status_history (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    status VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- App configuration table
CREATE TABLE IF NOT EXISTS app_config (
    id SERIAL PRIMARY KEY,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO admin_users (username, password, role) 
VALUES ('sara', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON CONFLICT (username) DO NOTHING;
-- Default password is 'password'

-- Insert default products
INSERT INTO products (name, price, category, description, is_portioned, is_custom) VALUES
-- Salgados Fritos
('Coxinha de Frango', 110.00, 'salgados', 'Coxinha tradicional com frango desfiado', FALSE, FALSE),
('Coxinha de Frango com Catupiry', 120.00, 'salgados', 'Coxinha de frango com catupiry', FALSE, FALSE),
('Bolinha de Queijo', 100.00, 'salgados', 'Bolinha crocante recheada com queijo', FALSE, FALSE),
('Pastel de Carne', 105.00, 'salgados', 'Pastel frito recheado com carne moída', FALSE, FALSE),
('Pastel de Queijo', 100.00, 'salgados', 'Pastel frito recheado com queijo', FALSE, FALSE),
('Risole de Camarão', 130.00, 'salgados', 'Risole cremoso com camarão', FALSE, FALSE),
('Risole de Frango', 115.00, 'salgados', 'Risole cremoso com frango', FALSE, FALSE),
('Enroladinho de Salsicha', 95.00, 'salgados', 'Massa crocante envolvendo salsicha', FALSE, FALSE),

-- Sortidos
('Sortido Simples', 90.00, 'sortidos', 'Mix de salgados variados', FALSE, FALSE),
('Sortido Especial', 110.00, 'sortidos', 'Mix premium de salgados', FALSE, FALSE),

-- Assados
('Pão de Açúcar', 8.00, 'assados', 'Pão doce tradicional', TRUE, FALSE),
('Pão de Batata', 6.00, 'assados', 'Pão macio de batata', TRUE, FALSE),
('Torta Salgada', 25.00, 'assados', 'Fatia de torta salgada', TRUE, FALSE),

-- Especiais
('Cachorro Quente Especial', 15.00, 'especiais', 'Cachorro quente completo', TRUE, FALSE),
('X-Burger', 18.00, 'especiais', 'Hambúrguer especial da casa', TRUE, FALSE),

-- Opcionais
('Refrigerante Lata', 5.00, 'opcionais', 'Refrigerante 350ml', TRUE, FALSE),
('Suco Natural', 8.00, 'opcionais', 'Suco de fruta natural', TRUE, FALSE),
('Água Mineral', 3.00, 'opcionais', 'Água mineral 500ml', TRUE, FALSE)

ON CONFLICT DO NOTHING;

-- Insert default configuration
INSERT INTO app_config (config_key, config_value) VALUES
('delivery_fee', '10.00'),
('min_order_value', '50.00'),
('store_address', 'RUA IDA BERLET 1738 B'),
('store_phone', '(54) 99999-9999'),
('store_hours', '08:00-18:00')
ON CONFLICT (config_key) DO NOTHING;

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_orders_user_id ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status);
CREATE INDEX IF NOT EXISTS idx_orders_created_at ON orders(created_at);
CREATE INDEX IF NOT EXISTS idx_order_status_history_order_id ON order_status_history(order_id);
CREATE INDEX IF NOT EXISTS idx_products_category ON products(category);
CREATE INDEX IF NOT EXISTS idx_users_phone ON users(phone);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);