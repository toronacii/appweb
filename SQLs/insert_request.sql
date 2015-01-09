-- Function: appweb.insert_request(bigint)

-- DROP FUNCTION appweb.insert_request(bigint);

CREATE OR REPLACE FUNCTION appweb.insert_request(bigint)
  RETURNS bigint AS
$BODY$

DECLARE

-- PARAMETROS

	_ID_TAX ALIAS FOR $1;

-- LOCALES

	_QUARTER appweb.quarter%rowtype;
	_ID_REQUEST bigint;
	_TAX RECORD;
	_ID_TASAS_TRAMITES bigint;

BEGIN

-- INHABILITAR TEMPORALMENTE

	-- RETURN -3;


-- TIENE UN CONVENIO DE PAGO

	PERFORM id
	FROM payment_agreement
	WHERE id_tax = _ID_TAX AND now()::date <= end_date;

	IF (FOUND) THEN
		RETURN -1;
	END IF;

-- INFORMACIÓN DE TAX

	SELECT INTO _TAX
	id_tax_type,
	CASE WHEN id_tax_type = 1 THEN 5 ELSE 10 END AS id_request_type,
	taxpayer.firm_name,
	address,
	CASE WHEN land_registry ISNULL OR land_registry = '' THEN land_registry_new ELSE land_registry END AS land_registry
	FROM appweb.tax
	INNER JOIN taxpayer ON id_taxpayer = taxpayer.id
	WHERE tax.id = _ID_TAX;

-- ACTIVIDADES ECONOMICAS (ARREGLAR PARA FEBRERO 2015)

	IF (_TAX.id_tax_type = 1) THEN

		IF (CURRENT_DATE < '2015-02-01') THEN

			_QUARTER.quarter = 1;
			_QUARTER.amount = 0;
			_QUARTER.last_date = ((EXTRACT('YEAR' FROM CURRENT_DATE) || '-' || EXTRACT('MONTH' FROM CURRENT_DATE) + 1 || '-01')::date - '1 days'::interval)::date;

		END IF;

	ELSE

		SELECT INTO _QUARTER *
		FROM appweb.quarter_valid(_ID_TAX);

	END IF;

-- TIENE ULTIMO TRIMESTRE DE PAGO

	IF (_QUARTER.quarter ISNULL) THEN
		RETURN -2;
	END IF;

-- ESTA SOLVENTE

	IF (appweb.total_debito(_ID_TAX) > 0)
		THEN RETURN 0;
	END IF;

-- INSERTAR REQUEST

	INSERT INTO request(id_tax, id_request_type, request_date, status,privilege, id_user, is_web, deleted)
	VALUES(_ID_TAX, _TAX.id_request_type, now(), 'Aprobado', 'REQUEST_PRINT', 198, true, false)
	RETURNING id INTO _ID_REQUEST;

-- REQUESTED_QUARTER

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'REQUESTED_QUARTER', _QUARTER.quarter);

-- QUARTER_AMOUNT

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'QUARTER_AMOUNT', _QUARTER.amount);

-- IS_SOLVENT

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'IS_SOLVENT', 'true');

-- APPROVAL DATE

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'APPROVAL_DATE', CURRENT_DATE::text);

-- EXPIRY_DATE

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'EXPIRY_DATE', _QUARTER.last_date);

-- TAXPAYER_FIRM_NAME

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'TAXPAYER_FIRM_NAME', _TAX.firm_name);

-- TAX_LAND_REGISTRY

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'TAX_LAND_REGISTRY', _TAX.land_registry);

-- TAX_ADDRESS

	INSERT INTO request_parameter(id_request, param_name, param_value)
	VALUES(_ID_REQUEST, 'TAX_ADDRESS', _TAX.address);

-- GUARDAR TASA TRAMITE USADA

	SELECT INTO _ID_TASAS_TRAMITES
	tasas_tramites.id
	FROM appweb.tasas_tramites
	INNER JOIN invoice ON invoice.id = tasas_tramites.id_invoice
	INNER JOIN invoice_fee ON invoice_fee.id_invoice = invoice.id
	INNER JOIN appweb.tax ON tax.id = tasas_tramites.id_tax
	WHERE NOT tasas_tramites.used
	AND invoice.status in (6,4)
	AND id_fee_type = CASE WHEN tax.id_tax_type = 1 THEN 6 ELSE 5 END
	AND tasas_tramites.id_tax = _ID_TAX
	ORDER BY tasas_tramites.created
	LIMIT 1;

	IF (FOUND) THEN
		UPDATE appweb.tasas_tramites SET used = TRUE, id_request = _ID_REQUEST WHERE id = _ID_TASAS_TRAMITES;
	END IF;

	RETURN _ID_REQUEST;

END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.insert_request(bigint) OWNER TO postgres;
COMMENT ON FUNCTION appweb.insert_request(bigint) IS '
DEVUELVE EL ID_REQUEST SI ES EXITOSO, SINO DEVUELVE < 0

 0: NO ESTÁ SOLVENTE
-1: TIENE UN CONVENIO DE PAGO POR CANCELAR
-2: NO HA PAGADO EL ULTIMO AFORO EXIGIBLE
-3: INHABILITADO TEMPORALMENTE';