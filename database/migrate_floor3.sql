-- Миграция: добавить 3-й этаж (для Галактики)
INSERT IGNORE INTO `floors` (`number`, `name`, `description`) VALUES
(3, '3 этаж', 'Дополнительный торговый уровень');
