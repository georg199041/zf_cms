<?php

class Form_PageForm extends Zend_Controller_Action
{

    public function createAction()
    {
		$pageForm = new Form_PageForm();
		if($this->getRequest()->isPost()) {
			if($pageForm->isValid($_POST)) {
				// create a new page Item
				$itemPage = new CMS_Content_Item_Page();
				$itemPage->name = $pageForm->getValue('name');
				$itemPage->headline = $pageForm->getValue('name');
				$itemPage->description = $pageForm->getValue('description');
				$itemPage->content = $pageForm->getValue('content');
				//upload the image
				if($pageForm->image->isUploaded()) {
					$pageForm->image->receive();
					$itemPage->image = '/images/upload/' . basename($pageForm->image->getFileName());
				}
				//save the content item
				$itemPage->save();
				return $this->_forward('list');
			}
		}
		$pageForm->setAction('/page/create');
		$this->view->form = $pageForm;
    }

    public function listAction()
    {
        $pageModel = new Model_Page();
		// fetch all of the current pages
		$select = $pageModel->select();
		$select->order('name');
		$currentPages = $pageModel->fetchAll($select);
		if($currentPages->count() > 0) {
			$this->view->pages = $currentPages;
		}else{
			$this->view->pages = null;
		}
    }
	
	public function editAction()
	{
		$id = $this->_request->getParam('id');
		$itemPage = new CMS_Content_Item_Page($id);
		$pageForm = new Form_PageForm();
		if($this->getRequest()->isPost()) {
			if($pageForm->isValid($_POST)) {
				$itemPage->name = $pageForm->getValue('name');
				$itemPage->headline = $pageForm->getValue('headline');
				$itemPage->description = $pageForm->getValue('description');
				$itemPage->content = $pageForm->getValue('content');
				if($pageForm->image->isUploaded()) {
					$pageForm->image->receive();
					$itemPage->image = '/images/upload/' . basename($pageForm->image->getFileName());
				}
				// save the content item
				$itemPage->save();
				return $this->_forward('list');
			}
		}
		
		$pageForm->populate($itemPage->toArray());
		
		// create the image preview
		$imagePreview = $pageForm->createElement('image', 'image_preview');
		// element options
		$imagePreview->setLabel('Preview Image: ');
		$imagePreview->setAttrib('style', 'width:200px;height:auto;');
		// add the element to the form
		$imagePreview->setOrder(4);
		$imagePreview->setImage($itemPage->image);
		$pageForm->addElement($imagePreview);
		
		$this->view->form = $pageForm;
	
	}

	public function deleteAction ()
	{
		$id = $this->_request->getParam('id');
		$itemPage = new CMS_Content_Item_Page($id);
		$itemPage->delete();
		return $this->_forward('list');
	}

}

