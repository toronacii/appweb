-- Function: appweb.get_interest_rate(date)

-- DROP FUNCTION appweb.get_interest_rate(date);

CREATE OR REPLACE FUNCTION appweb.get_interest_rate(date DEFAULT ('now'::text)::date)
  RETURNS interest_rate AS
$BODY$
DECLARE

-- PARAMETROS

	_DATE ALIAS FOR $1;

-- LOCALES

	_RETURN interest_rate%ROWTYPE;

BEGIN

	SELECT INTO _RETURN 
	id, 
	date, 
	calc_percent AS percent,
	created,
	modified
	FROM appweb.interest_rate_new 
	WHERE date <= _DATE 
	ORDER BY date DESC
	LIMIT 1;

RETURN _RETURN;

END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.get_interest_rate(date) OWNER TO postgres;
