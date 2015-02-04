<?php

$display_table_name	 = "Sales Manager Responsibilities";
//MUST BE NAME CONF TO WORK
$conf				 = array(
	'lead' => array(
		'id'		 => array(
			'display'	 => 'ID',
			'show'		 => array(
				'list'	 => true,
				'add'	 => false,
				'edit'	 => false
			),
			'form'		 => array(
				'type'	 => 'id',
				'length' => 8
			)
		),
		'country'	 => array(
			'display'	 => 'Country',
			'show'		 => array(
				'list'	 => true,
				'add'	 => true,
				'edit'	 => true
			),
			'form'		 => array(
				'type'		 => 'select',
				'transform'	 => array(
					"United States of America"	 => "United States of America",
					"Canada"					 => "Canada",
					"Afghanistan"				 => "Afghanistan",
					"Albania"					 => "Albania",
					"Algeria"					 => "Algeria",
					"American Samoa"			 => "American Samoa",
					"Andorra"					 => "Andorra",
					"Angola"					 => "Angola",
					"Argentina"					 => "Argentina",
					"Armenia"					 => "Armenia",
					"Aruba"						 => "Aruba",
					"Australia"					 => "Australia",
					"Austria"					 => "Austria",
					"Azerbaijan"				 => "Azerbaijan",
					"Bahamas"					 => "Bahamas",
					"Bahrain"					 => "Bahrain",
					"Bangladesh"				 => "Bangladesh",
					"Bassas da India"			 => "Bassas da India",
					"Belarus"					 => "Belarus",
					"Belgium"					 => "Belgium",
					"Belize"					 => "Belize",
					"Benin"						 => "Benin",
					"Bermuda"					 => "Bermuda",
					"Bhutan"					 => "Bhutan",
					"Bolivia"					 => "Bolivia",
					"Bosnia and Herzegovina"	 => "Bosnia and Herzegovina",
					"Botswana"					 => "Botswana",
					"Brazil"					 => "Brazil",
					"British Virgin Islands"	 => "British Virgin Islands",
					"Bulgaria"					 => "Bulgaria",
					"Cambodia"					 => "Cambodia",
					"Cameroon"					 => "Cameroon",
					"Central African Republic"	 => "Central African Republic",
					"Chad"						 => "Chad",
					"Chile"						 => "Chile",
					"China"						 => "China",
					"Colombia"					 => "Colombia",
					"Columbia"					 => "Columbia",
					"Costa Rica"				 => "Costa Rica",
					"Croatia"					 => "Croatia",
					"Cuba"						 => "Cuba",
					"Cyprus"					 => "Cyprus",
					"Czech Republic"			 => "Czech Republic",
					"Denmark"					 => "Denmark",
					"Djibouti"					 => "Djibouti",
					"Dominica"					 => "Dominica",
					"Dominican Republic"		 => "Dominican Republic",
					"Ecuador"					 => "Ecuador",
					"Egypt"						 => "Egypt",
					"El Salvador"				 => "El Salvador",
					"Equatorial Guinea"			 => "Equatorial Guinea",
					"Estonia"					 => "Estonia",
					"Falkland Islands"			 => "Falkland Islands",
					"Finland"					 => "Finland",
					"France"					 => "France",
					"French Guiana"				 => "French Guiana",
					"French Polynesia"			 => "French Polynesia",
					"Gaza Strip"				 => "Gaza Strip",
					"Georgia"					 => "Georgia",
					"Germany"					 => "Germany",
					"Ghana"						 => "Ghana",
					"Gibraltar"					 => "Gibraltar",
					"Greece"					 => "Greece",
					"Grenada"					 => "Grenada",
					"Guadeloupe"				 => "Guadeloupe",
					"Guam"						 => "Guam",
					"Guatemala"					 => "Guatemala",
					"Guernsey"					 => "Guernsey",
					"Guinea"					 => "Guinea",
					"Guinea-Bissau"				 => "Guinea-Bissau",
					"Guyana"					 => "Guyana",
					"Haiti"						 => "Haiti",
					"Honduras"					 => "Honduras",
					"Hong Kong"					 => "Hong Kong",
					"Hungary"					 => "Hungary",
					"India"						 => "India",
					"Indian Ocean"				 => "Indian Ocean",
					"Indonesia"					 => "Indonesia",
					"Iran"						 => "Iran",
					"Iraq"						 => "Iraq",
					"Ireland"					 => "Ireland",
					"Israel"					 => "Israel",
					"Italy"						 => "Italy",
					"Jamaica"					 => "Jamaica",
					"Japan"						 => "Japan",
					"Jersey"					 => "Jersey",
					"Jordan"					 => "Jordan",
					"Kazakhstan"				 => "Kazakhstan",
					"Kenya"						 => "Kenya",
					"Kiribati"					 => "Kiribati",
					"Korea"						 => "Korea",
					"Korea, North"				 => "Korea, North",
					"Korea, South"				 => "Korea, South",
					"Kuwait"					 => "Kuwait",
					"Kyrgyzstan"				 => "Kyrgyzstan",
					"Laos"						 => "Laos",
					"Latvia"					 => "Latvia",
					"Lebanon"					 => "Lebanon",
					"Lesotho"					 => "Lesotho",
					"Liberia"					 => "Liberia",
					"Libya"						 => "Libya",
					"Lithuania"					 => "Lithuania",
					"Luxembourg"				 => "Luxembourg",
					"Madagascar"				 => "Madagascar",
					"Malaysia"					 => "Malaysia",
					"Maldives"					 => "Maldives",
					"Mali"						 => "Mali",
					"Malta"						 => "Malta",
					"Mayotte"					 => "Mayotte",
					"Mexico"					 => "Mexico",
					"Micronesia"				 => "Micronesia",
					"Monaco"					 => "Monaco",
					"Mongolia"					 => "Mongolia",
					"Montserrat"				 => "Montserrat",
					"Morocco"					 => "Morocco",
					"Mozambique"				 => "Mozambique",
					"Namibia"					 => "Namibia",
					"Nauru"						 => "Nauru",
					"Nepal"						 => "Nepal",
					"Netherlands"				 => "Netherlands",
					"Netherlands Antilles"		 => "Netherlands Antilles",
					"New Caledonia"				 => "New Caledonia",
					"New Zealand"				 => "New Zealand",
					"Nicaragua"					 => "Nicaragua",
					"Niger"						 => "Niger",
					"Nigeria"					 => "Nigeria",
					"Niue"						 => "Niue",
					"Norway"					 => "Norway",
					"Pakistan"					 => "Pakistan",
					"Palau"						 => "Palau",
					"Palmyra Atoll"				 => "Palmyra Atoll",
					"Panama"					 => "Panama",
					"Papua New Guinea"			 => "Papua New Guinea",
					"Paraguay"					 => "Paraguay",
					"Peru"						 => "Peru",
					"Philippines"				 => "Philippines",
					"Poland"					 => "Poland",
					"Portugal"					 => "Portugal",
					"Puerto Rico"				 => "Puerto Rico",
					"Qatar"						 => "Qatar",
					"Reunion"					 => "Reunion",
					"Romania"					 => "Romania",
					"Russia"					 => "Russia",
					"Rwanda"					 => "Rwanda",
					"Samoa"						 => "Samoa",
					"San Marino"				 => "San Marino",
					"Sao Tome and Principe"		 => "Sao Tome and Principe",
					"Saudi Arabia"				 => "Saudi Arabia",
					"Senegal"					 => "Senegal",
					"Serbia and Montenegro"		 => "Serbia and Montenegro",
					"Seychelles"				 => "Seychelles",
					"Sierra Leone"				 => "Sierra Leone",
					"Singapore"					 => "Singapore",
					"Slovakia"					 => "Slovakia",
					"Slovenia"					 => "Slovenia",
					"Somalia"					 => "Somalia",
					"South Africa"				 => "South Africa",
					"Southern Ocean"			 => "Southern Ocean",
					"Spain"						 => "Spain",
					"Sri Lanka"					 => "Sri Lanka",
					"Sudan"						 => "Sudan",
					"Suriname"					 => "Suriname",
					"Swaziland"					 => "Swaziland",
					"Sweden"					 => "Sweden",
					"Switzerland"				 => "Switzerland",
					"Syria"						 => "Syria",
					"Taiwan"					 => "Taiwan",
					"Tajikistan"				 => "Tajikistan",
					"Tanzania"					 => "Tanzania",
					"Thailand"					 => "Thailand",
					"Togo"						 => "Togo",
					"Tokelau"					 => "Tokelau",
					"Tonga"						 => "Tonga",
					"Trinidad & Tobago"			 => "Trinidad & Tobago",
					"Trinidad and Tobago"		 => "Trinidad and Tobago",
					"Tunisia"					 => "Tunisia",
					"Turkey"					 => "Turkey",
					"Turkmenistan"				 => "Turkmenistan",
					"Turks and Caicos Islands"	 => "Turks and Caicos Islands",
					"Tuvalu"					 => "Tuvalu",
					"Uganda"					 => "Uganda",
					"Ukraine"					 => "Ukraine",
					"United Arab Emirates"		 => "United Arab Emirates",
					"United Kingdom"			 => "United Kingdom",
					"Uruguay"					 => "Uruguay",
					"Uzbekistan"				 => "Uzbekistan",
					"Vanuatu"					 => "Vanuatu",
					"Venezuala"					 => "Venezuala",
					"Venezuela"					 => "Venezuela",
					"Vietnam"					 => "Vietnam",
					"Virgin Islands"			 => "Virgin Islands",
					"Wallis and Futuna"			 => "Wallis and Futuna",
					"West Bank"					 => "West Bank",
					"Western Sahara"			 => "Western Sahara",
					"Yemen"						 => "Yemen",
					"Zambia"					 => "Zambia",
					"Zimbabwe"					 => "Zimbabwe",
				)
			)
		),
		'state'		 => array(
			'display'	 => 'State',
			'show'		 => array(
				'list'	 => true,
				'add'	 => true,
				'edit'	 => true
			),
			'form'		 => array(
				'type'		 => 'select',
				'transform'	 => array(
					"*"	 => "All",
					"AB" => "AB",
					"AK" => "AK",
					"AL" => "AL",
					"AR" => "AR",
					"AZ" => "AZ",
					"BC" => "BC",
					"CA" => "CA",
					"CO" => "CO",
					"CT" => "CT",
					"DC" => "DC",
					"DE" => "DE",
					"FL" => "FL",
					"GA" => "GA",
					"HI" => "HI",
					"IA" => "IA",
					"ID" => "ID",
					"IL" => "IL",
					"IN" => "IN",
					"KS" => "KS",
					"KY" => "KY",
					"LA" => "LA",
					"MA" => "MA",
					"MB" => "MB",
					"MD" => "MD",
					"ME" => "ME",
					"MI" => "MI",
					"MN" => "MN",
					"MO" => "MO",
					"MS" => "MS",
					"MT" => "MT",
					"NB" => "NB",
					"NC" => "NC",
					"ND" => "ND",
					"NE" => "NE",
					"NH" => "NH",
					"NJ" => "NJ",
					"NL" => "NL",
					"NM" => "NM",
					"NS" => "NS",
					"NT" => "NT",
					"NU" => "NU",
					"NV" => "NV",
					"NY" => "NY",
					"OH" => "OH",
					"OK" => "OK",
					"ON" => "ON",
					"OR" => "OR",
					"PA" => "PA",
					"PE" => "PE",
					"QC" => "QC",
					"RI" => "RI",
					"SC" => "SC",
					"SD" => "SD",
					"SK" => "SK",
					"TN" => "TN",
					"TX" => "TX",
					"UT" => "UT",
					"VA" => "VA",
					"VT" => "VT",
					"WA" => "WA",
					"WI" => "WI",
					"WV" => "WV",
					"WY" => "WY",
					"YK" => "YK",
				)
			)
		),
		'l_id'		 => array(
			'display'	 => 'User',
			'show'		 => array(
				'list'	 => true,
				'add'	 => true,
				'edit'	 => true
			),
			'form'		 => array(
				'type'			 => 'select',
				'select_show'	 => 'CONCAT(first_name," ",last_name)',
				'table'			 => 'users'
			)
		),
	)
);