-- Function: appweb.get_news_taxpayer(bigint)

-- DROP FUNCTION appweb.get_news_taxpayer(bigint);

CREATE OR REPLACE FUNCTION appweb.get_news_taxpayer(IN _id_taxpayer bigint)
  RETURNS TABLE(id bigint, id_taxpayer bigint, id_tax bigint, type character varying, title character varying, message text, created timestamp without time zone, authomatic boolean, read_date timestamp without time zone, firm_name character varying, tax_account_number character varying) AS
$BODY$

	SELECT DISTINCT news.id::bigint, news.id_taxpayer, news.id_tax, news.type, news.title, news.message, news.created, news.authomatic, news_users_web.read_date, taxpayer.firm_name, tax.tax_account_number
	FROM appweb.news
	INNER JOIN taxpayer ON taxpayer.id = news.id_taxpayer
	INNER JOIN appweb.tax ON tax.id = news.id_tax AND tax.id_taxpayer = _ID_TAXPAYER 
	LEFT JOIN appweb.news_users_web ON news_users_web.id_news = news.id AND news_users_web.id_taxpayer = taxpayer.id
	WHERE CURRENT_DATE BETWEEN date_from AND COALESCE(date_to, '2030-12-31')
	AND news_users_web.deleted_at ISNULL
	AND news.deleted_at ISNULL
	AND news.authomatic

	UNION 

	SELECT DISTINCT news.id::bigint, news.id_taxpayer, news.id_tax, news.type, news.title, news.message, news.created, news.authomatic, news_users_web.read_date, '', ''
	FROM appweb.news
	INNER JOIN appweb.tax ON tax.id_tax_type = ANY(news.id_tax_types) AND tax.id_taxpayer = _ID_TAXPAYER
	LEFT JOIN appweb.news_users_web ON news_users_web.id_news = news.id AND news_users_web.id_taxpayer = tax.id_taxpayer
	WHERE CURRENT_DATE BETWEEN date_from AND COALESCE(date_to, '2030-12-31')
	AND news_users_web.deleted_at ISNULL
	AND news.deleted_at ISNULL
	AND NOT(news.authomatic)
	
$BODY$
  LANGUAGE sql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION appweb.get_news_taxpayer(bigint)
  OWNER TO postgres;

