
SELECT tax.*
FROM appweb.tax
INNER JOIN appweb.users_web ON users_web.id_taxpayer = tax.id_taxpayer
WHERE real_initial_date < '2010-01-01' 
AND id_tax_type = 1
AND confirmed_email
AND appweb.have_statement(tax.id, TRUE, 2009, TRUE) = 0
AND appweb.have_statement(tax.id, TRUE, 2013, TRUE) = 0
AND (SELECT COUNT(*) FROM appweb.errors_declare_taxpayer_monthly(tax.id_taxpayer, TRUE, 2013)) = 0
LIMIT 10


SELECT * FROM appweb.errors_declare_taxpayer_monthly(80615, TRUE, 2010)


SELECT transaction.*,  appweb.paid_transaction(transaction.id)
FROM transaction
INNER JOIN transaction_type ON id_transaction_type = transaction_type.id
WHERE id_tax = 5869
AND NOT credit 
AND NOT canceled
AND id_transaction_type NOT IN (1, 8, 51, 16)
AND application_date BETWEEN '2011-01-01' AND now()
AND appweb.paid_transaction(transaction.id) < 0
AND transaction.id != ALL('{52740,6927640,6927653,96380, 96381, 96382, 5963109, 5963110, 5963136, 5963113, 6245732, 6212269, 6212267, 6212236, 6212243, 6212249}'::bigint[]);





SELECT
tax_classifier.id,
tax_classifier.code,
tax_classifier.description,
tax_classifier.aliquot,
tax_classifier.minimun_taxable,
appweb.get_tax_classifier_converter(activity_rebates.id_new) AS ids_specialized
FROM permissible_activities
INNER JOIN tax_classifier ON permissible_activities.id_classifier_tax = tax_classifier.id
INNER JOIN activity_rebates ON activity_rebates.id_old = tax_classifier.id
WHERE tax_classifier.code NOT LIKE '%.%' 
AND permissible_activities.id_tax = 103709

SELECT * FROM appweb.tax WHERE id_taxpayer = 459


SELECT statement.id, id_tax, fiscal_year, type, estimated_sterile, tax_total, statement.month
FROM statement
INNER JOIN appweb.tax ON tax.id = id_tax
WHERE id_taxpayer = 80615
AND tax.id_tax_type = 1 
AND statement.status = 2
AND NOT statement.canceled

SELECT * FROM activity_rebates

SELECT tax_classifier_specialized.id
FROM tax_classifier
INNER JOIN appweb.tax_classifier_converter ON id_tax_classifier = tax_classifier.id
INNER JOIN appweb.tax_classifier_specialized ON id_tax_classifier_specialized = tax_classifier_specialized.id
WHERE tax_classifier.id = _ID_TAX_CLASSIFIER
ORDER BY tax_classifier_specialized.code


"33201"
"6200401"


SELECT code, name, aliquot, minimun_taxable, fiscal_year, id_tax_classifier_new
FROM tax_classifier
INNER JOIN permissible_activities ON tax_classifier.id = id_classifier_tax 
WHERE id_tax = 103709
AND parent_level = 689


SELECT * FROM appweb.tax_unit(2009)

SELECT	
*
FROM tax_classifier 
WHERE -- tax_classifier.code NOT LIKE '%.%' 
-- AND COALESCE(tax_classifier.aliquot, 0) != 0
-- AND 
parent_level != 689
ORDER BY tax_classifier.code



SELECT 
tax_classifier.id,
tax_classifier.code,
tax_classifier.name,
tax_classifier.description,
tax_classifier.aliquot,
tax_classifier.minimun_taxable,
appweb.get_tax_classifier_converter(tax_classifier.id) AS ids_specialized
FROM tax_classifier 
WHERE id_tax_type = 1
AND attribute_classifier = 4
AND parent_level = 689
ORDER BY aliquot DESC, code

SELECT 
*
FROM tax_classifier 
WHERE id_tax_type = 1
AND attribute_classifier = 4
AND parent_level = 689
ORDER BY code, aliquot DESC
	            



