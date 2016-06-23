<?php

class Learning_Editor_Model_Resource_Editor_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Magento class constructor
     */
    protected function _construct()
    {
        $this->_init('learning_editor/editor');
    }

    /**
     * Filter collection by status
     *
     */
    public function addIsActiveFilter()
    {
        $this->addFieldToFilter('is_active', 1);

        return $this;
    }
}
