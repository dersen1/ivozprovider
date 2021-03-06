<?php

namespace Agi\Action;

class FriendCallAction extends RouterAction
{
    protected $_friend;

    protected $_number;

    protected $_dialStatus = null;

    protected $_dialContext = 'call-user';

    protected $_allowForwarding = true;

    protected $_processDialStatus = true;


    public function setFriend($friend)
    {
        $this->_friend = $friend;
        return $this;
    }

    public function setDestination($number)
    {
        $this->_number = $number;
        return $this;
    }

    public function call()
    {
        if (empty($this->_friend)) {
            $this->agi->error("Friend is not properly defined. Check configuration.");
            return;
        }

        // Local variables to improve readability
        $friend = $this->_friend;
        $number = $this->_number;

        // Some verbose dolan pls
        $this->agi->notice("Preparing call to %s through friend \e[0;36m%s [friend%d])\e[0m",
                        $number, $friend->getName(), $friend->getId());

        // Update Called name
        if ($this->agi->getCallType() == "external") {
            // Transfor number to User Preferred
            $preferred = $friend->E164ToPreferred($this->agi->getOrigCallerIdNum());
            $this->agi->setCallerIdNum($preferred);
            $this->agi->setCallerIdName("");
        }

        // Check if user is available before placing the call
        $endpointName = $friend->getSorcery();

        // Configure Dial options
        $options = ""; // FIXME

        // Call the PSJIP endpoint
        $this->agi->setVariable("DIAL_EXT", $number);
        $this->agi->setVariable("DIAL_DST", "PJSIP/$endpointName");
        $this->agi->setVariable("__DIAL_ENDPOINT", $endpointName);
        $this->agi->setVariable("DIAL_TIMEOUT", "");
        $this->agi->setVariable("DIAL_OPTS", $options);

        // Redirect to the calling dialplan context
        $this->agi->redirect('call-friend', $number);
    }

}
