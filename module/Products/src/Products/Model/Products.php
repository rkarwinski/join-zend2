<?php
namespace Products\Model;

use Products\Model\Products;
use Products\Model\ProductsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Products implements InputFilterAwareInterface
{
    public $id;
    public $nome_produto;
    public $valor_produto;
    public $id_categoria_produto;
    public $data_cadastro;
    public $categoria;
    protected $inputFilter;        

    public function exchangeArray($data)
    {
       
        $this->id            = (!empty($data['id_produto'])) ? $data['id_produto'] : null;
        $this->nome_produto  = (!empty($data['nome_produto'])) ? $data['nome_produto'] : null;
        $this->valor_produto = (!empty($data['valor_produto'])) ? $data['valor_produto'] : null;
        $this->id_categoria_produto = (!empty($data['id_categoria_produto'])) ? $data['id_categoria_produto'] : null;
        $this->categoria            = (!empty($data['categoria'])) ? $data['categoria'] : null;
        $this->data_cadastro        = (!empty($data['data_cadastro'])) ? $data['data_cadastro'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_categoria_produto',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'nome_produto',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 150,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    
}
