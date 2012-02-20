<?php

class Application_Model_User
{
    protected $_id;
    protected $_email;
    protected $_username;
    protected $_status;
    protected $_privileges;
    protected $_twitterId;
    protected $_facebookId;
    protected $_googleId;
    protected $_realName;
    protected $_birthDate;
    protected $_genre;
    protected $_creationDate;
    protected $_updateDate;
    protected $_lastLogin;

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.  get_class() .' property');
        }
        $this->$method($value);
    }
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '. get_class() .' property');
        }
        return $this->$method();
    }
    
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $keySplitted = explode('_',$key);
            foreach ($keySplitted as &$word) {
                $word = ucfirst($word);
            }
            $key = implode('',$keySplitted);
            $method = 'set' . $key;
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    public function setId($value)
    {
        $this->_id = $value;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->_email;
    }
    public function setEmail($value)
    {
        $this->_email = $value;
        return $this;
    }
    
    public function getUsername()
    {
        return $this->_username;
    }
    public function setUsername($value)
    {
        $this->_username = $value;
        return $this;
    }
  
    public function getStatus()
    {
        return $this->_status;
    }
    public function setStatus($value)
    {
        $this->_status = $value;
        return $this;
    }
    
    public function getPrivileges()
    {
        return $this->_privileges;
    }
    public function setPrivileges($value)
    {
        $this->_privileges = $value;
        return $this;
    }
    
    public function getCreationDate()
    {
        return $this->_creationDate;
    }
    public function setCreationDate($value)
    {
        $this->_creationDate = $value;
        return $this;
    }
    
    public function getUpdateDate()
    {
        return $this->_updateDate;
    }
    public function setUpdateDate($value)
    {
        $this->_updateDate = $value;
        return $this;
    }
    
    public function getLastLogin()
    {
        return $this->_lastLogin;
    }
    public function setLastLogin($value)
    {
        $this->_lastLogin = $value;
        return $this;
    }

}

class Application_Model_UserMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_User $user)
    {
        $data = array(
            'email'         => $user->getEmail(),
            'username'      => $user->getUsername(),
            'status'        => $user->getStatus(),
            'privileges'    => $user->getPrivileges(),
            'creation_date' => $user->getCreationDate(),
            'update_date'   => $user->getUpdateDate(),
            'last_login'    => $user->getLastLogin()
        );
 
        if (null === ($id = $user->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    private function setObject($row)
    {
        $dumpModel = new Application_Model_User();
        
        $dumpModel
                ->setId($row->id)
                ->setEmail($row->email)
                ->setUsername($row->username)
                ->setStatus($row->status)
                ->setPrivileges($row->privileges)
                ->setCreationDate($row->creation_date)
                ->setUpdateDate($row->update_date)
                ->setLastLogin($row->last_login)
                ;
        return $dumpModel;
    }   
 
    public function find($id, Application_Model_User $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user = $this->setObject($row);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $users= array();
        foreach ($resultSet as $row) {
            $entries[] = $this->setObject($row);
        }
        return $entries;
    }
}