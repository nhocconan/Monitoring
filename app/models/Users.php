<?php
use Phalcon\Mvc\Model\Validator\Email,
    Phalcon\Mvc\Model\Validator\Uniqueness,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class Users extends Phalcon\Mvc\Model
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $is_admin;
    public $monitor_servers;
    public $monitor_applications;
    public $last_login;
    public $last_login_ip;

    public function validation()
    {
        $this->validate(new Email(
            array(
                "field"   => "email",
                "message" => "The email is not valid"
            )
        ));

        $this->validate(new Uniqueness(
            array(
                "field"   => "email",
                "message" => "The email must be unique"
            )
        ));

        $this->validate(new PresenceOf(
            array(
                "field"   => "password",
                "message" => "The user must be assigned a password"
            )
        ));

        $this->validate(new PresenceOf(
            array(
                "field"   => "is_admin",
                "message" => "The user must be either an admin or a standard user"
            )
        ));

        $this->validate(new Numericality(
            array(
                "field"   => "monitor_servers",
                "message" => "Please enter a number for the servers to monitor"
            )
        ));

        $this->validate(new Numericality(
            array(
                "field"   => "monitor_applications",
                "message" => "Please enter a number for the applications to monitor"
            )
        ));

        return $this->validationHasFailed() != true;
    }

    public function setLastLogin($ip)
    {
        $this->last_login = (new \DateTime())->format('Y-m-d H:i:s');
        $this->last_login_ip = $ip;
    }
}