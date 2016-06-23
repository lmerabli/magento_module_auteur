<?php

class Learning_Editor_Block_Adminhtml_Editor extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller     = 'adminhtml_editor';
        $this->_blockGroup     = 'learning_editor';
        $this->_headerText     = Mage::helper('learning_editor')->__('Manage Editors');
        $this->_addButtonLabel = Mage::helper('learning_editor')->__('Add Editor');
        parent::__construct();
    }
}
