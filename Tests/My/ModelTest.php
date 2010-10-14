<?php

require_once('PHPUnit/Framework.php');
require_once ('Zend/Loader/Autoloader.php');
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('My_');

class MyModelTest extends PHPUnit_Framework_Testcase {
    
    protected $_fixture;
    protected $_form;
    
    protected function setUp(){
        $this->_form=new Zend_Form();
        $this->_fixture=new My_Model();
    }
    
    protected function tearDown(){
        unset($this->_form,$this->_fixture);
    }
    
    // _form
    /**
     * @expectedException My_Model_Exception
     */
    public function testSetFormThrowsExceptionForNonObject() {
        $this->_fixture->setForm(true);
    }
    
    /**
     * @expectedException My_Model_Exception
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
