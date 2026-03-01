-- Добавить колонку image для загружаемых картинок магазинов
ALTER TABLE `shops` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `contact`;
