-- Function: appweb.save_statement(bigint, boolean, integer, text[], integer)

-- DROP FUNCTION appweb.save_statement(bigint, boolean, integer, text[], integer);

CREATE OR REPLACE FUNCTION appweb.save_statement(bigint, boolean, integer, text[], integer DEFAULT NULL::integer)
  RETURNS bigint AS
$BODY$
DECLARE
-- PARAMETROS
	_ID_TAX ALIAS FOR $1;
	_TYPE ALIAS FOR $2; -- FALSE: estimada; TRUE: definitiva;
	_FISCAL_YEAR ALIAS FOR $3;
	_ACTIVITIES ALIAS FOR $4; -- [[id_tax_classifier,amount], ...]
	_MONTH ALIAS FOR $5;

-- VARIABLES LOCALES

	_ID_TAXPAYER bigint;
	_ID_STATEMENT_FORM bigint;
	_NEW_STATEMENT boolean = TRUE;
	_NUMBER_STATEMENT character varying(15);
	_RECORD_TAX_CLASSIFIER record;
	_TAX_UNIT double precision;
	_MINIMUN_TAXABLE smallint = 25;
	_CAUSED_TAX_FORM double precision;
	_CAUSED_TOTAL_FORM double precision = 0;
	_TAX_TOTAL_FORM double precision = 0;
	_DIFERENCE double precision;
	_SUBSTR_NUMBER_STATEMENT character varying;
	
BEGIN
	-- BUSCAR _ID_TAXPAYER

	SELECT INTO _ID_TAXPAYER id_taxpayer FROM tax WHERE id = _ID_TAX;

	-- VERIFICAR SI HAY ERRORES DE VALIDACION: ERROR_CODE -1
	PERFORM * FROM appweb.errors_declare_taxpayer_monthly(_ID_TAXPAYER, _TYPE, _FISCAL_YEAR, _MONTH)
	WHERE id_tax = _ID_TAX;

	IF (FOUND) THEN RETURN -1; END IF;

	_ID_STATEMENT_FORM = appweb.have_statement(_ID_TAX, _TYPE, _FISCAL_YEAR, FALSE, _MONTH); -- OTRA FORMA DE LLENAR UNA VARIABLE

	-- VERIFICAMOS SI NO HAY OTRA DECLARACION

	IF (_ID_STATEMENT_FORM > 0)  THEN
		UPDATE statement_form_ae SET canceled = TRUE WHERE id = _ID_STATEMENT_FORM;
	END IF;

	-- BUSCAMOS EL PROXIMO NUMERO DE DECLARACION

	_NUMBER_STATEMENT = appweb.get_number_statement(_TYPE, _FISCAL_YEAR, _MONTH);

	-- INSERTAMOS EL NUEVO REGISTRO
	
	INSERT INTO statement_form_ae (id_user, id_tax, statement_type, code, fiscal_year, canceled, month)
	VALUES (198,_ID_TAX,_TYPE,_NUMBER_STATEMENT,_FISCAL_YEAR, false, _MONTH)
	RETURNING id INTO _ID_STATEMENT_FORM;

	-- BUSCAR UNIDAD TRIBUTARIA

	SELECT INTO _TAX_UNIT value FROM appweb.tax_unit(CASE WHEN _TYPE THEN _FISCAL_YEAR ELSE _FISCAL_YEAR - 1 END);

	-- RECORRER ARRAY DE ACTIVIDADES

	FOR _I IN array_lower(_ACTIVITIES,1) .. array_upper(_ACTIVITIES,1) LOOP

		SELECT INTO _RECORD_TAX_CLASSIFIER
		aliquot, 
		minimun_taxable,
		CASE WHEN permissible_activities.id ISNULL THEN FALSE ELSE TRUE END AS permised
		FROM tax_classifier
		LEFT JOIN permissible_activities ON tax_classifier.id = id_classifier_tax AND id_tax = _ID_TAX
		WHERE tax_classifier.id = _ACTIVITIES[_I][1]::int;

		_CAUSED_TAX_FORM = _ACTIVITIES[_I][2]::double precision * _RECORD_TAX_CLASSIFIER.aliquot / 100;

		-- LOGICA PARA DECLARACIONES HASTA EL 2010

		IF (_FISCAL_YEAR <= 2010 AND _CAUSED_TAX_FORM < _TAX_UNIT * _RECORD_TAX_CLASSIFIER.minimun_taxable) THEN

			_CAUSED_TAX_FORM = _TAX_UNIT * _RECORD_TAX_CLASSIFIER.minimun_taxable;

		END IF;

		INSERT INTO statement_form_detail(id_tax_classifier, id_statement_form, authorized, monto, caused_tax_form)
		VALUES (_ACTIVITIES[_I][1]::int, _ID_STATEMENT_FORM, _RECORD_TAX_CLASSIFIER.permised, _ACTIVITIES[_I][2]::double precision, _CAUSED_TAX_FORM);

		_CAUSED_TOTAL_FORM = _CAUSED_TOTAL_FORM + _ACTIVITIES[_I][2]::double precision;

	END LOOP;

	-- LOGICA PARA DECLARACIONES A PARTIR DEL 2011

	IF (_FISCAL_YEAR > 2010) THEN

		-- ACTUALIZAR MINIMO TRIBUTARIO
	
		UPDATE statement_form_detail SET caused_tax_form = _MINIMUN_TAXABLE * _TAX_UNIT
		WHERE id_statement_form = _ID_STATEMENT_FORM
		AND id = (
			SELECT id FROM statement_form_detail
			WHERE id_statement_form = _ID_STATEMENT_FORM
			AND caused_tax_form = (
				SELECT MAX(caused_tax_form) 
				FROM statement_form_detail 
				WHERE id_statement_form = _ID_STATEMENT_FORM
				HAVING MAX(caused_tax_form) < _MINIMUN_TAXABLE * _TAX_UNIT
				LIMIT 1
			)
			LIMIT 1
		);

	END IF;

	-- ACTUALIZAR tax_total_form y codval statement_form_ae

	SELECT INTO _TAX_TOTAL_FORM
	SUM(caused_tax_form)
	FROM statement_form_detail
	WHERE id_statement_form = _ID_STATEMENT_FORM;

	-- DECLARACIONES MENSUALES

	_SUBSTR_NUMBER_STATEMENT = CASE WHEN _MONTH ISNULL THEN substr(_NUMBER_STATEMENT, 5) ELSE substr(_NUMBER_STATEMENT, 7, 6) END;

	-- RAISE NOTICE '_NUMBER_STATEMENT: %, _SUBSTR_NUMBER_STATEMENT: %', _NUMBER_STATEMENT, _SUBSTR_NUMBER_STATEMENT;

	UPDATE statement_form_ae 
	SET tax_total_form = _TAX_TOTAL_FORM,
	codval = appweb.codval(NOW()::date::text, _SUBSTR_NUMBER_STATEMENT, _TAX_TOTAL_FORM::text)
	WHERE id = _ID_STATEMENT_FORM;

	
	-- VERIFICAR QUE EL MONTO DE LA ESTIMADA NO SEA MENOR A LA DEFINITIVA ANTERIOR
	
	IF (NOT _TYPE) THEN

		SELECT INTO _DIFERENCE
		ROUND((_CAUSED_TOTAL_FORM - income)::numeric, 2)
		FROM statement
		INNER JOIN statement_detail ON id_statement = statement.id
		WHERE id_tax = _ID_TAX
		AND fiscal_year = _FISCAL_YEAR - 2
		AND type IN (2,5)
		AND NOT canceled
		AND status = 2;

		-- RAISE NOTICE '_CAUSED_TOTAL_FORM: %, _DIFERENCE: %',_CAUSED_TOTAL_FORM, _DIFERENCE;
		
		IF (FOUND AND _DIFERENCE < 0) THEN 
			RAISE EXCEPTION 'ERROR'; 
		END IF;
		
	END IF;

	RETURN _ID_STATEMENT_FORM;

EXCEPTION WHEN OTHERS THEN 
	RETURN 0;
	
END;   

$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION appweb.save_statement(bigint, boolean, integer, text[], integer)
  OWNER TO postgres;
