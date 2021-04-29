<?php
namespace Categories\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoriesTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        //$row = $resultSet->current();
        //echo '<pre>'; print_r($row); die;

        return $resultSet;
    }

    public function getCategories($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_categoria_planejamento' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }

        //trazer nome categoria pelo id no select 
        return $row;
    }

    public function saveCategories(Categories $categories)
    {
        $data = array(
            'nome_categoria' => $categories->nome_categoria,
        );

        $id = (int) $categories->id_categoria_planejamento;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($id)) {
                $this->tableGateway->update($data, array('id_categoria_planejamento' => $id));
            } else {
                throw new \Exception('Category id does not exist');
            }
        }
    }

    public function deleteCategories($id)
    {
        $this->tableGateway->delete(array('id_categoria_planejamento' => $id));
    }
}