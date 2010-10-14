<?
/**
 * Custom auth adapter abstract that can read a whitelist 
 * before attempting an ldap query. alternately, this bypass ldap
 * by checking against the db for a stored password.
 * This alternative is due to legacy issues and to accommodate for
 * legacy apps and those not on the the network such as consultants.
 * 
 * @category   My
 * @package    My_Auth
 * @subpackage My_Auth_Adapter
 * @version 0.0.1
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
abstract class My_Auth_Adapter_Ldap_Abstract implements Zend_Auth_Adapter_Interface, My_Whitelist{
    
    // abstract method determineMethod
}
