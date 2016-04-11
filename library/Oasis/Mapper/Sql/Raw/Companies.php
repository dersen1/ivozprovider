<?php

/**
 * Application Model Mapper
 *
 * @package Oasis\Mapper\Sql
 * @subpackage Raw
 * @author Luis Felipe Garcia
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Data Mapper implementation for Oasis\Model\Companies
 *
 * @package Oasis\Mapper\Sql
 * @subpackage Raw
 * @author Luis Felipe Garcia
 */

namespace Oasis\Mapper\Sql\Raw;
class Companies extends MapperAbstract
{
    protected $_modelName = 'Oasis\\Model\\Companies';


    protected $_urlIdentifiers = array();

    /**
     * Returns an array, keys are the field names.
     *
     * @param Oasis\Model\Raw\Companies $model
     * @return array
     */
    public function toArray($model, $fields = array())
    {

        if (!$model instanceof \Oasis\Model\Raw\Companies) {
            if (is_object($model)) {
                $message = get_class($model) . " is not a \Oasis\Model\Raw\Companies object in toArray for " . get_class($this);
            } else {
                $message = "$model is not a \\Oasis\Model\\Companies object in toArray for " . get_class($this);
            }

            $this->_logger->log($message, \Zend_Log::ERR);
            throw new \Exception('Unable to create array: invalid model passed to mapper', 2000);
        }

        if (empty($fields)) {
            $result = array(
                'id' => $model->getId(),
                'brandId' => $model->getBrandId(),
                'name' => $model->getName(),
                'nif' => $model->getNif(),
                'defaultTimezoneId' => $model->getDefaultTimezoneId(),
                'applicationServerId' => $model->getApplicationServerId(),
                'transformationRulesetGroupsId' => $model->getTransformationRulesetGroupsId(),
                'externalMaxCalls' => $model->getExternalMaxCalls(),
                'postalAddress' => $model->getPostalAddress(),
                'postalCode' => $model->getPostalCode(),
                'town' => $model->getTown(),
                'province' => $model->getProvince(),
                'country' => $model->getCountry(),
                'invoiceLanguageId' => $model->getInvoiceLanguageId(),
                'outbound_prefix' => $model->getOutboundPrefix(),
                'countryId' => $model->getCountryId(),
            );
        } else {
            $result = array();
            foreach ($fields as $fieldData) {
                $trimField = trim($fieldData);
                if (!empty($trimField)) {
                    if (strpos($trimField, ":") !== false) {
                        list($field,$params) = explode(":", $trimField, 2);
                    } else {
                        $field = $trimField;
                        $params = null;
                    }
                    $get = 'get' . ucfirst($field);
                    $value = $model->$get($params);

                    if (is_array($value) || is_object($value)) {
                        if (is_array($value) || $value instanceof Traversable) {
                            foreach ($value as $key => $item) {
                                if ($item instanceof \Oasis\Model\Raw\ModelAbstract) {
                                    $value[$key] = $item->toArray();
                                }
                            }
                        } else if ($value instanceof \Oasis\Model\Raw\ModelAbstract) {
                            $value = $value->toArray();
                        }
                    }
                    $result[lcfirst($field)] = $value;
                }
            }
        }

        return $result;

    }

    /**
     * Returns the DbTable class associated with this mapper
     *
     * @return Oasis\\Mapper\\Sql\\DbTable\\Companies
     */
    public function getDbTable()
    {
        if (is_null($this->_dbTable)) {
            $this->setDbTable('Oasis\\Mapper\\Sql\\DbTable\\Companies');
        }

        return $this->_dbTable;
    }

    /**
     * Deletes the current model
     *
     * @param Oasis\Model\Raw\Companies $model The model to delete
     * @see Oasis\Mapper\DbTable\TableAbstract::delete()
     * @return int
     */
    public function delete(\Oasis\Model\Raw\ModelAbstract $model)
    {
        if (!$model instanceof \Oasis\Model\Raw\Companies) {
            if (is_object($model)) {
                $message = get_class($model) . " is not a \\Oasis\\Model\\Companies object in delete for " . get_class($this);
            } else {
                $message = "$model is not a \\Oasis\\Model\\Companies object in delete for " . get_class($this);
            }

            $this->_logger->log($message, \Zend_Log::ERR);
            throw new \Exception('Unable to delete: invalid model passed to mapper', 2000);
        }

        $useTransaction = true;

        $dbTable = $this->getDbTable();
        $dbAdapter = $dbTable->getAdapter();

        try {

            $dbAdapter->beginTransaction();

        } catch (\Exception $e) {

            //Transaction already started
            $useTransaction = false;
        }

        try {

            //onDeleteCascades emulation
            if ($this->_simulateReferencialActions && count($deleteCascade = $model->getOnDeleteCascadeRelationships()) > 0) {

                $depList = $model->getDependentList();

                foreach ($deleteCascade as $fk) {

                    $capitalizedFk = '';
                    foreach (explode("_", $fk) as $part) {

                        $capitalizedFk .= ucfirst($part);
                    }

                    if (!isset($depList[$capitalizedFk])) {

                        continue;

                    } else {

                        $relDbAdapName = 'Oasis\\Mapper\\Sql\\DbTable\\' . $depList[$capitalizedFk]["table_name"];
                        $depMapperName = 'Oasis\\Mapper\\Sql\\' . $depList[$capitalizedFk]["table_name"];
                        $depModelName = 'Oasis\\Model\\' . $depList[$capitalizedFk]["table_name"];

                        if ( class_exists($relDbAdapName) && class_exists($depModelName) ) {

                            $relDbAdapter = new $relDbAdapName;
                            $references = $relDbAdapter->getReference('Oasis\\Mapper\\Sql\\DbTable\\Companies', $capitalizedFk);

                            $targetColumn = array_shift($references["columns"]);
                            $where = $relDbAdapter->getAdapter()->quoteInto($targetColumn . ' = ?', $model->getPrimaryKey());

                            $depMapper = new $depMapperName;
                            $depObjects = $depMapper->fetchList($where);

                            if (count($depObjects) === 0) {

                                continue;
                            }

                            foreach ($depObjects as $item) {

                                $item->delete();
                            }
                        }
                    }
                }
            }

            //onDeleteSetNull emulation
            if ($this->_simulateReferencialActions && count($deleteSetNull = $model->getOnDeleteSetNullRelationships()) > 0) {

                $depList = $model->getDependentList();

                foreach ($deleteSetNull as $fk) {

                    $capitalizedFk = '';
                    foreach (explode("_", $fk) as $part) {

                        $capitalizedFk .= ucfirst($part);
                    }

                    if (!isset($depList[$capitalizedFk])) {

                        continue;

                    } else {

                        $relDbAdapName = 'Oasis\\Mapper\\Sql\\DbTable\\' . $depList[$capitalizedFk]["table_name"];
                        $depMapperName = 'Oasis\\Mapper\\Sql\\' . $depList[$capitalizedFk]["table_name"];
                        $depModelName = 'Oasis\\Model\\' . $depList[$capitalizedFk]["table_name"];

                        if ( class_exists($relDbAdapName) && class_exists($depModelName) ) {

                            $relDbAdapter = new $relDbAdapName;
                            $references = $relDbAdapter->getReference('Oasis\\Mapper\\Sql\\DbTable\\Companies', $capitalizedFk);

                            $targetColumn = array_shift($references["columns"]);
                            $where = $relDbAdapter->getAdapter()->quoteInto($targetColumn . ' = ?', $model->getPrimaryKey());

                            $depMapper = new $depMapperName;
                            $depObjects = $depMapper->fetchList($where);

                            if (count($depObjects) === 0) {

                                continue;
                            }

                            foreach ($depObjects as $item) {

                                $setterName = 'set' . ucfirst($targetColumn);
                                $item->$setterName(null);
                                $item->save();
                            } //end foreach

                        } //end if
                    } //end else

                }//end foreach ($deleteSetNull as $fk)
            } //end if

            $where = $dbAdapter->quoteInto($dbAdapter->quoteIdentifier('id') . ' = ?', $model->getId());
            $result = $dbTable->delete($where);

            if ($this->_cache) {

                $this->_cache->remove(get_class($model) . "_" . $model->getPrimarykey());
            }

            $fileObjects = array();
            $availableObjects = $model->getFileObjects();

            foreach ($availableObjects as $fso) {

                $removeMethod = 'remove' . $fso;
                $model->$removeMethod();
            }


            if ($useTransaction) {
                $dbAdapter->commit();
            }
        } catch (\Exception $exception) {

            $message = 'Exception encountered while attempting to delete ' . get_class($this);
            if (!empty($where)) {
                $message .= ' Where: ';
                $message .= $where;
            } else {
                $message .= ' with an empty where';
            }

            $message .= ' Exception: ' . $exception->getMessage();
            $this->_logger->log($message, \Zend_Log::ERR);
            $this->_logger->log($exception->getTraceAsString(), \Zend_Log::DEBUG);

            if ($useTransaction) {

                $dbAdapter->rollback();
            }

            throw $exception;
        }


        return $result;

    }

    /**
     * Saves current row
     * @return integer primary key for autoincrement fields if the save action was successful
     */
    public function save(\Oasis\Model\Raw\Companies $model, $forceInsert = false)
    {
        return $this->_save($model, false, false, null, $forceInsert);
    }

    /**
     * Saves current and all dependent rows
     *
     * @param \Oasis\Model\Raw\Companies $model
     * @param boolean $useTransaction Flag to indicate if save should be done inside a database transaction
     * @return integer primary key for autoincrement fields if the save action was successful
     */
    public function saveRecursive(\Oasis\Model\Raw\Companies $model, $useTransaction = true,
            $transactionTag = null, $forceInsert = false)
    {
        return $this->_save($model, true, $useTransaction, $transactionTag, $forceInsert);
    }

    protected function _save(\Oasis\Model\Raw\Companies $model,
        $recursive = false, $useTransaction = true, $transactionTag = null, $forceInsert = false
    )
    {
        $this->_setCleanUrlIdentifiers($model);

        $fileObjects = array();

        $availableObjects = $model->getFileObjects();

        foreach ($availableObjects as $item) {

            $objectMethod = 'fetch' . $item;
            $fso = $model->$objectMethod(false);
            $specMethod = 'get' . $item . 'Specs';
            $fileSpects = $model->$specMethod();

            $fileSizeSetter = 'set' . $fileSpects['sizeName'];
            $baseNameSetter = 'set' . $fileSpects['baseNameName'];
            $mimeTypeSetter = 'set' . $fileSpects['mimeName'];

            if (!is_null($fso) && $fso->mustFlush()) {

                $fileObjects[$item] = $fso;

                $model->$fileSizeSetter($fso->getSize())
                      ->$baseNameSetter($fso->getBaseName())
                      ->$mimeTypeSetter($fso->getMimeType());
            }

            if (is_null($fso)) {
                $model->$fileSizeSetter(null)
                ->$baseNameSetter(null)
                ->$mimeTypeSetter(null);
            }
        }

        $data = $model->sanitize()->toArray();

        $primaryKey = $model->getId();
        $success = true;

        if ($useTransaction) {

            try {

                if ($recursive && is_null($transactionTag)) {

                    //$this->getDbTable()->getAdapter()->query('SET transaction_allow_batching = 1');
                }

                $this->getDbTable()->getAdapter()->beginTransaction();

            } catch (\Exception $e) {

                //transaction already started
            }


            $transactionTag = 't_' . rand(1, 999) . str_replace(array('.', ' '), '', microtime());
        }

        if (!$forceInsert) {
            unset($data['id']);
        }

        try {
            if (is_null($primaryKey) || empty($primaryKey) || $forceInsert) {
                if (is_null($primaryKey) || empty($primaryKey)) {
                    $uuid = new \Iron\Utils\UUID();
                    $model->setId($uuid->generate());
                    $data['id'] = $model->getId();
                }
                $primaryKey = $this->getDbTable()->insert($data);

                if ($primaryKey) {
                    $model->setId($primaryKey);
                } else {
                    throw new \Exception("Insert sentence did not return a valid primary key", 9000);
                }

                if ($this->_cache) {

                    $parentList = $model->getParentList();

                    foreach ($parentList as $constraint => $values) {

                        $refTable = $this->getDbTable();

                        $ref = $refTable->getReference('Oasis\\Mapper\\Sql\\DbTable\\' . $values["table_name"], $constraint);
                        $column = array_shift($ref["columns"]);

                        $cacheHash = 'Oasis\\Model\\' . $values["table_name"] . '_' . $data[$column] .'_' . $constraint;

                        if ($this->_cache->test($cacheHash)) {

                            $cachedRelations = $this->_cache->load($cacheHash);
                            $cachedRelations->results[] = $primaryKey;

                            if ($useTransaction) {

                                $this->_cache->save($cachedRelations, $cacheHash, array($transactionTag));

                            } else {

                                $this->_cache->save($cachedRelations, $cacheHash);
                            }
                        }
                    }
                }
            } else {
                $this->getDbTable()
                     ->update(
                         $data,
                         array(
                             $this->getDbTable()->getAdapter()->quoteIdentifier('id') . ' = ?' => $primaryKey
                         )
                     );
            }

            if (!empty($primaryKey) && !empty($fileObjects)) {

                foreach ($fileObjects as $key => $fso) {

                    $baseName = $fso->getBaseName();
                    if (!empty($baseName)) {
                        $fso->flush($primaryKey);
                    }
                }
            }


            if ($recursive) {
                if ($model->getCalendars(null, null, true) !== null) {
                    $calendars = $model->getCalendars();

                    if (!is_array($calendars)) {

                        $calendars = array($calendars);
                    }

                    foreach ($calendars as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getCallACL(null, null, true) !== null) {
                    $callACL = $model->getCallACL();

                    if (!is_array($callACL)) {

                        $callACL = array($callACL);
                    }

                    foreach ($callACL as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getCallACLPatterns(null, null, true) !== null) {
                    $callACLPatterns = $model->getCallACLPatterns();

                    if (!is_array($callACLPatterns)) {

                        $callACLPatterns = array($callACLPatterns);
                    }

                    foreach ($callACLPatterns as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getDDIs(null, null, true) !== null) {
                    $dDIs = $model->getDDIs();

                    if (!is_array($dDIs)) {

                        $dDIs = array($dDIs);
                    }

                    foreach ($dDIs as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getExtensions(null, null, true) !== null) {
                    $extensions = $model->getExtensions();

                    if (!is_array($extensions)) {

                        $extensions = array($extensions);
                    }

                    foreach ($extensions as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getExternalCallFilters(null, null, true) !== null) {
                    $externalCallFilters = $model->getExternalCallFilters();

                    if (!is_array($externalCallFilters)) {

                        $externalCallFilters = array($externalCallFilters);
                    }

                    foreach ($externalCallFilters as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getFaxes(null, null, true) !== null) {
                    $faxes = $model->getFaxes();

                    if (!is_array($faxes)) {

                        $faxes = array($faxes);
                    }

                    foreach ($faxes as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getHuntGroups(null, null, true) !== null) {
                    $huntGroups = $model->getHuntGroups();

                    if (!is_array($huntGroups)) {

                        $huntGroups = array($huntGroups);
                    }

                    foreach ($huntGroups as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getIVRCommon(null, null, true) !== null) {
                    $iVRCommon = $model->getIVRCommon();

                    if (!is_array($iVRCommon)) {

                        $iVRCommon = array($iVRCommon);
                    }

                    foreach ($iVRCommon as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getIVRCustom(null, null, true) !== null) {
                    $iVRCustom = $model->getIVRCustom();

                    if (!is_array($iVRCustom)) {

                        $iVRCustom = array($iVRCustom);
                    }

                    foreach ($iVRCustom as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getInvoices(null, null, true) !== null) {
                    $invoices = $model->getInvoices();

                    if (!is_array($invoices)) {

                        $invoices = array($invoices);
                    }

                    foreach ($invoices as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getLocutions(null, null, true) !== null) {
                    $locutions = $model->getLocutions();

                    if (!is_array($locutions)) {

                        $locutions = array($locutions);
                    }

                    foreach ($locutions as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getMusicOnHold(null, null, true) !== null) {
                    $musicOnHold = $model->getMusicOnHold();

                    if (!is_array($musicOnHold)) {

                        $musicOnHold = array($musicOnHold);
                    }

                    foreach ($musicOnHold as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getPickUpGroups(null, null, true) !== null) {
                    $pickUpGroups = $model->getPickUpGroups();

                    if (!is_array($pickUpGroups)) {

                        $pickUpGroups = array($pickUpGroups);
                    }

                    foreach ($pickUpGroups as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getPricingPlansRelCompanies(null, null, true) !== null) {
                    $pricingPlansRelCompanies = $model->getPricingPlansRelCompanies();

                    if (!is_array($pricingPlansRelCompanies)) {

                        $pricingPlansRelCompanies = array($pricingPlansRelCompanies);
                    }

                    foreach ($pricingPlansRelCompanies as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getSchedules(null, null, true) !== null) {
                    $schedules = $model->getSchedules();

                    if (!is_array($schedules)) {

                        $schedules = array($schedules);
                    }

                    foreach ($schedules as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getServices(null, null, true) !== null) {
                    $services = $model->getServices();

                    if (!is_array($services)) {

                        $services = array($services);
                    }

                    foreach ($services as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getTerminals(null, null, true) !== null) {
                    $terminals = $model->getTerminals();

                    if (!is_array($terminals)) {

                        $terminals = array($terminals);
                    }

                    foreach ($terminals as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getUsers(null, null, true) !== null) {
                    $users = $model->getUsers();

                    if (!is_array($users)) {

                        $users = array($users);
                    }

                    foreach ($users as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getParsedCDRs(null, null, true) !== null) {
                    $parsedCDRs = $model->getParsedCDRs();

                    if (!is_array($parsedCDRs)) {

                        $parsedCDRs = array($parsedCDRs);
                    }

                    foreach ($parsedCDRs as $value) {
                        $value->setCompanyId($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

            }

            if ($success === true) {

                foreach ($model->getOrphans() as $itemToDelete) {

                    $itemToDelete->delete();
                }

                $model->resetOrphans();
            }

            if ($useTransaction && $success) {

                $this->getDbTable()->getAdapter()->commit();

            } elseif ($useTransaction) {

                $this->getDbTable()->getAdapter()->rollback();

                if ($this->_cache) {

                    $this->_cache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($transactionTag));
                }
            }

        } catch (\Exception $e) {
            $message = 'Exception encountered while attempting to save ' . get_class($this);
            if (!empty($primaryKey)) {
                $message .= ' id: ' . $primaryKey;
            } else {
                $message .= ' with an empty primary key ';
            }

            $message .= ' Exception: ' . $e->getMessage();
            $this->_logger->log($message, \Zend_Log::ERR);
            $this->_logger->log($e->getTraceAsString(), \Zend_Log::DEBUG);

            if ($useTransaction) {
                $this->getDbTable()->getAdapter()->rollback();

                if ($this->_cache) {

                    if ($transactionTag) {

                        $this->_cache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($transactionTag));

                    } else {

                        $this->_cache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG);
                    }
                }
            }

            throw $e;
        }

        if ($success && $this->_cache) {

            if ($useTransaction) {

                $this->_cache->save($model->toArray(), get_class($model) . "_" . $model->getPrimaryKey(), array($transactionTag));

            } else {

                $this->_cache->save($model->toArray(), get_class($model) . "_" . $model->getPrimaryKey());
            }
        }


        if ($success === true) {
            return $primaryKey;
        }

        return $success;
    }

    /**
     * Loads the model specific data into the model object
     *
     * @param \Zend_Db_Table_Row_Abstract|array $data The data as returned from a \Zend_Db query
     * @param Oasis\Model\Raw\Companies|null $entry The object to load the data into, or null to have one created
     * @return Oasis\Model\Raw\Companies The model with the data provided
     */
    public function loadModel($data, $entry = null)
    {
        if (!$entry) {
            $entry = new \Oasis\Model\Companies();
        }

        // We don't need to log changes as we will reset them later...
        $entry->stopChangeLog();

        if (is_array($data)) {
            $entry->setId($data['id'])
                  ->setBrandId($data['brandId'])
                  ->setName($data['name'])
                  ->setNif($data['nif'])
                  ->setDefaultTimezoneId($data['defaultTimezoneId'])
                  ->setApplicationServerId($data['applicationServerId'])
                  ->setTransformationRulesetGroupsId($data['transformationRulesetGroupsId'])
                  ->setExternalMaxCalls($data['externalMaxCalls'])
                  ->setPostalAddress($data['postalAddress'])
                  ->setPostalCode($data['postalCode'])
                  ->setTown($data['town'])
                  ->setProvince($data['province'])
                  ->setCountry($data['country'])
                  ->setInvoiceLanguageId($data['invoiceLanguageId'])
                  ->setOutboundPrefix($data['outbound_prefix'])
                  ->setCountryId($data['countryId']);
        } else if ($data instanceof \Zend_Db_Table_Row_Abstract || $data instanceof \stdClass) {
            $entry->setId($data->{'id'})
                  ->setBrandId($data->{'brandId'})
                  ->setName($data->{'name'})
                  ->setNif($data->{'nif'})
                  ->setDefaultTimezoneId($data->{'defaultTimezoneId'})
                  ->setApplicationServerId($data->{'applicationServerId'})
                  ->setTransformationRulesetGroupsId($data->{'transformationRulesetGroupsId'})
                  ->setExternalMaxCalls($data->{'externalMaxCalls'})
                  ->setPostalAddress($data->{'postalAddress'})
                  ->setPostalCode($data->{'postalCode'})
                  ->setTown($data->{'town'})
                  ->setProvince($data->{'province'})
                  ->setCountry($data->{'country'})
                  ->setInvoiceLanguageId($data->{'invoiceLanguageId'})
                  ->setOutboundPrefix($data->{'outbound_prefix'})
                  ->setCountryId($data->{'countryId'});

        } else if ($data instanceof \Oasis\Model\Raw\Companies) {
            $entry->setId($data->getId())
                  ->setBrandId($data->getBrandId())
                  ->setName($data->getName())
                  ->setNif($data->getNif())
                  ->setDefaultTimezoneId($data->getDefaultTimezoneId())
                  ->setApplicationServerId($data->getApplicationServerId())
                  ->setTransformationRulesetGroupsId($data->getTransformationRulesetGroupsId())
                  ->setExternalMaxCalls($data->getExternalMaxCalls())
                  ->setPostalAddress($data->getPostalAddress())
                  ->setPostalCode($data->getPostalCode())
                  ->setTown($data->getTown())
                  ->setProvince($data->getProvince())
                  ->setCountry($data->getCountry())
                  ->setInvoiceLanguageId($data->getInvoiceLanguageId())
                  ->setOutboundPrefix($data->getOutboundPrefix())
                  ->setCountryId($data->getCountryId());

        }

        $entry->resetChangeLog()->initChangeLog()->setMapper($this);

        return $entry;
    }
}