INSERT INTO `nortcuyo`.`currency` (`id`, `name`, `symbol`, `is_default`, `created_at`, `updated_at`, `is_delete`, `code`) VALUES ('16474a68-0e6d-11eb-8905-3a54f5d23ebe','Peso Argentino', '$', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', 'ARS');
INSERT INTO `nortcuyo`.`currency` (`id`,`name`, `symbol`, `is_default`, `created_at`, `updated_at`, `is_delete`, `code`) VALUES ('3061966a-0e6d-11eb-8905-3a54f5d23ebe','Dólar Estadounidense', 'U$D', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', 'USD');

#ALL Prouct
update product set currency_id = '16474a68-0e6d-11eb-8905-3a54f5d23ebe';

#ALL ORDERS
INSERT INTO orders_total (SELECT uuid(),id,'16474a68-0e6d-11eb-8905-3a54f5d23ebe',total,total_vat,(total_vat - total),'2020-10-26 20:50:19','2020-10-26 20:50:19',0 FROM nortcuyo.orders);
INSERT INTO orders_total (SELECT uuid(),id,'3061966a-0e6d-11eb-8905-3a54f5d23ebe',0,0,0,'2020-10-26 20:50:19','2020-10-26 20:50:19',0 FROM nortcuyo.orders);