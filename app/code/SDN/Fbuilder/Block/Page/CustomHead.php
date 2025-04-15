<?php

namespace SDN\Fbuilder\Block\Page;

use MGS\Fbuilder\Block\Page\Head;

class CustomHead extends Head
{
    protected function _prepareLayout()
    {
        if ($this->getStoreConfig('fbuilder/font_style/fontawesome')) {
            $this->pageConfig->addPageAsset('MGS_Fbuilder::css/fontawesome.v4.7.0/fontawesome.css');
        }
        switch ($this->_generateHelper->getCurrentTheme()) {
            case "Mgs/claue":
            case "SDN/claue_bags":
            case "SDN/claue_parfums":
            case "SDN/claue_clothes":
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/claue_pbanner.css');
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/claue_styles.css');
                break;
            case "Infortis/base":
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/infortis_styles.css');
                break;
            case "MageArray/mageexpress":
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/mageexpress_styles.css');
                break;
            case "Pearl/weltpixel":
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/weltpixel_styles.css');
                break;
            case "Sm/market":
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/sm_market_styles.css');
                break;
            case "Smartwave/porto":
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/porto_styles.css');
                break;
            default:
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/pbanner.css');
                $this->pageConfig->addPageAsset('MGS_Fbuilder::css/styles.css');
                break;
        }
        $this->pageConfig->addPageAsset('MGS_Fbuilder::css/owl.carousel.css');
        $this->pageConfig->addPageAsset('MGS_Fbuilder::css/owl.theme.min.css');
        $this->pageConfig->addPageAsset('MGS_Fbuilder::css/animate.css');
        $this->pageConfig->addPageAsset('MGS_Fbuilder::css/magnific-popup.css');
        $this->pageConfig->addPageAsset('MGS_Fbuilder::css/lightbox.min.css');
        $this->pageConfig->addPageAsset('MGS_Fbuilder::css/twentytwenty.css');
        $this->pageConfig->addPageAsset('MGS_Fbuilder::js/timer.js');
        return parent::_prepareLayout();
    }
}
