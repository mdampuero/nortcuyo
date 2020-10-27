INSERT INTO `nortcuyo`.`currency` (`id`, `name`, `symbol`, `is_default`, `created_at`, `updated_at`, `is_delete`, `code`) VALUES ('16474a68-0e6d-11eb-8905-3a54f5d23ebe','Peso Argentino', '$', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', 'ARS');
INSERT INTO `nortcuyo`.`currency` (`id`,`name`, `symbol`, `is_default`, `created_at`, `updated_at`, `is_delete`, `code`) VALUES ('3061966a-0e6d-11eb-8905-3a54f5d23ebe','Dólar Estadounidense', 'U$D', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', 'USD');

#ALL Prouct
update product set currency_id = '16474a68-0e6d-11eb-8905-3a54f5d23ebe';