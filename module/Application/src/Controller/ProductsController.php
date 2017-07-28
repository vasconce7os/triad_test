<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ProductsForm;
use Application\Form\CalculoFreteForm;
use Application\Model\Products;
use Application\Model\CalculoFrete;
use Application\Model\ProductsTable;
use Application\Model\TransportadoraTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Application\Controller\TransportadorasController as TansportCtrl;

class ProductsController extends AbstractActionController
{
	private $table;

    public function __construct(ProductsTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $paginator = $this->table->fetchAll(true);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        return new ViewModel(['paginator' => $paginator]);
    }

    public function calcularFreteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 === $id) {
            return $this->redirect()->toRoute('products', ['action' => 'addss']);
        }
        $view = new ViewModel();
        try
        {
            $product = $this->table->getProducts($id);
            $view-> setVariable('product', $product);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('products', ['action' => 'index']);
        }
        $form = new CalculoFreteForm();
        $calculoFrete = new CalculoFrete();
        if ($this-> getRequest()->isPost())
        {
            $form->setInputFilter($calculoFrete->getInputFilter());
            $form->setData($this-> getRequest()->getPost());
            if ($form->isValid()) {
                $calculoFrete->exchangeArray($form->getData());
                $calculoFrete = $this->table->calculaFrete($calculoFrete);
                $view-> setVariable('calculoFrete', $calculoFrete);
            }
            $view-> setVariable('form', $form);
        }
        $view-> setVariable('form', $form);

        return $view;
    }

}
