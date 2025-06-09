# Salgados da Sara - Sistema de Pedidos

Sistema completo de pedidos online para a lanchonete "Salgados da Sara" com frontend em HTML/CSS/JS e backend em PHP.

## 🚀 Como executar o projeto

### Pré-requisitos

- PHP 7.4 ou superior
- PostgreSQL 12 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server
- Extensões PHP: pdo, pdo_pgsql, json

### 1. Configurar o Backend (PHP)

1. **Configure o banco de dados PostgreSQL:**
   ```bash
   # Conecte ao PostgreSQL
   psql -U postgres
   
   # Crie o banco de dados
   CREATE DATABASE salgados_da_sara;
   
   # Execute o schema (você precisará criar o arquivo schema.sql)
   psql -U postgres -d salgados_da_sara -f backend/database/schema.sql
   ```

2. **Configure a conexão com o banco:**
   Edite o arquivo `backend/config/database.php` com suas credenciais:
   ```php
   private $host = 'localhost';
   private $db_name = 'salgados_da_sara';
   private $username = 'postgres';
   private $password = 'sua_senha';
   private $port = '5432';
   ```

3. **Inicie o servidor PHP:**
   ```bash
   cd backend
   php -S localhost:8080
   ```

### 2. Configurar o Frontend

1. **Inicie um servidor web para o frontend:**
   ```bash
   cd front
   # Usando Python
   python -m http.server 3000
   
   # Ou usando Node.js (se tiver o http-server instalado)
   npx http-server -p 3000
   
   # Ou usando PHP
   php -S localhost:3000
   ```

2. **Acesse a aplicação:**
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8080/api
   - Painel Admin: http://localhost:3000#admin

### 3. Dados de Acesso

**Administrador padrão:**
- Usuário: `sara`
- Senha: `password`

**IMPORTANTE:** Altere a senha padrão em produção!

## 📁 Estrutura do Projeto

```
├── backend/                 # Backend PHP
│   ├── api/                # Endpoints da API
│   ├── config/             # Configurações
│   ├── models/             # Modelos de dados
│   └── .htaccess          # Configuração Apache
├── front/                  # Frontend HTML/CSS/JS
│   ├── js/                # Scripts JavaScript
│   ├── styles/            # Arquivos CSS
│   └── index.html         # Página principal
└── README.md
```

## 🔧 Configuração da API

O frontend está configurado para se conectar com o backend na URL `http://localhost:8080/api`. Se você estiver usando uma porta diferente, edite o arquivo `front/js/api.js`:

```javascript
const API = {
    baseURL: 'http://localhost:8080/api', // Altere aqui
    // ...
};
```

## 📱 Funcionalidades

### Para Clientes:
- ✅ Visualizar cardápio
- ✅ Adicionar itens ao carrinho
- ✅ Escolher quantidade (cento, meio cento, unidades)
- ✅ Selecionar entrega ou retirada
- ✅ Finalizar pedido
- ✅ Acompanhar histórico de pedidos

### Para Administradores:
- ✅ Gerenciar pedidos (confirmar, recusar, atualizar status)
- ✅ Gerenciar produtos (adicionar, editar, excluir)
- ✅ Gerenciar administradores
- ✅ Configurar taxa de entrega

## 🛠️ Solução de Problemas

### Erro "Unexpected token '<'"
Este erro indica que o frontend não consegue se conectar com o backend. Verifique:

1. **Backend está rodando?**
   ```bash
   cd backend
   php -S localhost:8080
   ```

2. **URL da API está correta?**
   Verifique o arquivo `front/js/api.js` e confirme que a `baseURL` está apontando para o servidor correto.

3. **CORS está configurado?**
   O arquivo `backend/config/cors.php` já está configurado para desenvolvimento.

### Erro 404 nas rotas da API
Verifique se o arquivo `.htaccess` está na pasta `backend/` e se o mod_rewrite está habilitado no Apache.

### Problemas de conexão com banco
1. Verifique se o PostgreSQL está rodando
2. Confirme as credenciais no arquivo `backend/config/database.php`
3. Certifique-se de que o banco `salgados_da_sara` foi criado

## 🌐 Deploy em Produção

### Backend:
1. Configure um servidor web (Apache/Nginx)
2. Aponte o DocumentRoot para a pasta `backend/`
3. Configure as variáveis de ambiente para produção
4. Use HTTPS em produção

### Frontend:
1. Pode ser servido por qualquer servidor web
2. Atualize a URL da API no arquivo `api.js` para o endereço de produção
3. Configure HTTPS

## 📞 Suporte

Se você encontrar problemas:

1. Verifique se todos os serviços estão rodando
2. Confira os logs do navegador (F12 → Console)
3. Verifique os logs do servidor PHP
4. Confirme as configurações de banco de dados

## 🔄 Próximas Funcionalidades

- [ ] Notificações em tempo real
- [ ] Integração com WhatsApp
- [ ] Sistema de cupons de desconto
- [ ] Relatórios de vendas
- [ ] App mobile (PWA)