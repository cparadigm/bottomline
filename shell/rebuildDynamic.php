<?php

/**
 * Rebuild Dynamic Categories
 *
 *
 * @author	Lucas van Staden (sales@proxiblue.com.au)
 *
 */
require_once 'abstract.php';

class Mage_Shell_RebuildDynamic extends Mage_Shell_Abstract {

    /**
     * Runner
     */
    public function run() {
        if (!Mage::getStoreConfig('dyncatprod/rebuild/max_exec')) {
            ini_set('max_execution_time', 3600); // 1 hour
        }
        $cronModel = mage::getModel('dyncatprod/cron');
        if ($this->getArg('type') == 'delayed') {
            $cronModel::rebuildDelayed(now());
        } else if ($this->getArg('type') == 'changed') {
            $cronModel::rebuildChangedDynamic(now());
        } elseif ($this->getArg('type') == 'all') {
            $cronModel::rebuildAllDynamic(now());
        } elseif ($this->getArg('type') == 'one' && $this->getArg('catid')) {
            $cronModel::rebuildOneDynamic($this->getArg('catid'));
        } else {
            echo $this->usageHelp();
        }
    }

    public function usageHelp() {
        return <<<USAGE
Usage:  php rebuildDynamic.php [options]

  --type <delayed|changed|all|one <category id>>
        delayed: Rebuild all categories that were saved and waiting for a delayed rebuild
        changed: Rebuild all categories that have attributes in rules, that were changed in any products
        all: Like it says on the box. Rebuild the lot.
        one <category id>: revuld one category from given id
USAGE;
    }

}

$shell = new Mage_Shell_RebuildDynamic();
$shell->run();








