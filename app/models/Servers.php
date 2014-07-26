<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class Servers extends Phalcon\Mvc\Model
{
    public $id;
    public $ip;
    public $monitor_key;
    public $monitor_pass;
    public $owner;
    public $friendly_name;
    public $default_threshold = 90; // Percent
    public $load_threshold;
    public $alert_owner;
    public $alert_admin;
    public $type; // What the server is being used for

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

        $this->validate(new Numericality(
            array(
                "field"   => "load_threshold",
                "message" => "The load threshold must be numeric"
            )
        ));

        return $this->validationHasFailed() != true;
    }
    public static function getServerTypes()
    {
        return [
            0 => "Other",
            1 => "Dedicated Web Server",
            2 => "Dedicated Database Server",
            3 => "Shared Web Server",
            4 => "Hypervisor",
            5 => "Storage or Backup Server"
        ];

    }
}