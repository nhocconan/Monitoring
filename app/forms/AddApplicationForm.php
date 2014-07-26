<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Regex;

class AddApplicationForm extends Form
{
    public $uid;
    public function __construct($uid = 0)
    {
        $this->uid = $uid;
        parent::__construct();
    }
    public function initialize()
    {
        // URL
        $url = new Text('url', array(
            'placeholder' => 'http(s)://host.name/app/test-page'
        ));

        $url->addValidators(array(
            new PresenceOf(array(
                'url' => 'The URL is required'
            ))
        ));

        $this->add($url);

        // Content to look for
        $content = new TextArea('content', array(
            'placeholder' => 'Content to look for'
        ));

        $this->add($content);

        // User
        $owner = new Select('owner', Users::find(), array(
            'using' => array('id', 'name')
        ));

        $this->add($owner);

        // Server
        if($this->uid === 0)
        {
            $server = new Select('server', Servers::find(), array(
                'using' => array('id', 'friendly_name')
            ));
        } else {
            $server = new Select('server', Servers::find(array(
                "conditions" => "owner = :uid:",
                "bind"       => array("uid" => $this->uid)
            )), array(
                'using' => array('id', 'friendly_name')
            ));
        }


        $this->add($server);

        // Type
        $types = new Select('type', Applications::getApplicationTypes());
        $this->add($types);

        // URL
        $name = new Text('friendly_name', array(
            'placeholder' => 'My App'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'friendly_name' => 'A friendly name is required'
            ))
        ));

        $this->add($name);

        // Submit button
        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
    }
}