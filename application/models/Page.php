<?php

require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH . '/models/ContentNode.php';

class Model_Page extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'pages';
	
	protected $_dependentTables = array('Model_ContentNode');
	protected $_referenceMap 	= array(
		'Page' => array(
			'columns' 	 => array('parent_id'),
			'refTable' 	 => 'Model_Page',
			'refColumns' => array('id'),
			'oneDelete'  => 	self::CASCADE,
			'onUpdate'   => self::RESTRICT,		
		),	
	);
	
	public function createPage($name, $namespace, $parentId = 0)
	{
		//create the new page
		$row = $this->createRow();
		$row->name = $name;
		$row->namespace = $namespace;
		$row->parent_id = $parentId;
		$row->date_created = time();
		$row->save();
		// now fetch the id of the row you just created and return it
		$id = $this->_db->lastInsertId();
		return $id;
	}
	
	public function deletePage($id)
	{
		// find the row that matches the id
		$row = $this->find($id)->current();
		if($row) {
			$row->delete();
			return true;
		} else {
			throw new Zend_Exception("Delete function failed; could not find page!");
		}
	}
	
}
