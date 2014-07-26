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

class AddUserForm extends Form
{
    public function initialize()
    {
        // Name
        $name = new Text('name', array(
            'placeholder' => 'Name'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'name' => 'The name is required'
            ))
        ));

        $this->add($name);

        // Email
        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        $email->addValidator(new Email(array(
            'message' => 'A valid email address is required'
        )));

        $this->add($email);

        // Password
        $password = new Password('password', array(
            'placeholder' => 'Password',
            'value'       => substr(md5(rand()), 0, 8)
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));

        $this->add($password);

        // Is Admin
        $admin = new Select('is_admin', array(
            0 => "No",
            1 => "Yes"
        ));

        $this->add($admin);

        // Number of servers to monitor
        $monitor_servers = new Text('monitor_servers', array(
            'placeholder' => 'Number of servers'
        ));

        $this->add($monitor_servers);

        // Number of applications to monitor
        $monitor_applications = new Text('monitor_applications', array(
            'placeholder' => 'Number of applications'
        ));

        $this->add($monitor_applications);

        // Send email with details?
        $details = new Check('send_details');

        $this->add($details);

        // Submit button
        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
    }
}