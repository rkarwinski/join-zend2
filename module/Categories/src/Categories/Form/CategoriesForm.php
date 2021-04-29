<?php
namespace Categories\Form;

use Zend\Form\Form;

class CategoriesForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('categories');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id_categoria_planejamento',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'nome_categoria',
            'type' => 'Text',
            //'options' => array(
            //    'label' => 'Nome da Categoria',
            //),
        ));
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