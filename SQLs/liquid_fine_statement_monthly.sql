-- Function: appweb.liquid_fine_statement_monthly(bigint, integer)

-- DROP FUNCTION appweb.liquid_fine_statement_monthly(bigint, integer);

CREATE OR REPLACE FUNCTION appweb.liquid_fine_statement_monthly(bigint, integer)
  RETURNS void AS
$BODY$ 
DECLARE

-- PARAMETROS

	_ID_STTM ALIAS FOR $1;
	_MONTH ALIAS FOR $2;
	
-- VARIABLES LOCALES

	_DATE_MIN_REPETITION date = '2015-02-01'; -- PERÍODO DE DECLARACION DEFINITIVA MENSUAL 2015
	_STTM statement%ROWTYPE;
	_TAX_UNIT record;
	_AMOUNT double precision;
	_CONCEPT text;
	_EXTEMP boolean;
	_TOTAL_REPETITION integer = 0;
	_HAVE_NOT_PERMISED boolean;
	_TAX_UNIT_PER_MONTH integer = 3;
	_MAX_TAX_UNIT integer = 30;
	_MAX_REPETITION integer = (_MAX_TAX_UNIT / _TAX_UNIT_PER_MONTH)::int;
BEGIN

-- BUSCAR REGISTRO DE DECLARACION

	SELECT INTO _STTM *
	FROM statement
	WHERE id = _ID_STTM;

	IF (NOT FOUND) THEN RETURN; END IF;

-- UNIDAD ACTUAL PARA MULTAS

	SELECT INTO _TAX_UNIT id, value FROM appweb.tax_unit(EXTRACT('YEAR' FROM CURRENT_DATE)::int);


-- MULTA POR DECLARACION EXTEMPORANEA

	IF (_STTM.extemp) THEN

		-- CALCULAR REINCIDENCIA DE EXTEMPORANEA
		/*
		SELECT INTO _TOTAL_REPETITION
		COUNT(*)
		FROM statement,
		( -- ULTIMA DECLARACION PAGADA A TIEMPO
			SELECT id, created, fiscal_year, month, type
			FROM statement 
			WHERE id_tax = _STTM.id_tax
			AND month IS NOT NULL
			AND NOT canceled
			AND status = 2
			AND statement_date >= _DATE_MIN_REPETITION
			AND NOT(COALESCE(extemp, false))
			ORDER BY created DESC
			LIMIT 1
		) AS t

		WHERE id_tax = _STTM.id_tax
		AND statement.month IS NOT NULL
		AND NOT canceled
		AND status = 2
		AND statement_date >= _DATE_MIN_REPETITION
		AND statement.statement_date > t.created;
		*/


		FOR _EXTEMP IN
			SELECT extemp
			FROM statement 
			WHERE id_tax = _STTM.id_tax
			AND month IS NOT NULL
			AND NOT canceled
			AND status = 2
			AND statement_date >= _DATE_MIN_REPETITION
			ORDER BY fiscal_year DESC
		LOOP
			IF NOT(_EXTEMP) THEN EXIT; END IF;
			
			_TOTAL_REPETITION = _TOTAL_REPETITION + 1;

		END LOOP;
		
		IF (_TOTAL_REPETITION > _MAX_REPETITION) THEN _TOTAL_REPETITION = _MAX_REPETITION; END IF;

		_AMOUNT =  ROUND((_TAX_UNIT_PER_MONTH * _TOTAL_REPETITION * _TAX_UNIT.value)::numeric, 2 );
		_CONCEPT = 'Multa por DJM Extemporánea de ' || 
							TO_CHAR(('2015-' || _STTM.month || '-01')::date, 'TMMonth') || ' ' ||  
							_STTM.fiscal_year || ' (' || 
							_TAX_UNIT_PER_MONTH * _TOTAL_REPETITION || ' U.T.)';
		

		INSERT INTO transaction(id_transaction_type, id_user, id_tax, id_statement, id_tax_unit, application_date, amount, concept, debt_status, canceled, original_amount, expiry_date)
		VALUES (4, 198, _STTM.id_tax, _ID_STTM, _TAX_UNIT.id, CURRENT_DATE, _AMOUNT, _CONCEPT, 1, false, _AMOUNT, CURRENT_DATE);

	END IF;

-- MULTA POR ACTIVIDADES NO PERMISADAS

	_TOTAL_REPETITION = 0;

	FOR _HAVE_NOT_PERMISED IN
		SELECT SUM((NOT permised)::int)::int::boolean
		FROM statement
		INNER JOIN statement_detail ON id_statement = statement.id
		WHERE id_tax = _STTM.id_tax
		AND month IS NOT NULL
		AND NOT canceled
		AND status = 2
		AND statement_date >= _DATE_MIN_REPETITION
		GROUP BY statement.id, fiscal_year
		ORDER BY fiscal_year DESC
	LOOP
		IF NOT (_HAVE_NOT_PERMISED) THEN EXIT; END IF;
		
		_TOTAL_REPETITION = _TOTAL_REPETITION + 1;

	END LOOP;

	IF (_TOTAL_REPETITION > 0) THEN

		IF (_TOTAL_REPETITION > 4) THEN _TOTAL_REPETITION = 4; END IF;

		_AMOUNT = ROUND((50 * _TOTAL_REPETITION * _TAX_UNIT.value)::numeric, 2 );
		_CONCEPT = 'Multa por anexo de ramo según Art 138 (' || (50 * _TOTAL_REPETITION) || ' U.T.)';
		
		INSERT INTO transaction(id_transaction_type, id_user, id_tax, id_statement, id_tax_unit, application_date, amount, concept, debt_status, canceled, original_amount, expiry_date)
		VALUES (275, 198, _STTM.id_tax, _ID_STTM, _TAX_UNIT.id, CURRENT_DATE, _AMOUNT, _CONCEPT, 1, false, _AMOUNT, CURRENT_DATE);
		
	END IF;

	RETURN;
	

END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.liquid_fine_statement_monthly(bigint, integer)
  OWNER TO postgres;
