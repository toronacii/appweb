-- View: activity_rebates

DROP VIEW activity_rebates;

CREATE OR REPLACE VIEW activity_rebates AS 
 SELECT tax_classifier.id AS id_new, tax_classifier.code AS code_new, tax_clasifier_old.id AS id_old, tax_clasifier_old.code AS code_old, 
        CASE
            WHEN tax_clasifier_old.code::integer = ANY (ARRAY[32111, 32112, 32113, 31221, 32121, 32122, 32201, 32202, 32203, 32204, 32205, 32206, 32207]) THEN 94
            WHEN tax_clasifier_old.code::integer = ANY (ARRAY[31121, 31131, 33141, 31151, 31165, 31174]) THEN 95
            ELSE NULL::integer
        END AS art
   FROM tax_classifier
   JOIN tax_classifier tax_clasifier_old ON tax_classifier.id = tax_clasifier_old.id_tax_classifier_new
  WHERE tax_classifier.code::text ~~ '%.%'::text AND tax_clasifier_old.parent_level = 689
  ORDER BY tax_classifier.code;

ALTER TABLE activity_rebates OWNER TO postgres;

