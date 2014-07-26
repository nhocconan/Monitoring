<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class Triggers extends Phalcon\Mvc\Model
{
    public $id;
    public $policy_id;
    public $alarm_on_timestamp;
    public $alarm_off_timestamp;

    public function validation()
    {
        $this->validate(new Numericality(
            array(
                "field"   => "policy_id",
                "message" => "You must enter a policy ID"
            )
        ));

        return $this->validationHasFailed() != true;
    }
}