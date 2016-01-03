<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'TestData\Controller\TestData' => 'TestData\Controller\TestDataController',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'testdata' => array(
                    'options' => array(
                        'route'    => 'testdata <module>',
                        'defaults' => array(
                            'controller' => 'TestData\Controller\TestData',
                            'action'     => 'generate'
                        )
                    )
                )
            )
        )
    )
);
