ALTER TABLE preformatted ADD title VARCHAR(64) DEFAULT NULL;
ALTER TABLE proformatted_item ADD preformatted_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:guid)';
ALTER TABLE proformatted_item ADD CONSTRAINT FK_9801CE817AC2C02F FOREIGN KEY (preformatted_id) REFERENCES preformatted (id);
CREATE INDEX IDX_9801CE817AC2C02F ON proformatted_item (preformatted_id);
CREATE TABLE preformatted_item (id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)', preformatted_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:guid)', product_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:guid)', position INT NOT NULL, INDEX IDX_FF0EB3977AC2C02F (preformatted_id), INDEX IDX_FF0EB3974584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB;
ALTER TABLE preformatted_item ADD CONSTRAINT FK_FF0EB3977AC2C02F FOREIGN KEY (preformatted_id) REFERENCES preformatted (id);
ALTER TABLE preformatted_item ADD CONSTRAINT FK_FF0EB3974584665A FOREIGN KEY (product_id) REFERENCES product (id);