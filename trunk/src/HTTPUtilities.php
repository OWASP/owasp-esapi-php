<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 * 
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * 
 */
require_once dirname(__FILE__).'/errors/AccessControlException.php';
require_once dirname(__FILE__).'/errors/AuthenticationException.php';
require_once dirname(__FILE__).'/errors/EncryptionException.php';
require_once dirname(__FILE__).'/errors/EnterpriseSecurityException.php';
require_once dirname(__FILE__).'/errors/IntrusionException.php';
require_once dirname(__FILE__).'/errors/ValidationException.php';
require_once dirname(__FILE__).'/filters/SafeRequest.php';
require_once dirname(__FILE__).'/filters/SafeResponse.php';

/**
 * The HTTPUtilities interface is a collection of methods that provide
 * additional security related to HTTP requests, responses, sessions, cookies,
 * headers, and logging.
 *
 * <img src="doc-files/HTTPUtilities.jpg">
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface HTTPUtilities
{
	/**
	 * Ensures that the current request uses SSL and POST to protect any sensitive parameters
	 * in the querystring from being sniffed or logged. For example, this method should
	 * be called from any method that uses sensitive data from a web form.
	 * 
	 * This method uses {@link HTTPUtilities#getCurrentRequest()} to obtain the current {@link HttpServletRequest} object 
	 * 
	 * @throws AccessControlException if security constraints are not met
	 */
	function assertSecureRequest( $request );

    
    /**
     * Adds the current user's CSRF token (see User.getCSRFToken()) to the URL for purposes of preventing CSRF attacks.
     * This method should be used on all URLs to be put into all links and forms the application generates.
     * 
     * @param href 
     * 		the URL to which the CSRF token will be appended
     * 
     * @return the updated URL with the CSRF token parameter added
     */
    function addCSRFToken($href);
    
    /**
     * Get the first cookie with the matching name.
     * @param name
     * @return the requested cookie
     */
	function getCookie($request, $name);
    
    /**
     * Returns the current user's CSRF token. If there is no current user then return null.
     * 
     * @return the current users CSRF token
     */
    function getCSRFToken();


    /**
     * Invalidate the old session after copying all of its contents to a newly created session with a new session id.
     * Note that this is different from logging out and creating a new session identifier that does not contain the
     * existing session contents. Care should be taken to use this only when the existing session does not contain
     * hazardous contents.
	 * 
	 * This method uses {@link HTTPUtilities#getCurrentRequest()} to obtain the current {@link HttpSession} object 
     * 
     * @return the new HttpSession with a changed id
     * @throws AuthenticationException the exception
     */
    function changeSessionIdentifier( $request );

    
	/**
     * Checks the CSRF token in the URL (see User.getCSRFToken()) against the user's CSRF token and
	 * throws an IntrusionException if it is missing.
     * 
	 * @throws IntrusionException if CSRF token is missing or incorrect
	 */
    function verifyCSRFToken($request);
    
    
    /**
	 * Decrypts an encrypted hidden field value and returns the cleartext. If the field does not decrypt properly,
	 * an IntrusionException is thrown to indicate tampering.
	 * 
	 * @param encrypted 
	 * 		hidden field value to decrypt
	 * 
	 * @return decrypted hidden field value stored as a String
	 */
	function decryptHiddenField($encrypted);

	/**
	 * Set a cookie containing the current User's remember me token for automatic authentication. The use of remember me tokens
	 * is generally not recommended, but this method will help do it as safely as possible. The user interface should strongly warn
	 * the user that this should only be enabled on computers where no other users will have access.  
	 * 
	 * Implementations should save the user's remember me data in an encrypted cookie and send it to the user. 
	 * Any old remember me cookie should be destroyed first. Setting this cookie should keep the user 
	 * logged in until the maxAge passes, the password is changed, or the cookie is deleted.
	 * If the cookie exists for the current user, it should automatically be used by ESAPI to
	 * log the user in, if the data is valid and not expired. 
	 * 
	 * The ESAPI reference implementation, DefaultHTTPUtilities.setRememberToken() implements all these suggestions.
	 * 
	 * The username can be retrieved with: User username = ESAPI.authenticator().getCurrentUser(); 
	 * 
	 * @param password 
	 * 		the user's password
	 * @param maxAge 
	 * 		the length of time that the token should be valid for in relative seconds
	 * @param domain 
	 * 		the domain to restrict the token to or null
	 * @param path 
	 * 		the path to restrict the token to or null
	 * 
	 * @return encrypted "Remember Me" token stored as a String
	 */
	function setRememberToken($request,$response, $password, $maxAge, $domain, $path);

    /**
     * Encrypts a hidden field value for use in HTML.
     * 
     * @param value 
     * 		the cleartext value of the hidden field
     * 
     * @return the encrypted value of the hidden field
     * 
     * @throws EncryptionException 
     */
	function encryptHiddenField($value);

	/**
	 * Takes a querystring (everything after the question mark in the URL) and returns an encrypted string containing the parameters.
	 * 
	 * @param query 
	 * 		the querystring to encrypt
	 * 
	 * @return encrypted querystring stored as a String
	 * 
	 * @throws EncryptionException
	 */
	function encryptQueryString($query);
	
	/**
	 * Takes an encrypted querystring and returns a Map containing the original parameters.
	 * 
	 * @param encrypted 
	 * 		the encrypted querystring to decrypt
	 * 
	 * @return a Map object containing the decrypted querystring
	 * 
	 * @throws EncryptionException
	 */
	function decryptQueryString($encrypted);

	
    /**
     * Extract uploaded files from a multipart HTTP requests. Implementations must check the content to ensure that it
     * is safe before making a permanent copy on the local filesystem. Checks should include length and content checks,
     * possibly virus checking, and path and name checks. Refer to the file checking methods in Validator for more
     * information.
	 * 
	 * This method uses {@link HTTPUtilities#getCurrentRequest()} to obtain the {@link HttpServletRequest} object
     * 
     * @param tempDir 
     * 		the temporary directory
     * @param finalDir 
     * 		the final directory
     * 
     * @return List of new File objects from upload
     * 
     * @throws ValidationException 
     * 		if the file fails validation
     */
    function getSafeFileUploads($request, $tempDir, $finalDir);

    /**
     * Retrieves a map of data from a cookie encrypted with encryptStateInCookie().
     * 
	 * @return a map containing the decrypted cookie state value
	 * 
	 * @throws EncryptionException
     */
    function decryptStateFromCookie($request);

    /**
     * Kill all cookies received in the last request from the browser. Note that new cookies set by the application in
     * this response may not be killed by this method.
     */
    function killAllCookies($request, $response);
    
    /**
     * Kills the specified cookie by setting a new cookie that expires immediately. Note that this
     * method does not delete new cookies that are being set by the application for this response. 
     */
    function killCookie($request, $response, $name);

    /**
     * Stores a Map of data in an encrypted cookie. Generally the session is a better
     * place to store state information, as it does not expose it to the user at all.
     * If there is a requirement not to use sessions, or the data should be stored
     * across sessions (for a long time), the use of encrypted cookies is an effective
     * way to prevent the exposure.
     */
    function encryptStateInCookie($response, $cleartext);

    
    /**
     * This method performs a forward to any resource located inside the WEB-INF directory. Forwarding to
     * publicly accessible resources can be dangerous, as the request will have already passed the URL
     * based access control check. This method ensures that you can only forward to non-publicly
     * accessible resources.
	 * 
     * @param context 
     * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
     * @param location 
     * 		the URL to forward to
     * 
     * @throws AccessControlException
     * @throws ServletException
     * @throws IOException
     */
	function safeSendForward($request, $response, $context, $location);
	

    /**
	 * Set the content type character encoding header on every HttpServletResponse in order to limit
	 * the ways in which the input data can be represented. This prevents
	 * malicious users from using encoding and multi-byte escape sequences to
	 * bypass input validation routines.
	 * 
	 * Implementations of this method should set the content type header to a safe value for your environment.
	 * The default is text/html; charset=UTF-8 character encoding, which is the default in early 
	 * versions of HTML and HTTP. See RFC 2047 (http://ds.internic.net/rfc/rfc2045.txt) for more
	 * information about character encoding and MIME.
	 * 
	 * The DefaultHTTPUtilities reference implementation sets the content type as specified.
	 * 
	 * @param response
	 * 		The servlet response to set the content type for.
     */
    function setSafeContentType($response);

    
    /**
     * Set headers to protect sensitive information against being cached in the browser. Developers should make this
     * call for any HTTP responses that contain any sensitive data that should not be cached within the browser or any
     * intermediate proxies or caches. Implementations should set headers for the expected browsers. The safest approach
     * is to set all relevant headers to their most restrictive setting. These include:
     * 
     * <PRE>
     * 
     * Cache-Control: no-store<BR>
     * Cache-Control: no-cache<BR>
     * Cache-Control: must-revalidate<BR>
     * Expires: -1<BR>
     * 
     * </PRE>
     * 
     * Note that the header "pragma: no-cache" is only useful in HTTP requests, not HTTP responses. So even though there
     * are many articles recommending the use of this header, it is not helpful for preventing browser caching. For more
     * information, please refer to the relevant standards:
     * <UL>
     * <LI><a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html">HTTP/1.1 Cache-Control "no-cache"</a>
     * <LI><a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9.1">HTTP/1.1 Cache-Control "no-store"</a>
     * <LI><a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9.2">HTTP/1.0 Pragma "no-cache"</a>
     * <LI><a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.32">HTTP/1.0 Expires</a>
     * <LI><a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21">IE6 Caching Issues</a>
     * <LI><a href="http://support.microsoft.com/kb/937479">Firefox browser.cache.disk_cache_ssl</a>
     * <LI><a href="http://www.mozilla.org/quality/networking/docs/netprefs.html">Mozilla</a>
     * </UL>
     * 
	 * This method uses {@link HTTPUtilities#getCurrentResponse()} to obtain the {@link HttpServletResponse} object
	 * 
     */
    function setNoCacheHeaders($response);

    /**
     * Stores the current HttpRequest and HttpResponse so that they may be readily accessed throughout
     * ESAPI (and elsewhere)
     * 
     * @param request 
     * 		the current request
     * @param response 
     * 		the current response
     */
    function setCurrentHTTP($request, $response);
    
    /**
     * Retrieves the current HttpServletRequest
     * 
     * @return the current request
     */
    function getCurrentRequest();
    
    /**
     * Retrieves the current HttpServletResponse
     * 
     * @return the current response
     */
    function getCurrentResponse();
    
    /**
     * Format the Source IP address, URL, URL parameters, and all form
     * parameters into a string suitable for the log file. The list of parameters to
     * obfuscate should be specified in order to prevent sensitive information
     * from being logged. If a null list is provided, then all parameters will
     * be logged. If HTTP request logging is done in a central place, the
     * parameterNamesToObfuscate could be made a configuration parameter. We
     * include it here in case different parts of the application need to obfuscate
     * different parameters.
     * 
	 * This method uses {@link HTTPUtilities#getCurrentResponse()} to obtain the {@link HttpServletResponse} object
	 * 
	 * @param logger 
	 * 		the logger to write the request to
     * @param parameterNamesToObfuscate
     * 		the sensitive parameters
     */
    function logHTTPRequest($request, $logger, $parameterNamesToObfuscate = null);

}
