<?php
namespace Categories\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Categories\Model\Categories;
use Categories\Form\CategoriesForm;

class CategoriesController extends AbstractActionController
{
    protected $categoriesTable;

    public function getCategoriesTable()
    {
        if (!$this->categoriesTable) {
            $sm = $this->getServiceLocator();
            $this->categoriesTable = $sm->get('Categories\Model\CategoriesTable');
        }
        return $this->categoriesTable;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'categories' => $this->getCategoriesTable()->fetchAll()
        ));
    }

    public function addAction()
    {
        $form = new CategoriesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $categories = new Categories();
            $form->setInputFilter($categories->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $categories->exchangeArray($form->getData());
                $this->getCategoriesTable()->saveCategories($categories);


                return $this->redirect()->toRoute('categories');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('categories', array(
                'action' => 'add'
            ));
        }

        try {
            $categories = $this->getCategoriesTable()->getCategories($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('categories', array(
                'action' => 'index'
            ));
        }

        $form  = new CategoriesForm();
        $form->bind($categories);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($categories->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCategoriesTable()->saveCategories($categories);

                return $this->redirect()->toRoute('categories');
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
            return $this->redirect()->toRoute('categories');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCategoriesTable()->deleteCategories($id);
            }

            return $this->redirect()->toRoute('categories');
        }

        return array(
            'id'    => $id,
            'categories' => $this->getCategoriesTable()->getCategories($id)
        );
    }
}