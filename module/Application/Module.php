<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;


use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module
{
    const VERSION = '3.0.0dev';

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }



    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\ProductsTable::class        => function ($container) {
                    $tableGateway = $container->get('Model\ProductsTableGateway');

                    return new Model\ProductsTable($tableGateway);
                },
                'Model\ProductsTableGateway' => function ($container) {
                    $dbAdapter          = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Products());

                    return new TableGateway('products', $dbAdapter, null, $resultSetPrototype);
                },

                Model\TransportadoraTable::class        => function ($container) {
                    $tableGateway = $container->get('Model\TransportadoraTableGateway');

                    return new Model\TransportadoraTable($tableGateway);
                },
                'Model\TransportadoraTableGateway' => function ($container) {
                    $dbAdapter          = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Transportadora());

                    return new TableGateway('carriers', $dbAdapter, null, $resultSetPrototype);
                },

                
            ],
        ];
    }



    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ProductsController::class => function ($container) {
                    return new Controller\ProductsController(
                        $container->get(Model\ProductsTable::class)
                    );
                },

                Controller\TransportadorasController::class => function ($container) {
                    return new Controller\TransportadorasController(
                        $container->get(Model\TransportadoraTable::class)
                    );
                },

                Controller\CustosController::class => function ($container) {
                    return new Controller\CustosController(
                        $container->get(Model\TransportadoraTable::class)
                    );
                },

            ],
        ];
    }

}
