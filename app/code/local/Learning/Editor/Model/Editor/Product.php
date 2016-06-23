<?php

class Learning_Editor_Model_Editor_Product extends Mage_Core_Model_Abstract
{
    protected function _construct(){
        $this->_init('learning_editor/editor_product');
    }
    public function saveEditorRelation($editor){
    $data = $editor->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveEditorRelation($editor, $data);
        }
        return $this;
    }
    public function getProductCollection($editor){
        $collection = Mage::getResourceModel('learning_editor/editor_product_collection')
            ->addEditorFilter($editor);
        return $collection;
    }
}