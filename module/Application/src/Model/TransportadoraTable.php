<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class TransportadoraTable
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
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the Transportadora entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Transportadora());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
        // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);

        return $paginator;
    }

    public function getTransportadora($id)
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

    public function saveTransportadora(Transportadora $transportadora)
    {
        $data = [
            'city' => $transportadora->city,
            'name'  => $transportadora->name,
            'freight_air'  => $transportadora->freight_air,
            'freight_earthly'  => $transportadora->freight_earthly,
            'freight_water'  => $transportadora->freight_water,
        ];
        $id = (int) $transportadora->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);

            return;
        }

        if (!$this->getTransportadora($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteTransportadora($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

    public function searchByName($tag)
    {
        $sql = "SELECT id, name as label, name as value FROM carriers where carriers.name like '%$tag%'";
        $stmt = $this->tableGateway->adapter->query($sql);
        $result = $stmt->execute();
        if($result->count() > 0) {
            $returnArr = array();
            while ($result->valid()) {
                $returnArr[] = $result->current();
                $result->next();
            }
            if(count($returnArr) > 0) {
                return $returnArr;
            }
        }
    }
}
