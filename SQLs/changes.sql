ALTER TABLE statement ADD month smallint
ALTER TABLE statement_form_ae ADD month smallint

DROP VIEW appweb.declaraciones_web;

ALTER TABLE statement_form_ae ALTER COLUMN code TYPE character varying(15)

CREATE OR REPLACE VIEW appweb.declaraciones_web AS 
 SELECT statement_form_ae.id, 
    statement_form_ae.id_tax, 
    statement_form_ae.statement_type, 
    tax.tax_account_number, 
    statement_form_ae.code, 
        CASE
            WHEN statement_form_ae.statement_type THEN 'DEFINITIVA '::text || statement_form_ae.fiscal_year::character varying::text
            ELSE 'ESTIMADA '::text || statement_form_ae.fiscal_year::character varying::text
        END AS type, 
    statement_form_ae.tax_total_form, 
    statement_form_ae.codval
   FROM statement_form_ae
   JOIN tax ON statement_form_ae.id_tax = tax.id
   JOIN statement ON statement_form_ae.code::text = statement.form_number::text
  WHERE statement_form_ae.id_user = 198 AND NOT statement_form_ae.canceled AND statement_form_ae.tax_total_form IS NOT NULL AND statement_form_ae.codval IS NOT NULL AND statement.tax_total = statement_form_ae.tax_total_form AND statement.status = 2 AND NOT statement.canceled
  ORDER BY statement_form_ae.created DESC, statement_form_ae.id_tax, statement_form_ae.statement_type;

ALTER TABLE appweb.declaraciones_web
  OWNER TO postgres;

-- DECUENTOS

ALTER TABLE discount ADD type smallint DEFAULT 0;

INSERT INTO discount(name, description, type) VALUES('Art. 219', 'Descuento 219', 1);
INSERT INTO discount(name, description, type) VALUES('Exonerados', 'Exonerados', 0);
INSERT INTO discount(name, description, type) VALUES('Exentos', 'Exentos', 0);

COMMENT ON COLUMN discount.type IS '
0: percent
1: amount is placed by the taxpayer';

ALTER TABLE tax_discount ADD month smallint;
ALTER TABLE tax_discount ADD is_forever boolean DEFAULT false;

ALTER TABLE tax_discount ADD
CONSTRAINT tax_discount_discount_type_fkey FOREIGN KEY (discount_type)
      REFERENCES discount (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;



CREATE UNIQUE INDEX unique_with_nulls
ON tax_discount(id_tax, discount_type, COALESCE(statement_type, false), COALESCE(month, 0), COALESCE(fiscal_year, 0));

COMMENT ON COLUMN tax_discount.month IS '
Mes para declaracion (1-12)';
COMMENT ON COLUMN tax_discount.is_forever IS '
true: se aplica para todos los meses
false: se aplica solo para la declaracion actual';

-- Table: appweb.tax_discount_statement

-- DROP TABLE appweb.tax_discount_statement;

CREATE TABLE appweb.tax_discount_statement
(
  id serial NOT NULL,
  id_tax_discount integer,
  id_statement bigint,
  amount double precision,
  created timestamp without time zone DEFAULT now(),
  modified timestamp without time zone DEFAULT now(),
  CONSTRAINT tax_discount_statement_pkey PRIMARY KEY (id),
  CONSTRAINT tax_discount_statement_id_statement_fkey FOREIGN KEY (id_statement)
      REFERENCES statement (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT tax_discount_statement_id_tax_discount_fkey FOREIGN KEY (id_tax_discount)
      REFERENCES tax_discount (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE appweb.tax_discount_statement
  OWNER TO postgres;

-- Trigger: update_modified on appweb.tax_discount_statement

-- DROP TRIGGER update_modified ON appweb.tax_discount_statement;

CREATE TRIGGER update_modified
  BEFORE UPDATE
  ON appweb.tax_discount_statement
  FOR EACH ROW
  EXECUTE PROCEDURE setmodified();

-- ALTER TABLE tax_discount DROP amount;

