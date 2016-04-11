<?php
require_once("BaseController.php");
use Oasis\Mapper\Sql as Mapper;
use Agi\Action\DDIAction;
use Agi\Action\ExtensionAction;
use Agi\Action\ExternalCallAction;
use Agi\Action\UserCallAction;
use Agi\Action\HuntGroupAction;
use Agi\Action\IVRAction;
use Agi\Action\ServiceAction;

/**
 * @brief Controller for Incoming and Outgoing calls
 *
 * This controllers is invoked from different contexts of dialplan and routes
 * the call based on configuretion
 * 
 * Following actions are defined in this controller
 * - incoming: Handle incoming calls from external numbers
 * - outgoing: Handle outgoing calls from registered users
 * - forwards: Handle channel redirections and transfers
 * - dialstatus: Handle post-call user call forwards  
 *
 * @package AGI
 * @subpackage CallsController
 * @author Gaizka Elexpe <gaizka@irontec.com>
 * @author Ivan Alonso [kaian] <kaian@irontec.com>
 */
class CallsController extends BaseController
{
    /**
     * @brief Incomming from from external numbers
     */
    public function incomingAction ()
    {
        // Get Dialed number
        $exten = $this->agi->getExtension();

        // Check if incoming DDI is for us
        $DDIMapper = new Mapper\DDIs();
        $ddi = $DDIMapper->findOneByField("DDI", $exten);
        if (empty($ddi)) {
            $this->agi->error("DDI %s not found in database.", $exten);
            return;
        }
    
        // Mark this call as external
        $this->agi->setCallType("external");

        // Set Outgoing Channels X-CID header variable
        $callid = $this->agi->getSIPHeader("Call-Id");
        if (!empty($callid)) {
            $this->agi->setVariable("__CALL_ID", $callid);
        }
    
        // Get company MusicClass: company, Generic or default
        $company = $ddi->getCompany();
        $this->_setMusicClass($company);
        $this->agi->setVariable("__COMPANYID", $company->getId());

        // Process this DDI
        $ddiAction = new DDIAction($this);
        $ddiAction
            ->setDDI($ddi)
            ->process();

    }
    
    /**
     * @brief Outgoing calls from terminals to Extensions, Services o World
     */
    public function outgoingAction ()
    {
        /**
         * Determine who is placing this call:
         * - SIPTRANSFER: set by asterisk on blind transfers
         * - Diversion:   set by asterisk on 302 Moved SIP Message
         * - CallerID:    set by asterisk reading From: SIP Header
         */
        if ($this->agi->getVariable("SIPTRANSFER")) {
            $transfererURI = $this->agi->getVariable("SIPREFERREDBYHDR");
            $callerid = $this->agi->extractURI($transfererURI, "num");
        } else if ($forwader = $this->agi->getRedirecting('from-num')) {
            $callerid = $forwader;
        } else {
            $callerid = $this->agi->getCallerID();
        }

        if (! $callerid) {
            $this->agi->error("Call without valid callerid. Dropping.");
            return;
        }
    
        // Get caller peer
        $terminalMapper = new Mapper\Terminals();
        $terminal = $terminalMapper->findOneByField("name", $callerid);
    
        if (empty($terminal)) {
            $this->agi->error("Terminal %s not found.", $callerid);
            return;
        }
    
        // Get caller user
        $user = $terminal->getUser();
        if (empty($user)) {
            $this->agi->error("Terminal %s has no user.", $terminal->getId());
            return;
        }
    
        // Get caller extension
        $extension = $user->getExtension();
        if (empty($extension)) {
            $this->agi->error("User %s has no extension.", $user->getId());
            return;
        }
    
        // Set Company/Brand/Generic Music class
        $company = $user->getCompany();
        $this->_setMusicClass($company);
        $this->agi->setVariable("__COMPANYID", $company->getId());

        // Check User's permission to does this call
        $exten = $this->agi->getExtension();

        // Mark this call as generated from user
        $this->agi->setCallType("internal");

        // Set Outgoing Channels X-CID header variable
        $callid = $this->agi->getSIPHeader("Call-Id");
        if (!empty($callid)) {
            $this->agi->setVariable("__CALL_ID", $callid);
        }

        // Some output
        $this->agi->verbose("Processing ougoing call from %s (%s) to %s",
                        $user->getFullName(), $user->getId(), $exten);

        if (! $user->hasSrcUserPerm($exten)) {
            $this->agi->error("User %s is not allowed to place this call.", $user->getId());
            return;
        }
    
        // Check if this is an extension call
        if (($dstExtension = $company->getExtension($exten))) {
            $this->agi->verbose("Number %s belongs to a company extension.", $exten);

            // Update who is calling
            if (isset($transfererURI) && !empty($transfererURI)) {
                // Nothing to do here?
            } else if (isset($forwader) && !empty($forwader)) {
                $this->agi->setRedirecting('from-name,i', $user->getFullName());
                $this->agi->setRedirecting('from-num,i', $user->getExtensionNumber());
            } else {
                $this->agi->setCallerIdName($user->getFullName());
                $this->agi->setCallerIdNum($user->getExtensionNumber());
            }

            // Handle extension
            $extensionAction = new ExtensionAction($this);
            $extensionAction
                ->setExtension($dstExtension)
                ->process();
            
        } elseif (($service = $company->getService($exten))) {
            $this->agi->verbose("Number %s belongs to a company service.", $exten);

            // Update who is calling
            if (isset($transfererURI) && !empty($transfererURI)) {
                // Nothing to do here?
            } else if (isset($forwader) && !empty($forwader)) {
                $this->agi->setRedirecting('from-name,i', $user->getFullName());
                $this->agi->setRedirecting('from-num,i', $user->getExtensionNumber());
            } else {
                $this->agi->setCallerIdName($user->getFullName());
                $this->agi->setCallerIdNum($user->getExtensionNumber());
            }

            // Handle service code
            $serviceAction = new ServiceAction($this);
            $serviceAction
                ->setUser($user)
                ->setService($service)
                ->process();
            
        } else {
            $this->agi->verbose("Number %s is handled as external DDI.", $exten);

            // Update who is calling
            if (isset($transfererURI) && !empty($transfererURI)) {
                // Nothing to do here?
            } else if (isset($forwader) && !empty($forwader)) {
                $this->agi->setRedirecting('from-name,i', $user->getFullName());
                $this->agi->setRedirecting('from-num,i', $user->getOutgoingDDINumber());
            } else {
                $this->agi->setCallerIdName($user->getFullName());
                $this->agi->setCallerIdNum($user->getOutgoingDDINumber());
            }

            // Otherwise, handle this call as external
            $externalCallAction = new ExternalCallAction($this);
            $externalCallAction
                ->setUser($user)
                ->setDestination($exten)
                ->process();
        }
    }
    
    /**
     * @brief Add SIP Headers for proxies
     */
    public function addheadersAction()
    {
        $companyId = $this->agi->getVariable("COMPANYID");
        $companyMapper = new Mapper\Companies();
        $company = $companyMapper->find($companyId);
        
        // Add headers for Friendly Kamailio  Proxy;-))            
        $this->agi->setSIPHeader("X-Call-Id",           $this->agi->getVariable("CALL_ID"));
        $this->agi->setSIPHeader("X-Info-BrandId",      $company->getBrandId());
        $this->agi->setSIPHeader("X-Info-CompanyId",    $company->getId());
        $this->agi->setSIPHeader("X-Info-CompanyName",  $company->getName());
        
        // Set Callee information. 
        // Use channelname to get this information because in case of ringall hungroup
        // this action will be invoked once per generated channel
        $peer = $this->agi->getPeer();
        $terminal = $company->getTerminal($peer);
        if (!empty($terminal)) {
            $extension = $terminal->getUser()->getExtension();
            $this->agi->setSIPHeader("X-Info-OutPrefix", $company->getOutboundPrefix());
            $this->agi->setSIPHeader("X-Info-Callee",    $extension->getNumber());
        } else {
            $this->agi->setSIPHeader("X-Info-MaxCalls",     $company->getExternalMaxCalls());
        }
    }
    
    /**
     * @brief Process User after call status
     */
    public function userstatusAction ()
    {
        // Process Dialed user dialstatus
        $iface = $this->agi->getVariable("DIAL_DST");
        $iface = preg_replace('/^\w+\//', '', $iface);
        $terminalMapper = new Mapper\Terminals();
        $terminal = $terminalMapper->findOneByField("name", $iface);
        if (empty($terminal)) {
            $this->agi->error("Terminal %s not found in database. (BUG?)", $iface);
            return;
        }
        
        // Get user from the terminal.
        $user = $terminal->getUser();
        if (empty($user)) {
            $this->agi->error("Terminal %s has no user (BUG?).", $iface);
            return;
        }
        
        // ProcessDialStatus
        $userAction = new UserCallAction($this);
        $userAction
            ->setUser($user)
            ->processDialStatus();
        
    }

    /**
     * @brief Process IVR after call status
     */
    public function ivrstatusAction ()
    {
        $dialStatus = $this->agi->getVariable("DIALSTATUS");
        
        // Noone picked up
        if ($dialStatus == "NOANSWER") {
            $ivrId = $this->agi->getVariable("IVRID");

            // Get IVRCommon..
            $ivrCommonMapper = new Mapper\IVRCommon();
            $ivr = $ivrCommonMapper->find($ivrId);

            // Or IVRcustom...
            if (empty($ivr)) {
                $ivrCustomMapper = new Mapper\IVRCustom();
                $ivr = $ivrCustomMapper->find($ivrId);
            }

            // Process NoAnswer handler
            $ivrAction = new IVRAction($this);
            $ivrAction
                ->setIvr($ivr)
                ->processTimeout();
        }
    }

    /**
     * @brief Call a user from a Huntgroup
     */
    public  function hgcalluserAction()
    {
        // Get running Huntgroup
        $huntgroupId = $this->agi->getVariable("HG_ID");
        $huntgroupMapper = new Mapper\HuntGroups();
        $huntgroup = $huntgroupMapper->find($huntgroupId);

        // Continue processing
        $hungroupAction = new HuntGroupAction($this);
        $hungroupAction
            ->setHuntGroup($huntgroup)
            ->call();
    }
    
    /**
     * @brief Process Huntgroup after call status
     */
    public function hgstatusAction ()
    {
        // Get running Huntgroup
        $huntgroupId = $this->agi->getVariable("HG_ID");
        $huntgroupMapper = new Mapper\HuntGroups();
        $huntgroup = $huntgroupMapper->find($huntgroupId);
        
        // Continue processing
        $hungroupAction = new HuntGroupAction($this);
        $hungroupAction
            ->setHuntGroup($huntgroup)
            ->processHuntgroupStatus();
    }
    
    /**
     * @brief Set musicclass for given company
     *
     * If no specific company music on hold is found, brand music will be used.
     * If no specific brand music  on hold is found, dafault music will be sued.
     *
     */
    private function _setMusicClass($company){
        
        $musicClass = "default";
        $musicOnHoldMapper = new Mapper\MusicOnHold();
        $musicOnHold = $musicOnHoldMapper->fetchOne("companyId='" . $company->getId() . "'");

        if (! empty($musicOnHold)) {
            $musicClass = $musicOnHold->getOwner();
        } else {
            $brandId = $company->getBrandId();
            $genericMusicOnHoldMapper = new Mapper\GenericMusicOnHold();
            $genericMusicOnHold = $genericMusicOnHoldMapper->fetchOne("brandId='" . $brandId . "'");
            if (! empty($genericMusicOnHold)) {
                $musicClass = $genericMusicOnHold->getOwner();
            }
        }

        $this->agi->setVariable("CHANNEL(musicclass)", $musicClass);
        return $this;
    }


   
}