-- Function: appweb.statement_missing(bigint, date)

-- DROP FUNCTION appweb.statement_missing(bigint, date);

CREATE OR REPLACE FUNCTION appweb.statement_missing(bigint, date DEFAULT NULL)
  RETURNS SETOF text AS
$BODY$ 
DECLARE

-- PARAMETROS
	_ID_TAX ALIAS FOR $1;	
	_DATE_NOW date := CASE WHEN $2 ISNULL THEN NOW()::DATE ELSE $2 END;
	
-- LOCALES
	
	_INITIAL_YEAR int;
	_YEAR_NOW int := EXTRACT('YEAR' FROM _DATE_NOW);

-- CONSTANTES

	_INITIAL_EVALUATE_YEAR int = 2009;
	
BEGIN

-- CREAR Y LLENAR TABLA TEMPORAL DE DECLARACIONES PARA ESTE TAX

CREATE TEMPORARY TABLE TEMP_statement(id bigint, fiscal_year int, type smallint) ON COMMIT DROP;

INSERT INTO TEMP_statement
SELECT statement.id, fiscal_year, type
FROM statement
WHERE id_tax = _ID_TAX 
AND status = 2
AND NOT canceled;

-- LLENAR EL AÑO DE INICIO DE ACTIVIDADES

SELECT INTO _INITIAL_YEAR
EXTRACT('YEAR' FROM real_initial_date)
FROM appweb.tax WHERE id = _ID_TAX;

IF (_INITIAL_YEAR < _INITIAL_EVALUATE_YEAR) THEN _INITIAL_YEAR = _INITIAL_EVALUATE_YEAR; END IF; 

FOR _YEAR_EVALUATE IN REVERSE _YEAR_NOW .. _INITIAL_YEAR LOOP

	/*

	-- ESTIMADA DEL AÑO SIGUIENTE O DEL AÑO EN CURSO

	IF ((_YEAR_EVALUATE = _YEAR_NOW AND EXTRACT('MONTH' FROM _DATE_NOW) >= 10) OR (_YEAR_EVALUATE + 1 = _YEAR_NOW)) THEN

		PERFORM id FROM TEMP_statement
		WHERE fiscal_year = _YEAR_EVALUATE + 1
		AND type IN (0,3);

		IF (NOT FOUND AND (_YEAR_EVALUATE + 1) < 2015) THEN
		
			RETURN NEXT 'Falta la declaración estimada del año ' || (_YEAR_EVALUATE + 1);

		END IF;
	
	END IF;

	*/

	-- DEFINITIVAS
	
	IF (_YEAR_EVALUATE != _INITIAL_YEAR) THEN


		PERFORM id FROM TEMP_statement
		WHERE fiscal_year = _YEAR_EVALUATE - 1
		AND type IN (2,5);

		IF (NOT FOUND) THEN
	
			RETURN NEXT 'Falta la declaración definitiva del año ' || (_YEAR_EVALUATE - 1);
		
		END IF;
	END IF;
	
END LOOP;

RETURN;
END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION appweb.statement_missing(bigint, date) OWNER TO postgres;
