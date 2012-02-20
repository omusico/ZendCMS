<?php

class Admin_UserController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $User = new Application_Model_UserMapper();
        $this->view->entries = $User->fetchAll();
    }
}