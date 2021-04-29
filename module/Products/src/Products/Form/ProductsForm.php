<?php
namespace Products\Form;

use Zend\Form\Form;


class ProductsForm extends Form
{

    protected $id;
    protected $array;

    public function __construct($name = null, $array = [])
    {
        parent::__construct('products', $array);
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'nome_produto',
            'type' => 'Text',
            //'options' => array(
            //    'label' => 'Nome do Produto',
            //),
        ));
        $this->add(array(
            'name' => 'valor_produto',
            'type' => 'Text',
            //'options' => array(
            //    'label' => 'Valor do Produto',
            //),
        ));

        $options = $array;
        
        $this->add(array(
            'name' => 'id_categoria_produto',
            'type' => 'select',
            'options' => array(
                //'label' => 'Categoria do Produto',
                'options' => $options,
                'empty_option' => 'Selecione uma opção'
            ),
        ));
        //$this->add(array(
        //    'name' => 'data_cadastrado',
        //    'type' => 'date',
        //    'options' => array(
        //        'label' => 'Data de Cadastramento',
        //    ),
        //));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Cadastrar',
                'id' => 'submitbutton',
            ),
        ));
    }
}