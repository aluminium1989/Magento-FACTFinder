<?php
/**
 * FACTFinder_Campaigns
 *
 * @category Mage
 * @package FACTFinder_Campaigns
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016, Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 */

/**
 * Class FACTFinder_Campaigns_Model_Observer
 *
 * @category Mage
 * @package FACTFinder_Campaigns
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016, Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 */
class FACTFinder_Campaigns_Model_Observer
{


    /**
     * Handles campaign redirects on
     * controller_action_layout_generate_blocks_after
     *
     * @param Varien_Object $observer
     */
    public function handleCampaignsRedirect($observer)
    {
        if (!Mage::helper('factfinder')->isEnabled('campaigns')
            || (Mage::registry('current_product') && !Mage::helper('factfinder_campaigns')->canShowCampaignsOnProduct())
        ) {
            return;
        }

        if (!Mage::registry('current_layer') && !Mage::registry('current_product')) {
            return;
        }

        if (Mage::registry('current_product')) {
            $product = Mage::registry('current_product');
            $ids = array($product->getData(Mage::helper('factfinder_campaigns')->getIdFieldName()));
            $handler = Mage::getSingleton('factfinder_campaigns/handler_product', $ids);
        } elseif (Mage::helper('factfinder/search')->getIsOnSearchPage()) {
            $handler = Mage::getSingleton('factfinder_campaigns/handler_search');
        } else {
            return;
        }

        $redirect = $handler->getRedirect();

        if ($redirect) {
            // handle relative urls
            if (!Zend_Uri::check($redirect)) {
                $redirect = Mage::getBaseUrl() . $redirect;
            }

            $response = Mage::app()->getResponse();
            $response->setRedirect($redirect);
            $response->sendResponse();
            exit;
        }
    }


}
