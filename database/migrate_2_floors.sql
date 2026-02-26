-- Миграция: оставить только 2 этажа
-- Выполнить после изменения schema.sql

-- Обновить ссылки на этажи в магазинах
-- Старые id: 1=подземный, 2=1эт, 3=2эт, 4=3эт, 5=4эт, 6=5эт
-- Новые: 1=1эт, 2=2эт

UPDATE shops SET floor_id = 1 WHERE floor_id = 2;
UPDATE shops SET floor_id = 2 WHERE floor_id IN (3, 4, 5, 6);

-- Удалить лишние этажи
DELETE FROM floors WHERE id > 2;

-- Обновить оставшиеся этажи
UPDATE floors SET number = 1, name = '1 этаж', description = 'Основной торговый уровень' WHERE id = 1;
UPDATE floors SET number = 2, name = '2 этаж', description = 'Торговая галерея, услуги, развлечения' WHERE id = 2;
