# Установка SQL (MySQL/MariaDB) — ТРЦ «Европа 27»

## 1. Установка MariaDB (Ubuntu/Debian)

```bash
sudo apt update
sudo apt install mariadb-server mariadb-client php-mysql php-pdo
```

## 2. Запуск сервера

```bash
sudo service mariadb start
# или на WSL без systemd:
sudo /usr/bin/mariadbd-safe &
```

Проверка:
```bash
sudo mariadb -e "SELECT 1"
```

## 3. Создание пользователя и пароля

```bash
sudo mariadb
```

В консоли MariaDB:

```sql
CREATE USER IF NOT EXISTS 'europa27'@'localhost' IDENTIFIED BY 'mypassword';
GRANT ALL PRIVILEGES ON europa27.* TO 'europa27'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 4. Настройка config.php

Убедитесь, что в `config/config.php` указаны:

```php
'db' => [
    'host' => 'localhost',
    'name' => 'europa27',
    'user' => 'europa27',
    'pass' => 'mypassword',
    'charset' => 'utf8mb4',
],
```

Или задайте через переменные окружения:

```bash
export EUROPA27_DB_USER=europa27
export EUROPA27_DB_PASS=mypassword
```

## 5. Создание БД и таблиц

```bash
cd /путь/к/проекту
php install.php
```

Должно вывести: `База данных успешно создана и настроена.`

## 6. Импорт данных

Откройте в браузере: `http://localhost:8000/index.php?page=import`

Или выполните:

```bash
php -S localhost:8000
```

Затем перейдите по ссылке импорта.

---

## Альтернатива: MySQL вместо MariaDB

```bash
sudo apt install mysql-server mysql-client php-mysql php-pdo
sudo service mysql start
```

Дальнейшие шаги те же (создание пользователя, `php install.php`).

---

## Troubleshooting

| Ошибка | Решение |
|--------|---------|
| `Access denied for user` | Проверьте логин/пароль в config.php, создайте пользователя в MariaDB |
| `Connection refused` | Запустите сервер: `sudo service mariadb start` |
| `command not found: mysql` | Установите `mariadb-client` или `mysql-client` |
