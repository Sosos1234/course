# InfinityFree — если не работает

## 1. Ошибка "No index file"

**Решение:** загрузите `index.php` в папку **htdocs** (в корень, не в подпапку).

Добавлены `index.html` и `.htaccess` — положите их в htdocs вместе с остальными файлами.

---

## 2. Ошибка подключения к БД (2002, No such file)

**Причина:** неверный host в config.

**Решение:** в `config/config.php` укажите:
```php
'host' => 'sqlXXX.infinityfree.com',
```
(точное значение — в cPanel → MySQL Databases → Remote MySQL / Database Details)

---

## 3. Ошибка "Access denied" (1045)

**Причина:** неверные логин или пароль БД.

**Решение:** проверьте user и pass в `config/config.php`. Имена в InfinityFree часто с префиксом, например `if0_12345678_europa27`.

---

## 4. Ошибка "Unknown database" (1049)

**Причина:** база не создана или указано неверное имя.

**Решение:** 
1. cPanel → MySQL Databases → Create Database
2. Создайте пользователя и привяжите к базе (All Privileges)
3. В config укажите точное имя БД из панели

---

## 5. Ошибка при выполнении SQL (FULLTEXT)

**Причина:** на бесплатном тарифе FULLTEXT может быть недоступен.

**Решение:** выполните **`database/schema_simple.sql`** вместо schema.sql. Поиск будет работать через LIKE.

---

## 6. Белый экран / 500 error

**Возможные причины:**
- Синтаксическая ошибка PHP
- Нет нужных расширений (pdo_mysql, mbstring)

**Решение:** включите показ ошибок — в начале `index.php` перед `require` добавьте:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
Посмотрите текст ошибки и исправьте по нему.

---

## 7. Путь к файлам

Убедитесь, что структура в htdocs такая:
```
htdocs/
├── index.php
├── index.html
├── .htaccess
├── bootstrap.php
├── config/
├── admin/
├── assets/
├── ...
```

Не должно быть лишней папки (например, htdocs/europa27-new/index.php — тогда URL будет другой).
