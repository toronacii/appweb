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
