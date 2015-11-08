ALTER TABLE  `tbl_transaction` ADD  `date_tmp` INT NOT NULL DEFAULT  '0';
update tbl_transaction set date_tmp = UNIX_TIMESTAMP(`date`);
ALTER TABLE  `tbl_transaction` CHANGE  `date`  `date` INT NOT NULL DEFAULT  '0';
update tbl_order set `date` = date_tmp;
ALTER TABLE `tbl_order` DROP `date_tmp`

ALTER TABLE  `tbl_order` ADD  `date_tmp` INT NOT NULL DEFAULT  '0';
update tbl_order set date_tmp = UNIX_TIMESTAMP(`date`);
ALTER TABLE  `tbl_order` CHANGE  `date`  `date` INT NOT NULL DEFAULT  '0';
update tbl_order set `date` = date_tmp;
ALTER TABLE `tbl_order` DROP `date_tmp`
