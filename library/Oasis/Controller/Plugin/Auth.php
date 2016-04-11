<?php

use Oasis\Model as Models;
use Oasis\Mapper\Sql as Mappers;

class Oasis_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Klear_Bootstrap
     */
    protected $_bootstrap;

    /**
     * Este método se ejecuta una vez se ha matcheado la ruta adecuada
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::routeShutdown()
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {

        if ('rest' !== $request->getModuleName()) {
            return;
        }

        if ('OPTIONS' === $request->getMethod()) {
            return;
        }

        if (!$this->_checkConfigRequiredAuth($request)) {
            return;
        }

        ini_set('xdebug.overload_var_dump', 0);
        $this->_initAuth($request);

    }

    protected function _checkConfigRequiredAuth($request)
    {

        $methodsOptions = APPLICATION_PATH . '/configs/restApi.ini';

        if (!file_exists($methodsOptions)) {
            throw new Exception(
                'No existe el archivos de configuración restApi.ini'
            );
        }

        $methodsConfig = new \Zend_Config_Ini(
            $methodsOptions,
            APPLICATION_ENV
        );
        $controller = $request->getControllerName();
        $method = strtolower($request->getMethod());

        if (empty($methodsConfig->$controller)) {
            $check = $methodsConfig->defaultPolicy;
        } else {
            $check = $methodsConfig->$controller;
        }

        if (empty($check->$method)) {
            $auth = $methodsConfig->defaultPolicy->$method->authorization;
        } else {
            $auth = $check->$method->authorization;
        }

        return (boolval($auth));

    }

    protected function _initAuth(Zend_Controller_Request_Abstract $request)
    {

        $view = new \Zend_View();

        $serverUrl = $view->serverUrl();
        $brandsMap = new Mappers\BrandURLs();
        $brand = $brandsMap->findOneByField('url', $serverUrl);

        $companiesMap = new Mappers\Companies();
        $companies = $companiesMap->findOneByField(
            'brandId',
            $brand->getBrandId()
        );

        if (empty($companies)) {
            $this->_errorAuth();
        }

        $token = $this->getRequest()->getHeader('Authorization');

        $tokenParts = explode(' ', $token);

        if (sizeof($tokenParts) !== 2) {
            $this->_errorAuth();
        }

        $authType = $tokenParts[0];

        $mapper = new Mappers\Users();

        if ($authType === 'Basic') {

            $getData = array(
                'user' => 'username',
                'pass' => 'pass'
            );

            $auth = new \Iron_Auth_RestBasic();
            $user = $auth->authenticate($tokenParts[1], $mapper, $getData);

            if ($user->getCompanyId() !== $companies->getId()) {
                $this->_errorAuth();
            }

        } elseif ($authType = 'Hmac') {

            $requestDate = $this->getRequest()->getHeader('Request-Date', false);

            $auth = new \Iron_Auth_RestHmac();
            $user = $auth->authenticate($tokenParts[1], $requestDate, $mapper);

            if ($user->getActive() !== 1) {
                $this->_errorAuth();
            }

            if ($user->getCompanyId() !== $companies->getId()) {
                $this->_errorAuth();
            }

        } else {
            $this->_errorAuth();
        }

    }

    /**
     * Mensaje de error en la autenticación.
     */
    protected function _errorAuth()
    {

        $resutl = array(
            'success' => false,
            'message' => 'Authorization incorrecta'
        );

        $response = $this->getResponse();
        $response->setHttpResponseCode(401);
        $response->setBody(json_encode($resutl));
        $response->sendResponse();
        exit();

    }

}