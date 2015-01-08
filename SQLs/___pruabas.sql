SELECT * FROM statement 
WHERE fiscal_year = 2009
AND created IS NOT NULL
ORDER BY created DESC
LIMIT 10

SELECT * FROM statement WHERE id = 2066252

SELECT * FROM interest_rate


SELECT *
FROM appweb.get_interest_rate('2012-01-01');



SELECT s.id, s.id_tax statement_date, uw.email 
FROM statement AS s
INNER JOIN appweb.tax ON s.id_tax = tax.id
INNER JOIN appweb.users_web AS uw ON uw.id_taxpayer = tax.id_taxpayer
WHERE s.fiscal_year = 2013
-- AND s.id_user = 198 
AND s.type IN (2,5) 
AND NOT s.canceled
AND s.status = 2
AND NOT s.extemp
AND confirmed_email
LIMIT 10


UPDATE statement
SET extemp = TRUE,
statement_date = CURRENT_DATE
WHERE id = 2066252

BEGIN; SELECT * FROM generate_statement_interest(2066252);

SELECT * 
FROM transaction 
WHERE id_tax = 105490
AND created::date = CURRENT_DATE 
ORDER BY creATED desc, 
application_date ASC

SELECT * FROM appweb.estado_cuenta(105490, NULL);

ROLLBACK;

COMMIT;




-- DEFINITIVAS 2013

2066252;"2014-01-31";"x4664@x.com"
2066964;"2014-01-31";"x20041@x.com"
2070106;"2014-01-30";"x24268@x.com"
2066955;"2014-01-31";"x27609@x.com"
2066199;"2014-01-31";"x39093@x.com"
2066361;"2014-01-31";"x17259@x.com"
2066321;"2014-01-31";"x92231@x.com"
2064471;"2014-01-27";"x118041@x.com"
2066190;"2014-01-31";"x60696@x.com"
2066777;"2014-01-31";"x113218@x.com"




SELECT (DATE_PART('YEAR', age('2011-02-01', '2010-01-01')) - 1) * 12,  DATE_PART('MONTH', age('2011-02-01', '2010-01-01')), 1


SELECT * FROM transaction WHERE id = 6202873

SELECT * FROM interest WHERE id = 156717



SELECT * 
FROM transaction 
WHERE parent_transaction = 27817432

SELECT * FROM tax WHERE id = 105490 -- "030000281" "030016967" "030019368" "030015548"


SELECT TRUNC((DATE_PART('month', '2010-05-01'::date) + 1) / 3)

SELECT * FROM appweb.get_interest_rate('2010-05-01')


SELECT * FROM transaction WHERE concept LIKE 'Interés Mensual (%) para Trimestre%' AND id_user = 198 LIMIT 50

SELECT * 
FROM transaction 
WHERE id_tax = 157715
AND expiry_date = '2011-08-01'


SELECT * 
FROM transaction 
WHERE id_transaction_type = 3 
AND parent_transaction ISNULL 
ORDER BY created DESC
LIMIT 10