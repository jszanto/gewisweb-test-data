<?php

namespace TestData;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get service configuration.
     *
     * @return array Service configuration
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'faker' => function($sm) {
                    return \Faker\Factory::create('en_US');
                }
            ),
            'invokables' => array(
                'testdata_service_activity' => 'TestData\Service\Activity',
                'testdata_service_company' => 'TestData\Service\Company',
                'testdata_service_decision' => 'TestData\Service\Decision',
                'testdata_service_education' => 'TestData\Service\Education',
                'testdata_service_page' => 'TestData\Service\Page',
                'testdata_service_photo' => 'TestData\Service\Photo',
                'testdata_service_poll' => 'TestData\Service\Poll',
                'testdata_service_user' => 'TestData\Service\User',
            ),
        );
    }
}
