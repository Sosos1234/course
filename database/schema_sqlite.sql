-- Схема для SQLite (портативная версия)
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    sort_order INTEGER DEFAULT 0
);
CREATE INDEX IF NOT EXISTS idx_cat_slug ON categories(slug);

CREATE TABLE IF NOT EXISTS floors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number INTEGER NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS shops (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    description TEXT,
    category_id INTEGER NOT NULL,
    floor_id INTEGER NOT NULL,
    pavilion VARCHAR(50),
    contact VARCHAR(255),
    image VARCHAR(255),
    fulltext TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (floor_id) REFERENCES floors(id)
);
CREATE INDEX IF NOT EXISTS idx_shop_cat ON shops(category_id);
CREATE INDEX IF NOT EXISTS idx_shop_floor ON shops(floor_id);
CREATE INDEX IF NOT EXISTS idx_shop_name ON shops(name);

CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    shop_id INTEGER NOT NULL,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    price REAL,
    fulltext TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_prod_shop ON products(shop_id);
CREATE INDEX IF NOT EXISTS idx_prod_name ON products(name);

CREATE TABLE IF NOT EXISTS product_variants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    name VARCHAR(255) NOT NULL,
    price REAL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_pv_product ON product_variants(product_id);

CREATE TABLE IF NOT EXISTS news (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    shop_id INTEGER,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE SET NULL
);

INSERT OR IGNORE INTO floors (id, number, name, description) VALUES (1, 1, '1 этаж', 'Основной торговый уровень'), (2, 2, '2 этаж', 'Торговая галерея, услуги, развлечения'), (3, 3, '3 этаж', 'Дополнительный торговый уровень');
INSERT OR IGNORE INTO categories (name, slug, sort_order) VALUES 
('Продукты', 'produkty', 1), ('Одежда и обувь', 'odezhda-obuv', 2), ('Детские товары', 'detskie-tovary', 3),
('Электроника и бытовая техника', 'elektronika', 4), ('Торговая галерея', 'torgovaya-galereya', 5),
('Спорт и фитнес', 'sport-fitnes', 6), ('Образование и услуги', 'obrazovanie-uslugi', 7);
INSERT OR IGNORE INTO news (title, content, shop_id, published_at) VALUES 
('Открытие нового кафе', 'На втором этаже открылось уютное кафе.', NULL, datetime('now')),
('Акция в супермаркете', 'Скидка 20% по пятницам.', NULL, datetime('now')),
('Набор в группы школы танцев', 'Первое занятие бесплатно!', NULL, datetime('now'));
