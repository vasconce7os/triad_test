<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\CustoForm;
use Application\Model\Transportadora;
use Application\Model\TransportadoraTable;

class CustosController extends AbstractActionController
{
	private $table;

    public function __construct(TransportadoraTable $table)
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


    public function inserirAction()
    {
        $form = new CustoForm();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }
        $custo = new Custo();
        $form->setInputFilter($custo->getInputFilter());
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return ['form' => $form];
        }
        $custo->exchangeArray($form->getData());
        $this->table->saveCusto($custo);
        return $this->redirect()->toRoute('custos');
    }

    public function gerenciarCustosAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('custo', ['action' => 'add']);
        }
        try {

            $custo = $this->table->getTransportadora($id);
            // print_r($custo); exit;
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('custo', ['action' => 'index']);
        }
        $form = new CustoForm();
        $form->bind($custo);
        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        // print_r($viewData);
        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($custo->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveTransportadora($custo);
        return $this->redirect()->toRoute('custos', ['action' => 'index']);
    }

}
