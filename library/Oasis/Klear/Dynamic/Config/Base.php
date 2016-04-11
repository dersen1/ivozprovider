<?php
namespace Oasis\Klear\Dynamic\Config;
use Oasis\Model\Brands;
use Oasis\Model\BrandURLs;
use Oasis\Klear\Auth\User;
abstract class Base extends \Klear_Model_Settings_Dynamic_Abstract
{
    /**
     *
     * @var \Oasis\Model\Brands
     */
    protected $_brand;

    /**
     * @var  \Oasis\Model\BrandURLs
     */
    protected $_brandURL;

    /**
     * @var User
     */
    protected $_user;


    protected $_title = '';
    protected $_subTitle = '';
    protected $_year = '2015';
    protected $_logo = "images/palmera90.png";

    protected $_sessionName = 'BrandOperatorSession';
    protected $_userMapper = 'Oasis\Klear\Auth\BrandOperators\Mapper';

    protected $_timezone;

    /**
     *
     * @var Zend_Config
     */
    protected $_siteConfig;

    public function setBrand(Brands $brand)
    {
        $this->_brand = $brand;
        return $this;
    }

    public function setLogo($logo)
    {
        $this->_logo = $logo;
        return $this;
    }

    public function setBrandUrl(BrandURLs $brandURL)
    {
        $this->_brandURL = $brandURL;
        return $this;
    }

    public function setTimezone ($timezone)
    {
        $this->_timezone = $timezone;
        return $this;
    }

    public function init ($siteConfig)
    {
        $this->_siteConfig = $siteConfig;
        if (\Zend_Auth::getInstance()) {
            $identity = \Zend_Auth::getInstance()->getIdentity();
            if ($identity) {
                $this->_user = $identity;
                $this->_user->postLogin();
            }
        }
        $this->postInit();
     }

    public function processSiteName($sitename)
    {
        return $this->_title;
    }

    public function processSiteSubName($sitesubname)
    {
        return $this->_subTitle;
    }

    public function processLogo($logo)
    {
        if (!is_null($this->_logo)) {
            return $this->_logo;
        }

        return $logo;
    }

    public function processYear($year)
    {
        return $this->_year;
    }

    public function processLang($lang)
    {
        return $lang;
    }

    public function processLangs($langs)
    {
        return $langs;
    }

    public function processjQueryUI($jQueryUIconf)
    {
        $themeParser = new \Klear_Model_JQueryUIThemeParser;
        $themeParser->init();

        try {
            return $themeParser->getPathForTheme($this->_brandURL->getKlearTheme());
        } catch(\Exception $e) {
            return $themeParser->getPathForTheme('hot-sneaks');
        }

    }

    public function processCssExtended($cssExtended)
    {
        return $cssExtended;
    }

    public function processRawCss($rawCss)
    {
        return $rawCss;
    }

    public function processRawJavascripts($rawJavascripts)
    {
        return $rawJavascripts;
    }

    public function processTimezone($timezone)
    {
        return $timezone;
    }


    public function processSignature($signature)
    {
        return $signature;

    }

    public function processAuthConfig($authConfig)
    {
        $config = new \Zend_Config(array(), true);
        $config->merge($authConfig->getRaw());

        $config->brandId = $this->_brand->getId();
        $config->session->name = $this->_sessionName;
        $config->userMapper = $this->_userMapper;

        $newAuthConfig = new \Klear_Model_ConfigParser();
        $newAuthConfig->setConfig($config);

        return $newAuthConfig;
    }

}