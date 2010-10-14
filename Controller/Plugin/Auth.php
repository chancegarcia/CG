<?
/**
 * Auth plugin 
 * utilizes a whitelisted LDAP
 * @internal this plugin will lazy-load acl and auth from the registry
 * 
 * @category    My
 * @package     My_Controller
 * @subpackage  My_Controller_Plugin
 * @version     0.0.1
 * @author      chancegarcia.com
 * @license     http://www.opensource.org/licenses/lgpl-3.0.html
 */
class My_Controller_Plugin_Auth extends 
Zend_Controller_Plugin_Abstract{
    
    protected $_acl=null;
    protected $_redirect;
    
    public function __construct() {
        $this->_redirect=array(
            'module'=>'default',
            'controller'=>'error',
            'action'=>'index'
            );
        $this->init();
    }
    
    public function init(){}
    
    public function dispatchLoopStartup() {
        throw Exception('use all the methods we made in here aka tie it 
all together for deployment');
    }
    
    public function setAcl($acl=null) {
        if (!is_object($acl)||!$acl instanceof Zend_Acl) {
            throw new My_Controller_Plugin_Auth_Exception(
                "please provide an instance of Zend_Acl",
                
My_Controller_Plugin_Auth_Exception::INVALID_TYPE
                );
        }
        
        $this->_acl=$acl;
        return $this;
    }
    
    public function getAcl() {
        if ($this->_acl===null) {
            try {
                $this->setAcl(Zend_Registry::get('Zend_Acl'));
            } catch (Zend_Exception $ze) {
                throw new My_Controller_Plugin_Auth_Exception(
                    "Acl not found in registry. Please register in the 
bootstrap under the appropriate key.",
                    
My_Controller_Plugin_Auth_Exception::MISSING_ACL,
                    $ze
                    );
            }
        }
        return $this->_acl;
    }
    
    public function getIdentity() {
        $auth=Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $auth->getStorage()->write(array(
                'role'=>'guest'
                ));
        }
        return $auth->getStorage()->read();
    }
    
    public function setRedirect($redirect=null) {
        if (!is_array($redirect)) {
             throw new My_Controller_Plugin_Auth_Exception(
                "please provide a valid array",
                
My_Controller_Plugin_Auth_Exception::INVALID_TYPE
                );
        }
        if (!array_key_exists('module',$redirect)
            ||!array_key_exists('controller',$redirect)
            ||!array_key_exists('action',$redirect)) {
            throw new My_Controller_Plugin_Auth_Exception(
                "please provide a valid array with the following keys: 
module,controller,action",
                
My_Controller_Plugin_Auth_Exception::MISSING_DATA
                );
        }
        $this->_redirect=$redirect;
        return $this;
    }
    
    public function getRedirect() {
        return $this->_redirect;
    }
    
    public function redirect() {
        $request=$this->getRequest();
        $redirect=$this->getRedirect();
        $request->setModuleName($redirect['module']);
        $request->setControllerName($redirect['controller']);
        $request->setActionName($redirect['action']);
        $request->setDispatched(false);
        return $this;
    }
}
