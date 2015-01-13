-- Function: appweb.get_tax_additional_information(bigint)

-- DROP FUNCTION appweb.get_tax_additional_information(bigint)

CREATE OR REPLACE FUNCTION appweb.get_tax_additional_information(bigint)
  RETURNS text AS
$BODY$

DECLARE
	-- PARAMETROS
	_ID_TAX ALIAS FOR $1;

	-- LOCALES
	_SERIAL_BODYWORK text;

BEGIN

	-- BUSCAR PLACA DE VEHICULO
	
	SELECT INTO _SERIAL_BODYWORK
	'{"placa": "' || additional_value_tax.string_value || '"}'
	FROM additional_value_tax 
	INNER JOIN additional_field_tax_type ON additional_value_tax.id_additional_field_tax_type = additional_field_tax_type.id
	WHERE additional_value_tax.id_tax = _ID_TAX 
	AND additional_field_tax_type.id = 19;

	
	RETURN _SERIAL_BODYWORK;

END;


$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.get_tax_additional_information(bigint) OWNER TO postgres;

