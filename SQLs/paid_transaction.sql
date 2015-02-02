-- Function: appweb.paid_transaction(bigint)

-- DROP FUNCTION appweb.paid_transaction(bigint);

CREATE OR REPLACE FUNCTION appweb.paid_transaction(bigint)
  RETURNS smallint AS
$BODY$ 
DECLARE
-- PARAMETROS

	_ID_TRANSACTION ALIAS FOR $1;	

-- VARIABLES LOCALES

	_RECORD record;
	_DEBT_AMOUNT double precision;
	_CREDIT boolean;

BEGIN

SELECT INTO _CREDIT credit
FROM transaction 
INNER JOIN transaction_type ON id_transaction_type = transaction_type.id
WHERE transaction.id = _ID_TRANSACTION;

-- VERIFICAR QUE LA TRANSACCION EXISTA

IF (NOT FOUND) THEN RETURN 0; END IF;

-- VERIFICAR QUE LA TRANSACCION SEA UN DEBITO

IF (_CREDIT) THEN RETURN 4; END IF;

-- VERIFICAR SI EL CARGO ESTA ASOCIADO A UN CONVENIO O FRACCIONAMIENTO DE PAGO 

SELECT INTO _RECORD DISTINCT
transaction.canceled,
CASE WHEN payment_agreement.id ISNULL THEN FALSE ELSE TRUE END AS convenio,
CASE WHEN payment_division.id ISNULL THEN FALSE ELSE TRUE END AS fraccionamiento,
CASE WHEN original_amount ISNULL THEN amount ELSE original_amount END AS original_amount
FROM transaction
LEFT JOIN payment_agreement_transaction convenio ON convenio.id_transaction = transaction.id
LEFT JOIN payment_agreement ON convenio.id_payment_agreement = payment_agreement.id
LEFT JOIN payment_division_transaction fraccionamiento ON fraccionamiento.id_transaction = transaction.id
LEFT JOIN payment_division ON fraccionamiento.id_payment_division = payment_division.id
WHERE transaction.id = _ID_TRANSACTION
AND (payment_agreement.id ISNULL OR NOT(payment_agreement.deleted))
AND (payment_agreement.id ISNULL OR NOT(payment_division.canceled));

-- EL CARGO ESTA ASOCIADO A UN CONVENIO DE PAGO
IF (_RECORD.convenio) THEN

	SELECT INTO _DEBT_AMOUNT
	round((payment_agreement.total_debt_amount - SUM (CASE WHEN capital_payment ISNULL THEN 0 ELSE capital_payment END))::numeric,2)
	FROM payment_agreement_transaction
	INNER JOIN payment_agreement ON payment_agreement_transaction.id_payment_agreement = payment_agreement.id
	INNER JOIN payment_agreement_detail ON payment_agreement_detail.id_payment_agreement = payment_agreement.id
	INNER JOIN payment_transaction ON payment_agreement_detail.id_transaction = id_transaction_debit
	LEFT JOIN payment ON payment_transaction.id_payment = payment.id AND payment.status = 2
	WHERE payment_agreement_transaction.id_transaction = _ID_TRANSACTION
	AND NOT payment_agreement.deleted
	GROUP BY payment_agreement.total_debt_amount;

	IF (_DEBT_AMOUNT <= 0) THEN
		RETURN 1;
	END IF;
	
	RETURN -1;

-- EL CARGO ESTA ASOCIADO A UN FRACCIONAMIENTO DE PAGO	
ELSIF (_RECORD.fraccionamiento) THEN

	SELECT INTO _DEBT_AMOUNT
	round((payment_division.total_debt_amount - SUM (CASE WHEN payment_division_detail.amount ISNULL THEN 0 ELSE payment_division_detail.amount END))::numeric,2)
	FROM payment_division_transaction
	INNER JOIN payment_division ON payment_division_transaction.id_payment_division = payment_division.id  
	INNER JOIN payment_division_detail ON payment_division_detail.id_payment_division = payment_division.id
	INNER JOIN payment_transaction ON payment_division_detail.id_transaction = id_transaction_debit
	LEFT JOIN payment ON payment_transaction.id_payment = payment.id AND payment.status = 2
	WHERE payment_division_transaction.id_transaction = _ID_TRANSACTION 
	AND NOT payment_division.canceled
	GROUP BY payment_division.total_debt_amount;

	IF (_DEBT_AMOUNT <= 0) THEN
		RETURN 2;
	END IF;
	
	RETURN -2;
	
-- ES UN CARGO NORMAL
ELSE

-- RAISE NOTICE '%', _RECORD;

	IF (_RECORD.canceled) THEN
		RETURN -4;
	END IF;
	
	SELECT INTO _DEBT_AMOUNT
	round((_RECORD.original_amount - CASE WHEN SUM(original_amount) ISNULL THEN 0 ELSE SUM(original_amount) END)::numeric, 2)
	FROM payment_transaction
	INNER JOIN transaction ON transaction.id = id_transaction_credit
	LEFT JOIN payment ON payment_transaction.id_payment = payment.id AND payment.status = 2
	WHERE id_transaction_debit = _ID_TRANSACTION
	AND NOT transaction.canceled;

	-- QUITAR LUEGO

	IF (_DEBT_AMOUNT = 0.01) THEN

		_DEBT_AMOUNT = ROUND(_DEBT_AMOUNT::NUMERIC, 1);
	
	END IF;

	-- FIN QUITAR LUEGO
	
	IF (_DEBT_AMOUNT <= 0) THEN
		RETURN 3;
	END IF;

	-- RAISE NOTICE '_DEBT_AMOUNT: %', _DEBT_AMOUNT;
	
	RETURN -3;


END IF;

END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.paid_transaction(bigint)
  OWNER TO postgres;
COMMENT ON FUNCTION appweb.paid_transaction(bigint) IS 'RETURNS
-4: transaccion sin convenio o fraccionamiento cancelada
-3: transaccion sin convenio o fraccionamiento sin pagar
-2: transaccion de fraccionamiento sin pagar
-1: transaccion de convenio sin pagar
0: transaccion no encontrada
1: transaccion de convenio pagada
2: transaccion de fraccionamiento pagada
3: transaccion sin convenio o fraccionamiento pagada
4: transaccion de credito
';
