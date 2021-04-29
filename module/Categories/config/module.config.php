<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Categories\Controller\Categories' => 'Categories\Controller\CategoriesController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'categories' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/categories[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Categories\Controller\Categories',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'categories' => __DIR__ . '/../view',
        ),
    ),
);