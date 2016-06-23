<?php

class Learning_Editor_Block_Adminhtml_Editor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('editor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('learning_editor')->__('Editor Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('products', array(
            'label' => Mage::helper('learning_editor')->__('Associated products'),
            'url'   => $this->getUrl('*/*/products', array('_current' => true)),
            'class'    => 'ajax'
        ));
            parent::_beforeToHtml();
    }
}
