<?php

/**
 * Product:       Xtento_XtCore (1.0.0)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-06-09T19:17:30+02:00
 * File:          app/code/local/Xtento/XtCore/Model/Rewrite/Crontest.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Model_Rewrite_Crontest extends TBT_Testsweet_Model_Observer_Crontest
{
    /*
     * After doing the crontest, the SweetTooth testweet module reinit()s the Magento config, causing issues if the Magento configuration was adjusted in realtime. The timestamp saving now happens in the Xtento_XtCore_Model_Observer_Event class.
     */
    public function run()
    {
        return $this;
    }
}