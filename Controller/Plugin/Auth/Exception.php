<?php
/**
 * When using the built-in error codes, the generated error message 
contains newlines. use nl2br for web formatting.
 * @category    My
 * @package     My_Controller_Plugin
 * @subpackage  My_Controller_Plugin_Auth_
 * @version 0.3.0
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
 
 class My_Controller_Plugin_Auth_Exception extends Exception{
     const INVALID_TYPE=0;
     const MISSING_ACL=-1;
     const MISSING_DATA=1;
 }
