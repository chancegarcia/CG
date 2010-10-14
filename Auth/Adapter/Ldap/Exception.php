<?php
/**
 * Base exception class for the My library
 * @category   My
 * @version 1.0
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
class My_Auth_Adapter_Ldap_Exception extends Exception{
    const INVALID_TYPE=1;
    const MISSING_DEPENDENCY=2;
}
