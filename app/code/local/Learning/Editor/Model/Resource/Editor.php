<?php

class Learning_Editor_Model_Resource_Editor extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Magento class constructor
     */
    protected function _construct()
    {
        $this->_init('learning_editor/editor', 'entity_id');
    }

}
