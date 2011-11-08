<?
/**
 * Custom auth adapter that can read a whitelist before attempting an ldap
 * query.
 * 
 * @category   CG
 * @package    CG_Auth
 * @subpackage CG_Auth_Adapter
 * @version 0.0.1
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
 class CG_Auth_Adapter_Ldap extends CG_Auth_Adapter_Ldap_Abstract
 {
    const LDAP_AUTHENTICATE="ldapAuthenticate";
    const DB_AUTHENTICATE="dbAuthenticate";
    
    protected $_ldap=null;
    protected $_dbAuth=null;
    protected $_whitelistTable=null;
    protected $_whitelistSelect=null;
    protected $_useWhitelist=true;
    protected $_whitelistEntry=null;
    protected $_identity=null;
    protected $_credential=null;
    protected $_treatment=null;
    
    public function authenticate() 
    {
        $messages=array();
        if ($this->isWhitelisted())
        {
            $entry=$this->getWhitelistEntry();
            if (null===$entry)
            {
                $rows=$this->getWhitelistEntryFromTable();
                $entry=$rows[0];
            }
            
            if ($auth=$this->determineMethod($entry)) {
                if (method_exists($this,$auth)){
                    /*
                    roll them together here later. only diff is treatment?
                    */
                    return $this->{$auth}();
                } else {
                    $messages[]="Unknown callback given";
                    return Zend_Auth_Result(
                        Zend_Auth_Result::FAILURE_UNCATEGORIZED,
                        $this->getIdentity(),
                        $messages
                        );
                }
            } else {
                $messages[]="Unknown callback given";
                return Zend_Auth_Result(
                    Zend_Auth_Result::FAILURE_UNCATEGORIZED,
                    $this->getIdentity(),
                    $messages
                    );
            }
        } 
        else 
        {
            $messages[]="User not found in whitelist. Please add to whitelist to begin authentication process.";
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                $this->getIdentity(),
                $messages
                );
        }
    }
    
    public function ldapAuthenticate()
    {
        $ldap=$this->getLdap();
        $ldap->setIdentity($this->getIdentity());
        $ldap->setCredential($this->getCredential());
        $result=$ldap->authenticate();
        // test this shit pls
        if (!$result->isValid())
        {
            $messages=$result->getMessages();
            preg_match(
                "/0x[0-9a-f]{2,4}/",
                $messages[1], // can also be found in 3 and 6
                $matches
                );
            switch ($matches[0]){
            // match up to exception code
            case Zend_Ldap_Exception::LDAP_NO_SUCH_OBJECT: // 0x20
                $finalMsg[0]="LDAP was just kidding. We really did succeed. it's just the LDAP implementation that's fail. See the other part of this array to know more.";
                $finalMsg[1]=$messages;
                $result=new Zend_Auth_Result(
                Zend_Auth_Result::SUCCESS,
                $this->getIdentity(),
                $finalMsg
                );
                break;
            default:
                break;
            }
        }
        return $result;
    }
    
    public function dbAuthenticate()
    {
        $db=$this->getDbAuth();
        $db->setIdentity($this->getIdentity());
        $db->setCredential($this->getCredential());
        $db->setCredentialTreatment($this->getTreatment());
        return $db->authenticate();
    }
    
    public function getLdap()
    {
        if ($this->_ldap===null)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an instance of Zend_Auth_Adapter_Ldap",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        return $this->_ldap;
    }
    
    public function setLdap($ldap=null)
    {
        if (!is_object($ldap)||!$ldap instanceof Zend_Auth_Adapter_Ldap)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an instance of Zend_Auth_Adapter_Ldap",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_ldap=$ldap;
        return $this;
    }
    
    public function setDbAuth($dbAuth=null) {
        if (!is_object($dbAuth)||!$dbAuth instanceof Zend_Auth_Adapter_DbTable) 
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an instance of Zend_Auth_Adapter_DbTable",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_dbAuth=$dbAuth;
        return $this;
    }
    
    public function getDbAuth()
    {
        if (null===$this->_dbAuth)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an instance of Zend_Auth_Adapter_DbTable",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        return $this->_dbAuth;
    }
    
    public function setIdentity($identity=null)
    {
        if (!is_string($identity))
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a string for identity",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_identity=$identity;
        return $this;
    }
    
    public function getIdentity()
    {
        if (null===$this->_identity)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please set identity with a string to continue",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        return $this->_identity;
    }
    
    public function setCredential($credential=null)
    {
        if (!is_string($credential))
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a string for credential",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_credential=$credential;
        return $this;
    }
    
    public function getCredential()
    {
        if (null===$this->_credential)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please set credential with a string to continue",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        return $this->_credential;
    }
    
    public function setTreatment($treatment=null) {
        if (!is_string($treatment))
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a string",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_treatment=$treatment;
        return $this;
    }
    
    public function getTreatment()
    {
        return $this->_treatment;
    }
    
    public function resetTreatment()
    {
        $this->_treatment=null;
    }
    
    public function determineMethod($entry=null) {
        if (null===$entry)
        {
            $entry=$this->getWhitelistEntry();
        }
        // should do as credential column?
        if (!is_array($entry))
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a valid array with expected keys",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        if (!array_key_exists('password',$entry))
        {
            return CG_Auth_Adapter_Ldap::LDAP_AUTHENTICATE;
        }
        else if (null===$entry['password'])
        {
            return CG_Auth_Adapter_Ldap::LDAP_AUTHENTICATE;
        } 
        else 
        {
            return CG_Auth_Adapter_Ldap::DB_AUTHENTICATE;
        }
        return false;
    }
    
    public function getWhitelistTable()
    {
        if ($this->_whitelistTable===null)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an instance of Zend_Db_Table_Abstract",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        return $this->_whitelistTable;
    }
    
    public function setWhitelistTable($table=null) {
        if (!is_object($table)||!$table instanceof Zend_Db_Table_Abstract)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an instance of Zend_Db_Table_Abstract",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_whitelistTable=$table;
        return $this;
    }
    
    public function setWhitelistSelect($select=null) {
        if (!is_object($select)||!$select instanceof Zend_Db_Select)
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a valid instance of Zend_Db_Select",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        
        $this->_whitelistSelect=$select;
        return $this;
    }
    
    public function getWhitelistSelect() {
        if ($this->_whitelistSelect===null) {
           throw new CG_Auth_Adapter_Ldap_Exception(
                "Whitelist select object has not been set.",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        return $this->_whitelistSelect;
    }
    
    public function setUseWhitelist($bool=null) {
        if (!is_bool($bool))
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a valid boolean value",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        $this->_useWhitelist=$bool;
        return $this;
    }
    
    public function getUseWhitelist() {
        return $this->_useWhitelist;
    }
    
    public function setWhitelist($whitelist=null)
    {
        return $this->setWhitelistEntry($whitelist);
    }
    
    public function getWhitelist()
    {
        return $this->getWhitelistEntry();
    }
    
    public function setWhitelistEntry($whitelistEntry=null)
    {
        if (!is_array($whitelistEntry)
            ||!array_key_exists('username',$whitelistEntry)
            ||!array_key_exists('role',$whitelistEntry))
        {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide an array with keys `username` and `role`",
                CG_Auth_Adapter_Ldap_Exception::MISSING_DEPENDENCY
                );
        }
        $this->_whitelistEntry=$whitelistEntry;
        return $this;
    }
    
    public function getWhitelistEntry()
    {
        return $this->_whitelistEntry;
    }
    
    public function getWhitelistEntryFromTable($select=null,$table=null,$objectReturn=false) {
        if ($table===null) {
            $table=$this->getWhitelistTable();
        } else if (!is_object($table)||!$table instanceof Zend_Db_Table_Abstract) {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a valid instance of Zend_Db_Table_Abstract",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        
        if ($select===null) {
            $select=$this->getWhitelistSelect();
        }
        
        if (!is_object($select)||!$select instanceof Zend_Db_Select) {
            throw new CG_Auth_Adapter_Ldap_Exception(
                "Please provide a valid instance of Zend_Db_Select",
                CG_Auth_Adapter_Ldap_Exception::INVALID_TYPE
                );
        }
        
        $rowset=$table->fetchAll($select);
        
        // ordered to allow object return for debugging first
        if ($objectReturn===true) {
            return $rowset;
        } else if ($rowset->count()==0) {
            return null;
        } else {
            $rows=$rowset->toArray();
            return $rows;
        }
    }
    /**
     * Compares a given username to a whitelist based on a table row query
     *
     * @throws CG_Auth_Adapter_Ldap_Exception
     * @param string $username username to find a whitelist entry for
     * @return bool whether or not the username appears on a whitelist
     */
    public function isWhitelisted()
    {
        $username=$this->getIdentity();
        $whitelistEntry=$this->getWhitelistEntry();
                
        if ($whitelistEntry===null) {
            $rows=$this->getWhitelistEntryFromTable();
            if ($rows===null) {
                return false;
            }
            $whitelistEntry=$rows[0]; // don't get me started but meh.
        }
        return ($whitelistEntry['username']==$username);
    }
}
