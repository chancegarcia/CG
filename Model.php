<?php
/**
 * Model stub used for testing.
 * @category   CG
 * @version 1.0
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
final class CG_Model extends CG_Model_Abstract{
    
    public function setStorage($storage){
        $this->_storage=$storage;
        return $this;
    }
    
    public function getStorage(){
        return $this->_storage;
    }
}
