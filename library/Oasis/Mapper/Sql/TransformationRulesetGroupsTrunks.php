<?php

/**
 * Application Model Mapper
 *
 * @package Mapper
 * @subpackage Sql
 * @author Luis Felipe Garcia
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Data Mapper implementation for Oasis\Model\TransformationRulesetGroupsTrunks
 *
 * @package Mapper
 * @subpackage Sql
 * @author Luis Felipe Garcia
 */
namespace Oasis\Mapper\Sql;

use Oasis\Gearmand\Jobs\Xmlrpc;
class TransformationRulesetGroupsTrunks extends Raw\TransformationRulesetGroupsTrunks
{
    public function delete(\Oasis\Model\Raw\ModelAbstract $model)
    {
        $result = parent::delete($model);
        try {
            $this->_sendXmlRcp();
        } catch (\Exception $e) {
            $message = $e->getMessage()."<p>Transformation ruleset groups trunks may have been deleted.</p>";
            throw new \Exception($message);
        }

        return $result;
    }

    protected function _sendXmlRcp()
    {
        $proxyServers = array(
                'proxytrunks' => "dialplan.reload"
        );
        $xmlrpcJob = new Xmlrpc();
        $xmlrpcJob->setProxyServers($proxyServers);
        $xmlrpcJob->send();
    }
}