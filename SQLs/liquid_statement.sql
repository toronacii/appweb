-- Function: appweb.liquid_statement(bigint)

-- DROP FUNCTION appweb.liquid_statement(bigint);

CREATE OR REPLACE FUNCTION appweb.liquid_statement(bigint)
  RETURNS integer AS
$BODY$ 
DECLARE

-- PARAMETROS
	
	_ID_STTM_FORM ALIAS FOR $1;
	
-- VARIABLES LOCALES

	_STTM_FORM statement_form_ae%ROWTYPE;
	_ID_TAXPAYER bigint;
	_DATE_LIMIT date;
	_DAYS_EXTENSION smallint = 0;
	_EXTEMP boolean;
	_TYPE smallint;
	_ID_STTM bigint;
	_TAX_UNIT record;
	_AMOUNT double precision;
	_CONCEPT character varying;
	_ID_TRANSACTION_TYPE integer;
	_APPLICATION_DATE date;
	_EXPIRY_DATE date;
	_DEBT_STATUS smallint;
	_TOTAL_TAX_UNIT_FINE smallint;
	_DATE_MIN_REPETITION date = '2013-10-01'; -- PERÍODO DE DECLARACION ESTIMADA 2014
	_TOTAL_REPETITION smallint;
	_ID_INTEREST bigint;
	_DEBT_AMOUNT double precision;
	_STATEMENT_DATE date := now()::date;
	_IS_STATEMENT_ACTUAL boolean;
	_ID_TAX_DISCOUNT bigint;
	
BEGIN

-- DATOS DE TABLA statement_form_ae: ERROR_CODE 0

	SELECT INTO _STTM_FORM  *
	FROM statement_form_ae 
	WHERE id = _ID_STTM_FORM AND NOT canceled;

	IF (NOT FOUND) THEN RETURN 0; END IF;

-- BUSCAR _ID_TAXPAYER

	SELECT INTO _ID_TAXPAYER id_taxpayer FROM tax WHERE id = _STTM_FORM.id_tax;

-- VERIFICAR SI HAY ERRORES DE VALIDACION: ERROR_CODE -1

	PERFORM * FROM appweb.errors_declare_taxpayer_monthly(_ID_TAXPAYER, _STTM_FORM.statement_type, _STTM_FORM.fiscal_year)
	WHERE id_tax = _STTM_FORM.id_tax;

	IF (FOUND) THEN RETURN -1; END IF;


	IF (_STTM_FORM.statement_type) THEN -- DECLARACION DEFINITIVA
		_TYPE = 2;
		_DATE_LIMIT = ((_STTM_FORM.fiscal_year + 1) || '-01-31')::date; -- + CAST(_DAYS_EXTENSION || ' days' AS INTERVAL)
		_IS_STATEMENT_ACTUAL = (_STTM_FORM.fiscal_year + 1 = EXTRACT('YEAR' FROM now()));
	ELSE -- DECLARACION ESTIMADA
		_TYPE = 0;
		_DATE_LIMIT = ((_STTM_FORM.fiscal_year - 1) || '-10-31')::date;
		_IS_STATEMENT_ACTUAL = (_STTM_FORM.fiscal_year - 1 = EXTRACT('YEAR' FROM now()));
	END IF;

-- TIEMPO DE PRORROGA SI SE PAGÓ EL 4TO TRIMESTRE A TIEMPO

	IF (_IS_STATEMENT_ACTUAL AND now()::date > _DATE_LIMIT) THEN

		SELECT INTO _DEBT_AMOUNT
		round((t.original_amount - CASE WHEN SUM(t2.original_amount) ISNULL THEN 0 ELSE SUM(t2.original_amount) END)::numeric, 2)
		FROM transaction t
		INNER JOIN payment_transaction ON t.id = id_transaction_debit
		INNER JOIN transaction t2 ON t2.id = id_transaction_credit
		LEFT JOIN payment ON payment_transaction.id_payment = payment.id AND payment.status = 2 AND payment.date <= _DATE_LIMIT
		WHERE t.id_tax = _STTM_FORM.id_tax
		AND t.id_transaction_type = 1
		AND t.expiry_date = _DATE_LIMIT
		AND NOT t.canceled
		AND NOT t2.canceled
		GROUP BY t.original_amount;

		IF (_DEBT_AMOUNT <= 0) THEN
			_DAYS_EXTENSION = 14;
			_STATEMENT_DATE = _DATE_LIMIT;
		END IF;
	END IF;
	
	_DATE_LIMIT = (_DATE_LIMIT + CAST(_DAYS_EXTENSION || ' days' AS INTERVAL))::date;

-- VALIDAR EXTEMPORANEA

	IF (now()::date > _DATE_LIMIT) THEN 
		_STATEMENT_DATE = now()::date; 
		_EXTEMP = TRUE;
	END IF;
	
	-- RAISE EXCEPTION '%', _DATE_LIMIT;

-- ACTUALIZAR TAX_DISCOUNT SI APLICA DESCUENTO POR ART. 219

	UPDATE tax_discount
	SET applied = TRUE
	WHERE id_tax = _STTM_FORM.id_tax
	AND statement_type = _STTM_FORM.statement_type
	AND fiscal_year = _STTM_FORM.fiscal_year
	AND NOT applied
	RETURNING id INTO _ID_TAX_DISCOUNT;

	-- RAISE NOTICE '_ID_TAX_DISCOUNT: %',_ID_TAX_DISCOUNT;

-- INSERTAR DATOS EN STATEMENT

	INSERT INTO statement(id_user, id_receive_user, id_tax, form_number,statement_date, tax_total, type, status, extemp, fiscal_year, canceled, id_statement_form, id_tax_discount)
	VALUES(198, 198, _STTM_FORM.id_tax, _STTM_FORM.code, _STATEMENT_DATE, _STTM_FORM.tax_total_form, _TYPE, 2, _EXTEMP, _STTM_FORM.fiscal_year, false, _ID_STTM_FORM, _ID_TAX_DISCOUNT) 
	RETURNING id INTO _ID_STTM;

-- INSERTAR EN TABLA STATEMENT_DETAIL

	INSERT INTO statement_detail(id_classifier_tax, id_statement, permised, income, caused_tax)
	SELECT id_tax_classifier, _ID_STTM, authorized, monto, caused_tax_form
	FROM statement_form_detail 
	WHERE id_statement_form = _ID_STTM_FORM;

	-- RAISE EXCEPTION '%', _DATE_LIMIT;
	

-- BUSCAR UNIDAD TRIBUTARIA VIGENTE

	SELECT INTO _TAX_UNIT id, value FROM appweb.tax_unit(_STTM_FORM.fiscal_year);

	IF (_STTM_FORM.statement_type) THEN --DECLARACION DEFINITIVA
	
		-- BUSCAR MONTO DE DECLARACION ESTIMADA ANTERIOR PARA SACAR COMPLEMENTO
		
		SELECT INTO _AMOUNT tax_total
		FROM statement
		WHERE id_tax = _STTM_FORM.id_tax
		AND type IN (0,3)
		AND NOT canceled
		AND status = 2
		AND fiscal_year = _STTM_FORM.fiscal_year;

		IF (NOT FOUND) THEN _AMOUNT = 0; END IF;

		_AMOUNT = ROUND((_STTM_FORM.tax_total_form - _AMOUNT)::numeric, 2);
		
		_CONCEPT = 'Complementario por Declaración Definitiva Año ' || _STTM_FORM.fiscal_year;

		IF (_AMOUNT < 0) THEN -- CREDITO PARA EL CONTRIBUYENTE
			_AMOUNT = -1 * _AMOUNT;
			_ID_TRANSACTION_TYPE = 17;
			_APPLICATION_DATE = now()::date;
			_EXPIRY_DATE = now()::date;
			_DEBT_STATUS = NULL;
		ELSE -- DEBITO PARA EL CONTRIBUYENTE
			_ID_TRANSACTION_TYPE = 8;
			_APPLICATION_DATE = ((_STTM_FORM.fiscal_year + 1) || '-03-01')::date;
			_EXPIRY_DATE = ((_STTM_FORM.fiscal_year + 1) || '-03-31')::date;
			_DEBT_STATUS = 1;
		END IF;

		-- ID DEL INTERES

		SELECT INTO _ID_INTEREST 
		id_interest 
		FROM transaction_type
		WHERE id = _ID_TRANSACTION_TYPE;
		
		INSERT INTO transaction(id_transaction_type, id_user, id_tax, id_interest, id_statement, id_tax_unit, application_date, amount, concept, debt_status, canceled, original_amount, expiry_date)
		VALUES (_ID_TRANSACTION_TYPE, 198, _STTM_FORM.id_tax, _ID_INTEREST, _ID_STTM, _TAX_UNIT.id, _APPLICATION_DATE, _AMOUNT, _CONCEPT, _DEBT_STATUS, false, _AMOUNT, _EXPIRY_DATE);

	ELSE --DECLARACION ESTIMADA
	
		_AMOUNT := ROUND((_STTM_FORM.tax_total_form / 4)::numeric, 2);

		-- ID DEL INTERES

		SELECT INTO _ID_INTEREST 
		id_interest 
		FROM tax 
		INNER JOIN tax_type ON id_tax_type = tax_type.id 
		WHERE tax.id = _STTM_FORM.id_tax;

		FOR _I IN 1 .. 4 LOOP

			IF (_I = 1) THEN
				_APPLICATION_DATE = (_STTM_FORM.fiscal_year || '-01-01')::date;
				_EXPIRY_DATE = (_STTM_FORM.fiscal_year || '-01-31')::date;
			ELSIF (_I = 2) THEN
				_APPLICATION_DATE = (_STTM_FORM.fiscal_year || '-04-01')::date;
				_EXPIRY_DATE = (_STTM_FORM.fiscal_year || '-04-30')::date;
			ELSIF (_I = 3) THEN
				_APPLICATION_DATE = (_STTM_FORM.fiscal_year || '-07-01')::date;
				_EXPIRY_DATE = (_STTM_FORM.fiscal_year || '-07-31')::date;
			ELSIF (_I = 4) THEN
				_APPLICATION_DATE = (_STTM_FORM.fiscal_year || '-10-01')::date;
				_EXPIRY_DATE = (_STTM_FORM.fiscal_year || '-10-31')::date;
			END IF;

			_CONCEPT = 'Trimestre ' || _I;

			INSERT INTO transaction(id_transaction_type, id_user, id_tax, id_interest, id_statement, id_tax_unit, application_date, amount, concept, debt_status, canceled, original_amount, expiry_date)
			VALUES (1, 198, _STTM_FORM.id_tax, _ID_INTEREST, _ID_STTM, _TAX_UNIT.id, _APPLICATION_DATE, _AMOUNT, _CONCEPT, 1, false, _AMOUNT, _EXPIRY_DATE);

		END LOOP;
	END IF;

-- ESTIMADA ESTERIL

	IF (_STTM_FORM.statement_type) THEN

		PERFORM appweb.generate_estimated_sterile(_STTM_FORM.id_tax, _STTM_FORM.fiscal_year);
		
	END IF;

-- MULTAS

	PERFORM appweb.liquid_fine_statement(_ID_STTM);

-- INTERESES

	PERFORM generate_statement_interest(_ID_STTM);

	RETURN _ID_STTM;
	
EXCEPTION WHEN OTHERS THEN 
	RETURN -2;


END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.liquid_statement(bigint) OWNER TO postgres;
