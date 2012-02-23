<?php

//require APPLICATION_PATH.'/modules/default/models/User.php';

class Admin_UserController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $User = new Application_Model_User();
        $this->view->entries = $User->fetchAll();
    }
}