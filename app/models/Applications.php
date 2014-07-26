<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf;

class Applications extends Phalcon\Mvc\Model
{
    public $id;
    public $url;
    public $content;
    public $owner;
    public $server;
    public $type;
    public $last_probed;
    public $last_probed_from;
    public $friendly_name;

    public function validation()
    {
        $this->validate(new Regex(
            array(
                "field"   => "url",
                "pattern" => '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                "message" => "You must enter a valid URL"
            )
        ));

        $this->validate(new PresenceOf(
            array(
                "field"   => "owner",
                "message" => "You must enter a valid owner"
            )
        ));

        return $this->validationHasFailed() != true;
    }

    public static function getApplicationTypes()
    {
        return [
            0 => "Other",
            1 => "Interactive",
            2 => "Batch Processing",
        ];

    }
}