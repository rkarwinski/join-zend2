<?php
namespace Categories;

use Categories\Model\Categories;
use Categories\Model\CategoriesTable;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Categories\Model\CategoriesTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategoriesTableGateway');
                    $table = new CategoriesTable($tableGateway);
                    return $table;
                },
                'CategoriesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Categories());
                    return new TableGateway('tb_categoria_produto', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );

        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}