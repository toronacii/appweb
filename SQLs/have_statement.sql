-- Function: appweb.have_statement(bigint, boolean, integer, boolean)

-- DROP FUNCTION appweb.have_statement(bigint, boolean, integer, boolean);

CREATE OR REPLACE FUNCTION appweb.have_statement(bigint, boolean, integer, boolean, integer DEFAULT NULL)
  RETURNS bigint AS
$BODY$
DECLARE
-- PARAMETROS
	_ID_TAX ALIAS FOR $1;
	_TYPE ALIAS FOR $2; -- FALSE: estimada; TRUE: definitiva
	_FISCAL_YEAR ALIAS FOR $3;
	_SAVE_LIQUID ALIAS FOR $4; -- FALSE save; TRUE liquid
	_MONTH integer = COALESCE($5, 0);

-- VARIABLES LOCALES
	_ID_STATEMENT bigint;
BEGIN
	IF (_SAVE_LIQUID) THEN -- statement
		SELECT INTO _ID_STATEMENT id
		FROM statement
		WHERE id_tax = _ID_TAX
		AND fiscal_year = _FISCAL_YEAR
		AND CASE WHEN _TYPE THEN type IN (2,5) ELSE type IN (0,3) END
		AND status = 2
		AND NOT canceled
		AND NOT estimated_sterile
		AND COALESCE(month, 0) = _MONTH;
		
	ELSE -- statement_form_ae
		SELECT INTO _ID_STATEMENT id
		FROM statement_form_ae
		WHERE id_tax = _ID_TAX
		AND fiscal_year = _FISCAL_YEAR
		AND statement_type = _TYPE
		AND tax_total_form IS NOT NULL
		AND NOT canceled
		AND COALESCE(month, 0) = _MONTH;
	END IF;

	IF (NOT FOUND) THEN 
		RETURN 0;
	END IF;
	RETURN _ID_STATEMENT;
END;   

$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.have_statement(bigint, boolean, integer, boolean, integer)
  OWNER TO postgres;
