<?php
/**
 * Exceptions for CG_Ssh
 * @category   CG
 * @package    CG_Ssh
 * @version 1.0
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
class CG_Ssh_Exception extends Exception{
    const INVALID_HOSTNAME=1;
    const INVALID_PORT=2;
    const INVALID_USERNAME=3;
    const INVALID_IDENTITY_FILE=4;
    const INVALID_PASSWORD_VALUE=5;
    const INVALID_RESOURCE_TYPE=6;
    const FAILED_CONNECTION=7;
    const MISSING_USERNAME=8;
    const INVALID_COMMAND_ARGUMENT=9;
    const MISSING_CONNECTION=10;
    const MISSING_HOSTNAME=11;
    const INVALID_OPTIONS=12;
}
