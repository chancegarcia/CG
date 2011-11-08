<?
/**
 * Interface for making whitelists.
 * 
 * @category   My
 * @package    My
 * @version 0.0.1
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
interface CG_Whitelist{
    
    public function getWhitelist();
    public function setWhitelist($whitelist=null);
    public function isWhitelisted();
}
