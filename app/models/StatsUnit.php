<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class StatsUnit extends Phalcon\Mvc\Model
{
    public $id;
    public $mem_used_mb;
    public $mem_cached_mb;
    public $mem_free_mb;
    public $load;
    public $disks;
    public $networks;
    public $server_id;
    public $timestamp;

    public function validation()
    {
        $this->validate(new PresenceOf(
            array(
                "field"   => "mem_used_mb",
                "message" => "Memory used is required"
            )
        ));
        $this->validate(new Numericality(
            array(
                "field"   => "mem_used_mb",
                "message" => "Memory used must be numerical"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "mem_cached_mb",
                "message" => "Memory cached is required"
            )
        ));
        $this->validate(new Numericality(
            array(
                "field"   => "mem_cached_mb",
                "message" => "Memory cached must be numerical"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "mem_free_mb",
                "message" => "Memory free is required"
            )
        ));
        $this->validate(new Numericality(
            array(
                "field"   => "mem_free_mb",
                "message" => "Memory free must be numerical"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "load",
                "message" => "Load is required"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "server_id",
                "message" => "Server ID is required"
            )
        ));
        $this->validate(new Numericality(
            array(
                "field"   => "server_id",
                "message" => "Server ID must be numerical"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "timestamp",
                "message" => "Timestamp is required"
            )
        ));

        return $this->validationHasFailed() != true;
    }
    public function afterFetch()
    {
        $this->epoch    = (new \DateTime($this->timestamp))->format('U');
        $this->networks = unserialize($this->networks);
        $this->disks    = unserialize($this->disks);

        // Add default values to disks
        foreach(array_keys(get_object_vars($this->disks)) as $disk)
        {
            if(empty($this->disks->$disk->read_kb)) $this->disks->$disk->read_kb = 0;
            if(empty($this->disks->$disk->write_kb)) $this->disks->$disk->write_kb = 0;
        }
    }
}