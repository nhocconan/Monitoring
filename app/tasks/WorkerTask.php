<?php

class WorkerTask extends \Phalcon\CLI\Task
{
    public function mainAction() {
        $config = $this->getDI()->getServices()['config']->resolve();
        while (true) {
            // Find an app to probe based on last probed time
            $threshold  = (new \DateTime(sprintf('%d minutes ago', $config->probes->interval)))->format('Y-m-d H:i:s');
            $app        = Applications::findFirst(array(
                "conditions" => "last_probed < :timestamp: OR last_probed IS NULL",
                "bind"       => array("timestamp" => $threshold),
                "order"      => "last_probed"
            ));

            // If there is no matching item, wait and continue
            if (!$app) {
                sleep(5);
                continue;
            }

            // Get the next probe
            $index = array_search($app->last_probed_from, array_keys((array) $config->probes->hosts));
            $app->last_probed_from = ($index === false || $index >= (count((array) $config->probes->hosts))-1) ? array_keys((array) $config->probes->hosts)[0] : array_keys((array) $config->probes->hosts)[$index+1];

            // Set the last probed details
            $app->last_probed      = (new \DateTime())->format('Y-m-d H:i:s');
            $app->save();

            // Echo status
            echo sprintf("Probing application %d from %s...\n", $app->id, $app->last_probed_from);

            // Connect to probe and get status
            $url = sprintf("http://%s/fsockopen.php?key=%s&url=%s&string=%s",
                (array)$config->probes->hosts[$app->last_probed_from],
                $config->probes->key,
                $app->url,
                $app->content
            );
            $result = file_get_contents($url);
            if($result === false) continue;
            $result = json_decode($result);

            // Figure out if this is the first entry this hour/day
            $statsHour = AStatsHour::find(array(
                "conditions" => "application_id = :id: AND timestamp > :hour:",
                "bind"       => array(
                    "id"  => $app->id,
                    "hour" => (new \DateTime())->format('Y-m-d H')
                )
            ));
            $statsDay = AStatsDay::find(array(
                "conditions" => "application_id = :id: AND timestamp > :day:",
                "bind"       => array(
                    "id"  => $app->id,
                    "day" => (new \DateTime())->format('Y-m-d')
                )
            ));

            // If it's the first of the hour
            if(count($statsHour) < 1)
            {
                $statsHour = new AStatsHour();
                $this->setMonitoringData($statsHour, $result, $app->id, $app->last_probed_from);
                $statsHour->save();
            }

            // If it's the first of the day
            if(count($statsDay) < 1)
            {
                $statsDay = new AStatsDay();
                $this->setMonitoringData($statsDay, $result, $app->id, $app->last_probed_from);
                $statsDay->save();
            }

            // The unit is always saved
            $statsUnit = new AStatsUnit();
            $this->setMonitoringData($statsUnit, $result, $app->id, $app->last_probed_from);
            $statsUnit->save();
        }
    }
    private function setMonitoringData(&$entity, $result, $app_id, $probe)
    {
        $entity->status         = ($result->status == 'success') ? 1 : 0;
        $entity->string_found   = $result->stringFound;
        $entity->connect_time   = $result->connectTime;
        $entity->get_time       = $result->getTime - $result->connectTime;
        $entity->code           = $result->code;
        $entity->probe          = $probe;
        $entity->application_id = $app_id;
        $entity->timestamp      = (new \DateTime())->format('Y-m-d H:i:s');
    }
}