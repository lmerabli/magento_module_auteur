<?php

class Learning_Editor_Model_Resource_Editor_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    protected $_joinedFields = false;
    
    public function joinFields(){
        if (!$this->_joinedFields){
            $this->getSelect()->join(
                array('related' => $this->getTable('learning_editor/editor_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }
    public function addEditorFilter($editor){
        if ($editor instanceof Learning_Editor_Model_Editor){
        $editor = $editor->getId();
    }
        if (!$this->_joinedFields){
            $this->joinFields();
        }
        $this->getSelect()->where('related.editor_id = ?', $editor);
        return $this;
    }
}