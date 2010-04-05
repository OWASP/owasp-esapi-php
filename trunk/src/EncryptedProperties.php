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
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Implementations will require EncryptionException.
 */
require_once  dirname(__FILE__) . '/errors/EncryptionException.php';


/**
 * The EncryptedProperties interface represents a properties file where all the
 * data is encrypted before it is added, and decrypted when it retrieved. This
 * interface can be implemented in a number of ways, the simplest being
 * extending Properties and overloading the getProperty and setProperty methods.
 *
 * <img src="doc-files/EncryptedProperties.jpg">
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface EncryptedProperties {

	/**
	 * Gets the property value from the encrypted store, decrypts it, and returns the plaintext value to the caller.
	 * 
	 * @param key
	 *      the name of the property to get 
	 * 
	 * @return 
	 * 		the decrypted property value
	 * 
	 * @throws EncryptionException
	 *      if the property could not be decrypted
	 */
	function getProperty($key);

	/**
	 * Encrypts the plaintext property value and stores the ciphertext value in the encrypted store.
	 * 
	 * @param key
	 *      the name of the property to set
	 * @param value
	 * 		the value of the property to set
	 * 
	 * @return 
	 * 		the encrypted property value
	 * 
	 * @throws EncryptionException
	 *      if the property could not be encrypted
	 */
	function setProperty($key, $value);
	
	/**
	 * Returns a Set view of properties. The Set is backed by a Hashtable, so changes to the 
	 * Hashtable are reflected in the Set, and vice-versa. The Set supports element 
	 * removal (which removes the corresponding entry from the Hashtable), but not element addition.
	 * 
	 * @return 
	 * 		a set view of the properties contained in this map.
	 */
	function keySet();
		
	/**
	 * Reads a property list (key and element pairs) from the input stream.
	 * 
	 * @param in
	 * 		the input stream that contains the properties file
	 * 
	 * @throws IOException
	 *      Signals that an I/O exception has occurred.
	 */
	function load($in);
	
	/**
	 * Writes this property list (key and element pairs) in this Properties table to 
	 * the output stream in a format suitable for loading into a Properties table using the load method. 
	 * 
	 * @param out
	 * 		the output stream that contains the properties file
	 * @param comments
	 *            a description of the property list (ex. "Encrypted Properties File").
	 * 
	 * @throws IOException
	 *             Signals that an I/O exception has occurred.
	 */
	function store($out, $comments);	
	
	
}
?>