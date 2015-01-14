-- View: appweb.get_cadastral_document

-- DROP VIEW appweb.get_cadastral_document;

CREATE OR REPLACE VIEW appweb.get_cadastral_document AS 
 SELECT cadastral_document.id, 
    tax.id_taxpayer, 
    tax.id AS id_tax,
    tax.tax_account_number, 
    tax.id_tax_type, 
    cadastral_document.cadastral_number, 
        CASE
            WHEN cadastral_document.cadastral_request_type = 1 THEN 'Actualización'::text
            WHEN cadastral_document.cadastral_request_type = 2 THEN 'Corrección de Datos'::text
            WHEN cadastral_document.cadastral_request_type = 3 THEN 'Razón social'::text
            WHEN cadastral_document.cadastral_request_type = 4 THEN 'Cambio de Firma'::text
            WHEN cadastral_document.cadastral_request_type = 5 THEN 'Terreno a Construcción'::text
            WHEN cadastral_document.cadastral_request_type = 6 THEN 'Integración'::text
            WHEN cadastral_document.cadastral_request_type = 9 THEN ''::text
            WHEN cadastral_document.cadastral_request_type = 10 THEN 'Inscripción Catastral'::text
            ELSE NULL::text
        END AS type, 
        CASE
            WHEN (cadastral_document.prepared_by + cadastral_document.revised_by + cadastral_document.confirmed_by) IS NOT NULL AND cadastral_document.status_rent <> 3 AND (cadastral_document.status_print = false OR cadastral_document.status_print IS NULL) THEN 'En proceso'::text
            WHEN (cadastral_document.prepared_by + cadastral_document.revised_by + cadastral_document.confirmed_by) IS NOT NULL AND cadastral_document.status_rent = 3 AND (cadastral_document.status_print = false OR cadastral_document.status_print IS NULL) THEN 'Aprobado'::text
            WHEN (cadastral_document.prepared_by + cadastral_document.revised_by + cadastral_document.confirmed_by) IS NOT NULL AND cadastral_document.status_rent = 3 AND cadastral_document.status_print THEN 'Listo para retirar'::text
            ELSE NULL::text
        END AS status
   FROM cadastre.cadastral_document
   JOIN tax ON cadastral_document.id_tax = tax.id
  ORDER BY cadastral_document.created DESC;

ALTER TABLE appweb.get_cadastral_document
  OWNER TO postgres;
