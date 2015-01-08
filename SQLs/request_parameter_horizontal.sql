
CREATE TYPE appweb.TYPE_request_parameter_horizontal AS (
	id_request bigint,
	approval_date character varying, 
	expiry_date character varying, 
	is_solvent character varying, 
	quarter_amount character varying,
	requested_quarter character varying, 
	tax_address character varying, 
	tax_land_registry character varying,
	taxpayer_firm_name character varying
);

-- Function: appweb.request_parameter_horizontal(bigint)

-- DROP FUNCTION appweb.request_parameter_horizontal(bigint);

CREATE OR REPLACE FUNCTION appweb.request_parameter_horizontal(bigint)
  RETURNS appweb.type_request_parameter_horizontal AS
$BODY$

DECLARE

-- PARAMETROS

	_ID_REQUEST ALIAS FOR $1;

-- LOCALES

	_RECORD appweb.TYPE_request_parameter_horizontal;

BEGIN


	SELECT INTO _RECORD 
	* FROM crosstab ('
		SELECT id_request::bigint, param_name AS item_name, param_value AS item_value
		FROM request_parameter 
		WHERE id_request = ' || _ID_REQUEST || '
		ORDER BY param_name	
	');

	RETURN _RECORD;

END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.request_parameter_horizontal(bigint) OWNER TO postgres;
