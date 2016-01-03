<?php

namespace TestData\Service;

use Application\Service\AbstractService;

abstract class AbstractTestDataService extends AbstractService
{

    protected $faker;
    protected $em;

    public function setServiceManager($sm)
    {
        parent::setServiceManager($sm);
        $this->faker = $sm->get('faker');
        $this->em = $sm->get('doctrine.entitymanager.orm_default');
    }

    /**
     * Generate test data
     */
    abstract public function generateTestData();
}