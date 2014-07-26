<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class PolicyConditions extends Phalcon\Mvc\Model
{
    public $id;
    public $trigger_id;
    public $server_id;
    public $app_id;
    public $metric;
    public $operator;
    public $threshold;

    public $metric_words;
    public $operator_words;
    public $server_name;
    public $app_name;

    public function validation()
    {
        $this->validate(new Numericality(
            array(
                "field"   => "trigger_id",
                "message" => "The field trigger_id is required"
            )
        ));

        $this->validate(new PresenceOf(
            array(
                "field"   => "operator",
                "message" => "The system needs to know what to do"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "threshold",
                "message" => "The threshold must be specified"
            )
        ));

        return $this->validationHasFailed() != true;
    }

    public function afterFetch()
    {
        $this->metric_words   = self::getMetrics()[$this->metric];
        $this->operator_words = self::getOperators()[$this->operator];

        if($this->server_id)
        {
            $this->server_name = Servers::findFirst(array(
                "conditions" => "id = :id:",
                "bind"       => array("id" => $this->server_id)
            ))->friendly_name;
        } else {
            $this->app_name = Applications::findFirst(array(
                "conditions" => "id = :id:",
                "bind"       => array("id" => $this->app_id)
            ))->friendly_name;
        }
    }
    public static function getMetrics()
    {
        return array(
            "app-tcp-s" => "application TCP response time (seconds)",
            "app-page-s" => "application page load time (seconds)",
            "server-load" => "server load",
            "server-mem-per" => "server memory usage (percent)",
            "sever-disk-per" => "server disk space usage (percent)",
            "server-iface-mbs" => "server network interface usage (MB/s)"
        );
    }
    public static function getOperators()
    {
        return array(
            "lt"  => "less than",
            "lte" => "less than or equal to",
            "eq"  => "equal to",
            "gt"  => "greater than",
            "gte" => "greater than or equal to"
        );
    }
}