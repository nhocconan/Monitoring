<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf;

class Brutes extends Phalcon\Mvc\Model
{
    public $id;
    public $ip;
    public $timestamp;

    public function validation()
    {
        $this->validate(new PresenceOf(
            array(
                "field"   => "timestamp",
                "message" => "The field timestamp is required"
            )
        ));

        $this->validate(new Regex(
            array(
                "field"   => "ip",
                "pattern" => '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',
                "message" => "The email must be unique"
            )
        ));

        return $this->validationHasFailed() != true;
    }
    public function setBruteDetails($ip)
    {
        $this->timestamp = (new \DateTime())->format('Y-m-d H:i:s');
        $this->ip        = $ip;
    }
}