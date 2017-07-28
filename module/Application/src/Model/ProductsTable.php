<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Application\Model\CalculoFrete;

class ProductsTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }
        return $this->tableGateway->select();
    }

    private function fetchPaginatedResults()
    {
        $select = new Select($this->tableGateway->getTable());
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Products());
        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getProducts($id)
    {
        $id     = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row    = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function calculaFrete(CalculoFrete $calculoFrete)
    {
        $data = [
            'products_qde' => $calculoFrete->products_qde,
            'transport_type'  => $calculoFrete->transport_type,
            'transport_id'  => $calculoFrete->transport_id,
            'products_id'  => $calculoFrete->products_id,
        ];

        $product = $this-> getProducts($data['products_id']);

        $sql = "SELECT * FROM carriers where carriers.id = '". $data['transport_id'] . "'";
        $stmt = $this->tableGateway->adapter->query($sql);
        $result = $stmt->execute();
        if($result->count() > 0)
        {
            $transport = $result->current();
        }
        if($data ['transport_type'] == 'freight_air')
        {
            $vF = $transport['freight_air'];
        } else if($data ['transport_type'] == 'freight_water')
        {
            $vF = $transport['freight_water'];
        } else if($data ['transport_type'] == 'freight_earthly')
        {
            $vF = $transport['freight_earthly'];
        }
        return array ('total'=> (($data['products_qde'] * $product-> price )) + ($data['products_qde'] * $vF)
            , 'transport'=> $transport
            , 'product'=> $product
            );
        
    }
}
