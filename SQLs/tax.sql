-- View: appweb.tax

-- DROP VIEW appweb.tax;

CREATE OR REPLACE VIEW appweb.tax AS 
 SELECT tax.id, 
    tax.id_taxpayer, 
    tax.id_tax_type, 
    tax.id_tax_classifier, 
    tax.id_tax_status, 
    tax.tax_account_number, 
    tax.initial_date, 
    tax.expiry_date, 
    tax.approval_number, 
    tax.approval_date, 
    tax.license_number, 
    tax.patent_number, 
    tax.created, 
    tax.modified, 
    tax.removed, 
    tax.canceled, 
    tax.observation, 
    tax.active, 
    tax.valid, 
    tax.registration_date, 
    tax.start_activities_date, 
    tax.rent_account, 
    tax.account_type, 
    tax.land_registry, 
    tax.land_registry_new, 
    tax.id_user, 
    tax.advertising_permission_number, 
        CASE
            WHEN tax.initial_date IS NULL THEN tax.start_activities_date
            ELSE tax.initial_date
        END AS real_initial_date, 
    regexp_replace(address.address::text || 
        CASE
            WHEN address.street IS NOT NULL THEN ((', '::text || address.street::text) || ', '::text) || address.name::text
            ELSE ''::text
        END, '[\n\r\t]+'::text, ' '::text, 'g'::text) AS address,
   appweb.get_tax_additional_information(tax.id) AS tax_information_condensed
   FROM tax
   LEFT JOIN tax_address ON tax_address.id_tax = tax.id
   LEFT JOIN address ON address.id = tax_address.id_address
  WHERE tax.id_tax_status = 1 AND NOT tax.canceled AND NOT tax.removed AND address.main;

ALTER TABLE appweb.tax
  OWNER TO postgres;
