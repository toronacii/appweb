-- Function: generate_statement_interest(bigint)

-- DROP FUNCTION generate_statement_interest(bigint);

CREATE OR REPLACE FUNCTION generate_statement_interest(bigint)
  RETURNS bigint AS
$BODY$
DECLARE

-- PARAMETROS

	_ID_STTM ALIAS FOR $1;

-- VARIABLES LOCALES

	_INTEREST_RATE appweb.interest_rate_new%ROWTYPE;
	_STTM record;
	_DATE_NOW date = now()::date;
	_YEAR_NOW integer = EXTRACT('YEAR' FROM NOW());
	_COMPLEMENT double precision;

	_DATE_STATEMENT_EXIGIBLE date;
	_TOTAL_MONTHS int = 0;
	_AMOUNT double precision;
	_SUM_AMOUNT double precision = 0;
	_QUARTERS record;
	_PARENT_TRANSACTION bigint;
	_APPLICATION_DATE date;
	_CONCEPT text;
	_ID_GENERATED_TRANSACTION bigint;

	_DATE_LOOP date;
	_TO int;


BEGIN

-- BUSCAMOS LA DECLARACION

SELECT INTO _STTM
id_tax, type, fiscal_year, tax_total, statement_date, statement.id_user,
EXTRACT('YEAR' FROM CASE WHEN initial_date ISNULL THEN start_activities_date ELSE initial_date END) AS initial_year
FROM statement
INNER JOIN tax ON tax.id = id_tax
WHERE statement.id = _ID_STTM
AND extemp
AND NOT statement.canceled
AND status = 2;

IF (NOT FOUND) THEN RETURN 0; END IF;

-- LLENAMOS AÑO DE LA DECLARACION

_DATE_NOW = _STTM.statement_date;

_YEAR_NOW = EXTRACT('YEAR' FROM _DATE_NOW);

-- VALIDAR ESTIMADA DEL AÑO CON INITIAL_DATE DEL AÑO EN CURSO

IF (_YEAR_NOW = _STTM.fiscal_year AND _STTM.initial_year = _YEAR_NOW) THEN RETURN 0; END IF;

IF (_STTM.type IN (2,5) AND _STTM.fiscal_year + 1 <= _YEAR_NOW) THEN -- DEFINITIVAS

	_DATE_STATEMENT_EXIGIBLE = ((_STTM.fiscal_year + 1) || '-01-01')::date;

	_TOTAL_MONTHS = DATE_PART('YEAR', age(_DATE_NOW, _DATE_STATEMENT_EXIGIBLE)) * 12 + DATE_PART('MONTH', age(_DATE_NOW, _DATE_STATEMENT_EXIGIBLE)) + 1;

	RAISE NOTICE '_DATE_STATEMENT_EXIGIBLE: %', _DATE_STATEMENT_EXIGIBLE;

	-- LLENAMOS EL COMPLEMENTO

	SELECT INTO _COMPLEMENT
	_STTM.tax_total - tax_total
	FROM statement
	WHERE fiscal_year = _STTM.fiscal_year
	AND id_tax = _STTM.id_tax
	AND NOT canceled
	AND status = 2
	AND type IN (0,3)
	AND NOT estimated_sterile;

	-- LLENAMOS EL PARENT_TRANSACTION

	SELECT INTO _PARENT_TRANSACTION id
	FROM transaction
	WHERE id_transaction_type = 8
	AND id_statement = _ID_STTM;

	-- LLENAMOS EL INTEREST_RATE PARA LA FECHA DE LA DECLARACION

	SELECT INTO _INTEREST_RATE * FROM appweb.get_interest_rate(_STTM.statement_date);

	_CONCEPT = 'Interés ('  || ROUND((_INTEREST_RATE.calc_percent / 12)::numeric,2) || '%) para Complementario por Declaracion Definitiva Año ' || _STTM.fiscal_year;

	-- RAISE NOTICE '_COMPLEMENT: %', _COMPLEMENT;

	IF (_COMPLEMENT ISNULL) THEN  -- NO TIENE ESTIMADA DEL AÑO

		_TOTAL_MONTHS = 26 + 4 * _TOTAL_MONTHS;

		_DATE_LOOP = ((_STTM.fiscal_year + 1) || '-03-01')::date;

		WHILE (_DATE_LOOP <= _STTM.statement_date) LOOP

			IF (DATE_PART('year', _DATE_LOOP) - (_STTM.fiscal_year + 1) = 0) THEN -- EL PRIMER AÑO DE INTERESES NO SE COBRAN TODOS LOS MESES

				_TO = DATE_PART('quarter', _DATE_LOOP + '1 month'::interval) - 1;

			ELSE -- DESPUES DEL PRIMER AÑO SE COBRAN TODOS LOS MESES

				_TO = 4;

			END IF;

			FOR _I IN 1 .. _TO LOOP

				SELECT INTO _INTEREST_RATE * FROM appweb.get_interest_rate(_DATE_LOOP);

				_AMOUNT = (_STTM.tax_total / 4) * (_INTEREST_RATE.calc_percent / 12) / 100;

				_SUM_AMOUNT = _SUM_AMOUNT + _AMOUNT;

				RAISE NOTICE '(_STTM.tax_total / 4) * (_INTEREST_RATE.calc_percent / 12) / 100: (% / 4)  * (% / 12) /100 = %', _STTM.tax_total, _INTEREST_RATE.calc_percent, _AMOUNT;

				-- RAISE NOTICE 'INTERES TRIMESTRE % FECHA: %, _TO: %', _I, _DATE_LOOP, _TO;

			END LOOP;

			RAISE NOTICE '--------------------';

			_DATE_LOOP = (_DATE_LOOP + '1 month'::interval)::date;

		END LOOP;

		RAISE NOTICE '_SUM_AMOUNT: %', _SUM_AMOUNT;

		_AMOUNT = ROUND((_TOTAL_MONTHS * (_STTM.tax_total / 4) * (_INTEREST_RATE.percent / 12) / 100)::numeric, 2);

		RAISE NOTICE '_TOTAL_MONTHS * (_STTM.tax_total / 4) * (_INTEREST_RATE.percent / 12) / 100: % * (% / 4) * (% / 12) /100 = %', _TOTAL_MONTHS, _STTM.tax_total, _INTEREST_RATE.percent, _AMOUNT;

		-- INSERTAR MOVIMIENTO DE INTERES

		INSERT INTO transaction(id_transaction_type, parent_transaction, id_user, id_tax, id_interest_rate, application_date, amount, concept, debt_status, canceled)
		VALUES (3, _PARENT_TRANSACTION, _STTM.id_user, _STTM.id_tax, _INTEREST_RATE.id, now()::date, _AMOUNT, _CONCEPT, 1, FALSE)
		RETURNING id INTO _ID_GENERATED_TRANSACTION;

		-- INSERTAR EN LA TABLA DE LOG

		INSERT INTO log_interest(id_transaction, last_date, id_generated_transaction)
		VALUES (_PARENT_TRANSACTION, now()::date, _ID_GENERATED_TRANSACTION);

	ELSIF (_COMPLEMENT > 0) THEN -- TIENE ESTIMADA	CON COMPLEMENTO POSITIVO

		IF (_STTM.fiscal_year + 1 < _YEAR_NOW) THEN -- DEFINITIVAS ANTERIORES

			_DATE_LOOP = ((_STTM.fiscal_year + 1) || '-04-01')::date;

			WHILE (_DATE_LOOP <= _STTM.statement_date) LOOP

				SELECT INTO _INTEREST_RATE * FROM appweb.get_interest_rate(_DATE_LOOP);

				_AMOUNT = _COMPLEMENT * (_INTEREST_RATE.calc_percent / 12) / 100;

				_SUM_AMOUNT = _SUM_AMOUNT + _AMOUNT;

				RAISE NOTICE '_COMPLEMENT * (_INTEREST_RATE.calc_percent / 12) / 100: %  * (% / 12) /100 = %', _COMPLEMENT, _INTEREST_RATE.calc_percent, _AMOUNT;

				_DATE_LOOP = (_DATE_LOOP + '1 month'::interval)::date;

			END LOOP;

			_TOTAL_MONTHS = _TOTAL_MONTHS - 3;

			_AMOUNT = ROUND((_TOTAL_MONTHS * _COMPLEMENT * (_INTEREST_RATE.percent / 12) / 100)::numeric, 2);

			RAISE NOTICE '_TOTAL_MONTHS * _COMPLEMENT * (_INTEREST_RATE.percent / 12) / 100: % * % * (% / 12) /100 = %', _TOTAL_MONTHS, _COMPLEMENT, _INTEREST_RATE.percent, _AMOUNT;

			-- INSERTAR MOVIMIENTO DE INTERES

			INSERT INTO transaction(id_transaction_type, parent_transaction, id_user, id_tax, id_interest_rate, application_date, amount, concept, debt_status, canceled)
			VALUES (3, _PARENT_TRANSACTION, _STTM.id_user, _STTM.id_tax, _INTEREST_RATE.id, now()::date, _AMOUNT, _CONCEPT, 1, FALSE)
			RETURNING id INTO _ID_GENERATED_TRANSACTION;

			-- INSERTAR EN LA TABLA DE LOG

			INSERT INTO log_interest(id_transaction, last_date, id_generated_transaction)
			VALUES (_PARENT_TRANSACTION, now()::date, _ID_GENERATED_TRANSACTION);

		ELSIF (EXTRACT('MONTH' FROM _DATE_NOW) >= 4) THEN -- DEFINITIVAS DEL PERIODO FISCAL EN CURSO (DESGLOSADAS) DESDE MAYO

			_AMOUNT = ROUND((_COMPLEMENT * (_INTEREST_RATE.percent / 12) / 100)::numeric, 2);

			RAISE NOTICE '_COMPLEMENT * (_INTEREST_RATE.percent / 12) / 100: % * (% / 12) /100 = %', _COMPLEMENT, _INTEREST_RATE.percent, _AMOUNT;

			FOR _I IN 4 .. EXTRACT('MONTH' FROM _DATE_NOW) LOOP

				_APPLICATION_DATE = (_YEAR_NOW || '-' || _I || '-01')::date;

				_CONCEPT = 'Interés Mensual ('  || ROUND((_INTEREST_RATE.percent / 12)::numeric,2) || '%) para Complementario por Declaracion Definitiva Año ' || _STTM.fiscal_year;

				-- INSERTAR MOVIMIENTO DE INTERES

				INSERT INTO transaction(id_transaction_type, parent_transaction, id_user, id_tax, id_interest_rate, application_date, amount, concept, debt_status, canceled)
				VALUES (3, _PARENT_TRANSACTION, _STTM.id_user, _STTM.id_tax, _INTEREST_RATE.id, _APPLICATION_DATE, _AMOUNT, _CONCEPT, 1, FALSE)
				RETURNING id INTO _ID_GENERATED_TRANSACTION;

				-- INSERTAR EN LA TABLA DE LOG

				INSERT INTO log_interest(id_transaction, last_date, id_generated_transaction)
				VALUES (_PARENT_TRANSACTION, now()::date, _ID_GENERATED_TRANSACTION);

			END LOOP;

		END IF;



	ELSE -- DA UN COMPLEMENTO NEGATIVO
		RETURN -1;
	END IF;

END IF;

IF (_STTM.type IN (0,3) AND _STTM.fiscal_year = _YEAR_NOW AND EXTRACT('MONTH' FROM _DATE_NOW) > 1) THEN -- ESTIMADAS EXTEMPORANEAS DEL PERIODO FISCAL EN CURSO (DESGLOSADAS) DESDE MARZO

	SELECT INTO _QUARTERS
	SUM(CASE WHEN  concept LIKE '%Trimestre 1%' THEN id ELSE 0 END) AS t1,
	SUM(CASE WHEN  concept LIKE '%Trimestre 2%' THEN id ELSE 0 END) AS t2,
	SUM(CASE WHEN  concept LIKE '%Trimestre 3%' THEN id ELSE 0 END) AS t3,
	SUM(CASE WHEN  concept LIKE '%Trimestre 4%' THEN id ELSE 0 END) AS t4
	FROM transaction
	WHERE id_transaction_type = 1
	AND id_statement = _ID_STTM;

	IF (NOT FOUND) THEN RETURN -2; END IF;

	_AMOUNT = ROUND((_STTM.tax_total / 4 * (_INTEREST_RATE.percent / 12) / 100)::numeric, 2);

	RAISE NOTICE '_STTM.tax_total / 4 * (_INTEREST_RATE.percent / 12) / 100: % / 4 * (% / 12) /100 = %', _STTM.tax_total, _INTEREST_RATE.percent, _AMOUNT;

	FOR _I IN 2 .. EXTRACT('MONTH' FROM _DATE_NOW) LOOP 

		FOR _J IN 1 .. ((_I + 1)/ 3)::int LOOP

			_APPLICATION_DATE = (_YEAR_NOW || '-' || _I || '-01')::date;

			_PARENT_TRANSACTION = CASE
				WHEN _J = 1 THEN _QUARTERS.t1
				WHEN _J = 2 THEN _QUARTERS.t2
				WHEN _J = 3 THEN _QUARTERS.t3
				WHEN _J = 4 THEN _QUARTERS.t4
			END;

			_CONCEPT = 'Interés Mensual ('  || ROUND((_INTEREST_RATE.percent / 12)::numeric,2) || '%) para Trimestre ' || _J;

			-- INSERTAR MOVIMIENTO DE INTERES

			INSERT INTO transaction(id_transaction_type, parent_transaction, id_user, id_tax, id_interest_rate, application_date, amount, concept, debt_status, canceled)
			VALUES (3, _PARENT_TRANSACTION, _STTM.id_user, _STTM.id_tax, _INTEREST_RATE.id, _APPLICATION_DATE, _AMOUNT, _CONCEPT, 1, FALSE)
			RETURNING id INTO _ID_GENERATED_TRANSACTION;

			-- INSERTAR EN LA TABLA DE LOG

			INSERT INTO log_interest(id_transaction, last_date, id_generated_transaction)
			VALUES (_PARENT_TRANSACTION, now()::date, _ID_GENERATED_TRANSACTION);

		END LOOP;


	END LOOP;

END IF;


RAISE EXCEPTION '1';

RETURN 1;

END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION generate_statement_interest(bigint) OWNER TO postgres;
