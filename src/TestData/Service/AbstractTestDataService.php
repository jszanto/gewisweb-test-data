<?php

namespace TestData\Service;

use Application\Service\AbstractService;

abstract class AbstractTestDataService extends AbstractService
{

    protected $faker;
    protected $em;

    function __construct()
    {
        parent::__construct();
        $this->faker = $this->sm->get('faker');
        $this->em = $this->sm->get('doctrine.entitymanager.orm_default');
    }

    /**
     * Generate test data
     */
    abstract public function generateTestData();
}