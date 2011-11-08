<?php
/**
 * Custom model abstraction. This library deprecates classes found in the Custom library.
 * @category   CG
 * @package    CG_Model
 * @version 1.0
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
abstract class CG_Model_Abstract{
    
    protected $_form;
    protected $_storage;
    
    // think about expanding to make constructor take form and storage as arguments?
    
    public function setForm($form=null) {
        if ($form instanceof Zend_Form) {
            $this->_form=$form;
            return $this;
        }
        throw new CG_Model_Exception("Argument must be an instance of Zend_Form",CG_Model_Exception::INVALID_ARGUMENT);
    }
    
    public function getForm() {
        return $this->_form;
    }
    
    abstract function setStorage($storage=null);
    abstract function getStorage();
}
