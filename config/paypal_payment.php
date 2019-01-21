<?php

return array(
	# Account credentials from developer portal
	'Account' => array(
		'ClientId' => 'AR0gLV19N7iSaWTK_2EfL91WFv4TBlSudr_8HP1TVKZfk71X32l9oNw1QevKIZMmfPyfA5aPPU7YZyWL',
		'ClientSecret' => 'EDl5nHi3m1fTxc7Z27iDnnCwPdw8VxXmv3ofN41R19-x-yrX2uceV2iYX_XvUXoExV78C-pHQcVU2og6',
	),

	# Connection Information
	'Http' => array(
		'ConnectionTimeOut' => 30,
		'Retry' => 1,
		//'Proxy' => 'http://[username:password]@hostname[:port][/path]',
	),

	# Service Configuration
	'Service' => array(
		# For integrating with the live endpoint,
		# change the URL to https://api.paypal.com!
                'EndPoint' => 'https://api.sandbox.paypal.com',
	),


	# Logging Information
	'Log' => array(
		'LogEnabled' => true,

		# When using a relative path, the log file is created
		# relative to the .php file that is the entry point
		# for this request. You can also provide an absolute
		# path here
		'FileName' => '../PayPal.log',

		# Logging level can be one of FINE, INFO, WARN or ERROR
		# Logging is most verbose in the 'FINE' level and
		# decreases as you proceed towards ERROR
		'LogLevel' => 'FINE',
	),
);
