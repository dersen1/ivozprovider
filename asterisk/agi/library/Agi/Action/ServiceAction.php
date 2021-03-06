<?php

namespace Agi\Action;

class ServiceAction extends RouterAction
{

    protected  $_service;

    public function setService($service)
    {
        $this->_service = $service;
        return $this;
    }

    public function process()
    {
        if (empty($this->_service) || empty($this->_caller)) {
             $this->agi->error("Service is not properly defined. Check configuration.");
            return;
        }
        // Local variables to improve readability
        $service = $this->_service;

        // Some feedback for asterisk cli
        $this->agi->notice("Processing Service %s [service%d]",
                        $service->getService()->getName(), $service->getId());

        // Process this service
        switch ($service->getService()->getIden()) {
            case 'Voicemail':
                $this->_processVoiceMail();
                break;
            case 'DirectPickUp':
                $this->_processDirectPickUp();
                break;
            case 'GroupPickUp':
                $this->_processGroupPickUp();
                break;
        }
    }

    protected function _processVoiceMail()
    {
        // Local variables to improve readability
        $caller = $this->_caller;

        // Checkvoicemail for this user
        $this->agi->checkVoicemail($caller->getVoiceMail());
    }

    protected function _processDirectPickUp()
    {
        // Local variables to improve readability
        $caller = $this->_caller;
        $service = $this->_service;
        $company = $caller->getCompany();

        $exten = substr($this->agi->getExtension(), strlen($service->getCode()) + 1);
        $extension = $company->getExtension($exten);
        if (empty($extension)) {
            $this->agi->error("Extension %s not found for company %s.", $exten, $company->getId());
            return;
        }
        $capturedUser = $extension->getUser();

        if (empty($capturedUser) || $capturedUser == $caller) {
            $this->agi->verbose("Pickup without valid destination.");
            return;
        }

        //check if user is in any pickupgroup
        $pickUpGroups = $caller->getPickUpGroups();
        if (empty($pickUpGroups)) {
            $this->agi->verbose("User %s (%s) has no capture groups.", $caller->getFullName(), $caller->getId());
            return;
        }

        $isCapturable = false;
        foreach ($pickUpGroups as $pickUpGroup) {
            $isCapturable = $pickUpGroup->isPickUpable($capturedUser);
            if ($isCapturable) {
                break;
            }
        }
        if (! $isCapturable) {
            $this->agi->verbose("User %s can not be pickuped.", $capturedUser->getId());
            return;
        }

        // Get user terminal
        $capturedTerminal = $capturedUser->getTerminal();
        if (empty($capturedTerminal)) {
            $this->agi->verbose("User %s has not associated terminal.", $capturedUser->getId());
            return;
        }

        // Get Terminal endpoint
        $capturedEndpoint = $capturedTerminal->getSorcery();
        $this->agi->verbose("Attempting pickup %s", $capturedEndpoint);
        $result = $this->agi->pickup($capturedEndpoint);

        if ($result == "SUCCESS") {
            $this->agi->verbose("Successful pickup %s", $capturedEndpoint);
        } else {
            // Target not found here
            $this->agi->hangup(3);
        }

    }

    protected function _processGroupPickUp()
    {
        // Local variables to improve readability
        $service = $this->_service;
        $caller = $this->_caller;

        //check if user is in any pickupgroup
        $pickUpGroups = $caller->getPickUpGroups();
        if (empty($pickUpGroups)) {
            $this->agi->verbose("User %s (%s) has no capture groups.", $caller->getFullName(), $caller->getId());
            return;
        }

        $result = $this->agi->pickup();

        if ($result == "SUCCESS") {
            $this->agi->verbose("Successful pickup %s", $capturedTerminal);
        } else {
            // Target not found here
            $this->agi->hangup(3);
        }

    }

}
