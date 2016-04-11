<?php

/**
 * Application Model
 *
 * @package Oasis\Model\Raw
 * @subpackage Model
 * @author Luis Felipe Garcia
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * [entity]
 *
 * @package Oasis\Model
 * @subpackage Model
 * @author Luis Felipe Garcia
 */

namespace Oasis\Model\Raw;
class HuntGroupsRelUsers extends ModelAbstract
{


    /**
     * [uuid:php]
     * Database var type binary(36)
     *
     * @var binary
     */
    protected $_id;

    /**
     * Database var type binary(36)
     *
     * @var binary
     */
    protected $_huntGroupId;

    /**
     * Database var type binary(36)
     *
     * @var binary
     */
    protected $_userId;

    /**
     * Database var type smallint
     *
     * @var int
     */
    protected $_timeoutTime;

    /**
     * Database var type smallint
     *
     * @var int
     */
    protected $_priority;


    /**
     * Parent relation HuntGroupsRelUsers_ibfk_1
     *
     * @var \Oasis\Model\Raw\HuntGroups
     */
    protected $_HuntGroup;

    /**
     * Parent relation HuntGroupsRelUsers_ibfk_2
     *
     * @var \Oasis\Model\Raw\Users
     */
    protected $_User;


    protected $_columnsList = array(
        'id'=>'id',
        'huntGroupId'=>'huntGroupId',
        'userId'=>'userId',
        'timeoutTime'=>'timeoutTime',
        'priority'=>'priority',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'id'=> array('uuid:php'),
        ));

        $this->setMultiLangColumnsList(array(
        ));

        $this->setAvailableLangs(array('es', 'en'));

        $this->setParentList(array(
            'HuntGroupsRelUsersIbfk1'=> array(
                    'property' => 'HuntGroup',
                    'table_name' => 'HuntGroups',
                ),
            'HuntGroupsRelUsersIbfk2'=> array(
                    'property' => 'User',
                    'table_name' => 'Users',
                ),
        ));

        $this->setDependentList(array(
        ));




        $this->_defaultValues = array(
        );

        $this->_initFileObjects();
        parent::__construct();
    }

    /**
     * This method is called just after parent's constructor
     */
    public function init()
    {
    }
    /**************************************************************************
    ************************** File System Object (FSO)************************
    ***************************************************************************/

    protected function _initFileObjects()
    {

        return $this;
    }

    public function getFileObjects()
    {

        return array();
    }


    /**************************************************************************
    *********************************** /FSO ***********************************
    ***************************************************************************/

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param binary $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setId($data)
    {

        if ($this->_id != $data) {
            $this->_logChange('id');
        }

        $this->_id = $data;
        return $this;
    }

    /**
     * Gets column id
     *
     * @return binary
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param binary $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setHuntGroupId($data)
    {

        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_huntGroupId != $data) {
            $this->_logChange('huntGroupId');
        }

        $this->_huntGroupId = $data;
        return $this;
    }

    /**
     * Gets column huntGroupId
     *
     * @return binary
     */
    public function getHuntGroupId()
    {
        return $this->_huntGroupId;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param binary $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setUserId($data)
    {

        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_userId != $data) {
            $this->_logChange('userId');
        }

        $this->_userId = $data;
        return $this;
    }

    /**
     * Gets column userId
     *
     * @return binary
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setTimeoutTime($data)
    {

        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_timeoutTime != $data) {
            $this->_logChange('timeoutTime');
        }

        if ($data instanceof \Zend_Db_Expr) {
            $this->_timeoutTime = $data;

        } else if (!is_null($data)) {
            $this->_timeoutTime = (int) $data;

        } else {
            $this->_timeoutTime = $data;
        }
        return $this;
    }

    /**
     * Gets column timeoutTime
     *
     * @return int
     */
    public function getTimeoutTime()
    {
        return $this->_timeoutTime;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setPriority($data)
    {

        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_priority != $data) {
            $this->_logChange('priority');
        }

        if ($data instanceof \Zend_Db_Expr) {
            $this->_priority = $data;

        } else if (!is_null($data)) {
            $this->_priority = (int) $data;

        } else {
            $this->_priority = $data;
        }
        return $this;
    }

    /**
     * Gets column priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Sets parent relation HuntGroup
     *
     * @param \Oasis\Model\Raw\HuntGroups $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setHuntGroup(\Oasis\Model\Raw\HuntGroups $data)
    {
        $this->_HuntGroup = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setHuntGroupId($primaryKey);
        }

        $this->_setLoaded('HuntGroupsRelUsersIbfk1');
        return $this;
    }

    /**
     * Gets parent HuntGroup
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Oasis\Model\Raw\HuntGroups
     */
    public function getHuntGroup($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'HuntGroupsRelUsersIbfk1';

        $usingDefaultArguments = is_null($where) && is_null($orderBy);
        if (!$usingDefaultArguments) {
            $this->setNotLoaded($fkName);
        }

        $dontSkipLoading = !($avoidLoading);
        $notLoadedYet = !($this->_isLoaded($fkName));

        if ($dontSkipLoading && $notLoadedYet) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_HuntGroup = array_shift($related);
            if ($usingDefaultArguments) {
                $this->_setLoaded($fkName);
            }
        }

        return $this->_HuntGroup;
    }

    /**
     * Sets parent relation User
     *
     * @param \Oasis\Model\Raw\Users $data
     * @return \Oasis\Model\Raw\HuntGroupsRelUsers
     */
    public function setUser(\Oasis\Model\Raw\Users $data)
    {
        $this->_User = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setUserId($primaryKey);
        }

        $this->_setLoaded('HuntGroupsRelUsersIbfk2');
        return $this;
    }

    /**
     * Gets parent User
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Oasis\Model\Raw\Users
     */
    public function getUser($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'HuntGroupsRelUsersIbfk2';

        $usingDefaultArguments = is_null($where) && is_null($orderBy);
        if (!$usingDefaultArguments) {
            $this->setNotLoaded($fkName);
        }

        $dontSkipLoading = !($avoidLoading);
        $notLoadedYet = !($this->_isLoaded($fkName));

        if ($dontSkipLoading && $notLoadedYet) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_User = array_shift($related);
            if ($usingDefaultArguments) {
                $this->_setLoaded($fkName);
            }
        }

        return $this->_User;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Oasis\Mapper\Sql\HuntGroupsRelUsers
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Oasis\Mapper\Sql\HuntGroupsRelUsers')) {

                $this->setMapper(new \Oasis\Mapper\Sql\HuntGroupsRelUsers);

            } else {

                 new \Exception("Not a valid mapper class found");
            }

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(false);
        }

        return $this->_mapper;
    }

    /**
     * Returns the validator class for this model
     *
     * @return null | \Oasis\Model\Validator\HuntGroupsRelUsers
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Oasis\\Validator\HuntGroupsRelUsers')) {

                $this->setValidator(new \Oasis\Validator\HuntGroupsRelUsers);
            }
        }

        return $this->_validator;
    }

    public function setFromArray($data)
    {
        return $this->getMapper()->loadModel($data, $this);
    }

    /**
     * Deletes current row by deleting the row that matches the primary key
     *
     * @see \Mapper\Sql\HuntGroupsRelUsers::delete
     * @return int|boolean Number of rows deleted or boolean if doing soft delete
     */
    public function deleteRowByPrimaryKey()
    {
        if ($this->getId() === null) {
            $this->_logger->log('The value for Id cannot be null in deleteRowByPrimaryKey for ' . get_class($this), \Zend_Log::ERR);
            throw new \Exception('Primary Key does not contain a value');
        }

        return $this->getMapper()->getDbTable()->delete(
            'id = ' .
             $this->getMapper()->getDbTable()->getAdapter()->quote($this->getId())
        );
    }

    public function mustUpdateEtag()
    {
        return true;
    }

}