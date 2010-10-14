<?php
/**
 * unit tests for My_Auth_Adapter_Ldap
 * @todo finish properly documenting these tests
 * @category    My
 * @package     My_Tests
 * @version     0.0.1
 * @author      chancegarcia.com
 * @license     http://www.opensource.org/licenses/lgpl-3.0.html
 */
class My_Auth_Adapter_LdapTest extends PHPUnit_Framework_Testcase {
    protected $_fixture;
    
    protected function setUp(){
        $this->_fixture=new My_Auth_Adapter_Ldap();
    }
    
    protected function tearDown() {
        unset($this->_fixture);
    }
    /**
     * Ldap can sometimes give an error where it authenticates but doesn't find
     * the account object. 
     * This function replicates the auth result of this situation so that we
     * don't encounter this false negative.
     * @return Zend_Auth_Result
     */
    protected function _getLdapJustKiddingFailure()
    {
        return new Zend_Auth_Result(
            Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
            'ckent',
            array(
                "Account not found: ckent", 
                "0x20 (No such object; 0000208D: NameErr: DSID-031001CD, problem 2001 (NO_OBJECT), data 0, best match of: 'CN=Users,DC=stc,DC=corp' ): searching: (&(objectClass=user)(sAMAccountName=ckent))",
                 "host=10.1.0.245,port=389,baseDn=CN=USER,CN=USERS,DC=STC,DC=CORP,accountDomainName=stc.corp,accountDomainNameShort=STC1",
                 "0x20 (No such object; 0000208D: NameErr: DSID-031001CD, problem 2001 (NO_OBJECT), data 0, best match of: 'CN=Users,DC=stc,DC=corp' ): searching: (&(objectClass=user)(sAMAccountName=ckent))",
                 "host=10.1.0.246,port=389,baseDn=CN=USER,CN=USERS,DC=STC,DC=CORP,accountDomainName=stc.corp,accountDomainNameShort=STC1",
                 "Skipping previously failed authority: stc.corp", 
                 "ckent authentication failed: 0x20 (No such object; 0000208D: NameErr: DSID-031001CD, problem 2001 (NO_OBJECT), data 0, best match of: 'CN=Users,DC=stc,DC=corp' ): searching: (&(objectClass=user)(sAMAccountName=ckent))"
                )
            );
    }
    
    /**
     * Ldap can sometimes give an error where it authenticates but doesn't find
     * the account object.
     * 
     * This function replicates a valid error.
     * @return Zend_Auth_Result
     */
    protected function _getLdapValidFailure()
    {
        return new Zend_Auth_Result(
            Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
            'ckent',
            array(
                "Invalid credentials",
                "0x31 (Invalid credentials; 80090308: LdapErr: DSID-0C090334, comment: AcceptSecurityContext error, data 52e, vece): STC1\ckent",
                "host=10.1.0.245,port=389,baseDn=CN=USER,CN=USERS,DC=STC,DC=CORP,accountDomainName=stc.corp,accountDomainNameShort=STC1",
                "0x31 (Invalid credentials; 80090308: LdapErr: DSID-0C090334, comment: AcceptSecurityContext error, data 52e, vece): STC1\ckent",
                "host=10.1.0.246,port=389,baseDn=CN=USER,CN=USERS,DC=STC,DC=CORP,accountDomainName=stc.corp,accountDomainNameShort=STC1",
                "Skipping previously failed authority: stc.corp",
                "ckent authentication failed: 0x31 (Invalid credentials; 80090308: LdapErr: DSID-0C090334, comment: AcceptSecurityContext error, data 52e, vece): STC1\ckent"
                )
            );
        
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetLdapThrowExceptionForNonObject() {
        $this->_fixture->setLdap();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetLdapThrowsExceptionForNonZendAuthAdapterLdapObject() {
        $this->_fixture->setLdap($this);
    }
    
    public function testSetLdap() {
        $ldap=new Zend_Auth_Adapter_Ldap();
        $this->_fixture->setLdap($ldap);
        $this->assertAttributeEquals(
            $ldap,
            '_ldap',
            $this->_fixture
            );
    }
    
    public function testLdapPropertyInitializesNull() {
        $this->assertAttributeEquals(
            null,
            '_ldap',
            new My_Auth_Adapter_Ldap()
            );
    }
    
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetLdapThrowsExceptionForNullLdapProperty() {
        $this->_fixture->getLdap();
    }
    
    public function testGetLdap() {
        $ldap=new Zend_Auth_Adapter_Ldap();
        $this->_fixture->setLdap($ldap);
        $this->assertEquals($ldap,$this->_fixture->getLdap());
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetIdentityThrowsExceptionForNonString() {
        $this->_fixture->setIdentity();
    }
    
    public function testSetIdentity() {
        $this->_fixture->setIdentity('foo');
        $this->assertAttributeEquals(
            'foo',
            '_identity',
            $this->_fixture
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetIdentityThrowsExceptionForUnsetIdentity() {
        $this->_fixture->getIdentity();
    }
    
    public function testGetIdentity() {
        $this->_fixture->setIdentity('foo');
        $this->assertEquals('foo',$this->_fixture->getIdentity());
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetCredentialThrowsExceptionForNonString() {
        $this->_fixture->setCredential();
    }
    
    public function testSetCredential() {
        $this->_fixture->setCredential('bar');
        $this->assertAttributeEquals(
            'bar',
            '_credential',
            $this->_fixture
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetCredentialThrowsExceptionForUnsetCredential() {
        $this->_fixture->getCredential();
    }
    
    public function testGetCredential() {
        $this->_fixture->setCredential('baz');
        $this->assertEquals('baz',$this->_fixture->getCredential());
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistTableThrowsExceptionForNonObject() {
        $this->_fixture->setWhitelistTable();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistTableThrowsExceptionForNonZendDbTableAbstract() {
        $this->_fixture->setWhitelistTable($this);
    }
    
    public function testSetWhitelistTable() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        $table=$this->getMock('Zend_Db_Table');
        $selectMock=$this->getMock('Zend_Db_Select',null,array($mock));
        $table->expects($this->any())
        ->method('select')
        ->will($this->returnValue($selectMock));
        $this->_fixture->setWhitelistTable($table);
        $this->assertAttributeEquals(
            $table,
            '_whitelistTable',
            $this->_fixture
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetWhitelistTableThrowsExceptionForNullTableProperty() {
        $this->_fixture->getWhitelistTable();
    }
    
    public function testGetWhitelistTable() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        $table=$this->getMock('Zend_Db_Table');
        $selectMock=$this->getMock('Zend_Db_Select',null,array($mock));
        $table->expects($this->any())
        ->method('select')
        ->will($this->returnValue($selectMock));
        $this->_fixture->setWhitelistTable($table);
        $this->assertEquals($table,$this->_fixture->getWhitelistTable());
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistSelectThrowsExceptionForNonObject() {
        $this->_fixture->setWhitelistSelect();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistSelectThrowsExceptionForNonZendDbSelectObject() {
        $this->_fixture->setWhitelistSelect($this);
    }
    
    public function testSetWhitelistSelect() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        $table=$this->getMock('Zend_Db_Table');
        $selectMock=$this->getMock('Zend_Db_Select',null,array($mock));
        $table->expects($this->any())
        ->method('select')
        ->will($this->returnValue($selectMock));
        $this->_fixture->setWhitelistSelect($table->select());
        $this->assertAttributeEquals(
            $table->select(),
            '_whitelistSelect',
            $this->_fixture
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetWhitelistSelectThrowsExceptionForNullProperty() 
    {
        $this->_fixture->getWhitelistSelect();
    }
    
    public function testGetWhitelistSelectReturnsSetObject() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
       $table=$this->getMock('Zend_Db_Table');
        $selectMock=$this->getMock('Zend_Db_Select',null,array($mock));
        $table->expects($this->any())
        ->method('select')
        ->will($this->returnValue($selectMock));
        $this->_fixture->setWhitelistSelect($table->select());
        $this->assertEquals(
            $table->select(),
            $this->_fixture->getWhitelistSelect()
            );
    }
    
    public function testUseWhitelistInitializesTrue() {
        $this->assertAttributeEquals(
            true,
            "_useWhitelist",
            new My_Auth_Adapter_Ldap()
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetUseWhitelistThrowsExceptionForNonBool() {
        $this->_fixture->setUseWhitelist(null);
    }
    
    public function testSetUseWhitelist() {
        $this->_fixture->setUseWhitelist(false);
        $this->assertAttributeEquals(
            false,
            "_useWhitelist",
            $this->_fixture
            );
    }
    
    public function testGetUseWhitelist() {
        $this->_fixture->setUseWhitelist(false);
        $this->assertEquals(false,$this->_fixture->getUseWhitelist());
    }
    
    public function testWhitelistPropertyInitializesNull()
    {
        $this->assertAttributeEquals(
            null,
            '_whitelistEntry',
            new My_Auth_Adapter_Ldap()
            );
    }
    
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistEntryThrowsExceptionForNonArray() {
        $this->_fixture->setWhitelistEntry();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistEntryThrowsExceptionForMissingUsernameKey() {
        $expected=array(
            'role'=>'user'
            );
        $this->_fixture->setWhitelistEntry($expected);
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetWhitelistEntryThrowsExceptionForMissingRoleKey() {
        $expected=array(
            'username'=>'foo'
            );
        $this->_fixture->setWhitelistEntry($expected);
    }
    
    public function testSetWhitelistEntry() {
        $expected=array(
            'username'=>'foo',
            'password'=>null,
            'role'=>'user'
            );
        $this->_fixture->setWhitelistEntry($expected);
        $this->assertAttributeEquals(
            $expected,
            "_whitelistEntry",
            $this->_fixture
            );
    }
    
    public function testGetWhitelistEntryReturnsNullForUnsetWhitelist() {
        $this->assertNull($this->_fixture->getWhitelistEntry());
    }
    
    public function testGetWhitelistReturnsArrayForSetWhitelist() {
        $expected=array(
            'username'=>'foo',
            'password'=>null,
            'role'=>'user'
            );
        $this->_fixture->setWhitelist($expected);
        $this->assertType('array',$this->_fixture->getWhitelist());
    }
    
    public function testGetWhitelist() {
        $expected=array(
            'username'=>'foo',
            'password'=>null,
            'role'=>'user'
            );
        $this->_fixture->setWhitelist($expected);
        $this->assertEquals($expected,$this->_fixture->getWhitelist());
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetWhitelistEntryFromTableThrowsExceptionForNonObjectTableParameter() {
        // same as passing null,null
        $this->_fixture->getWhitelistEntryFromTable();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetWhitelistEntryFromTableThrowExceptionForNonZendDbTableAbstractTableParameter() {
        $this->_fixture->getWhitelistEntryFromTable(null,$this);
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetWhitelistEntryFromTableThrowsExceptionForNonNullNonObjectSelectArgument() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        // mock the table return
        $tableMock=$this->getMock('Zend_Db_Table');
        $this->_fixture->setWhitelistTable($tableMock);
        $this->_fixture->getWhitelistEntryFromTable("foo");
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetWhitelistEntryFromTableThrowsExceptionForNonZendDbSelectObjectSelectArgument() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        // mock the table return
        $tableMock=$this->getMock('Zend_Db_Table');
        $this->_fixture->setWhitelistTable($tableMock);
        $this->_fixture->getWhitelistEntryFromTable($this);
    }
    
    public function testGetWhitelistEntryFromTableReturnsNullForNoMatchesFound() {
        $arr=array();
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        // mock the table return
        $tableMock=$this->getMock('Zend_Db_Table');
        $rowsetMock=$this->getMock('Zend_Db_Table_Rowset',null,array(array('data'=>$arr)));
        $rowsetMock->expects($this->any())
        ->method('count')
        ->will($this->returnValue(0));
        
        $tableMock->expects($this->any())
        ->method('fetchAll')
        ->will($this->returnValue($rowsetMock));
        $select=$this->getMock('Zend_Db_Select',null,array($mock));
        $this->_fixture->setWhitelistTable($tableMock);
        $this->assertNull($this->_fixture->getWhitelistEntryFromTable($select));
    }
    
    public function testGetWhitelistEntryFromTableReturnsArray() {
        $arr=array(array('username'=>'foo','password'=>null,'role_id'=>1));
        
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        // mock the table return
        $tableMock=$this->getMock('Zend_Db_Table');
        $rowsetMock=$this->getMock('Zend_Db_Table_Rowset',null,array(array('data'=>$arr)));
        $rowsetMock->expects($this->any())
        ->method('count')
        ->will($this->returnValue(1));
        
        $tableMock->expects($this->any())
        ->method('fetchAll')
        ->will($this->returnValue($rowsetMock));
        $select=$this->getMock('Zend_Db_Select',null,array($mock));
        $this->_fixture->setWhitelistTable($tableMock);
        $this->assertType('array',$this->_fixture->getWhitelistEntryFromTable($select));
    }
    
    public function testGetWhitelistEntryFromTableReturnsZendDbRowsetAbstractObjectForObjectReturnParameterTrue() {
        $arr=array(array('username'=>'foo','password'=>null,'role_id'=>1));
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        // mock the table return
        $tableMock=$this->getMock('Zend_Db_Table');
        $rowsetMock=$this->getMock('Zend_Db_Table_Rowset',null,array(array('data'=>$arr)));
        $rowsetMock->expects($this->any())->method('count')->will($this->returnValue(0));
        $tableMock->expects($this->any())->method('fetchAll')->will($this->returnValue($rowsetMock));
        $select=$this->getMock('Zend_Db_Select',null,array($mock));
        $this->assertThat(
            $this->_fixture->getWhitelistEntryFromTable($select,$tableMock,true),
            $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract')
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testIsWhitelistedThrowsExceptionForNoIdentityPropertySet() {
        $arr=array('username'=>'foo','password'=>null,'role_id'=>1);
        $this->_fixture->setWhitelistEntry($arr);
        $this->_fixture->isWhitelisted();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testIsWhitelistedThrowsExceptionForMissingWhitelist() {
        $this->_fixture->setIdentity('foo');
        $this->_fixture->isWhitelisted();
    }
    
    /* should test for other exceptions that bubble. 
       time constraints mean come back later for that since they are covered
       in other tests. on a side note, if we do that, the above tests should
       also set those other expectancies to eliminate a false positive
    */
    
    public function testIsWhitelistedReturnFalseForUserNotFoundInWhitelistTable() {
        $arr=array();
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        Zend_Db_Table::setDefaultAdapter($mock);
        // mock the table return
        $tableMock=$this->getMock('Zend_Db_Table');
        // mock the tableRow
        $rowsetMock=$this->getMock('Zend_Db_Table_Rowset',null,array(array('data'=>$arr)));
        $rowsetMock->expects($this->any())
        ->method('count')
        ->will($this->returnValue(0));
        
        $tableMock->expects($this->once())
        ->method('fetchAll')
        ->will($this->returnValue($rowsetMock));
        $this->_fixture->setWhitelistTable($tableMock);
        $select=$this->getMock('Zend_Db_Select',null,array($mock));
        $this->_fixture->setWhitelistSelect($select);
        $this->_fixture->setIdentity('foo');
        $this->assertFalse($this->_fixture->isWhitelisted());
    }
    
    public function testIsWhitelistedReturnsTrueForEntryFoundInWhitelist() {
        $arr=array('username'=>'foo','password'=>null,'role'=>1);
        $this->_fixture->setWhitelistEntry($arr);
        $this->_fixture->setIdentity('foo');
        $this->assertTrue($this->_fixture->isWhitelisted());
        
    }
    
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetDbAuthThrowsExceptionForNonObject() {
        $this->_fixture->setDbAuth();
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetDbAuthThrowsExceptionForNonZendAuthAdapterDbTableObject() {
        $this->_fixture->setDbAuth($this);
    }
    
    public function testSetDbAuth() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        $table=new Zend_Auth_Adapter_DbTable($mock,'foo');        $this->_fixture->setDbAuth($table);
        $this->assertAttributeEquals(
            $table,
            '_dbAuth',
            $this->_fixture
            );
    }
    
    public function testDbAuthPropertyInitializesNull() {
        $this->assertAttributeEquals(
            null,
            '_dbAuth',
            new My_Auth_Adapter_Ldap()
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testGetDbAuthThrowsExceptionForNullProperty() {
        $this->_fixture->getDbAuth();
    }
    
    public function testGetDbAuth() {
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        $dbAuth=new Zend_Auth_Adapter_DbTable($mock);
        $this->_fixture->setDbAuth($dbAuth);
        $this->assertEquals($dbAuth,$this->_fixture->getDbAuth());
    }
    
    public function testTreatmentPropertyInitializesNull() {
        $this->assertAttributeEquals(
            null,
            '_treatment',
            new My_Auth_Adapter_Ldap()
            );
    }
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testSetTreatmentThrowsExceptionForNonString() {
        $this->_fixture->setTreatment();
    }
    
    public function testSetTreatment() {
        $this->_fixture->setTreatment('foo');
        $this->assertAttributeEquals(
            'foo',
            '_treatment',
            $this->_fixture
            );
    }
    
    public function testGetTreatment() {
        $this->_fixture->setTreatment('foo');
        $this->assertEquals('foo',$this->_fixture->getTreatment());
    }
    
    public function testResetTreatmentReturnsTreatementPropertyToNull() {
        $this->_fixture->setTreatment('foo');
        $this->assertAttributeEquals(
            'foo',
            '_treatment',
            $this->_fixture
            );
        
        $this->_fixture->resetTreatment();
        $this->assertAttributeEquals(
            null,
            '_treatment',
            $this->_fixture
            );
    }
    
    /**
     * @expectedException My_Auth_Adapter_Ldap_Exception
     */
    public function testDetermineMethodThrowsExceptionForNonArray() 
    {
        // could send null too b/c it will try to lazy load whitelist
         $this->_fixture->determineMethod('foo');
    }
    
    public function testDetermineMethodReturnsPublicCallbackLdapAuthenticateForNoPasswordKeyInWhitelist() {
        $this->assertEquals(My_Auth_Adapter_Ldap::LDAP_AUTHENTICATE,
        $this->_fixture->determineMethod(array()));
    }
    
    public function testDetermineMethodReturnsPublicCallbackLdapAuthenticateForNullPasswordInWhitelist() {
        $this->assertEquals(My_Auth_Adapter_Ldap::LDAP_AUTHENTICATE,
            $this->_fixture->determineMethod(array('password'=>null)));
    }
    
    public function testDetermineMethodReturnsPublicCallbackDbAuthenticateForNonNullPasswordInWhitelist() {
        $this->assertEquals(My_Auth_Adapter_Ldap::DB_AUTHENTICATE,
            $this->_fixture->determineMethod(array('password'=>'foo')));
    }
    
    public function testAuthenticateCallsIsWhitelistedMethod(){
        $mock=$this->getMock('My_Auth_Adapter_Ldap',array('isWhitelisted'));
        $mock
        ->expects($this->any())
        ->method('isWhitelisted')
        ->will($this->throwException(new Exception('test',11093)));
        try {
            $mock->authenticate();
        } catch (Exception $e) {
            if ($e->getCode()==11093) {
                return;
            }
            $this->fail('caught');
        }
        $this->fail('authenticate method failed to call isWhitelisted method');
    }
    
    /* if we make this return a result object, we wouldn't have to test the other returns. Since the authenticate method calls all the other testable
    methods, we should be covered.
    */
    public function testAuthenticateReturnsZendAuthResultForNonWhitelistedUsername() {
        $arr=array();
        $mock=$this->getMock('Zend_Db_Adapter_Pdo_Sqlite',null,array(),"",false);
        $tableMock=$this->getMock('Zend_Db_Table');
        $tableMock->expects($this->any())->method('getDefaultAdapter')->will($this->returnValue(true));
        $rowsetMock=$this->getMock('Zend_Db_Table_Rowset',null,array($arr));
        $tableMock->expects($this->any())->method('fetchAll')->will($this->returnValue($rowsetMock));
        $select=$this->getMock('Zend_Db_Select',null,array($mock));
        $this->_fixture->setWhitelistSelect($select);
        $this->_fixture->setWhitelistTable($tableMock);
        $this->_fixture->setIdentity('foo');
        $this->_fixture->setCredential('bar');
        $this->assertThat($this->_fixture->authenticate(),$this->isInstanceOf('Zend_Auth_Result'));
    }
    
    public function testLdapAuthenticateNoSuchObjectFailureReturnsSuccess()
    {
        $mock=$this->getMock('Zend_Auth_Adapter_Ldap');
        // mock will return the false positive
        $mock->expects($this->any())
        ->method('authenticate')
        ->will($this->returnValue($this->_getLdapJustKiddingFailure()));
        
        $this->_fixture->setIdentity('ckent');
        $this->_fixture->setCredential('iRsupa4!! break it to your mother represent');
        $this->_fixture->setLdap($mock);
        
        $this->assertTrue($this->_fixture->ldapAuthenticate()->isValid());
    }
    
    public function testLdapAuthenticateReturnsFailureForAllOtherFailures()
    {
        $mock=$this->getMock('Zend_Auth_Adapter_Ldap');
        $mock->expects($this->any())
        ->method('authenticate')
        ->will($this->returnValue($this->_getLdapValidFailure()));
        
        $this->_fixture->setIdentity('ckent');
        $this->_fixture->setCredential('iRsupa4!! break it to your mother represent');
        $this->_fixture->setLdap($mock);
        
        $this->assertFalse($this->_fixture->ldapAuthenticate()->isValid());
    }

}
