-- Function: appweb.get_number_statement(boolean, integer, integer)

-- DROP FUNCTION appweb.get_number_statement(boolean, integer, integer);

CREATE OR REPLACE FUNCTION appweb.get_number_statement(boolean, integer, integer DEFAULT NULL::integer)
  RETURNS text AS
$BODY$
DECLARE

-- PARAMETROS
	_TYPE ALIAS FOR $1; -- FALSE: ESTIMADA, TRUE: DEFINITIVA
	_FISCAL_YEAR ALIAS FOR $2;
	_MONTH ALIAS FOR $3;

-- VARIABLES LOCALES

	_NUMBER_STATEMENT character varying(15); -- "E13-10008296"
	_NAME_SEQUENCE character varying(10);
	_START bigint;

BEGIN

	_NAME_SEQUENCE = 'E' || SUBSTRING(_FISCAL_YEAR::text, 3);
	
	-- DEFINITIVA
	IF (_TYPE) THEN 
		IF (_MONTH ISNULL) THEN
			_NAME_SEQUENCE = 'D' || SUBSTRING(_FISCAL_YEAR::text, 3);
		ELSE
			_NAME_SEQUENCE = 'DJM_' || SUBSTR(TO_CHAR(('2000-' || _MONTH || '-01')::date, 'TMMonth'), 1, 3) || '_' || SUBSTRING(_FISCAL_YEAR::text, 3); 
		END IF;
	END IF;

	-- RAISE NOTICE '_NAME_SEQUENCE: %', _NAME_SEQUENCE;
	
	IF NOT EXISTS (SELECT 0 FROM pg_class WHERE relkind = 'S' AND oid::regclass::text = 'appweb.' || quote_ident(LOWER(_NAME_SEQUENCE))) THEN

		SELECT INTO _START t.start FROM (
			SELECT LOWER(substring(form_number,1,3)) AS prefix, MAX(substring(form_number,6)::bigint) + 1 AS start
			FROM statement WHERE form_number LIKE '___-1%' AND id_user = 198
			GROUP BY LOWER(substring(form_number,1,3))
		) AS t
		WHERE t.prefix = quote_ident(LOWER(_NAME_SEQUENCE));

		IF (NOT FOUND) THEN _START = 1; END IF;
		
		EXECUTE 'CREATE SEQUENCE appweb.' || LOWER(_NAME_SEQUENCE) || '
		INCREMENT 1
		MINVALUE 1
		MAXVALUE 9223372036854775807
		START ' || _START || '
		CACHE 1';
	END IF;


	IF (_MONTH IS NOT NULL) THEN

		SELECT INTO _NUMBER_STATEMENT 
		'DM' || SUBSTRING(_FISCAL_YEAR::text, 3) || '-1' || 
		LPAD(NEXTVAL(('appweb.' || LOWER(_NAME_SEQUENCE))::regclass)::character varying, 6, '0')
		|| '-' || LPAD(_MONTH::text, 2, '0');
	ELSE
		SELECT INTO _NUMBER_STATEMENT 
		_NAME_SEQUENCE || '-1' || 
		LPAD(NEXTVAL(('appweb.' || LOWER(_NAME_SEQUENCE))::regclass)::character varying, 7, '0'); 
		
	END IF;
		

	RETURN _NUMBER_STATEMENT;
END
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.get_number_statement(boolean, integer, integer)
  OWNER TO postgres;

