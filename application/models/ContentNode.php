<?php

require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH . '/models/Page.php';
class Model_ContentNode extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'content_nodes';
	
	protected $_referenceMap = array(
		'Page' => array(
			'columns' => array('page_id'),
			'refTableClass' => 'Model_Page',
			'refColumns' => array('id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT,				
		),		
	);
	
	public function setNode($pageId, $node, $value)
	{
		// fetch the row if it exists
		$select = $this->select();
		$select->where("page_id = ?", $pageId);
		$select->where("node = ?", $node);
		$row = $this->fetchRow($select);
		//if it does not then create it
		if(!$row) {
			$row = $this->createRow();
			$row->page_id = $pageId;
			$row->node = $node;
		}
		//set the content
		$row->content = $value;
		$row->save();
	}
	
	public function updatePage($id, $data)
	{
		// find the page
		$row = $this->find($id)->current();
		if($row) {
			// update each of the columns that are stored in the pages table
			$row->name = $data['name'];
			$row->parent_id = $data['parent_id'];
			$row->save();
			// unset each of the fields that are set in the pages table
			unset($data['id']);
			unset($data['name']);
			unset($data['parent_id']);
			// set each of the other fields in the content_nodes table
			if(count($data) > 0) {
				$mdlContentNode = new Model_ContentNode();
				foreach ($data as $key => $value) {
					$mdlContentNode->setNode($id, $key, $value);
				}
			}
		} else {
			throw new Zend_Exception('Could not open page to update!');
		}
	}
	
	
}
