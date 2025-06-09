# Salgados da Sara - Configuração para XAMPP

## 🚀 Como executar no XAMPP

### Pré-requisitos
- XAMPP instalado e funcionando
- PostgreSQL configurado (ou MySQL como alternativa)
- Extensões PHP: pdo, pdo_pgsql (ou pdo_mysql), json

### 1. Configuração do Projeto

1. **Copie os arquivos para o XAMPP:**
   ```
   C:\xampp\htdocs\
   ├── front\          # Frontend da aplicação
   └── backend\        # Backend PHP
   ```

2. **Configure o banco de dados:**
   
   **Opção A - PostgreSQL:**
   - Instale PostgreSQL separadamente
   - Edite `backend/config/database.php`:
   ```php
   private $host = 'localhost';
   private $db_name = 'salgados_da_sara';
   private $username = 'postgres';
   private $password = 'sua_senha';
   private $port = '5432';
   ```

   **Opção B - MySQL (mais fácil com XAMPP):**
   - Use o MySQL que vem com o XAMPP
   - Edite `backend/config/database.php`:
   ```php
   private $host = 'localhost';
   private $db_name = 'salgados_da_sara';
   private $username = 'root';
   private $password = '';
   private $port = '3306';
   ```

3. **Crie o banco de dados:**
   
   **Para PostgreSQL:**
   ```sql
   CREATE DATABASE salgados_da_sara;
   ```
   
   **Para MySQL (via phpMyAdmin):**
   - Acesse http://localhost/phpmyadmin
   - Crie um banco chamado `salgados_da_sara`

### 2. Configuração do Apache

1. **Inicie o XAMPP:**
   - Abra o XAMPP Control Panel
   - Inicie Apache e MySQL (ou PostgreSQL)

2. **Verifique o mod_rewrite:**
   - O arquivo `.htaccess` já está configurado na pasta `backend/`
   - Certifique-se de que o mod_rewrite está habilitado no Apache

### 3. Acesso à Aplicação

- **Frontend:** http://localhost/front
- **Backend API:** http://localhost/backend/api
- **Painel Admin:** http://localhost/front#admin

### 4. Dados de Teste

**Administrador padrão:**
- Usuário: `sara`
- Senha: `password`

### 5. Solução de Problemas

**Erro "Backend não está respondendo JSON":**
1. Verifique se o Apache está rodando
2. Teste a API diretamente: http://localhost/backend/api
3. Verifique se o arquivo `.htaccess` está na pasta `backend/`

**Erro de conexão com banco:**
1. Verifique se o MySQL/PostgreSQL está rodando
2. Confirme as credenciais em `backend/config/database.php`
3. Certifique-se de que o banco foi criado

**Erro 404 nas rotas:**
1. Verifique se o mod_rewrite está habilitado
2. Teste acessar: http://localhost/backend/api/products

### 6. Estrutura no XAMPP

```
C:\xampp\htdocs\
├── front\
│   ├── index.html
│   ├── js\
│   ├── styles\
│   └── ...
└── backend\
    ├── .htaccess
    ├── api\
    ├── config\
    ├── models\
    └── ...
```

### 7. URLs de Teste

Teste estas URLs para verificar se está funcionando:

- http://localhost/front (Frontend)
- http://localhost/backend/api (Documentação da API)
- http://localhost/backend/api/products (Lista de produtos)
- http://localhost/backend/api/config (Configurações)

Se alguma dessas URLs retornar erro 404, verifique a configuração do Apache e do .htaccess.