<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Mike Boberski (mike.boberski @ owasp.org)
 * @created 2009
 */

require_once dirname(__FILE__).'/../src/ESAPI.php';

$ESAPI = new ESAPI(dirname(__FILE__)."/../ESAPI.xml");
$ESAPI = new ESAPI();

$validator = ESAPI::getValidator();

echo "Testing input validation for an email address...";

echo "\n\nTest # 1: Expected result: Successs.";
if($validator->isValidInput("test", "hello@world.com", "Email", 100, false)){
	echo "\nInput email address successfully validated!";
}else{
	echo "\nInput email address validation failed!";	
}

echo "\n\nTest # 2: Expected result: Failure.";
if($validator->isValidInput("test", "helloworld", "Email", 100, false)){
	echo "\nInput email address successfully validated!\n\n";
}else{
	echo "\nInput email address validation failed!\n\n";	
}

?>