<?php

class PageController extends Zend_Controller_Action
{
	public function createAction()
	{
		$pageForm = new Form_PageForm();
		$pageForm->setAction('/page/create');
		$this->view->form = $pageForm;
	}
}