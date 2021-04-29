<?php
namespace Products;

use Products\Model\Products;
use Products\Model\ProductsTable;
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
                'Products\Model\ProductsTable' =>  function($sm) {
                    $tableGatewayp = $sm->get('ProductsTableGateway');
                    $tablep = new ProductsTable($tableGatewayp);
                    return $tablep;
                },
                'Categories\Model\CategoriesTable' =>  function($sm) {
                    $tableGatewayc = $sm->get('CategoriesTableGateway');
                    $tablec = new CategoriesTable($tableGatewayc);
                    return $tablec;
                },
                'ProductsTableGateway' => function ($sm) {
                    $dbAdapterp = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototypep = new ResultSet();
                    $resultSetPrototypep->setArrayObjectPrototype(new Products());
                    return new TableGateway('tb_produto', $dbAdapterp, null, $resultSetPrototypep);
                },
                'CategoriesTableGateway' => function ($sm) {
                    $dbAdapterc = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototypec = new ResultSet();
                    $resultSetPrototypec->setArrayObjectPrototype(new Categories());
                    return new TableGateway('tb_categoria_produto', $dbAdapterc, null, $resultSetPrototypec);
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}