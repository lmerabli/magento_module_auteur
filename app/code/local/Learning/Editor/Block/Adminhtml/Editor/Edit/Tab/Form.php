<?php

class Learning_Editor_Block_Adminhtml_Editor_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('editor_form', array('legend' => Mage::helper('learning_editor')->__('Editor information')));

        $fieldset->addType('image', 'Learning_Editor_Block_Adminhtml_Form_Renderer_Image');

        $fieldset->addField('name', 'text', array(
            'label'    => Mage::helper('learning_editor')->__('Name'),
            'name'     => 'name',
            'class'    => 'required-entry',
            'required' => true
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('learning_editor')->__('Status'),
            'name'     => 'is_active',
            'class'    => 'required-entry',
            'values'   => Mage::getSingleton('adminhtml/system_config_source_enabledisable')->toOptionArray(),
            'required' => true
        ));

        $fieldset->addField('position', 'text', array(
            'label'    => Mage::helper('learning_editor')->__('Position'),
            'class'    => 'validate-number',
            'name'     => 'position',
            'required' => true,
            'value'    => 0
        ));
        $fieldset->addField('image_url', 'image', array(
            'label'     => Mage::helper('learning_editor')->__('Image'),
            'required'  => false,
            'name'      => 'image_url',
            'directory' => 'editor/'
        ));

        if (Mage::getSingleton('adminhtml/session')->getEditorData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEditorData());
            Mage::getSingleton('adminhtml/session')->getEditorData(null);
        } elseif (Mage::registry('editor_data')) {
            $form->setValues(Mage::registry('editor_data')->getData());
        }

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return Mage::helper('learning_editor')->__('Editor Information');
    }

    public function getTabTitle()
    {
        return Mage::helper('learning_editor')->__('Editor Information');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
