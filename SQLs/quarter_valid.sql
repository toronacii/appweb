-- Function: appweb.quarter_valid(bigint, date)

-- DROP FUNCTION appweb.quarter_valid(bigint, date);

CREATE OR REPLACE FUNCTION appweb.quarter_valid(bigint, date DEFAULT ('now'::text)::date)
  RETURNS appweb.quarter AS
$BODY$

DECLARE
	-- PARAMETROS
	_ID_TAX ALIAS FOR $1;
	_NOW ALIAS FOR $2;

	-- LOCALES
	_RETURN appweb.quarter%rowtype;

BEGIN

	-- CREAMOS Y LLENAMOS TABLA TEMPORAL PARA GUARDAR LA DATA DE LOS TRIMESTRES PAGOS DE ESTE AÑO

	CREATE TEMPORARY TABLE TEMP_quarters (quarter character varying, amount character varying, last_date character varying, must_paid boolean, paid boolean) ON COMMIT DROP;

	INSERT INTO TEMP_quarters
	SELECT DATE_PART('quarter', application_date)::character varying AS quarter,
	(CASE WHEN transaction.amount ISNULL THEN original_amount ELSE amount END)::character varying AS amount,
	(application_date + '3 months'::interval - '1 days'::interval)::date::character varying AS last_date,
	DATE_PART('quarter', application_date) <= DATE_PART('quarter', _NOW) must_paid,
	appweb.paid_transaction(transaction.id) > 0 AS paid
	FROM tax
	INNER JOIN transaction ON tax.id = transaction.id_tax
	WHERE
	tax.id = _ID_TAX
	AND id_transaction_type = 1
	AND DATE_PART('year',application_date) > DATE_PART('year', _NOW) - 1
	AND NOT("transaction".canceled);


	-- VERIFICAMOS QUE TODOS LOS TRIMESTRES QUE DEBERIAN ESTAR PAGOS LO ESTEN

	PERFORM 1
	FROM TEMP_quarters
	WHERE must_paid OR paid
	HAVING SUM(CASE WHEN paid THEN 0 ELSE 1 END) = 0;

	IF (NOT FOUND) THEN
		RETURN _RETURN;
	END IF;

	-- LLENAMOS LA VARIABLE DE RETORNO CON EL ULTIMO TRIMESTRE PAGO (SEA EXIGIBLE O NO)

	SELECT INTO _RETURN
	quarter, amount, last_date
	FROM TEMP_quarters
	WHERE paid
	ORDER BY quarter DESC
	LIMIT 1;

	RETURN _RETURN;

END;


$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.quarter_valid(bigint, date) OWNER TO postgres;
COMMENT ON FUNCTION appweb.quarter_valid(bigint, date) IS 'DEVUELVE EL TRIMESTRE VALIDO, EL MONTO, Y LA ULTIMA FECHA DE ESE TRIMESTRE';
