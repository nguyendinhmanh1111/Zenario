<?php
/*
 * Copyright (c) 2023, Tribal Limited
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Zenario, Tribal Limited nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL TRIBAL LTD BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
if (!defined('NOT_ACCESSED_DIRECTLY')) exit('This file may not be directly accessed');

ze\dbAdm::revision(12
, <<<_sql
	DROP TABLE IF EXISTS `[[DB_PREFIX]]country_manager_countries`
_sql

, <<<_sql
	CREATE TABLE `[[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries` (
		`id` varchar(5) NOT NULL,
		`english_name` varchar(255),
		`active` tinyint(1),
		PRIMARY KEY (`id`)
	) ENGINE=[[ZENARIO_TABLE_ENGINE]] CHARSET=[[ZENARIO_TABLE_CHARSET]] COLLATE=[[ZENARIO_TABLE_COLLATION]]
_sql
, 
<<<_sql
	REPLACE INTO [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries (`id`,`english_name`,`active`)
	VALUES 
		("AD","Andorra",1),
		("AE","United Arab Emirates",1),
		("AF","Afghanistan",1),
		("AG","Antigua and Barbuda",1),
		("AI","Anguilla",1),
		("AL","Albania",1),
		("AM","Armenia",1),
		("AN","Netherlands Antilles",1),
		("AO","Angola",1),
		("AQ","Antarctica",1),
		("AR","Argentina",1),
		("AS","American Samoa",1),
		("AT","Austria",1),
		("AU","Australia",1),
		("AW","Aruba",1),
		("AX","Åland Islands",1),
		("AZ","Azerbaijan",1),
		("BA","Bosnia and Herzegovina",1),
		("BB","Barbados",1),
		("BD","Bangladesh",1),
		("BE","Belgium",1),
		("BF","Burkina Faso",1),
		("BG","Bulgaria",1),
		("BH","Bahrain",1),
		("BI","Burundi",1),
		("BJ","Benin",1),
		("BL","Saint Barthélemy",1),
		("BM","Bermuda",1),
		("BN","Brunei Darussalam",1),
		("BO","Bolivia, Plurinational State of",1),
		("BR","Brazil",1),
		("BS","Bahamas",1),
		("BT","Bhutan",1),
		("BV","Bouvet Island",1),
		("BW","Botswana",1),
		("BY","Belarus",1),
		("BZ","Belize",1),
		("CA","Canada",1),
		("CC","Cocos (Keeling) Islands",1),
		("CD","Congo, the Democratic Republic of the",1),
		("CF","Central African Republic",1),
		("CG","Congo",1),
		("CH","Switzerland",1),
		("CI","Côte d'Ivoire",1),
		("CK","Cook Islands",1),
		("CL","Chile",1),
		("CM","Cameroon",1),
		("CN","China",1),
		("CO","Colombia",1),
		("CR","Costa Rica",1),
		("CU","Cuba",1),
		("CV","Cape Verde",1),
		("CX","Christmas Island",1),
		("CY","Cyprus",1),
		("CZ","Czech Republic",1),
		("DE","Germany",1),
		("DJ","Djibouti",1),
		("DK","Denmark",1),
		("DM","Dominica",1),
		("DO","Dominican Republic",1),
		("DZ","Algeria",1),
		("EC","Ecuador",1),
		("EE","Estonia",1),
		("EG","Egypt",1),
		("EH","Western Sahara",1),
		("ER","Eritrea",1),
		("ES","Spain",1),
		("ET","Ethiopia",1),
		("FI","Finland",1),
		("FJ","Fiji",1),
		("FK","Falkland Islands (Malvinas)",1),
		("FM","Micronesia, Federated States of",1),
		("FO","Faroe Islands",1),
		("FR","France",1),
		("GA","Gabon",1),
		("GB","United Kingdom",1),
		("GD","Grenada",1),
		("GE","Georgia",1),
		("GF","French Guiana",1),
		("GG","Guernsey",1),
		("GH","Ghana",1),
		("GI","Gibraltar",1),
		("GL","Greenland",1),
		("GM","Gambia",1),
		("GN","Guinea",1),
		("GP","Guadeloupe",1),
		("GQ","Equatorial Guinea",1),
		("GR","Greece",1),
		("GS","South Georgia and the South Sandwich Islands",1),
		("GT","Guatemala",1),
		("GU","Guam",1),
		("GW","Guinea-Bissau",1),
		("GY","Guyana",1),
		("HK","Hong Kong",1),
		("HM","Heard Island and McDonald Islands",1),
		("HN","Honduras",1),
		("HR","Croatia",1),
		("HT","Haiti",1),
		("HU","Hungary",1),
		("ID","Indonesia",1),
		("IE","Ireland",1),
		("IL","Israel",1),
		("IM","Isle of Man",1),
		("IN","India",1),
		("IO","British Indian Ocean Territory",1),
		("IQ","Iraq",1),
		("IR","Iran, Islamic Republic of",1),
		("IS","Iceland",1),
		("IT","Italy",1),
		("JE","Jersey",1),
		("JM","Jamaica",1),
		("JO","Jordan",1),
		("JP","Japan",1),
		("KE","Kenya",1),
		("KG","Kyrgyzstan",1),
		("KH","Cambodia",1),
		("KI","Kiribati",1),
		("KM","Comoros",1),
		("KN","Saint Kitts and Nevis",1),
		("KP","Korea, Democratic People's Republic of",1),
		("KR","Korea, Republic of",1),
		("KW","Kuwait",1),
		("KY","Cayman Islands",1),
		("KZ","Kazakhstan",1),
		("LA","Lao People's Democratic Republic",1),
		("LB","Lebanon",1),
		("LC","Saint Lucia",1),
		("LI","Liechtenstein",1),
		("LK","Sri Lanka",1),
		("LR","Liberia",1),
		("LS","Lesotho",1),
		("LT","Lithuania",1),
		("LU","Luxembourg",1),
		("LV","Latvia",1),
		("LY","Libyan Arab Jamahiriya",1),
		("MA","Morocco",1),
		("MC","Monaco",1),
		("MD","Moldova, Republic of",1),
		("ME","Montenegro",1),
		("MF","Saint Martin (French part)",1),
		("MG","Madagascar",1),
		("MH","Marshall Islands",1),
		("MK","Macedonia, the former Yugoslav Republic of",1),
		("ML","Mali",1),
		("MM","Myanmar",1),
		("MN","Mongolia",1),
		("MO","Macao",1),
		("MP","Northern Mariana Islands",1),
		("MQ","Martinique",1),
		("MR","Mauritania",1),
		("MS","Montserrat",1),
		("MT","Malta",1),
		("MU","Mauritius",1),
		("MV","Maldives",1),
		("MW","Malawi",1),
		("MX","Mexico",1),
		("MY","Malaysia",1),
		("MZ","Mozambique",1),
		("NA","Namibia",1),
		("NC","New Caledonia",1),
		("NE","Niger",1),
		("NF","Norfolk Island",1),
		("NG","Nigeria",1),
		("NI","Nicaragua",1),
		("NL","Netherlands",1),
		("NO","Norway",1),
		("NP","Nepal",1),
		("NR","Nauru",1),
		("NU","Niue",1),
		("NZ","New Zealand",1),
		("OM","Oman",1),
		("PA","Panama",1),
		("PE","Peru",1),
		("PF","French Polynesia",1),
		("PG","Papua New Guinea",1),
		("PH","Philippines",1),
		("PK","Pakistan",1),
		("PL","Poland",1),
		("PM","Saint Pierre and Miquelon",1),
		("PN","Pitcairn",1),
		("PR","Puerto Rico",1),
		("PS","Palestinian Territory, Occupied",1),
		("PT","Portugal",1),
		("PW","Palau",1),
		("PY","Paraguay",1),
		("QA","Qatar",1),
		("RE","Réunion",1),
		("RO","Romania",1),
		("RS","Serbia",1),
		("RU","Russian Federation",1),
		("RW","Rwanda",1),
		("SA","Saudi Arabia",1),
		("SB","Solomon Islands",1),
		("SC","Seychelles",1),
		("SD","Sudan",1),
		("SE","Sweden",1),
		("SG","Singapore",1),
		("SH","Saint Helena",1),
		("SI","Slovenia",1),
		("SJ","Svalbard and Jan Mayen",1),
		("SK","Slovakia",1),
		("SL","Sierra Leone",1),
		("SM","San Marino",1),
		("SN","Senegal",1),
		("SO","Somalia",1),
		("SR","Suriname",1),
		("ST","Sao Tome and Principe",1),
		("SV","El Salvador",1),
		("SY","Syrian Arab Republic",1),
		("SZ","Swaziland",1),
		("TC","Turks and Caicos Islands",1),
		("TD","Chad",1),
		("TF","French Southern Territories",1),
		("TG","Togo",1),
		("TH","Thailand",1),
		("TJ","Tajikistan",1),
		("TK","Tokelau",1),
		("TL","Timor-Leste",1),
		("TM","Turkmenistan",1),
		("TN","Tunisia",1),
		("TO","Tonga",1),
		("TR","Turkey",1),
		("TT","Trinidad and Tobago",1),
		("TV","Tuvalu",1),
		("TW","Taiwan, Province of China",1),
		("TZ","Tanzania, United Republic of",1),
		("UA","Ukraine",1),
		("UG","Uganda",1),
		("UM","United States Minor Outlying Islands",1),
		("US","United States",1),
		("UY","Uruguay",1),
		("UZ","Uzbekistan",1),
		("VA","Holy See (Vatican City State)",1),
		("VC","Saint Vincent and the Grenadines",1),
		("VE","Venezuela, Bolivarian Republic of",1),
		("VG","Virgin Islands, British",1),
		("VI","Virgin Islands, U.S.",1),
		("VN","Vietnam",1),
		("VU","Vanuatu",1),
		("WF","Wallis and Futuna",1),
		("WS","Samoa",1),
		("YE","Yemen",1),
		("YT","Mayotte",1),
		("ZA","South Africa",1),
		("ZM","Zambia",1),
		("ZW","Zimbabwe",1)
_sql
,
<<<_sql
DROP TABLE IF EXISTS [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions;
_sql
,
<<<_sql
	CREATE TABLE IF NOT EXISTS [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions (
		`id` varchar(5) NOT NULL,
		`country_id` varchar(5) NOT NULL,
		`name` varchar(255),
		`active` tinyint(1),
		PRIMARY KEY (`id`,`country_id`)
	) ENGINE=[[ZENARIO_TABLE_ENGINE]] CHARSET=[[ZENARIO_TABLE_CHARSET]] COLLATE=[[ZENARIO_TABLE_COLLATION]]
_sql
);
ze\dbAdm::revision(16
, <<<_sql
ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions
CHANGE COLUMN id code varchar(5), 
ADD COLUMN id int(10) AUTO_INCREMENT FIRST,
DROP PRIMARY KEY, 
ADD PRIMARY KEY (`id`);
_sql
);
ze\dbAdm::revision(17
, <<<_sql
ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions
ADD CONSTRAINT UNIQUE (`code`,`country_id`);
_sql
);
ze\dbAdm::revision(25
,<<<_sql
INSERT IGNORE
	[[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions (name,code,country_id,active) 
VALUES 
	('Alabama','AL','US',1),
	('Alaska','AK','US',1),
	('Arizona','AZ','US',1),
	('Arkansas','AR','US',1),
	('California','CA','US',1),
	('Colorado','CO','US',1),
	('Connecticut','CT','US',1),
	('Delaware','DE','US',1),
	('District of Columbia','DC','US',1),
	('Florida','FL','US',1),
	('Georgia','GA','US',1),
	('Hawaii','HI','US',1),
	('Idaho','ID','US',1),
	('Illinois','IL','US',1),
	('Indiana','IN','US',1),
	('Iowa','IA','US',1),
	('Kansas','KS','US',1),
	('Kentucky','KY','US',1),
	('Louisiana','LA','US',1),
	('Maine','ME','US',1),
	('Maryland','MD','US',1),
	('Massachusetts','MA','US',1),
	('Michigan','MI','US',1),
	('Minnesota','MN','US',1),
	('Mississippi','MS','US',1),
	('Missouri','MO','US',1),
	('Montana','MT','US',1),
	('Nebraska','NE','US',1),
	('Nevada','NV','US',1),
	('New Hampshire','NH','US',1),
	('New Jersey','NJ','US',1),
	('New Mexico','NM','US',1),
	('New York','NY','US',1),
	('North Carolina','NC','US',1),
	('North Dakota','ND','US',1),
	('Ohio','OH','US',1),
	('Oklahoma','OK','US',1),
	('Oregon','OR','US',1),
	('Pennsylvania','PA','US',1),
	('RhodeIsland','RI','US',1),
	('South Carolina','SC','US',1),
	('South Dakota','SD','US',1),
	('Tennessee','TN','US',1),
	('Texas','TX','US',1),
	('Utah','UT','US',1),
	('Vermont','VT','US',1),
	('Virginia','VA','US',1),
	('Washington','WA','US',1),
	('West Virginia','WV','US',1),
	('Wisconsin','WI','US',1),
	('Wyoming','WY','US',1),
	('Alberta','AB','CA',1),
	('British Columbia','BC','CA',1),
	('Manitoba','MB','CA',1),
	('New Brunswick','NB','CA',1),
	('Newfoundland and Labrador','NL','CA',1),
	('Northwest Territories','NT','CA',1),
	('Nova Scotia','NS','CA',1),
	('Nunavut','NU','CA',1),
	('Ontario','ON','CA',1),
	('Prince Edward Island','PE','CA',1),
	('Quebec','QC','CA',1),
	('Saskatchewan','SK','CA',1),
	('Yukon','YT','CA',1);
_sql
);
ze\dbAdm::revision(27
, <<<_sql
	ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions
	ADD COLUMN `parent_id` int(10) NOT NULL DEFAULT 0 AFTER `id`
_sql
);
ze\dbAdm::revision(28
,<<<_sql
	ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions
	DROP KEY `code`,
ADD CONSTRAINT UNIQUE  (`name`,`parent_id`,`country_id`)
_sql
);
ze\dbAdm::revision(58
,<<<_sql
	ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions 
	ADD COLUMN `region_type` enum('region', 'state', 'city') NOT NULL DEFAULT 'region',
	ADD KEY (`region_type`);
_sql
);
// Add phone code to countries
ze\dbAdm::revision(62
,<<<_sql
	ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries
	ADD COLUMN `phonecode` int(5) NOT NULL
_sql
,<<<_sql
	INSERT INTO [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries (id, phonecode) 
	VALUES 
		('AF', 93),
		('AL', 355),
		('DZ', 213),
		('AS', 1684),
		('AD', 376),
		('AO', 244),
		('AI', 1264),
		('AQ', 0),
		('AG', 1268),
		('AR', 54),
		('AM', 374),
		('AW', 297),
		('AU', 61),
		('AT', 43),
		('AZ', 994),
		('BS', 1242),
		('BH', 973),
		('BD', 880),
		('BB', 1246),
		('BY', 375),
		('BE', 32),
		('BZ', 501),
		('BJ', 229),
		('BM', 1441),
		('BT', 975),
		('BO', 591),
		('BA', 387),
		('BW', 267),
		('BV', 0),
		('BR', 55),
		('IO', 246),
		('BN', 673),
		('BG', 359),
		('BF', 226),
		('BI', 257),
		('KH', 855),
		('CM', 237),
		('CA', 1),
		('CV', 238),
		('KY', 1345),
		('CF', 236),
		('TD', 235),
		('CL', 56),
		('CN', 86),
		('CX', 61),
		('CC', 672),
		('CO', 57),
		('KM', 269),
		('CG', 242),
		('CD', 242),
		('CK', 682),
		('CR', 506),
		('CI', 225),
		('HR', 385),
		('CU', 53),
		('CY', 357),
		('CZ', 420),
		('DK', 45),
		('DJ', 253),
		('DM', 1767),
		('DO', 1809),
		('EC', 593),
		('EG', 20),
		('SV', 503),
		('GQ', 240),
		('ER', 291),
		('EE', 372),
		('ET', 251),
		('FK', 500),
		('FO', 298),
		('FJ', 679),
		('FI', 358),
		('FR', 33),
		('GF', 594),
		('PF', 689),
		('TF', 0),
		('GA', 241),
		('GM', 220),
		('GE', 995),
		('DE', 49),
		('GH', 233),
		('GI', 350),
		('GR', 30),
		('GL', 299),
		('GD', 1473),
		('GP', 590),
		('GU', 1671),
		('GT', 502),
		('GN', 224),
		('GW', 245),
		('GY', 592),
		('HT', 509),
		('HM', 0),
		('VA', 39),
		('HN', 504),
		('HK', 852),
		('HU', 36),
		('IS', 354),
		('IN', 91),
		('ID', 62),
		('IR', 98),
		('IQ', 964),
		('IE', 353),
		('IL', 972),
		('IT', 39),
		('JM', 1876),
		('JP', 81),
		('JO', 962),
		('KZ', 7),
		('KE', 254),
		('KI', 686),
		('KP', 850),
		('KR', 82),
		('KW', 965),
		('KG', 996),
		('LA', 856),
		('LV', 371),
		('LB', 961),
		('LS', 266),
		('LR', 231),
		('LY', 218),
		('LI', 423),
		('LT', 370),
		('LU', 352),
		('MO', 853),
		('MK', 389),
		('MG', 261),
		('MW', 265),
		('MY', 60),
		('MV', 960),
		('ML', 223),
		('MT', 356),
		('MH', 692),
		('MQ', 596),
		('MR', 222),
		('MU', 230),
		('YT', 269),
		('MX', 52),
		('FM', 691),
		('MD', 373),
		('MC', 377),
		('MN', 976),
		('MS', 1664),
		('MA', 212),
		('MZ', 258),
		('MM', 95),
		('NA', 264),
		('NR', 674),
		('NP', 977),
		('NL', 31),
		('AN', 599),
		('NC', 687),
		('NZ', 64),
		('NI', 505),
		('NE', 227),
		('NG', 234),
		('NU', 683),
		('NF', 672),
		('MP', 1670),
		('NO', 47),
		('OM', 968),
		('PK', 92),
		('PW', 680),
		('PS', 970),
		('PA', 507),
		('PG', 675),
		('PY', 595),
		('PE', 51),
		('PH', 63),
		('PN', 0),
		('PL', 48),
		('PT', 351),
		('PR', 1787),
		('QA', 974),
		('RE', 262),
		('RO', 40),
		('RU', 70),
		('RW', 250),
		('SH', 290),
		('KN', 1869),
		('LC', 1758),
		('PM', 508),
		('VC', 1784),
		('WS', 684),
		('SM', 378),
		('ST', 239),
		('SA', 966),
		('SN', 221),
		('RS', 381),
		('SC', 248),
		('SL', 232),
		('SG', 65),
		('SK', 421),
		('SI', 386),
		('SB', 677),
		('SO', 252),
		('ZA', 27),
		('GS', 0),
		('ES', 34),
		('LK', 94),
		('SD', 249),
		('SR', 597),
		('SJ', 47),
		('SZ', 268),
		('SE', 46),
		('CH', 41),
		('SY', 963),
		('TW', 886),
		('TJ', 992),
		('TZ', 255),
		('TH', 66),
		('TL', 670),
		('TG', 228),
		('TK', 690),
		('TO', 676),
		('TT', 1868),
		('TN', 216),
		('TR', 90),
		('TM', 7370),
		('TC', 1649),
		('TV', 688),
		('UG', 256),
		('UA', 380),
		('AE', 971),
		('GB', 44),
		('US', 1),
		('UM', 1),
		('UY', 598),
		('UZ', 998),
		('VU', 678),
		('VE', 58),
		('VN', 84),
		('VG', 1284),
		('VI', 1340),
		('WF', 681),
		('EH', 212),
		('YE', 967),
		('ZM', 260),
		('ZW', 263),
		('IM', 44),
		('JE', 44),
		('GG', 44)
	ON DUPLICATE KEY UPDATE phonecode = VALUES(phonecode);
_sql

); ze\dbAdm::revision(67
,<<<_sql
	ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries
	MODIFY COLUMN `phonecode` int(5) NOT NULL DEFAULT 0
_sql

); ze\dbAdm::revision(69
,<<<_sql
	UPDATE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries
	SET `active` = 0 WHERE active IS NULL
_sql

,<<<_sql
	ALTER TABLE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries
	MODIFY COLUMN `active` tinyint(1) NOT NULL DEFAULT 1
_sql

); ze\dbAdm::revision(70
, <<<_sql
	UPDATE [[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries
	SET english_name = "Taiwan"
		WHERE id = "TW"
_sql


//In 9.3, we're going through and fixing the character-set on several columns that should
//have been using "ascii"
);	ze\dbAdm::revision(80
, <<<_sql
	ALTER TABLE `[[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_countries`
	MODIFY COLUMN `id` varchar(5) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL
_sql

, <<<_sql
	ALTER TABLE `[[DB_PREFIX]][[ZENARIO_COUNTRY_MANAGER_PREFIX]]country_manager_regions`
	MODIFY COLUMN `country_id` varchar(5) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL
_sql


);