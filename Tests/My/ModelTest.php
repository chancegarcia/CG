<?php
/**
 * tests for CG_Model
 * @todo finish properly documenting these tests
 * @category    CG
 * @package     CG_Tests
 * @version     0.1
 * @author      chancegarcia.com
 * @license     http://www.opensource.org/licenses/lgpl-3.0.html
 */
class CGModelTest extends PHPUnit_Framework_Testcase {
    
    protected $_fixture;
    protected $_form;
    
    protected function setUp(){
        $this->_form=new Zend_Form();
        $this->_fixture=new CG_Model();
    }
    
    protected function tearDown(){
        unset($this->_form,$this->_fixture);
    }
    
    // _form
    /**
     * @expectedException CG_Model_Exception
     */
    public function testSetFormThrowsExceptionForNonObject() {
        $this->_fixture->setForm(true);
    }
    
    /**
     * @expectedException CG_Model_Exception
     */
    public function testSetFormThrowsExceptionForNonZendFormObject() {
        $this->_fixture->setForm($this);
    }
    
    public function testSetForm() {
        $this->_fixture->setForm($this->_form);
        $this->assertAttributeEquals(
          $this->_form,  /* expected value */
          '_form',  /* attribute name */
          $this->_fixture /* object         */
        );
    }
    
    public function testGetForm() {
        $this->_fixture->setForm($this->_form);
        $this->assertEquals($this->_form,$this->_fixture->getForm());
    }
    
    // _storage
    public function testSetStorage(){
        $this->_fixture->setStorage($this);
        $this->assertAttributeEquals(
          $this,  /* expected value */
          '_storage',  /* attribute name */
          $this->_fixture /* object         */
        );
    }
    
    public function testGetStorage(){
        $this->_fixture->setStorage($this);
        $this->assertEquals($this,$this->_fixture->getStorage());
    }
}
