<?php

class TBT_Bss_Model_Test_Suite_Bss_Template extends TBT_Testsweet_Model_Test_Suite_Abstract {

    public function getRequireTestsweetVersion() {
        return '1.0.0.0';
    }

    public function getSubject() {
        return $this->__('Check template files');
    }

    public function getDescription() {
        return $this->__('Check required template files are found');
    }

    protected function generateSummary() {

        $paths = array(
            Mage::getBaseDir('design') . '/frontend/base/default/layout/bss.xml',
            Mage::getBaseDir('design') . '/frontend/base/default/template/bss/dym.phtml',
            Mage::getBaseDir('design') . '/frontend/base/default/template/bss/dym/results.phtml',
            Mage::getBaseDir('design') . '/frontend/base/default/template/bss/cms/result.phtml'
        );

        foreach ($paths as $path) {
            if (realpath($path))
                $this->addPass($this->__('Found: %s',  $path));
            else
                $this->addFail($this->__('Missing %s',  $path));
        }
    }
}
