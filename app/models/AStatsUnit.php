<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class AStatsUnit extends Phalcon\Mvc\Model
{
    public $id;
    public $application_id;
    public $status;
    public $string_found;
    public $connect_time;
    public $get_time;
    public $code;
    public $timestamp;
    public $probe;

    public function validation()
    {
        $this->validate(new Numericality(
            array(
                "field"   => "application_id",
                "message" => "Application ID must be numerical"
            )
        ));
        $this->validate(new Numericality(
            array(
                "field"   => "status",
                "message" => "Status must be numerical"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "timestamp",
                "message" => "Timestamp is required"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "probe",
                "message" => "Probe is required"
            )
        ));

        return $this->validationHasFailed() != true;
    }
    public function afterFetch()
    {
        $this->epoch = (new \DateTime($this->timestamp))->format('U');
    }
}