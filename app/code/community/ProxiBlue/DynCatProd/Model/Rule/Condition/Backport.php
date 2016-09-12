<?php

/**
 * Conditions combine for dynamic products
 * Backporte code to make compatible with pre 1.6
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Backport extends Mage_Rule_Model_Condition_Combine
{

    /**
     * Store all used condition models
     *
     * @var array
     */
    protected static $_conditionModels = array();

    /**
     * Retrieve new object for each requested model.
     * If model is requested first time, store it at static array.
     *
     * It's made by performance reasons to avoid initialization of same models each time when rules are being processed.
     *
     * @param  string $modelClass
     * @return Mage_Rule_Model_Condition_Abstract|bool
     */
    protected function _getNewConditionModelInstance($modelClass)
    {
        if (empty($modelClass)) {
            return false;
        }

        if (!array_key_exists($modelClass, self::$_conditionModels)) {
            $model = Mage::getModel($modelClass);
            self::$_conditionModels[$modelClass] = $model;
        } else {
            $model = self::$_conditionModels[$modelClass];
        }

        if (!$model) {
            return false;
        }

        $newModel = clone $model;

        return $newModel;
    }

}
