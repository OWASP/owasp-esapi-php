<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Implementations require ValidationException and IntrusionException.
 */
require_once dirname(__FILE__).'/errors/IntrusionException.php';
require_once dirname(__FILE__).'/errors/ValidationException.php';


/**
 * The Validator interface defines a set of methods for canonicalizing and
 * validating untrusted input. Implementors should feel free to extend this
 * interface to accommodate their own data formats. Rather than throw
 * exceptions, this interface returns boolean results because not all
 * validation problems are security issues. Boolean returns allow developers to
 * handle both valid and invalid results more cleanly than exceptions.
 *
 * <img src="doc-files/Validator.jpg">
 *
 * Implementations must adopt a "whitelist" approach to validation where a
 * specific pattern or character set is matched. "Blacklist" approaches that
 * attempt to identify the invalid or disallowed characters are much more likely
 * to allow a bypass with encoding or other tricks.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface Validator {

    /**
     * Returns true if input is valid according to the specified type. The type
     * parameter must be the name of a defined type in the ESAPI configuration
     * or a valid regular expression. Implementers should take care to make the
     * type storage simple to understand and configure.
     *
     * @param context A descriptive name of the parameter that you are
     *        validating (e.g. LoginPage_UsernameField). This value is used by
     *        any logging or error handling that is done with respect to the
     *        value passed in.
     * @param input The actual user input data to validate.
     * @param type The regular expression name that maps to the actual regular
     *        expression from "ESAPI.xml".
     * @param maxLength The maximum post-canonicalized String length allowed.
     * @param allowNull If allowNull is true then an input that is NULL or an
     *        empty string will be legal. If allowNull is false then NULL or an
     *        empty String will throw a ValidationException.
     *
     * @return true, if the input is valid based on the rules set by 'type' or
     *         false otherwise.
     */
    function isValidInput($context, $input, $type, $maxLength, $allowNull);


    /**
     * Asserts that the input is valid according to the supplied type.
     * Invalid input will generate a descriptive ValidationException.  This
     * method does not perform canonicalization of the input.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $type The regular expression name that maps to the actual regular
     *         expression from "ESAPI.xml".
     * @param  $maxLength The maximum string length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException.
     */
    function assertValidInput($context, $input, $type, $maxLength, $allowNull);


    /**
     * Returns true if input is a valid date according to the specified date
     * format or false otherwise. This method canonicalizes non-null and
     * non-empty inputs before performing validation.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_DoB). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $format Required formatting of date inputted {@see strftime}.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return true if input is a valid date according to the format specified
     *         by $format, or false otherwise.
     */
    function isValidDate($context, $input, $format, $allowNull);


    /**
     * Asserts that the input is a valid date according to the supplied format.
     * Invalid input will throw a descriptive ValidationException or, in the
     * case of inputs which are found to contain mixed or double encoding, an
     * IntrusionException. This method canonicalizes non-null and non-empty
     * inputs before performing validation.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_DoB). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $format Required formatting of date inputted {@see strftime}.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException or IntrusionException.
     */
    function assertValidDate($context, $input, $format, $allowNull);


	/**
	 * Returns true if input is "safe" HTML. Implementors should reference the OWASP AntiSamy project for ideas
	 * on how to do HTML validation in a whitelist way, as this is an extremely difficult problem.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param maxLength 
	 * 		The maximum post-canonicalized String length allowed.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is valid safe HTML
	 * 
	 * @throws IntrusionException
	 */
	function isValidSafeHTML($context, $input, $maxLength, $allowNull);
	
	/**
	 * Returns canonicalized and validated "safe" HTML. Implementors should reference the OWASP AntiSamy project for ideas
	 * on how to do HTML validation in a whitelist way, as this is an extremely difficult problem. Instead of
	 * throwing a ValidationException on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param maxLength 
	 * 		The maximum post-canonicalized String length allowed.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return Valid safe HTML
	 * 
	 * @throws IntrusionException
	 */
	function getValidSafeHTML($context, $input, $maxLength, $allowNull, $errorList=null);

	/**
	 * Returns true if input is a valid credit card. Maxlength is mandated by valid credit card type.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid credit card number
	 * 
	 * @throws IntrusionException
	 */
	function isValidCreditCard($context, $input, $allowNull);
	
	/**
	 * Returns a canonicalized and validated credit card number as a String. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A valid credit card number
	 * 
	 * @throws IntrusionException
	 */
	function getValidCreditCard($context, $input, $allowNull, $errorList = null);
	
	/**
	 * Returns true if input is a valid directory path.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid directory path
	 * 
	 * @throws IntrusionException 
	 */
	function isValidDirectoryPath($context, $input, $allowNull);
	
	/**
	 * Returns a canonicalized and validated directory path as a String. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 *  
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
     * 
     * @return A valid directory path
     * 
     * @throws IntrusionException
	 */
	function getValidDirectoryPath($context, $input, $allowNull, $errorList = null);
	
	
	/**
	 * Returns true if input is a valid file name.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
     * @param input 
     * 		The actual input data to validate.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
     * 
     * @return true, if input is a valid file name
     * 
     * @throws IntrusionException
	 */
	function isValidFileName($context, $input, $allowNull);
	
	/**
	 * Returns a canonicalized and validated file name as a String. Implementors should check for allowed file extensions here, as well as allowed file name characters, as declared in "ESAPI.properties".  Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
     * 
     * @return A valid file name
     * 
     * @throws IntrusionException
	 */
	function getValidFileName($context, $input, $allowNull, $errorList  = null);
		
	/**
	 * Returns true if input is a valid number within the range of minValue to maxValue.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
     * @param input 
     * 		The actual input data to validate.
     * @param minValue 
     * 		Lowest legal value for input.
     * @param maxValue 
     * 		Highest legal value for input.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
     * 
     * @return true, if input is a valid number
     * 
     * @throws IntrusionException
	 */
	function isValidNumber($context, $input, $minValue, $maxValue, $allowNull);

	/**
	 * Returns a validated number as a double within the range of minValue to maxValue. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
     * @param maxValue 
     * 		Highest legal value for input.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A validated number as a double.
     * 
     * @throws IntrusionException
	 */
	function getValidNumber($context, $input, $minValue, $maxValue, $allowNull, $errorList=null);

	/**
	 * Returns true if input is a valid integer within the range of minValue to maxValue.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
     * @param input 
     * 		The actual input data to validate.
     * @param minValue 
     * 		Lowest legal value for input.
     * @param maxValue 
     * 		Highest legal value for input.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
     * 
     * @return true, if input is a valid integer
     * 
     * @throws IntrusionException
	 */
	function isValidInteger($context, $input, $minValue, $maxValue, $allowNull);
	
	/**
	 * Returns a validated integer. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
     * @param maxValue 
     * 		Highest legal value for input.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A validated number as an integer.
     * 
     * @throws IntrusionException
	 */
	function getValidInteger($context, $input, $minValue, $maxValue, $allowNull, $errorList = null);
		
	/**
	 * Returns true if input is a valid double within the range of minValue to maxValue.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
     * @param input 
     * 		The actual input data to validate.
     * @param minValue 
     * 		Lowest legal value for input.
     * @param maxValue 
     * 		Highest legal value for input.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
     * 
     * @return true, if input is a valid double.
     * 
     * @throws IntrusionException
	 * 
	 */
	function isValidDouble($context, $input, $minValue, $maxValue, $allowNull);

	/**
	 * Returns a validated real number as a double. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
     * @param maxValue 
     * 		Highest legal value for input.
     * @param allowNull 
     * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A validated real number as a double.
     * 
     * @throws IntrusionException
	 */
	function getValidDouble($context, $input, $minValue, $maxValue, $allowNull, $errorList = null);

	/**
	 * Returns true if input is valid file content.  This is a good place to check for max file size, allowed character sets, and do virus scans.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param maxBytes 
	 * 		The maximum number of bytes allowed in a legal file.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input contains valid file content.
	 * 
	 * @throws IntrusionException
	 */
	function isValidFileContent($context, $input, $maxBytes, $allowNull);

	/**
	 * Returns validated file content as a byte array. This is a good place to check for max file size, allowed character sets, and do virus scans.  Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param maxBytes 
	 * 		The maximum number of bytes allowed in a legal file.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context.
	 * 
	 * @return A byte array containing valid file content.
	 * 
	 * @throws IntrusionException
	 */
	function getValidFileContent($context, $input, $maxBytes, $allowNull, $errorList = null);

	/**
	 * Returns true if a file upload has a valid name, path, and content.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param filepath 
	 * 		The file path of the uploaded file.
	 * @param filename 
	 * 		The filename of the uploaded file
	 * @param content 
	 * 		A byte array containing the content of the uploaded file.
	 * @param maxBytes 
	 * 		The max number of bytes allowed for a legal file upload.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if a file upload has a valid name, path, and content.
	 * 
	 * @throws IntrusionException
	 */
	function isValidFileUpload($context, $filepath, $filename, $content, $maxBytes, $allowNull);

	/**
	 * Validates the filepath, filename, and content of a file. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param filepath 
	 * 		The file path of the uploaded file.
	 * @param filename 
	 * 		The filename of the uploaded file
	 * @param content 
	 * 		A byte array containing the content of the uploaded file.
	 * @param maxBytes 
	 * 		The max number of bytes allowed for a legal file upload.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @throws IntrusionException
	 */
	function assertValidFileUpload($context, $filepath, $filename, $content, $maxBytes, $allowNull, $errorList = null);
	
	/**
     * Validate the current HTTP request by comparing parameters, headers, and cookies to a predefined whitelist of allowed
     * characters. See the SecurityConfiguration class for the methods to retrieve the whitelists.
     * 
     * @return true, if is a valid HTTP request
     * 
     * @throws IntrusionException
     */
	function isValidHTTPRequest();
	
	/**
	 * Validates the current HTTP request by comparing parameters, headers, and cookies to a predefined whitelist of allowed
	 * characters. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException.
	 * 
	 * @throws ValidationException
	 * @throws IntrusionException
	 */
	function assertIsValidHTTPRequest();
	
	/**
	 * Returns true if input is a valid list item.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The value to search 'list' for.
	 * @param list 
	 * 		The list to search for 'input'.
	 * 
	 * @return true, if 'input' was found in 'list'.
	 * 
	 * @throws IntrusionException
	 */
	function isValidListItem($context, $input, $list);

	/**
	 * Returns the list item that exactly matches the canonicalized input. Invalid or non-matching input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The value to search 'list' for.
	 * @param list 
	 * 		The list to search for 'input'.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return The list item that exactly matches the canonicalized input.
	 * 
	 * @throws IntrusionException
	 */
	function getValidListItem($context, $input, $list, $errorList = null);
	
	/**
	 * Returns true if the parameters in the current request contain all required parameters and only optional ones in addition.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param required 
	 * 		parameters that are required to be in HTTP request 
	 * @param optional 
	 * 		additional parameters that may be in HTTP request
	 * 
	 * @return true, if all required parameters are in HTTP request and only optional parameters in addition.  Returns false if parameters are found in HTTP request that are not in either set (required or optional), or if any required parameters are missing from request.
	 * 
	 * @throws IntrusionException
	 */
	function isValidHTTPRequestParameterSet($context, $required, $optional);
	
	/**
	 * Validates that the parameters in the current request contain all required parameters and only optional ones in
	 * addition. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException on error, 
	 * this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param required 
	 * 		parameters that are required to be in HTTP request
	 * @param optional 
	 * 		additional parameters that may be in HTTP request
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @throws IntrusionException
	 */
	function assertIsValidHTTPRequestParameterSet($context, $required, $optional, $errorList = null);
	
	/**
	 * Returns true if input contains only valid printable ASCII characters.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		data to be checked for validity
	 * @param maxLength 
	 * 		Maximum number of bytes stored in 'input'
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if 'input' is less than maxLength and contains only valid, printable characters
	 * 
	 * @throws IntrusionException
	 */
	function isValidPrintable($context, $input, $maxLength, $allowNull);

	/**
	 * Returns canonicalized and validated printable characters as a byte array. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException on error, 
	 * this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		data to be returned as valid and printable
	 * @param maxLength 
	 * 		Maximum number of bytes stored in 'input'
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return a byte array containing only printable characters, made up of data from 'input'
	 * 
	 * @throws IntrusionException
	 */
	function getValidPrintable($context, $input, $maxLength, $allowNull, $errorList = null);

	
	/**
	 * Returns true if input is a valid redirect location, as defined by "ESAPI.properties".
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		redirect location to be checked for validity, according to rules set in "ESAPI.properties"
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if 'input' is a valid redirect location, as defined by "ESAPI.properties", false otherwise.
	 * 
	 * @throws IntrusionException
	 */
	function isValidRedirectLocation($context, $input, $allowNull);


	/**
	 * Returns a canonicalized and validated redirect location as a String. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		redirect location to be returned as valid, according to encoding rules set in "ESAPI.properties"
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A canonicalized and validated redirect location, as defined in "ESAPI.properties"
	 * 
	 * @throws IntrusionException
	 */
	function getValidRedirectLocation($context, $input, $allowNull, $errorList = null);
	
	/**
	 * Reads from an input stream until end-of-line or a maximum number of
	 * characters. This method protects against the inherent denial of service
	 * attack in reading until the end of a line. If an attacker doesn't ever
	 * send a newline character, then a normal input stream reader will read
	 * until all memory is exhausted and the platform throws an OutOfMemoryError
	 * and probably terminates.
	 * 
	 * @param inputStream 
	 * 		The InputStream from which to read data
	 * @param maxLength 
	 * 		Maximum characters allowed to be read in per line
	 * 
	 * @return a String containing the current line of inputStream
	 * 
	 * @throws ValidationException
	 */
	function safeReadLine($inputStream, $maxLength);

}

