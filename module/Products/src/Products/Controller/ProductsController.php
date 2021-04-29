<?php
namespace Products\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Products\Model\Products;
use Categories\Model\Categories;
use Products\Form\ProductsForm;

class ProductsController extends AbstractActionController 
{
    protected $productsTable;

    public function getProductsTable($module = 'Products')
    {
        $sm = $this->getServiceLocator();
        $ad = "{$module}\\Model\\{$module}Table"; 
        $this->productsTable = $sm->get($ad);
        return $this->productsTable;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'products' => $this->getProductsTable()->fetchAll(),
        ));
    }

    public function addAction()
    {

        $array['categories'] = $this->getProductsTable('Categories')->fetchAll(); 
        foreach ($array['categories'] as $key => $value) {
            $options[$value->id_categoria_planejamento] = $value->nome_categoria;
        }

        $form = new ProductsForm(null, $options);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $products = new Products();
            $form->setInputFilter($products->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $products->exchangeArray($form->getData());
                $this->getProductsTable()->saveProducts($products);
                return $this->redirect()->toRoute('products');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('products', array(
                'action' => 'add'
            ));
        }

        try {
            $products = $this->getProductsTable()->getProducts($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('products', array(
                'action' => 'index'
            ));
        }

        $array['categories'] = $this->getProductsTable('Categories')->fetchAll(); 
        foreach ($array['categories'] as $key => $value) {
            $options[$value->id_categoria_planejamento] = $value->nome_categoria;
        }

        $form  = new ProductsForm(null, $options);
        $form->bind($products);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($products->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $products->id = $id;
                $this->getProductsTable()->saveProducts($products);

                return $this->redirect()->toRoute('products');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('products');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProductsTable()->deleteProducts($id);
            }

            return $this->redirect()->toRoute('products');
        }

        return array(
            'id'    => $id,
            'products' => $this->getProductsTable()->getProducts($id)
        );
    }

    
}