<?php
class Oasis_Klear_Filter_Brand implements KlearMatrix_Model_Field_Select_Filter_Interface
{
    protected $_condition = array();

    public function setRouteDispatcher(KlearMatrix_Model_RouteDispatcher $routeDispatcher)
    {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            //TODO Exceptionante
            throw new Klear_Exception_Default("No brand emulated");
        }
        $loggedUser = $auth->getIdentity();
        $currentBrandyId = $loggedUser->brandId;

        $this->_condition[] = "`brandId` = '".$currentBrandyId."'";

        return true;
    }

    public function getCondition()
    {
        if (count($this->_condition) > 0) {
            return '(' . implode(" AND ", $this->_condition) . ')';
        }
        return;
    }
}