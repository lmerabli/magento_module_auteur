<?php

class Learning_Editor_Model_Resource_Editor_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function  _construct(){
        $this->_init('learning_editor/editor_product', 'rel_id');
    }
    public function saveEditorRelation($editor, $data){
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('editor_id=?', $editor->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert($this->getMainTable(), array(
                'editor_id'      => $editor->getId(),
                'product_id'     => $productId,
                'position'      => $info['position']
            ));
        }
        return $this;
    }
}