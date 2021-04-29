<?php
namespace Products\Model;

use Zend\Db\TableGateway\TableGateway;

class ProductsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $select = $this->tableGateway->getSql()->select();
 
        $select->columns(array('*'))
            ->join('tb_categoria_produto', 'tb_categoria_produto.id_categoria_planejamento = tb_produto.id_categoria_produto', 
            array('categoria' => 'nome_categoria'));
        
        $items = $this->tableGateway->selectWith($select);

        return $items;
    }

    public function getProducts($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_produto' => $id));
        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row $id");
        }

        return $row;
    }

    public function saveProducts(Products $products)
    {
        $data = array(
            'nome_produto'     => $products->nome_produto,
            'valor_produto'    => (float)$products->valor_produto,
            'data_cadastro'  => date('Y-m-d H:i:s'),
            'id_categoria_produto'  => $products->id_categoria_produto,
        );

        $id = (int) $products->id;

        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProducts($id)) {
                $this->tableGateway->update($data, array('id_produto' => $id));
            } else {
                throw new \Exception('Products id does not exist');
            }
        }
    }

    public function deleteProducts($id)
    {
        $this->tableGateway->delete(array('id_produto' => $id));
    }
}