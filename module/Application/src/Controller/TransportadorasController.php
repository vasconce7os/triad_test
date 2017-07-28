<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\TransportadoraForm;
use Application\Model\Transportadora;
use Application\Model\TransportadoraTable;

class TransportadorasController extends AbstractActionController
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
        $form = new TransportadoraForm();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }
        $transportadora = new Transportadora();
        $form->setInputFilter($transportadora->getInputFilter());
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return ['form' => $form];
        }
        $transportadora->exchangeArray($form->getData());
        $this->table->saveTransportadora($transportadora);
        return $this->redirect()->toRoute('transportadoras');
    }


    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('transportadora', ['action' => 'add']);
        }

        // Retrieve the transportadora with the specified id. Doing so raises
        // an exception if the transportadora is not found, which should result
        // in redirecting to the landing page.
        try {
            $transportadora = $this->table->getTransportadora($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('transportadora', ['action' => 'index']);
        }

        $form = new TransportadoraForm();
        $form->bind($transportadora);
        $form->get('submit')->setAttribute('value', 'Editar');  // overwrite attribute form

        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($transportadora->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveTransportadora($transportadora);

        // Redirect to transportadora list
        return $this->redirect()->toRoute('transportadoras', ['action' => 'index']);
    }

}
