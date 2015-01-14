-- Function: appweb.errors_declare_taxpayer_monthly(bigint, boolean, integer, integer)

-- DROP FUNCTION appweb.errors_declare_taxpayer_monthly(bigint, boolean, integer, integer);

CREATE OR REPLACE FUNCTION appweb.errors_declare_taxpayer_monthly(bigint, boolean, integer, integer DEFAULT NULL::integer)
  RETURNS SETOF appweb.type_errors_declare_taxpayer AS
$BODY$ 
DECLARE

-- PARAMETROS

	_ID_TAXPAYER ALIAS FOR $1;
	_TYPE ALIAS FOR $2; -- FALSE: estimada; TRUE: definitiva
	_FISCAL_YEAR ALIAS FOR $3;
	_MONTH ALIAS FOR $4; -- NULL PARA DECLARACIONES ANUALES

-- VARIABLES LOCALES

	_ID_TAX bigint;
	_INITIAL_YEAR int;
	_RECORD_RETURN appweb.type_errors_declare_taxpayer;
	_RECORD_AFOROS record;
	_PAID smallint;
	_COMPLEMENTO double precision;
	_YEAR_TO int;
	_I int = 0;
	_AFFIDAVIT_EVALUATE_DATE date;
	_YEAR_EVALUATE_LOOP int;
	_MONTH_EVALUATE_LOOP int;

-- CONSTANTES

	_INITIAL_EVALUATE_YEAR int = 2009;
	_TYPE_AFFIDAVIT_MONTHY int[] = '{4,7}';

	-- ID TRANSACTIONS QUE SON EXCEPCIONES DE MONTOS ACCESORIOS
	_EXCEPTIONS_MONTOS_ACCESORIOS bigint[] = '{52740,6927640,6927653,96380, 96381, 96382, 5963109, 5963110, 5963136, 5963113, 6245732, 6212269, 6212267, 6212236, 6212243, 6212249}';
	_YEAR_NOW int := EXTRACT('YEAR' FROM now());
	_NAME_MONTHS character varying[] = '{enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre}';
	_TYPE_NEW_ID_TRANSACTION int[] = '{150, 25}';
BEGIN

-- CREAR Y LLENAR TABLA TEMPORAL DE DECLARACIONES PARA ESTE TAXPAYER

CREATE TEMPORARY TABLE TEMP_statement(id bigint, id_tax bigint, fiscal_year int, type smallint, estimated_sterile boolean, tax_total double precision, "month" integer) ON COMMIT DROP;

INSERT INTO TEMP_statement
SELECT statement.id, id_tax, fiscal_year, type, estimated_sterile, tax_total, statement.month
FROM statement
INNER JOIN appweb.tax ON tax.id = id_tax
WHERE id_taxpayer = _ID_TAXPAYER
AND tax.id_tax_type = 1 
AND statement.status = 2
AND NOT statement.canceled;

FOR _ID_TAX, _INITIAL_YEAR IN

	SELECT id, EXTRACT('YEAR' FROM real_initial_date)
	FROM appweb.tax
	WHERE id_taxpayer = _ID_TAXPAYER
	AND tax.id_tax_type = 1

LOOP	

	-- RETURN;
	-- INICIO DE VARIABLES

	IF (_INITIAL_YEAR < _INITIAL_EVALUATE_YEAR) THEN _INITIAL_YEAR = _INITIAL_EVALUATE_YEAR; END IF; 

	RAISE NOTICE '_INITIAL_YEAR: %', _INITIAL_YEAR;

	_RECORD_RETURN.id_tax = _ID_TAX;

	-- DECLARACION EVALUADA DEBE SER CONSISTENTE CON SU FECHA DE INICIO DE ACTIVIDADES
	
	IF ((_TYPE AND _FISCAL_YEAR < _INITIAL_YEAR) OR -- DEFINITIVA
	    (NOT(_TYPE) AND _FISCAL_YEAR <= _INITIAL_YEAR)) -- ESTIMADA
	THEN
		_RECORD_RETURN.id_message := 0;
		_RECORD_RETURN.message := 'La fecha de inicio de actividades es inconsistente con la declaración exigida';
		RETURN NEXT _RECORD_RETURN;
	END IF;

	-- NO DEJAR DECLARAR ESTIMADAS MAYORES O IGUALES A 2015

	IF (NOT(_TYPE) AND _FISCAL_YEAR >= 2015) THEN

		_RECORD_RETURN.id_message := 0;
		_RECORD_RETURN.message := 'No se necesita la declaración estimada ' || _FISCAL_YEAR;
		RETURN NEXT _RECORD_RETURN;
		
	END IF;

	-- DECLARACIONES ANUALES

	IF (_MONTH ISNULL) THEN

		PERFORM id FROM TEMP_statement 
		WHERE id_tax = _ID_TAX
		AND fiscal_year = _FISCAL_YEAR
		AND CASE WHEN _TYPE THEN type IN (2,5) ELSE type IN (0,3) END;

	-- DECLARACIONES MENSUALES
	
	ELSE

		PERFORM id FROM TEMP_statement 
		WHERE id_tax = _ID_TAX
		AND fiscal_year = _FISCAL_YEAR
		AND month = _MONTH;

	END IF;

	IF (FOUND) THEN

		_RECORD_RETURN.id_message := 1;
		_RECORD_RETURN.message := 'Ya existe una declaración para este mismo período';

		RETURN NEXT _RECORD_RETURN;

	END IF;

	-- VERIFICAR SOLVENCIA CON MONTOS ACCESORIOS > 2010

	PERFORM transaction.id 
	FROM transaction
	INNER JOIN transaction_type ON id_transaction_type = transaction_type.id
	WHERE id_tax = _ID_TAX
	AND NOT credit 
	AND NOT canceled
	AND id_transaction_type NOT IN (1, 8, 51, 16)
	AND application_date BETWEEN '2011-01-01' AND now()
	AND appweb.paid_transaction(transaction.id) < 0
	AND transaction.id != ALL('{52740,6927640,6927653,96380, 96381, 96382, 5963109, 5963110, 5963136, 5963113, 6245732, 6212269, 6212267, 6212236, 6212243, 6212249}'::bigint[]);

	IF (FOUND) THEN

		_RECORD_RETURN.id_message := 2;
		_RECORD_RETURN.message := 'Su estado de cuenta no está solvente';

		RETURN NEXT _RECORD_RETURN;

	END IF;

	-- VERIFICAR LA COMPENSACIÓN DE LOS 4 AFOROS TRIMESTRALES (DEFINITIVA 2014) Y ESTIMADA 2014 FALTANTE

	IF (_MONTH ISNULL AND _TYPE AND _FISCAL_YEAR = 2014 AND _YEAR_NOW = 2015) THEN

		-- FALTA ESTIMADA 2014
	/*	
		IF (appweb.have_statement(_ID_TAX, FALSE, 2014, TRUE) = 0) THEN

			_RECORD_RETURN.id_message := 3;
			_RECORD_RETURN.message := 'Falta la declaración estimada del año ' || 2014;

			RETURN NEXT _RECORD_RETURN;

		-- VALIDAR PAGO DE AFOROS TRIMESTRALES
	*/
		
		IF (appweb.have_statement(_ID_TAX, FALSE, 2014, TRUE) > 0) THEN

			FOR _RECORD_AFOROS IN
				SELECT concept, appweb.paid_transaction(id) AS paid
				FROM transaction
				WHERE id_tax = _ID_TAX 
				AND id_transaction_type = 1
				AND EXTRACT('YEAR' FROM expiry_date) = 2014
				ORDER BY concept
			LOOP

				IF (_RECORD_AFOROS.paid < 0 AND _RECORD_AFOROS.paid != -4) THEN

					_RECORD_RETURN.id_message := 2;
					_RECORD_RETURN.message := 'Adeuda el ' || _RECORD_AFOROS.concept || ' del año ' || 2014;

					IF (_RECORD_AFOROS.paid = -1) THEN

						_RECORD_RETURN.message := _RECORD_RETURN.message || ' por un convenio de pago';
						
					ELSIF (_RECORD_AFOROS.paid = -2) THEN

						_RECORD_RETURN.message := _RECORD_RETURN.message || ' por un fraccionamiento de pago';
						
					END IF;

					RETURN NEXT _RECORD_RETURN;
				END IF;

				_I = _I + 1;

			END LOOP;

			IF (_I < 4) THEN
				_RECORD_RETURN.id_message := 2;
				_RECORD_RETURN.message := 'Adeuda algún aforo trimestral del año ' || 2014;
			
				RETURN NEXT _RECORD_RETURN;
			END IF;

		END IF;

	END IF;

	-- VERIFICAR DECLARACIONES DEFINITIVAS FALTANTES (ANUALES HASTA D14)

	_YEAR_TO = CASE WHEN _TYPE THEN _FISCAL_YEAR - 1 ELSE _FISCAL_YEAR - 2 END;

	FOR _YEAR_EVALUATE IN _INITIAL_YEAR .. CASE WHEN _YEAR_TO > 2014 THEN 2014 ELSE _YEAR_TO END LOOP

		-- DECLARACIONES ANUALES

		PERFORM id FROM TEMP_statement
		WHERE id_tax = _ID_TAX
		AND fiscal_year = _YEAR_EVALUATE
		AND type IN (2,5);

		IF (NOT FOUND) THEN
			_RECORD_RETURN.id_message := 3;
			_RECORD_RETURN.message := 'Falta la declaración definitiva del año ' || _YEAR_EVALUATE;

			RETURN NEXT _RECORD_RETURN;

		END IF;

		-- VALIDAR PAGO DE COMPLEMENTO

		IF (_YEAR_EVALUATE >= _INITIAL_EVALUATE_YEAR) THEN
			
			SELECT INTO _PAID appweb.paid_transaction(transaction.id)
			FROM transaction
			INNER JOIN statement ON id_statement = statement.id
			WHERE transaction.id_tax = _ID_TAX 
			AND id_transaction_type IN (8,17)
			AND fiscal_year = _YEAR_EVALUATE
			AND appweb.paid_transaction(transaction.id) != -4;

			IF (NOT FOUND) THEN

				IF (_YEAR_EVALUATE > 2010) THEN

					SELECT INTO _COMPLEMENTO
					ROUND(SUM(CASE WHEN type IN (2,5) THEN tax_total ELSE -1 * tax_total END)::numeric, 2)
					FROM TEMP_statement
					WHERE id_tax = _ID_TAX
					AND fiscal_year = _YEAR_EVALUATE;

					_COMPLEMENTO = CASE WHEN _ID_TAX || '_' || _YEAR_EVALUATE IN ('1001609_2011', '144402_2011') THEN 0 ELSE _COMPLEMENTO END; 

					-- VALIDAR COMPLEMENTO PARA DEFINITIVA 2014 EXIGIBLE EN MARZO 2015

					_COMPLEMENTO = CASE WHEN _YEAR_EVALUATE = 2014 AND COALESCE(_MONTH, 1) < 3 THEN 0 ELSE _COMPLEMENTO END;

					IF (_COMPLEMENTO ISNULL OR _COMPLEMENTO != 0) THEN
					
						_RECORD_RETURN.id_message := 2;
						_RECORD_RETURN.message := 'Adeuda el complemento de la declaracion definitiva del año ' || _YEAR_EVALUATE;

						RETURN NEXT _RECORD_RETURN;
					END IF;

				END IF;

			ELSIF (_PAID < 0) THEN

				_RECORD_RETURN.id_message := 2;
				_RECORD_RETURN.message := 'Adeuda el complemento de la declaracion definitiva del año ' || _YEAR_EVALUATE;

				IF (_PAID = -1) THEN

					_RECORD_RETURN.message := _RECORD_RETURN.message || ' por un convenio de pago';
					
				ELSIF (_PAID = -2) THEN

					_RECORD_RETURN.message := _RECORD_RETURN.message || ' por un fraccionamiento de pago';
					
				END IF;

				RETURN NEXT _RECORD_RETURN;

			END IF;
		END IF;

	END LOOP;

	-- VERIFICAR DECLARACION JURADA MENSUAL

	IF (_MONTH IS NOT NULL AND _FISCAL_YEAR > 2014) THEN

		-- RECORRER AÑOS

		FOR _YEAR_EVALUATE IN 2015 .. _FISCAL_YEAR LOOP

			-- RECORRER MESES

			FOR _MONTH_YEAR IN 1 .. _MONTH LOOP

				_AFFIDAVIT_EVALUATE_DATE = ((_YEAR_EVALUATE || '-' || _MONTH_YEAR || '-01')::date - '1 MONTH'::interval)::date;

				_YEAR_EVALUATE_LOOP = EXTRACT('YEAR' FROM _AFFIDAVIT_EVALUATE_DATE)::int;

				_MONTH_EVALUATE_LOOP = EXTRACT('MONTH' FROM _AFFIDAVIT_EVALUATE_DATE)::int;

				IF (EXTRACT('YEAR' FROM _AFFIDAVIT_EVALUATE) > 2014) THEN
				
					PERFORM id FROM TEMP_statement
					WHERE id_tax = _ID_TAX
					AND fiscal_year = _YEAR_EVALUATE_LOOP
					AND statement.month = _MONTH_EVALUATE_LOOP
					AND type = ANY(_TYPE_AFFIDAVIT_MONTHY);

					-- VERIFICAR DECLARACION

					IF (NOT FOUND) THEN

						_RECORD_RETURN.id_message := 3;
						_RECORD_RETURN.message := 'Falta la declaración jurada mensual de ' || _NAME_MONTHS[_MONTH_EVALUATE_LOOP] || ' del año ' || _YEAR_EVALUATE_LOOP;

						RETURN NEXT _RECORD_RETURN;

					-- VERIFICAR PAGO DE COMPLEMENTO
					
					ELSE

						SELECT INTO _PAID appweb.paid_transaction(transaction.id)
						FROM transaction
						INNER JOIN TEMP_statement ON transaction.id_tax = _ID_TAX AND id_statement = TEMP_statement.id
						WHERE id_transaction_type = ANY(_TYPE_NEW_ID_TRANSACTION)
						AND fiscal_year = _YEAR_EVALUATE_LOOP
						AND TEMP_statement.month = _MONTH_EVALUATE_LOOP
						AND appweb.paid_transaction(transaction.id) != -4;

						IF (NOT FOUND) THEN

							SELECT INTO _COMPLEMENTO
							ROUND(tax_total::numeric, 2)
							FROM TEMP_statement
							WHERE id_tax = _ID_TAX
							AND fiscal_year = _YEAR_EVALUATE
							AND TEMP_statement.month = _MONTH_EVALUATE_LOOP;

							IF (_COMPLEMENTO ISNULL OR _COMPLEMENTO != 0) THEN
				
								_RECORD_RETURN.id_message := 2;
								_RECORD_RETURN.message := 'Adeuda el complemento de la declaracion jurada mensual de ' || _NAME_MONTHS[_MONTH_EVALUATE_LOOP] || ' del año ' || _YEAR_EVALUATE_LOOP;

								RETURN NEXT _RECORD_RETURN;
							END IF;

						ELSIF (_PAID < 0) THEN

							_RECORD_RETURN.id_message := 2;
							_RECORD_RETURN.message := 'Adeuda el complemento de la declaracion jurada mensual de ' || _NAME_MONTHS[_MONTH_EVALUATE_LOOP] || ' del año ' || _YEAR_EVALUATE_LOOP;

							IF (_PAID = -1) THEN

								_RECORD_RETURN.message := _RECORD_RETURN.message || ' por un convenio de pago';
								
							ELSIF (_PAID = -2) THEN

								_RECORD_RETURN.message := _RECORD_RETURN.message || ' por un fraccionamiento de pago';
								
							END IF;

							RETURN NEXT _RECORD_RETURN;

						END IF;

					END IF;

				END IF;

			END LOOP;

		END LOOP;

	END IF;

	IF (_TYPE) THEN

		-- VERIFICAR QUE EL CONTRIBUYENTE NO ESTE SIENDO AUDITADO 
	/*	
		PERFORM id FROM tecnologia.auditoria
		WHERE id_tax = _ID_TAX
		AND active = '1'
		AND auditoria.status_caso != 0;

		IF (FOUND) THEN

		    _RECORD_RETURN.id_message := 4;
		    _RECORD_RETURN.message := 'Esta siendo auditado';

		    RETURN NEXT _RECORD_RETURN;
		END IF;
	*/
		-- VALIDAR QUE NO SE DEBA UN CONVENIO O FRACCIONAMIENTO DE PAGO

		PERFORM * FROM appweb.reparos_tax(_ID_TAX, '2011-01-01', NULL) WHERE active;

		IF (FOUND) THEN
			_RECORD_RETURN.id_message := 5;
			_RECORD_RETURN.message := 'Tiene un reparo fiscal pendiente por pagar';

			RETURN NEXT _RECORD_RETURN;
			
		END IF;

	END IF;
	
END LOOP;	

RETURN;
END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION appweb.errors_declare_taxpayer_monthly(bigint, boolean, integer, integer)
  OWNER TO postgres;

  
/*



SELECT tax_account_number, appweb.have_statement(1026186,:type,:fiscal_year,FALSE) AS id_sttm_form, 
tax.id AS id_tax, id_message, message FROM 
tax
LEFT JOIN appweb.errors_declare_taxpayer_monthly(:id_taxpayer,:type,:fiscal_year) AS errors ON tax.id = id_tax
WHERE id_taxpayer = :id_taxpayer
AND id_tax_type = 1
AND id_tax_status = 1
AND NOT tax.canceled
AND NOT tax.removed
ORDER BY tax_account_number, message DESC

*/