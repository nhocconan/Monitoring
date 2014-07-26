<?php

class AlertTask extends \Phalcon\CLI\Task
{
    public function mainAction() {
        foreach(Policies::find() as $policy)
        {
            echo "Checking policy $policy->id...\n";
            $values = array();
            foreach($policy->conditions as $condition)
            {
                if($condition->server_id)
                {
                    // If this condition is server-dependent
                    $stat = StatsUnit::findFirst(array(
                        "conditions" => "server_id = :id:",
                        "bind"       => array("id" => $condition->server_id),
                        "order"      => "timestamp DESC"
                    ));
                } else {
                    // If it is app-dependent
                    $stat = AStatsUnit::findFirst(array(
                        "conditions" => "application_id = :id:",
                        "bind"       => array("id" => $condition->app_id),
                        "order"      => "timestamp DESC"
                    ));
                }

                // If we haven't crossed the threshold for this condition and stat, move on to the next policy
                if(!$this->compareCondition($condition, $stat)){
                    $this->setAlarmOff($policy->id);
                    echo "Condition $condition->id doesn't match...\n";
                    continue 2;
                }

                // Otherwise, add the value to the values array for overall operator comparison
                $values[] = true;
            }

            for($i=1; $i<count($policy->conditions); $i++)
            {
                $thisConditional = sprintf("operator_%d", $i);
                switch($policy->$thisConditional)
                {
                    case "and":
                        if(!($values[$i] && $values[$i+1])){
                            $this->setAlarmOff($policy->id);
                            continue 2;
                        }
                        break;
                    case "or":
                        if(!($values[$i] || $values[$i+1])){
                            $this->setAlarmOff($policy->id);
                            continue 2;
                        }
                        break;
                    default:
                        continue 2;
                }
            }

            // The alarm is on! Check for a previous alarm
            echo "Checking for previous alarm...\n";
            $lastAlarm = Triggers::findFirst(array(
                "conditions" => "policy_id = :policy: AND alarm_off_timestamp IS NULL",
                "bind"       => array("policy" => $policy->id)
            ));
            if(!$lastAlarm)
            {
                // We need to trigger the action
                echo "Raising alarm...\n";
                $trigger                     = new Triggers();
                $trigger->policy_id          = $policy->id;
                $trigger->alarm_on_timestamp = $stat->timestamp;
                $trigger->save();

                if($policy->what_to_do == 'alert')
                {
                    $config   = $this->getDI()->getServices()['config']->resolve();
                    $postmark = new \Postmark($config->postmark->key, $config->postmark->key);
                    $postmark->to($policy->user->email)
                        ->subject('Monitoring Policy Triggered')
                        ->plain_message(sprintf("The following Monitoring policy has been triggered:\n\n%s\n\nTimestamp: %s\n\nFor more information please log in to SeverMetrics.", $policy->wordy, (new \DateTime())->format('Y-m-d H:i:s')))
                        ->send();
                }
            }
        }
    }
    private function compareCondition($condition, $stat)
    {
        foreach($this->getMetricValue($condition, $stat) as $value)
        {
            switch($condition->operator)
            {
                case "lt":
                    if($value < $condition->threshold) return true;
                    break;
                case "lte":
                    if($value <= $condition->threshold) return true;
                    break;
                case "gt":
                    if($value > $condition->threshold) return true;
                    break;
                case "gte":
                    if($value >= $condition->threshold) return true;
                    break;
                case "eq":
                    if($value == $condition->threshold) return true;
                    break;
            }
        }
        return false;
    }
    private function getMetricValue($condition, $stat)
    {
        switch($condition->metric)
        {
            case "app-tcp-s":
                return array($stat->connect_time);
            case "app-page-s":
                return array($stat->get_time);
            case "server-load":
                return array($stat->load);
            case "server-mem-per":
                return array(($stat->mem_used_mb / ($stat->mem_cached_mb + $stat->mem_used_mb + $stat->mem_free_mb)) * 100);
            case "sever-disk-per":
                $return = array();
                foreach($stat->disks as $disk) $return[] = (($disk->total_space_mb - $disk->free_space_mb) / $disk->total_space_mb) * 100;
                return $return;
            case "server-iface-mbs":
                $return = array();
                foreach($stat->networks as $network) $return[] = ($network->ingress_mb + $network->egress_mb) / 300;
                return $return;
            default:
                return array();
        }
    }
    private function setAlarmOff($policy)
    {
        $triggers = Triggers::find(array(
            "conditions" => "policy_id = :policy:",
            "bind"       => array("policy" => $policy)
        ));

        foreach($triggers as $trigger)
        {
            $trigger->alarm_off_timestamp = (new \DateTime())->format('Y-m-d H:i:s');
            $trigger->save();
        }
    }
}