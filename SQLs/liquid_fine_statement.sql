﻿-- Function: appweb.liquid_fine_statement(bigint)

-- DROP FUNCTION appweb.liquid_fine_statement(bigint);

CREATE OR REPLACE FUNCTION appweb.liquid_fine_statement(bigint)
  RETURNS void AS
$BODY$ 
DECLARE

-- PARAMETROS

	_ID_STTM ALIAS FOR $1;
	
-- VARIABLES LOCALES

	_DATE_MIN_REPETITION date = '2013-10-01'; -- PERÍODO DE DECLARACION DEFINITIVA 2014
	_STTM statement%ROWTYPE;
	_TAX_UNIT record;
	_AMOUNT double precision;
	_CONCEPT text = 'Multa por Declaración Extemporánea para Definitiva año ';
	_EXTEMP boolean;
	_TOTAL_REPETITION integer = 0;
	_HAVE_NOT_PERMISED boolean;
	
	-- POR DEFECTO PARA DECLARACIONES ANUALES

	_EXTEMP_TAX_UNIT_PER_MONTH integer = 10;
	_EXTEMP_MAX_TAX_UNIT integer = 50;
	_EXTEMP_MAX_REPETITION integer = (_EXTEMP_MAX_TAX_UNIT / _EXTEMP_TAX_UNIT_PER_MONTH)::int;

	_PERMISED_TAX_UNIT_PER_MONTH integer = 50;
	_PERMISED_MAX_TAX_UNIT integer = 200;
	_PERMISED_MAX_REPETITION integer = (_PERMISED_MAX_TAX_UNIT / _PERMISED_TAX_UNIT_PER_MONTH)::int;

BEGIN

-- BUSCAR REGISTRO DE DECLARACION

	SELECT INTO _STTM *
	FROM statement
	WHERE id = _ID_STTM;

	IF (NOT FOUND) THEN RETURN; END IF;

-- DECLARACION MENSUAL

	IF (_STTM.month IS NOT NULL) THEN
		
		_DATE_MIN_REPETITION = '2015-02-01';
		_EXTEMP_TAX_UNIT_PER_MONTH = 4;
		_EXTEMP_MAX_TAX_UNIT = 40;
		_EXTEMP_MAX_REPETITION = (_EXTEMP_MAX_TAX_UNIT / _EXTEMP_TAX_UNIT_PER_MONTH)::int;

		_PERMISED_TAX_UNIT_PER_MONTH = 50;
		_PERMISED_MAX_TAX_UNIT = 200;
		_PERMISED_MAX_REPETITION = (_PERMISED_MAX_TAX_UNIT / _PERMISED_TAX_UNIT_PER_MONTH)::int;

		_CONCEPT = 'Multa por Declaración Mensual Extemporánea de ' || TO_CHAR(('2015-' || _STTM.month || '-01')::date, 'TMMonth') || ' ';

	END IF;

-- UNIDAD ACTUAL PARA MULTAS

	SELECT INTO _TAX_UNIT id, value FROM appweb.tax_unit(EXTRACT('YEAR' FROM CURRENT_DATE)::int);


-- MULTA POR DECLARACION EXTEMPORANEA

	IF (_STTM.extemp) THEN

		FOR _EXTEMP IN

			SELECT extemp
			FROM statement 
			WHERE id_tax = _STTM.id_tax
			AND CASE WHEN _STTM.type IN (0,3) THEN type IN (0,3) ELSE 
						CASE WHEN _STTM.month ISNULL THEN type IN (2,5) ELSE month IS NOT NULL END 
					END
			AND NOT canceled
			AND status = 2
			AND statement_date >= _DATE_MIN_REPETITION
			ORDER BY fiscal_year DESC, month DESC

		LOOP
			IF NOT(_EXTEMP) THEN EXIT; END IF;
			
			_TOTAL_REPETITION = _TOTAL_REPETITION + 1;

		END LOOP;
		
		IF (_TOTAL_REPETITION > _EXTEMP_MAX_REPETITION) THEN _TOTAL_REPETITION = _EXTEMP_MAX_REPETITION; END IF;

		_AMOUNT =  ROUND((_EXTEMP_TAX_UNIT_PER_MONTH * _TOTAL_REPETITION * _TAX_UNIT.value)::numeric, 2 );
		_CONCEPT = _CONCEPT || _STTM.fiscal_year || ' (' || _EXTEMP_TAX_UNIT_PER_MONTH * _TOTAL_REPETITION || ' U.T.)';
		
		IF (_STTM.estimated_sterile) THEN
			_CONCEPT = 'Multa por Declaración Extemporánea para Estimada año ' || _STTM.fiscal_year || ' (' || 10 * _TOTAL_REPETITION || ' U.T.)';
		END IF;
		
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
		AND CASE WHEN _STTM.type IN (0,3) THEN type IN (0,3) ELSE 
					CASE WHEN _STTM.month ISNULL THEN type IN (2,5) ELSE month IS NOT NULL END 
				END
		AND NOT canceled
		AND status = 2
		AND statement_date >= _DATE_MIN_REPETITION
		GROUP BY statement.id, fiscal_year
		ORDER BY fiscal_year DESC, month DESC

	LOOP
		IF NOT (_HAVE_NOT_PERMISED) THEN EXIT; END IF;
		
		_TOTAL_REPETITION = _TOTAL_REPETITION + 1;

	END LOOP;

	RAISE NOTICE '_TOTAL_REPETITION: %', _TOTAL_REPETITION;

	IF (_TOTAL_REPETITION > 0) THEN

		IF (_TOTAL_REPETITION > _PERMISED_MAX_REPETITION) THEN _TOTAL_REPETITION = _PERMISED_MAX_REPETITION; END IF;

		_AMOUNT = ROUND((_PERMISED_TAX_UNIT_PER_MONTH * _TOTAL_REPETITION * _TAX_UNIT.value)::numeric, 2 );
		_CONCEPT = 'Multa por anexo de ramo según Art 138 (' || _PERMISED_TAX_UNIT_PER_MONTH * _TOTAL_REPETITION || ' U.T.)';
		
		INSERT INTO transaction(id_transaction_type, id_user, id_tax, id_statement, id_tax_unit, application_date, amount, concept, debt_status, canceled, original_amount, expiry_date)
		VALUES (275, 198, _STTM.id_tax, _ID_STTM, _TAX_UNIT.id, CURRENT_DATE, _AMOUNT, _CONCEPT, 1, false, _AMOUNT, CURRENT_DATE);
		
	END IF;

	RETURN;
	

END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.liquid_fine_statement(bigint)
  OWNER TO postgres;
