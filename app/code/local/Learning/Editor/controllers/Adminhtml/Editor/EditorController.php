<?php

class Learning_Editor_Adminhtml_Editor_EditorController extends Mage_Adminhtml_Controller_Action
{

    /**
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        return $this->loadLayout()->_setActiveMenu('learning_editor');
    }

    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function indexAction()
    {
        return $this->_initAction()->renderLayout();
    }

    /**
     * @return $this
     */
    public function newAction()
    {
        $this->_forward('edit');

        return $this;
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Learning_Editor_Model_Editor $editor */
        $editor = Mage::getModel('learning_editor/editor')->load($id);

        if ($editor->getId() || $id == 0) {

            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $editor->setData($data);
            }
            Mage::register('editor_data', $editor);

            return $this->_initAction()->renderLayout();
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('learning_editor')->__('Editor does not exist'));

        return $this->_redirect('*/*/');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
          $delete = (!isset($data['image_url']['delete']) || $data['image_url']['delete'] != '1') ? false : true;
          $data['image_url'] = $this->_saveImage('image_url', $delete);

            /** @var Learning_Editor_Model_Editor $editor */
            $editor = Mage::getModel('learning_editor/editor');

            if ($id = $this->getRequest()->getParam('id')) {
                $editor->load($id);
            }

            try {
                $editor->addData($data);

                $products = $this->getRequest()->getPost('products', -1);

                if ($products != -1) {
                    $editor->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }

                $editor->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('learning_editor')->__('The editor has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array(
                        'id'       => $editor->getId(),
                        '_current' => true
                    ));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->addException($e, Mage::helper('learning_editor')->__('An error occurred while saving the editor.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array(
                'id' => $this->getRequest()->getParam('id')
            ));

            return;
        }
        $this->_redirect('*/*/');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                /** @var Learning_Editor_Model_Editor $editor */
                $editor = Mage::getModel('learning_editor/editor');
                $editor->load($id)->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('learning_editor')->__('Editor was successfully deleted'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }

        return $this->_redirect('*/*/');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function massDeleteAction()
    {
        $editorIds = $this->getRequest()->getParam('editor');
        if (!is_array($editorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('learning_editor')->__('Please select editor(s)'));
        } else {
            try {
                foreach ($editorIds as $editor) {
                    Mage::getModel('learning_editor/editor')->load($editor)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('learning_editor')->__('Total of %d editor(s) were successfully deleted', count($editorIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        return $this->_redirect('*/*/index');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function massStatusAction()
    {
        $editorIds = $this->getRequest()->getParam('editor');
        if (!is_array($editorIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select editor(s)'));
        } else {
            try {
                foreach ($editorIds as $editor) {
                    Mage::getSingleton('learning_editor/editor')->load($editor)->setIsActive($this->getRequest()->getParam('is_active'))->setIsMassupdate(true)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('learning_editor')->__('Total of %d editor(s) were successfully updated', count($editorIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        return $this->_redirect('*/*/index');
    }

    /**
     *
     */
    protected function _saveImage($imageAttr, $delete)
    {
        if ($delete) {
            $image = '';
        } elseif (isset($_FILES[$imageAttr]['name']) && $_FILES[$imageAttr]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($imageAttr);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('media') . DS . 'editor' . DS;
                $uploader->save($path, $_FILES[$imageAttr]['name']);
                $image = $_FILES[$imageAttr]['name'];
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                return $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        } else {
            $model = Mage::getModel('learning_editor/editor')->load($this->getRequest()->getParam('id'));
            $image = $model->getData($imageAttr);
        }
        return $image;
    }

    public function productsAction(){
        $this->_initEditor();
        $this->loadLayout();
        $this->getLayout()->getBlock('editor.edit.tab.product')
            ->setEditorProducts($this->getRequest()->getPost('editor_products', null));
        $this->renderLayout();
    }

    public function productsgridAction(){
        $this->_initEditor();
        $this->loadLayout();
        $this->getLayout()->getBlock('editor.edit.tab.product')
            ->setEditorProducts($this->getRequest()->getPost('editor_products', null));
        $this->renderLayout();
    }

    protected function _initEditor()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Learning_Editor_Model_Editor $editor */
        $editor = Mage::getModel('learning_editor/editor')->load($id);

        if ($editor->getId() || $id == 0) {
            Mage::register('current_editor', $editor);
        }
    }
}
