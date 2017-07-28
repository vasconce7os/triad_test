<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\ProductsForm;
use Application\Model\Products;
use Application\Model\ProductsTable;

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


    public function addAction()
    {
        $form = new ProductsForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);

        return $this->redirect()->toRoute('album');
    }

}
