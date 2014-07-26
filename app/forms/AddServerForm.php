<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Regex;

class AddServerForm extends Form
{
    public function initialize()
    {
        // Key
        $key = new Text('monitor_key', array(
            'placeholder' => 'Key',
            'value'       => substr(md5(rand()), 0, 32)
        ));

        $key->addValidators(array(
            new PresenceOf(array(
                'key' => 'The key is required'
            ))
        ));

        $this->add($key);

        // Password
        $password = new Text('monitor_pass', array(
            'placeholder' => 'Password',
            'value'       => substr(md5(rand()), 0, 32)
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));

        $this->add($password);

        // IP
        $ip = new Text('ip', array(
            'placeholder' => 'IP address'
        ));

        $ip->addValidator(new Regex(array(
            'message' => 'The IP address is required',
            'pattern' => '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/'
        )));

        $this->add($ip);

        // User
        $owner = new Select('owner', Users::find(), array(
            'using' => array('id', 'name')
        ));

        $this->add($owner);

        // User
        $name = new Text('friendly_name', array(
            'placeholder' => 'Friendly name'
        ));

        $this->add($name);

        // Load threshold
        $load = new Select('load_threshold', array(
            1 => 1,
            2 => 2,
            4 => 4,
            8 => 8,
            12 => 12,
            16 => 16,
            24 => 24,
            32 => 32
        ));

        $load->addValidator(new PresenceOf(array(
            'message' => 'The load threshold is required'
        )));

        $this->add($load);

        // Alerting
        $alert_owner = new Select('alert_owner', array(
            0 => "No",
            1 => "Yes",
        ));

        $this->add($alert_owner);

        $alert_admin = new Select('alert_admin', array(
            0 => "No",
            1 => "Yes",
        ));

        $this->add($alert_admin);

        // Type
        $types = new Select('type', Servers::getServerTypes());
        $this->add($types);

        // Submit button
        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
    }
}