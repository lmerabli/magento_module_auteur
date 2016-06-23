<?php
class Learning_Editor_Block_Editor extends Mage_Core_Block_Template
{
	public function getEditors()
	{
		$editors = Mage::getModel('learning_editor/editor')
				->getCollection()
				->addIsActiveFilter()
				->addOrderByPosition();
		return $editors;
	}
}
