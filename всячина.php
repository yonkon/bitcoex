_______________________________________
Полезные запросы
_____________
SELECT t.src_wallet,  t.dst_wallet, t.src_count, t.src_price, t.src_count, t.dst_price,
IF(sw.type=1, src_count, src_price*src_count) AS srcUSD,
IF(dw.type=1, dst_count, dst_price*src_count) AS dstUSD,
IF(sw.type=2, src_count, dst_price/src_count) AS srcBTC,
IF(dw.type=2, dst_count, src_price/src_count) AS dstBTC
FROM `tbl_transaction` t join tbl_wallet sw on sw.id = t.src_wallet join tbl_wallet dw on dw.id = t.dst_wallet
____________
UPDATE `tbl_order` SET rest = 0, status=1 WHERE 1;
UPDATE `tbl_wallet` SET `money`=10000,`available`=10000 WHERE 1;

_______________________________________
Перевод ассертов в эксепшны
_______
assert(.*);\s*\/\/\s(.*)\);$
if(!$1) { throw new Exception($2); }
_______
add to bootstrap-theme.css & bootstrap.css at end if minified
/*# sourceMappingURL=bootstrap-theme.css.map */

_____________________________________
Логотип
_______
Шрифт "S"  в долларе лого - Cambria, полужирный
