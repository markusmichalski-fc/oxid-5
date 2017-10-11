<?php

/**
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */
class fcPayOneViewConf extends fcPayOneViewConf_parent {

    /**
     * Name of the module folder
     * @var string
     */
    protected $_sModuleFolder = "fcPayOne";

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Hosted creditcard js url
     * 
     * @var string
     */
    protected $_sFcPoHostedJsUrl = 'https://secure.pay1.de/client-api/js/v1/payone_hosted_min.js';

    /**
     * List of handled themes
     * @var array
     */
    protected $_aSupportedThemes = array(
        'flow' => 'flow',
        'azure' => 'azure',
        'mobile' => 'mobile',
    );

    /**
     * Initializing needed things
     */
    public function __construct() {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
    }

    /**
     * Returns the path to module
     * 
     * @param void
     * @return string
     */
    public function fcpoGetModulePath() {
        return $this->getModulePath($this->_sModuleFolder);
    }

    /**
     * Returns the url to module
     * 
     * @param void
     * @return string
     */
    public function fcpoGetModuleUrl() {
        return $this->getModuleUrl($this->_sModuleFolder);
    }

    /**
     * Returns url to module img folder (admin)
     * 
     * @param void
     * @return string
     */
    public function fcpoGetAdminModuleImgUrl() {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleAdminImgUrl = $sModuleUrl . 'out/admin/img/';

        return $sModuleAdminImgUrl;
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetAbsModuleJsPath($sFile = "") {
        $sModulePath = $this->fcpoGetModulePath();
        $sModuleJsPath = $sModulePath . 'out/src/js/';
        if ($sFile) {
            $sModuleJsPath = $sModuleJsPath . $sFile;
        }

        return $sModuleJsPath;
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetModuleJsPath($sFile = "") {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleJsUrl = $sModuleUrl . 'out/src/js/';
        if ($sFile) {
            $sModuleJsUrl = $sModuleJsUrl . $sFile;
        }

        return $sModuleJsUrl;
    }

    /**
     * Returns integer of shop version
     * 
     * @param void
     * @return string
     */
    public function fcpoGetIntShopVersion() {
        return $this->_oFcpoHelper->fcpoGetIntShopVersion();
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetModuleCssPath($sFile = "") {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleUrl = $sModuleUrl . 'out/src/css/';
        if ($sFile) {
            $sModuleUrl = $sModuleUrl . $sFile;
        }

        return $sModuleUrl;
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetAbsModuleTemplateFrontendPath($sFile = "") {
        $sModulePath = $this->fcpoGetModulePath();
        $sModulePath = $sModulePath . 'application/views/frontend/tpl/';
        if ($sFile) {
            $sModulePath = $sModulePath . $sFile;
        }

        return $sModulePath;
    }

    /**
     * Returns hosted js url
     * 
     * @return string
     */
    public function fcpoGetHostedPayoneJs() {
        return $this->_sFcPoHostedJsUrl;
    }
    
    /**
     * Returns Iframe mappings
     * 
     * @param void
     * @return array
     */
    public function fcpoGetIframeMappings() {
        $oErrorMapping = oxNew('fcpoerrormapping');
        $aExistingErrorMappings = $oErrorMapping->fcpoGetExistingMappings('iframe');

        return $aExistingErrorMappings;
    }
    
    /**
     * Returns abbroviation by given id
     * 
     * @param string $sLangId
     * @return string
     */
    public function fcpoGetLangAbbrById($sLangId) {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        return $oLang->getLanguageAbbr($sLangId);
    }

    /**
     * Returns if amazonpay is active and though button can be displayed
     *
     * @param void
     * @return bool
     */
    public function fcpoCanDisplayAmazonPayButton() {
        $oPayment = oxNew('oxpayment');
        $oPayment->load('fcpoamazonpay');
        $blIsActive = (bool) $oPayment->oxpayments__oxactive->value;

        return $blIsActive;
    }


    /**
     * Returns amazon widgets url depending if mode is live or test
     */
    public function fcpoGetAmazonWidgetsUrl() {
        $oPayment = oxNew('oxpayment');
        $oPayment->load('fcpoamazonpay');
        $blIsLive = $oPayment->oxpayments__fcpolivemode->value;

        $sAmazonWidgetsUrl = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/sandbox/lpa/js/Widgets.js';
        if ($blIsLive) {
            $sAmazonWidgetsUrl = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/lpa/js/Widgets.js';
        }

        return $sAmazonWidgetsUrl;
    }

    /**
     * Returns amazon client id
     *
     * @return string
     */
    public function fcpoGetAmazonPayClientId() {
        $oConfig = oxRegistry::getConfig();
        $sClientId = $oConfig->getConfigParam('sFCPOAmazonPayClientId');

        return (string)$sClientId;
    }

    /**
     * Returns amazon seller id
     *
     * @return string
     */
    public function fcpoGetAmazonPaySellerId() {
        $oConfig = oxRegistry::getConfig();
        $sSellerId = $oConfig->getConfigParam('sFCPOAmazonPaySellerId');

        return (string)$sSellerId;
    }

    /**
     * Method returns previously saved reference id
     *
     * @param void
     * @return mixed
     */
    public function fcpoGetAmazonPayReferenceId() {
        $sAmazonReferenceId = $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoAmazonReferenceId');

        return $sAmazonReferenceId;
    }

    /**
     * Returns config value for button type
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmazonPayButtonType() {
        $oConfig = oxRegistry::getConfig();
        $sValue = $oConfig->getConfigParam('sFCPOAmazonButtonType');

        return (string)$sValue;
    }

    /**
     * Returns config value for button color
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmazonPayButtonColor() {
        $oConfig = oxRegistry::getConfig();
        $sValue = $oConfig->getConfigParam('sFCPOAmazonButtonColor');

        return (string)$sValue;
    }

    /**
     * Returns if address widget should be displayed readonly
     *
     * @param void
     * @return bool
     */
    public function fcpoGetAmazonPayAddressWidgetIsReadOnly() {
        $blAmazonPayAddressWidgetLocked =
            (bool) $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoAmazonPayAddressWidgetLocked');
        return $blAmazonPayAddressWidgetLocked;
    }

    /**
     * Returns url that will be send to amazon for redirect after login
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmazonRedirectUrl() {
        $oConfig = oxRegistry::getConfig();
        $sShopUrl = $oConfig->getSslShopUrl();
        // force protocol to be 100% ssl
        if (strpos($sShopUrl, 'http://') !== false) {
            $sShopUrl = str_replace('http://', 'https://', $sShopUrl);
        }
        $sRedirectUrl = $sShopUrl."index.php?cl=user&fnc=fcpoamazonloginreturn";

        return $sRedirectUrl;
    }

    /**
     * Method returns if there is an active amazon session
     *
     * @param void
     * @return bool
     */
    public function fcpoAmazonLoginSessionActive() {
        $oSession = oxRegistry::getSession();
        $sAmazonLoginAccessToken = $oSession->getVariable('sAmazonLoginAccessToken');
        $blLoggedIn = false;
        if ($sAmazonLoginAccessToken) {
            $blLoggedIn = true;
        }

        return $blLoggedIn;
    }

    /**
     * Method returns active theme path by checking current theme and its parent
     * If theme is not assignable, 'azure' will be the fallback
     *
     * @param void
     * @return string
     */
    public function fcpoGetActiveThemePath() {
        $sReturn = 'azure';
        $oTheme = $this->_oFcpoHelper->getFactoryObject('oxTheme');

        $sCurrentActiveId = $oTheme->getActiveThemeId();
        $oTheme->load($sCurrentActiveId);
        $aThemeIds = array_keys($this->_aSupportedThemes);
        $sCurrentParentId = $oTheme->getInfo('parentTheme');

        // we're more interested on the parent then on child theme
        if ($sCurrentParentId) {
            $sCurrentActiveId = $sCurrentParentId;
        }

        if (in_array($sCurrentActiveId, $aThemeIds)) {
            $sReturn = $this->_aSupportedThemes[$sCurrentActiveId];
        }

        return $sReturn;
    }

    /**
     * Makes this Email unique to be able to handle amazon users different from standard users
     * Currently the email address simply gets a prefix
     *
     * @param $sEmail
     * @return string
     */
    public function fcpoAmazonEmailEncode($sEmail) {
        $sAmazonEmail = "fcpoamz_".$sEmail;

        return $sAmazonEmail;
    }

    /**
     * Returns the origin email of an amazon encoded email
     *
     * @param $sEmail
     * @return string
     */
    public function fcpoAmazonEmailDecode($sEmail) {
        $sOriginEmail = $sEmail;
        if (strpos($sEmail, 'fcpoamz_') !== false) {
            $sOriginEmail = str_replace('fcpoamz_', '', $sEmail);
        }

        return $sOriginEmail;
    }

    /**
     * Returns if amazon runs in async mode
     *
     * @param void
     * @return bool
     */
    public function fcpoIsAmazonAsyncMode() {
        $oConfig = $this->getConfig();
        $sFCPOAmazonMode = $oConfig->getConfigParam('sFCPOAmazonMode');
        $blReturn = false;
        if ($sFCPOAmazonMode == 'alwaysasync') {
            $blReturn = true;
        }

        return $blReturn;
    }

    /**
     * Checks if popup method should be used. Depends on setting and/or
     * ssl state
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmzPopup() {
        $oConfig = $this->getConfig();
        $sFCPOAmazonLoginMode = $oConfig->getConfigParam('sFCPOAmazonLoginMode');
        switch ($sFCPOAmazonLoginMode) {
            case 'popup':
                $sReturn = 'true';
                break;
            case 'redirect':
                $sReturn = 'false';
                break;
            default:
                if ($this->isSsl()) {
                    $sReturn = 'true';
                } else {
                    $sReturn = 'false';
                }
        }

        return $sReturn;
    }

}
