-- Схема БЕЗ FULLTEXT (для хостингов, где FULLTEXT недоступен)
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `sort_order` INT DEFAULT 0,
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `floors` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `number` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    UNIQUE KEY `idx_floor_number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `shops` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL,
    `description` TEXT,
    `category_id` INT UNSIGNED NOT NULL,
    `floor_id` INT UNSIGNED NOT NULL,
    `pavilion` VARCHAR(50) DEFAULT NULL,
    `contact` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `fulltext` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category_id`),
    INDEX `idx_floor` (`floor_id`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_name` (`name`(50)),
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`floor_id`) REFERENCES `floors`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `shop_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `price` DECIMAL(10,2) DEFAULT NULL,
    `fulltext` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_shop` (`shop_id`),
    INDEX `idx_name` (`name`(50)),
    FOREIGN KEY (`shop_id`) REFERENCES `shops`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10,2) DEFAULT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `news` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT,
    `shop_id` INT UNSIGNED DEFAULT NULL,
    `published_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_shop` (`shop_id`),
    FOREIGN KEY (`shop_id`) REFERENCES `shops`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `floors` (`number`, `name`, `description`) VALUES
(1, '1 этаж', 'Основной торговый уровень'),
(2, '2 этаж', 'Торговая галерея, услуги, развлечения');

INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES
('Продукты', 'produkty', 1),
('Одежда и обувь', 'odezhda-obuv', 2),
('Детские товары', 'detskie-tovary', 3),
('Электроника и бытовая техника', 'elektronika', 4),
('Торговая галерея', 'torgovaya-galereya', 5),
('Спорт и фитнес', 'sport-fitnes', 6),
('Образование и услуги', 'obrazovanie-uslugi', 7);

INSERT INTO `news` (`title`, `content`, `shop_id`, `published_at`) VALUES
('Открытие нового кафе', 'На втором этаже открылось уютное кафе. Завтраки, бизнес-ланчи, свежая выпечка.', NULL, NOW()),
('Акция в супермаркете', 'Скидка 20% на продукцию собственного производства по пятницам.', NULL, NOW()),
('Набор в группы школы танцев', 'Открыт набор в детские и взрослые группы. Первое занятие бесплатно!', NULL, NOW());
