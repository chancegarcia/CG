<?php

/* 
 be sure to use bootstrap in test directory root

 object only manages single connections. refactor later to manage multiple?()
 */
/**
 * tests for My_Ssh
 * @todo only implementing a few of the functions. need to implement all in the future
 * @todo finish properly documenting these tests
 * @category    My
 * @package     My_Tests
 * @version     0.1
 * @author      chancegarcia.com
 * @license     http://www.opensource.org/licenses/lgpl-3.0.html
 */
class MySshTest extends PHPUnit_Framework_Testcase
{
    protected $_fixture;
    protected $_ini;
    protected $_hostname;
    protected $_username;
    protected $_password;
    
    protected function setUp()
    {
        $this->_fixture=new My_Ssh();
	$this->_ini=new Zend_Config_Ini('config/my/testSsh.ini','development');
	$this->_hostname=$this->_ini->hostname;
	$this->_username=$this->_ini->username;
	$this->_password=$this->_ini->password;
    }
    
    protected function tearDown()
    {
        unset($this->_fixture);
    }
    
    public function testFingerprintMd5Constant()
    {
        $this->assertEquals(
            SSH2_FINGERPRINT_MD5,
            My_Ssh::FINGERPRINT_MD5
            );
    }
    
    public function testFingerprintSha1Constant()
	{
		$this->assertEquals(
            SSH2_FINGERPRINT_SHA1,
            My_Ssh::FINGERPRINT_SHA1
            );
	}
    
    public function testFingerprintHexConstant()
	{
		$this->assertEquals(
            SSH2_FINGERPRINT_HEX,
            My_Ssh::FINGERPRINT_HEX
            );
	}
    
    public function testFingerprintRawConstant()
	{
		$this->assertEquals(
            SSH2_FINGERPRINT_RAW,
            My_Ssh::FINGERPRINT_RAW
            );
	}
    
    public function testTermUnitCharsConstant()
	{
		$this->assertEquals(
            SSH2_TERM_UNIT_CHARS,
            My_Ssh::TERM_UNIT_CHARS
            );
	}
    
    public function testTermUnitPixelsConstant()
	{
		$this->assertEquals(
            SSH2_TERM_UNIT_PIXELS,
            My_Ssh::TERM_UNIT_PIXELS
            );
	}
    
    public function testDefaultTermWidthConstant()
	{
		$this->assertEquals(
            SSH2_DEFAULT_TERM_WIDTH,
            My_Ssh::DEFAULT_TERM_WIDTH
            );
	}
    
    public function testDefaultTermHeightConstant()
	{
		$this->assertEquals(
            SSH2_DEFAULT_TERM_HEIGHT,
            My_Ssh::DEFAULT_TERM_HEIGHT
            );
	}
    
    public function testDefaultTermUnitConstant()
	{
		$this->assertEquals(
            SSH2_DEFAULT_TERM_UNIT,
            My_Ssh::DEFAULT_TERM_UNIT
            );
	}
    
    public function testStreamStdioConstant()
	{
		$this->assertEquals(
            SSH2_STREAM_STDIO,
            My_Ssh::STREAM_STDIO
            );
	}
    
    public function testStreamStderrConstant()
	{
		$this->assertEquals(
            SSH2_STREAM_STDERR,
            My_Ssh::STREAM_STDERR
            );
	}
    
    public function testDefaultTerminalConstant()
	{
		$this->assertEquals(
            SSH2_DEFAULT_TERMINAL,
            My_Ssh::DEFAULT_TERMINAL
            );
	}
	
	public function testHostnamePropertyInitializesNull()
	{
	    $this->assertAttributeEquals(
	        null,
	        "_hostname",
	        $this->_fixture
	        );
	}
	
	/**
	 * @expectedException My_Ssh_Exception
	 */
	public function testSetHostnameThrowsExceptionForNonString()
	{
	    $this->_fixture->setHostname(null);
	}
	
	public function testSetHostname()
	{
	    $this->_fixture->setHostname('foo');
	    $this->assertAttributeEquals(
	        'foo',
	        '_hostname',
	        $this->_fixture
	        );
	}
	
	public function testGetHostname()
	{
	    $this->_fixture->setHostname('bar');
	    $this->assertEquals(
	        'bar',
	        $this->_fixture->getHostname()
	        );
	}
	
	public function testPortPropertyInitializes22()
	{
	    $this->assertAttributeEquals(
	        22,
	        "_port",
	        $this->_fixture
	        );
	}
	
	/**
	 * @expectedException My_Ssh_Exception
	 */
	public function testSetPortThrowsExceptionForNonNumeric()
	{
	    $this->_fixture->setPort(null);
	}
	
	/**
	 * @expectedException My_Ssh_Exception
	 */
	public function testSetPortThrowsExceptionForNonIntNumeric()
	{
	    $this->_fixture->setPort(1.234);
	}
	
	public function testSetPort()
	{
	    $this->_fixture->setPort(443);
	    $this->assertAttributeEquals(
	        443,
	        "_port",
	        $this->_fixture
	        );
	}
	
	/**
	 * @depends testPortPropertyInitializes22
	 */
	public function testGetPort()
	{
	    $this->assertEquals(
	        22,
	        $this->_fixture->getPort()
	        );
	}
	
	public function testUsernamePropertyInitializesNull()
	{
	    $this->assertAttributeEquals(
	        null,
	        "_username",
	        $this->_fixture
	        );
	}
	
	/**
	 * @expectedException My_Ssh_Exception
	 */
	public function testSetUsernameThrowsExceptionForNonString()
	{
	    $this->_fixture->setUsername(null);
	}
	
	public function testSetUsername()
	{
	    $this->_fixture->setUsername('foo');
	    $this->assertAttributeEquals(
	        'foo',
	        '_username',
	        $this->_fixture
	        );
	}
	
	/**
	 * @depends testUsernamePropertyInitializesNull
	 */
	public function testGetUsername()
	{
	    $this->assertEquals(
	        null,
	        $this->_fixture->getUsername()
	        );
	}
	
	public function testIdentityFilePropertyInitializesNull()
	{
	    $this->assertAttributeEquals(
	        null,
	        "_identityFile",
	        $this->_fixture
	        );
	}
	
	public function testSetIdentityFileThrowsExceptionForNonFile()
	{
	    $dir='/tmp/isDir';
	    mkdir($dir);
	    try 
	    {
	        $this->_fixture->setIdentityFile($dir);
	    } catch (My_Ssh_Exception $mse) {
	        rmdir($dir);
	        if ($mse->getCode()==My_Ssh_Exception::INVALID_IDENTITY_FILE)
	        {
	            return;
	        }
	    }
	    rmdir($dir);
	    $this->fail('failed to catch expected exception for setIdentityFile()');
	}
	
	public function testSetIdentityFile()
	{
	    $file='/tmp/isFile';
	    touch($file);
	    $this->_fixture->setIdentityFile($file);
	    $this->assertAttributeEquals(
	        $file,
	        '_identityFile',
	        $this->_fixture
	        );
	    unlink($file);
	}
	
	/**
	 * @depends testIdentityFilePropertyInitializesNull
	 */
	public function testGetIdentityFile()
	{
	    $this->assertEquals(
	        null,
	        $this->_fixture->getIdentityFile()
	        );
	}
	
	public function testPasswordPropertyInitializesNull()
	{
	    $this->assertAttributeEquals(
	        null,
	        "_password",
	        $this->_fixture
	        );
	}
	
	/**
	 * @expectedException My_Ssh_Exception
	 */
	public function testSetPasswordThrowsExeptionForNonString()
	{
	    $this->_fixture->setPassword(null);
	}
	
	public function testSetPassword()
	{
	    $this->_fixture->setPassword('foo');
	    $this->assertAttributeEquals(
	        'foo',
	        '_password',
	        $this->_fixture
	        );
	}
	
	/**
	 * @depends testPasswordPropertyInitializesNull
	 */
	public function testGetPassword()
	{
	    $this->assertEquals(
	        null,
	        $this->_fixture->getPassword()
	        );
	}
	
	public function testConnectionPropertyInitializesFalse()
	{
	    $this->assertAttributeEquals(
	        false,
	        "_connection",
	        $this->_fixture
	        );
	}
	
	/**
	 * @expectedException My_Ssh_Exception
	 */
	public function testSetConnectionThrowsExceptionForNonResource()
	{
	    $this->_fixture->setConnection(null);
		$this->markTestIncomplete();
	}
	
	public function testSetConnectionThrowsExceptionForNonSsh2SessionResource()
	{
	    $d=opendir('/tmp');
	    try{
	        $this->_fixture->setConnection($d);
	    } catch (My_Ssh_Exception $mse) {
	        closedir($d);
	        if ($mse->getCode()==My_Ssh_Exception::INVALID_RESOURCE_TYPE)
	        {
	            return;
	        }
	    }
	    closedir($d);
	    $this->fail("failed to catch exception in setConnection() for invalid resource type");
	}
	
    public function testSsh2ConnectFunctionExists()
    {
        $this->assertTrue(function_exists('ssh2_connect'));  
    }
    
    /**
     * @depends testSsh2ConnectFunctionExists
     */
	public function testSetConnection()
	{
	    $con=ssh2_connect($this->_hostname);
	    $this->_fixture->setConnection($con);
	    $this->assertAttributeEquals(
	        $con,
	        '_connection',
	        $this->_fixture
	        );
	    unset($con);
	}
	
    /**
     * @depends testSsh2ConnectFunctionExists
     */
	public function testGetConnectionReturnsValueFromImplicitSetConnnection()
	{
	    $con=ssh2_connect($this->_hostname);
	    $this->_fixture->setConnection($con);
	    $this->assertEquals(
	        $con,
	        $this->_fixture->getConnection()
	        );
	}
	
	/**
	 * @depends testConnectionPropertyInitializesFalse
	 */
	public function testGetConnection()
	{
	    $this->assertEquals(
	        false,
	        $this->_fixture->getConnection()
	        );
	}
	
	/**
     * @depends testSsh2ConnectFunctionExists
	 * @depends testSetConnection
	 */
	public function testResetConnectionSetsConnectionPropertyToFalse()
	{
	    $con=ssh2_connect($this->_hostname);
	    $this->_fixture->setConnection($con);
	    $this->_fixture->resetConnection();
	    $this->assertAttributeEquals(
	        false,
	        '_connection',
	        $this->_fixture
	        );
	    unset($con);
	}
	
	/**
	 * @depends testHostnamePropertyInitializesNull
	 * @expectedException My_Ssh_Exception
	 */
	public function testConnectThrowsExecptionForNullHostname()
	{
	    $this->_fixture->connect();
	}
	
	// error reporting is on so have to expect this error/exception* @depends testSsh2ConnectFunctionExists
	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function testConnectReturnsFalseOnError()
	{
	    $this->_fixture->setHostname('foo.fail');
	    $this->assertEquals(
	        false,
	        $this->_fixture->connect()
	        );
	}
	
	/**
     * @depends testSsh2ConnectFunctionExists
     */
	public function testConnectReturnsResourceOnSuccess()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->assertType(
	        'resource',
	        $this->_fixture->connect()
	        );
	}
	
	/**
	 * @depends testSsh2ConnectFunctionExists
	 * @depends testConnectReturnsResourceOnSuccess
	 */
	public function testConnectReturnsSsh2SessionResourceOnSuccess()
	{
	    // doesn't look like phpunit will let me check resource type easily
	    $this->_fixture->setHostname($this->_hostname);
	    $resource=$this->_fixture->connect();
	    $this->assertEquals(
	        'SSH2 Session',
	        get_resource_type($resource)
	        );
	    unset($resource);
	}

	// will implement way to derive knownhosts sometime later. ditto with fingerprint
	public function testKnownHostsPropertyInitializesNull()
	{
	    $this->assertAttributeEquals(
	        null,
	        "_knownHosts",
	        $this->_fixture
	        );
	}
	
	public function testConstructorCallsInit()
	{
	    $msg="was called";
	    $mock=$this->getMock('My_Ssh');
	    $mock->expects($this->any())
	    ->method('init')
	    ->will($this->throwException(new Exception($msg,0)));
	    try
	    {
	        $mock->__construct();
	    } catch (Exception $e) {
	        if ($msg==$e->getMessage())
	        {
	            return;
	        }
	        $this->fail("Unknown exception caught");
	    }
	    $this->fail("failed to detect call to method init()");
	}
	
	public function testInitReturnsSelf()
	{
	    $this->assertEquals(
	        new My_Ssh(),
	        $this->_fixture->init()
	        );
	}
	
	public function testOptionsPropertyInitializesNull()
	{
	    $this->assertAttributeEquals(
	        null,
	        "_options",
	        $this->_fixture
	        );
	}
	
	/**
     * @expectedException My_Ssh_Exception
     */
	public function testSetOptionsThrowsExceptionForNonArray()
	{
		$this->_fixture->setOptions(null);
	}
	
	public function testSetOptions()
	{
	    $opts=array(
	        'hostname'=>'foo',
	        'username'=>'bar',
	        'password'=>'baz'
	        );
	    $this->_fixture->setOptions($opts);
	    // assertions should be in separate tests
	    $this->assertAttributeEquals(
	        $opts,
	        '_options',
	        $this->_fixture
	        );
	    $this->assertEquals(
	        $opts['hostname'],
	        $this->_fixture->getHostname()
	        );
	    $this->assertEquals(
	        $opts['username'],
	        $this->_fixture->getUsername()
	        );
	    $this->assertEquals(
	        $opts['password'],
	        $this->_fixture->getPassword()
	        );
	}
	
	public function testGetOptions()
	{
		$opts=array(
		    'hostname'=>'foo',
		    'username'=>'bar',
		    'baz'=>'bzr'
		    );
		$this->_fixture->setOptions($opts);
		$this->assertEquals(
		    $opts,
		    $this->_fixture->getOptions()
		    );
	}
	
	public function testAuthenticatedPropertyInitializesFalse()
	{
	    $this->assertAttributeEquals(
	        false,
	        "_authenticated",
	        $this->_fixture
	        );
	}
	
	/**
	 * @depends testAuthenticatedPropertyInitializesFalse
	 */
	public function testAuthenticated()
	{
		$this->assertEquals(
		    false,
		    $this->_fixture->authenticated()
		    );
	}
	
	public function testSsh2AuthPasswordFunctionExists()
	{
	    $this->assertTrue(function_exists('ssh2_auth_password'));
	}
	
	/* 
	maybe make way to choose auth method in the future. for now user/pass only
	also think about making it attempt a connection in the future
	*/
	/**
	 * @depends testSsh2AuthPasswordFunctionExists
	 * @expectedException My_Ssh_Exception
	 */
	public function testAuthPasswordMethodThrowsExceptionForMissingConnectionProperty()
	{
	    $this->_fixture->setUsername('foo');
	    $this->_fixture->setPassword('baz');
	    $this->_fixture->authPassword();
	}
	
	// really should be able to pass arguments to the method in the future
	/**
	 * @depends testSsh2AuthPasswordFunctionExists
	 * @depends testUsernamePropertyInitializesNull
	 * @expectedException My_Ssh_Exception
	 */
	public function testAuthPasswordThrowsExceptionForMissingUsernameProperty()
	{
	    $this->_fixture->setPassword('baz');
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->authPassword();
	}
	
	// error reporting is on so have to expect this error/exception 
	/**
	 * @depends testSsh2AuthPasswordFunctionExists
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @depends testAuthenticated
	 */
	public function testAuthPasswordSetsAuthenticatedPropertyToReturnValueFalse()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername('foo');
	    $this->_fixture->setPassword('baz');
	    $this->_fixture->authPassword();
	    $this->assertEquals(
	        false,
	        $this->_fixture->authenticated()
	        );
	}
	
	/**
	 * @depends testSsh2AuthPasswordFunctionExists
	 * @depends testAuthenticated
	 */
	public function testAuthPasswordSetsAuthenticatedPropertyToReturnValueTrue()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername($this->_username);
	    $this->_fixture->setPassword($this->_password);
	    $this->_fixture->authPassword();
	    $this->assertTrue(
	        $this->_fixture->authenticated()
	        );
	}
	
	// error reporting is on so have to expect this error/exception
	/**
	 * @depends testSsh2AuthPasswordFunctionExists
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function testAuthPasswordReturnFalse()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername('foo');
	    $this->_fixture->setPassword('baz');
	    $this->assertEquals(
	        false,
	        $this->_fixture->authPassword()
	        );
	}
	
	/**
	 * @depends testSsh2AuthPasswordFunctionExists
	 */
	public function testAuthPasswordReturnTrue()
	{
	    
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername($this->_username);
	    $this->_fixture->setPassword($this->_password);
	    $this->assertTrue(
	        $this->_fixture->authPassword()
	        );
	}
	
	public function testSsh2ExecFunctionExists()
	{
	    $this->assertTrue(function_exists('ssh2_exec'));
	}
	
	/**
	 * @depends testSsh2ExecFunctionExists
	 * @expectedException My_Ssh_Exception
	 */
	public function testExecThrowsExceptionForNonStringArgument()
	{
	    $this->_fixture->exec(null);
	}
	
	/**
	 * @depends testSsh2ExecFunctionExists
	 */
	public function testExecReturnsFalseWhenAuthenticatedPropertyIsFalse()
	{
	    $this->assertFalse($this->_fixture->exec('pwd'));
	}
	
	/**
	 * @depends testSsh2ExecFunctionExists
	 * @depends testResetConnectionSetsConnectionPropertyToFalse
	 * @expectedException My_Ssh_Exception
	 */
	public function testExecThrowsExceptionForMissingConnection()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername($this->_username);
	    $this->_fixture->setPassword($this->_password);
	    $this->_fixture->authPassword();
	    $this->_fixture->resetConnection();
		$this->_fixture->exec('pwd');
	}
	
	// don't know how to make a failure. unless you can mock php functions
	// this should never hit this state except when mocked. possibly on regression
	/**
	 * @depends testSsh2ExecFunctionExists
	 * @depends testSsh2AuthPasswordFunctionExists
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function testExecReturnsFalseOnFailureAndAuthenticatedPropertyIsTrue()
	{
	    $mock=$this->getMock('My_Ssh',
	        array(
	            "authenticated",
	            "exec"
	            ));
	    $mock->expects($this->any())
	    ->method('authenticated')
	    ->will($this->returnValue(true));
	    $this->assertFalse($mock->exec('pwd'));
	}
	
	// only attempt exec command when authenticated
	/**
	 * @depends testSsh2ExecFunctionExists
	 * @depends testAuthenticated
	 */
	public function testExecReturnsResourceOnSuccessAndAuthenticatedPropertyIsTrue()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername($this->_username);
	    $this->_fixture->setPassword($this->_password);
	    $this->_fixture->authPassword();
	    $this->assertType(
	        'resource',
	        $this->_fixture->exec('pwd')
	        );
	}
	
	/**
	 * @depends testSsh2ExecFunctionExists
	 * @depends testExecReturnsResourceOnSuccessAndAuthenticatedPropertyIsTrue
	 */
	public function testExecReturnsStreamResourceOnSuccessAndAuthenticatedPropertyIsTrue()
	{
	    $this->_fixture->setHostname($this->_hostname);
	    $this->_fixture->setUsername($this->_username);
	    $this->_fixture->setPassword($this->_password);
	    $this->_fixture->authPassword();
        $resource=$this->_fixture->exec('pwd');
        $this->assertEquals(
            'stream',
            get_resource_type($resource)
            );
	}
	
	public function testSsh2FetchStreamFunctionExists()
	{
	    $this->assertTrue(function_exists('ssh2_fetch_stream'));
	}
	
	/**
	 * @depends testSsh2FetchStreamFunctionExists
	 * @expectedException My_Ssh_Exception
	 */
	public function testFetchStreamThrowsExceptionForNonResourceStreamArgument()
	{
		$this->markTestSkipped("to be implemented later or as needed");
	}
	
	/**
	 * @depends testSsh2FetchStreamFunctionExists
	 */
	public function testFetchStream()
	{
		$this->markTestSkipped("to be implemented later or as needed");
	}
}
