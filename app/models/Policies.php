<?php
use Phalcon\Mvc\Model\Validator\Regex,
    Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\Numericality;

class Policies extends Phalcon\Mvc\Model
{
    public $id;
    public $name;
    public $owner;
    public $what_to_do;
    public $condition_1;
    public $operator_1;
    public $condition_2;
    public $operator_2;
    public $condition_3;

    public $wordy;
    public $conditions;
    public $user;

    public function validation()
    {
        $this->validate(new Numericality(
            array(
                "field"   => "owner",
                "message" => "The field owner is required"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "name",
                "message" => "You must give a name to your policy"
            )
        ));
        $this->validate(new PresenceOf(
            array(
                "field"   => "what_to_do",
                "message" => "The system needs to know what to do"
            )
        ));

        return $this->validationHasFailed() != true;
    }

    public function afterFetch()
    {
        // We need these every time
        $this->conditions = PolicyConditions::find(array(
            "conditions" => "trigger_id = :id:",
            "bind"       => array("id" => $this->id)
        ));
        $this->user       = Users::findFirst(array(
            "conditions" => "id = :id:",
            "bind"       => array("id" => $this->owner)
        ));
        $this->wordy = $this->createWordyPolicy();
    }

    private function createWordyPolicy()
    {
        $string = sprintf("%s when %s's %s is %s %s",
            ucfirst($this->what_to_do),
            is_null($this->conditions[0]->app_id) ? $this->conditions[0]->server_name : $this->conditions[0]->app_name,
            $this->conditions[0]->metric_words,
            $this->conditions[0]->operator_words,
            $this->conditions[0]->threshold
        );
        if(count($this->conditions) > 1)
        {
            for($i=1; $i<count($this->conditions); $i++)
            {
                $thisConditional = sprintf("operator_%d", $i);
                $string .= sprintf(" %s %s's %s is %s %s",
                    $this->$thisConditional,
                    is_null($this->conditions[$i]->app_id) ? $this->conditions[$i]->server_name : $this->conditions[$i]->app_name,
                    $this->conditions[$i]->metric_words,
                    $this->conditions[$i]->operator_words,
                    $this->conditions[$i]->threshold
                );
            }
        }
        return $string.'.';
    }
}