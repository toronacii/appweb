/*
Navicat PGSQL Data Transfer

Source Server         : 172.16.1.7
Source Server Version : 90204
Source Host           : 172.16.1.7:5432
Source Database       : vansat
Source Schema         : appweb

Target Server Type    : PGSQL
Target Server Version : 90204
File Encoding         : 65001

Date: 2014-12-17 23:29:26
*/


-- ----------------------------
-- Table structure for "appweb"."interest_rate_new"
-- ----------------------------
DROP TABLE "appweb"."interest_rate_new";
CREATE TABLE "appweb"."interest_rate_new" (
"id" int4 DEFAULT nextval('"appweb".interest_rate_new_id_seq'::regclass) NOT NULL,
"date" date NOT NULL,
"percent" float4 DEFAULT (0)::real NOT NULL,
"calc_percent" float4 DEFAULT (0)::real NOT NULL,
"published" bool,
"created" timestamp(6) DEFAULT now(),
"modified" timestamp(6) DEFAULT now()
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "appweb"."interest_rate_new" IS 'Tasas de interes';
COMMENT ON COLUMN "appweb"."interest_rate_new"."id" IS 'Identificador unico';
COMMENT ON COLUMN "appweb"."interest_rate_new"."date" IS 'Mes en el que aplica el interes';
COMMENT ON COLUMN "appweb"."interest_rate_new"."percent" IS 'Porcentaje';
COMMENT ON COLUMN "appweb"."interest_rate_new"."calc_percent" IS 'Porcentaje multiplicado por 1,2 por ley, es el utilizado para el calculo';
COMMENT ON COLUMN "appweb"."interest_rate_new"."published" IS 'Booleano que determina si la tasa fue publicada, falso: no fue publicada y se utiliza la del mes anterior';
COMMENT ON COLUMN "appweb"."interest_rate_new"."created" IS 'Fecha y hora de creacion del registro';
COMMENT ON COLUMN "appweb"."interest_rate_new"."modified" IS 'Fecha y hora de modificacion del registro';

-- ----------------------------
-- Records of interest_rate_new
-- ----------------------------
INSERT INTO "appweb"."interest_rate_new" VALUES ('1', '2006-01-01', '15.86', '19.03', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('2', '2006-02-01', '15.78', '18.94', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('3', '2006-03-01', '15.33', '18.4', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('4', '2006-04-01', '14.93', '17.92', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('5', '2006-05-01', '14.79', '17.75', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('6', '2006-06-01', '14.43', '17.32', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('7', '2006-07-01', '15.04', '18.05', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('8', '2006-08-01', '15.6', '18.72', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('9', '2006-09-01', '15.2', '18.24', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('10', '2006-10-01', '15.91', '19.09', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('11', '2006-11-01', '15.95', '19.14', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('12', '2006-12-01', '16.06', '19.27', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('13', '2007-01-01', '16.87', '20.24', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('14', '2007-02-01', '16.18', '19.42', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('15', '2007-03-01', '15.36', '18.43', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('16', '2007-04-01', '16.54', '19.85', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('17', '2007-05-01', '16.87', '20.24', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('18', '2007-06-01', '16.1', '19.32', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('19', '2007-07-01', '17.02', '20.42', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('20', '2007-08-01', '17.61', '21.13', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('21', '2007-09-01', '17.92', '21.5', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('22', '2007-10-01', '18.05', '21.66', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('23', '2007-11-01', '20.95', '25.14', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('24', '2007-12-01', '23.08', '27.7', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('25', '2008-01-01', '25.93', '31.12', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('26', '2008-02-01', '24.67', '29.6', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('27', '2008-03-01', '24.03', '28.84', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('28', '2008-04-01', '24.47', '29.36', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('29', '2008-05-01', '25.97', '31.16', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('30', '2008-06-01', '24.78', '29.74', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('31', '2008-07-01', '25.84', '31.01', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('32', '2008-08-01', '25.09', '30.11', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('33', '2008-09-01', '24.72', '29.66', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('34', '2008-10-01', '24.44', '29.33', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('35', '2008-11-01', '24.88', '29.86', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('36', '2008-12-01', '23.32', '27.98', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('37', '2009-01-01', '26.41', '31.69', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('38', '2009-02-01', '26.89', '32.27', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('39', '2009-03-01', '25.87', '31.04', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('40', '2009-04-01', '24.65', '29.58', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('41', '2009-05-01', '24.04', '28.9', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('42', '2009-06-01', '22.42', '26.9', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('43', '2009-07-01', '22.3', '26.76', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('44', '2009-08-01', '22.31', '26.77', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('45', '2009-09-01', '20.87', '25.04', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('46', '2009-10-01', '21.96', '26.35', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('47', '2009-11-01', '21.62', '25.94', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('48', '2009-12-01', '21.73', '26.08', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('49', '2010-01-01', '21.2', '25.44', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('50', '2010-02-01', '22.33', '26.8', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('51', '2010-03-01', '20.9', '25.08', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('52', '2010-04-01', '21.19', '25.43', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('53', '2010-05-01', '20.36', '24.43', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('54', '2010-06-01', '20.42', '24.5', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('55', '2010-07-01', '20.3', '24.36', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('56', '2010-08-01', '20.01', '24.01', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('57', '2010-09-01', '21.02', '25.22', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('58', '2010-10-01', '19.58', '23.5', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('59', '2010-11-01', '20.04', '24.05', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('60', '2010-12-01', '20.04', '24.05', 'f', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('61', '2011-01-01', '19.83', '23.8', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('62', '2011-02-01', '19.9', '23.88', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('63', '2011-03-01', '19.88', '23.86', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('64', '2011-04-01', '20.02', '24.02', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('65', '2011-05-01', '20.77', '24.92', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('66', '2011-06-01', '19.91', '23.89', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('67', '2011-07-01', '20.41', '24.49', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('68', '2011-08-01', '19.14', '22.97', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('69', '2011-09-01', '19.68', '23.62', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('70', '2011-10-01', '20.24', '24.29', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('71', '2011-11-01', '18.59', '22.31', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('72', '2011-12-01', '17.9', '21.48', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('73', '2012-01-01', '18.66', '22.39', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('74', '2012-02-01', '18.44', '22.13', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('75', '2012-03-01', '17.07', '20.48', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('76', '2012-04-01', '17.84', '21.41', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('77', '2012-05-01', '18.63', '22.36', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('78', '2012-06-01', '17.73', '21.28', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('79', '2012-07-01', '17.3', '20.76', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('80', '2012-08-01', '18.36', '22.03', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('81', '2012-09-01', '18.61', '22.33', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('82', '2012-10-01', '18.13', '21.76', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('83', '2012-11-01', '17.26', '20.71', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('84', '2012-12-01', '17.9', '21.48', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('85', '2013-01-01', '16.3', '19.56', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('86', '2013-02-01', '18.07', '21.68', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('87', '2013-03-01', '16.67', '20', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('88', '2013-04-01', '16.85', '20.22', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('89', '2013-05-01', '17.22', '20.66', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('90', '2013-06-01', '16.33', '19.59', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('91', '2013-07-01', '16.74', '20.08', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('92', '2013-08-01', '18.02', '21.62', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('93', '2013-09-01', '16.97', '20.36', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('94', '2013-10-01', '16.7', '20.04', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('95', '2013-11-01', '16.95', '20.34', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('96', '2013-12-01', '17.38', '20.85', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('97', '2014-01-01', '17.07', '20.48', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('98', '2014-02-01', '18.24', '21.88', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('99', '2014-03-01', '17.31', '20.77', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('100', '2014-04-01', '18.29', '21.95', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('101', '2014-05-01', '18.29', '21.95', 'f', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('102', '2014-06-01', '18.29', '21.95', 'f', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('103', '2014-07-01', '18.81', '22.57', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('104', '2014-08-01', '19.93', '23.91', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('105', '2014-09-01', '19.87', '23.84', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('106', '2014-10-01', '20.77', '24.92', 't', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('107', '2014-11-01', '20.77', '24.92', 'f', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');
INSERT INTO "appweb"."interest_rate_new" VALUES ('108', '2014-12-01', '20.77', '24.92', 'f', '2014-12-12 00:00:00.817366', '2014-12-12 00:00:00.817366');

-- ----------------------------
-- Alter Sequences Owned By 
-- ----------------------------

-- ----------------------------
-- Indexes structure for table "appweb"."interest_rate_new"
-- ----------------------------
CREATE UNIQUE INDEX "inx1_interest_rate_new" ON "appweb"."interest_rate_new" USING btree ("date");

-- ----------------------------
-- Triggers structure for table "appweb"."interest_rate_new"
-- ----------------------------
CREATE TRIGGER "update_modified" BEFORE UPDATE ON "appweb"."interest_rate_new"
FOR EACH ROW
EXECUTE PROCEDURE "setmodified"();

-- ----------------------------
-- Checks structure for table "appweb"."interest_rate_new"
-- ----------------------------
ALTER TABLE "appweb"."interest_rate_new" ADD CHECK ((percent >= (0)::real) AND (percent <= (100)::real));

-- ----------------------------
-- Primary Key structure for table "appweb"."interest_rate_new"
-- ----------------------------
ALTER TABLE "appweb"."interest_rate_new" ADD PRIMARY KEY ("id");
