<?php

namespace TestData\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class TestDataController extends AbstractActionController
{
    public function generateAction()
    {
        $acl = $this->getServiceLocator()->get('acl');
        // quick hack to disable the acl
        $acl->allow('guest');
        $module = $this->getRequest()->getParam('module');
        $this->getServiceLocator()->get('testdata_service_' . $module)->generateTestData();
    }
}
