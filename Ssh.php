<?php
/**
 * Encapsulate the ssh2 PECL library [@link http://www.php.net/manual/en/book.ssh2.php]
 * into a class for reuse.
 * @todo only implementing a few of the functions. need to implement all in the future
 * @category   CG
 * @version 0.1
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
class CG_Ssh{
    
    const FINGERPRINT_SHA1=SSH2_FINGERPRINT_SHA1;
    const FINGERPRINT_MD5=SSH2_FINGERPRINT_MD5;
    const FINGERPRINT_HEX=SSH2_FINGERPRINT_HEX;
    const FINGERPRINT_RAW=SSH2_FINGERPRINT_RAW;
    const TERM_UNIT_CHARS=SSH2_TERM_UNIT_CHARS;
    const TERM_UNIT_PIXELS=SSH2_TERM_UNIT_PIXELS;
    const DEFAULT_TERM_WIDTH=SSH2_DEFAULT_TERM_WIDTH;
    const DEFAULT_TERM_HEIGHT=SSH2_DEFAULT_TERM_HEIGHT;
    const DEFAULT_TERM_UNIT=SSH2_DEFAULT_TERM_UNIT;
    const STREAM_STDIO=SSH2_STREAM_STDIO;
    const STREAM_STDERR=SSH2_STREAM_STDERR;
    const DEFAULT_TERMINAL=SSH2_DEFAULT_TERMINAL;
    
    /**
     * @var string
     */
    protected $_hostname=null;
    
    /**
     * @var int
     */
    protected $_port=22;
    
    /**
     * @var string
     */
    protected $_username=null;
    
    /**
     * @var string
     */
    protected $_password=null;
    
    /**
     * @var bool
     */
    protected $_authenticated=false;
    
    /**
     * @var resource|bool value is false or of resource type "SSH2 Session"
     */
    protected $_connection=null;
    
    /**
     * @var string path to known hosts file
     */
    protected $_knownHosts=null;
    
    /**
     * @var string path to identity file
     */
    protected $_identityFile=null;
    
    /**
     * @var array
     */
    protected $_options=null;
    
    /**
     * Create new instance of SSH client with optional options
     * @param array $options options array with keys equal to setable properties
     * @return CG_Ssh returns self for fluid interface
     */
    public function __construct($options=null)
    {
        if (is_array($options))
        {
            $this->setOptions($options);
        }
        $this->init();
        return $this;
    }
    
    /**
     * Any class that extends this can initialize child construction operations
     * safely here.
     * @return CG_Ssh returns self for fluid interface
     */
    public function init()
    {
        return $this;
    }
    
    /**
     * set hostname property
     * @param string $hostname host to establish connection with
     * @return CG_Ssh returns self for fluid interface
     */
    public function setHostname($hostname=null)
    {
        if (!is_string($hostname))
        {
            throw new CG_Ssh_Exception(
                "Please provide hostname as a string.",
                CG_Ssh_Exception::INVALID_HOSTNAME
                );
        }
        
        $this->_hostname=$hostname;
        return $this;
    }
    
    /**
     * Retrieve protected hostname property
     * @return null|string
     */
    public function getHostname()
    {
        return $this->_hostname;
    }
    
    /**
     * Set port property
     * @param int $port port to connect to
     * @return CG_Ssh return self for fluid interface
     */
    public function setPort($port=null)
    {
        if (!is_numeric($port)||!is_int($port))
        {
            throw new CG_Ssh_Exception(
                "Port must be a valid integer value.",
                CG_Ssh_Exception::INVALID_PORT
                );
        }
        $this->_port=$port;
        return $this;
    }
    
    /**
     * Return protected port property
     * @return int
     */
    public function getPort()
    {
        return $this->_port;
    }
    
    /**
     * Set protected username property
     * @param string $username username need for authentication
     * @return CG_Ssh return self for fluid interface
     */
    public function setUsername($username=null)
    {
        if (!is_string($username))
        {
            throw new CG_Ssh_Exception(
                "please provide a valid string",
                CG_Ssh_Exception::INVALID_USERNAME
                );
        }
        $this->_username=$username;
        return $this;
    }
    
    /**
     * Get protected username property
     * @return null|string
     */
    public function getUsername()
    {
        return $this->_username;
    }
    
    /**
     * Set path to identity file. Absolute path is recommended for best results
     * @param string $identityFile path to identity file
     * @return CG_Ssh return self for fluid interface
     */
    public function setIdentityFile($identityFile=null)
    {
        if (!is_file($identityFile))
        {
            throw new CG_Ssh_Exception(
                "please provide a path to the identity file",
                CG_Ssh_Exception::INVALID_IDENTITY_FILE
                );
        }
        $this->_identityFile=$identityFile;
        return $this;
    }
    
    /**
     * Get protected identity file property
     * @return null|string
     */
    public function getIdentityFile()
    {
        return $this->_identityFile;
    }
    
    /**
     * set password property
     * @param string $password
     * @return CG_Ssh return self for fluid interface
     */
    public function setPassword($password=null)
    {
        if (!is_string($password))
        {
            throw new CG_Ssh_Exception(
                "please provide a string value",
                CG_Ssh_Exception::INVALID_PASSWORD_VALUE
                );
        }
        $this->_password=$password;
        return $this;
    }
    
    /**
     * get protected password property
     * @return null|string
     */
    public function getPassword()
    {
        return $this->_password;
    }
    
    /**
     *  Set an existing ssh connection to use
     * @param bool|resource $connection set the connection to false when needing to return to default state
     * @return CG_Ssh return self for fluid interface
     */
    public function setConnection($connection=null)
    {
        if (!is_resource($connection) || "SSH2 Session"!==get_resource_type($connection)) 
        {
            throw new CG_Ssh_Exception(
                "please provide value false to reset connection or a valid connection",
                CG_Ssh_Exception::INVALID_RESOURCE_TYPE
                );
        }
        
        $this->_connection=$connection;
        return $this;
    }
    
    /**
     * get protected connection property
     * @return bool|resource returns false for no connection set
     */
    public function getConnection()
    {
        return $this->_connection;
    }
    
    /**
     * set connection property back to false
     * @return CG_Ssh return self for fluid interface
     */
    public function resetConnection()
    {
        $this->_connection=false;
        return $this;
    }
    
    /**
     * set protected options property. uses array with keys equal to setable options
     * @param array $options setable options as key/value pairs. silently ignores invalid property setters
     * @return CG_Ssh return self for fluid interface
     */
    public function setOptions($options=null)
    {
        if (!is_array($options))
        {
            throw new CG_Ssh_Exception(
                "please give an array of valid options",
                CG_Ssh_Exception::INVALID_OPTIONS
                );
        }
        
        $this->_options=$options;
        
        foreach ($options as $k=>$v)
        {
            $method="set".ucfirst($k);
            if (method_exists($this,$method))
            {
                $this->{$method}($v);
            }
        }
        return $this;
    }
    
    /**
     * get protected options property
     * @return null|array
     */
    public function getOptions()
    {
        return $this->_options;
    }
    
    /**
     * find out if an the connection has already been authenticated correctly
     * @return bool
     */
    public function authenticated()
    {
        return $this->_authenticated;
    }
    
    /**
     * Using the hostname and port properties, establish a connection.
     * If a resource value was set by {@link setConnection()}, return the connection set.
     * @return bool|resource returns false on errors and resource on success
     */
    public function connect()
    {
        if (!is_string($this->getHostname()))
        {
            throw new CG_Ssh_Exception(
                "please provide a host to connect to.",
                CG_Ssh_Exception::MISSING_HOSTNAME
                );
        }
        
        $this->_connection=ssh2_connect($this->getHostname(),$this->getPort());
        return $this->_connection;
    }
    
    /**
     * authenticate ssh connection using username and password properties
     * @return bool success or failure of authentication attempt
     */
    public function authPassword()
    {
        // don't try to auth again after success. (hangs in cli and write future test)
        if ($this->_authenticated===true)
        {
            return true;
        }
        
        $con=$this->connect();
        if (false===$con)
        {
            throw new CG_Ssh_Exception(
                "failed to establish connection for authentication attempt",
                CG_Ssh_Exception::FAILED_CONNECTION
                );
        }
        
        $username=$this->getUsername();
        if (null===$username)
        {
            throw new CG_Ssh_Exception(
                "unable to find username to authenticate with",
                CG_Ssh_Exception::MISSING_USERNAME
                );
        }
        
        $this->_authenticated=ssh2_auth_password($con,$username,$this->getPassword());
        return $this->_authenticated;
    }
    
    /**
     * @todo take out the escaping of the command. put the onus on the user or develop smart detection system
     * Remotely execute a command
     */
    public function exec($cmd=null)
    {
        if (!is_string($cmd))
        {
            throw new CG_Ssh_Exception(
                "please provide a string command",
                CG_Ssh_Exception::INVALID_COMMAND_ARGUMENT
                );
        }
        
        if (false===$this->_authenticated)
        {
            return false;
        }
        
        if (!is_resource($this->getConnection()))
        {
            throw new CG_Ssh_Exception(
                "unable to find connection",
                CG_Ssh_Exception::MISSING_CONNECTION
                );
        }
        
        return ssh2_exec($this->getConnection(),escapeshellcmd($cmd));
    }
}
