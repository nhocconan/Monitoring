<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class EmailForm extends Form
{

    public function initialize()
    {
        // Email
        $subject = new Text('subject', array(
            'placeholder' => 'Subject Line'
        ));

        $subject->addValidators(array(
            new PresenceOf(array(
                'subject' => 'The subject line is required'
            )),
        ));

        $this->add($subject);

        // Message
        $message = new TextArea('message', array(
            'placeholder' => 'Message Body'
        ));

        $message->addValidators(array(
            new PresenceOf(array(
                'message' => 'The message body is required'
            )),
        ));

        $this->add($message);

        // Submit button
        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
    }
}