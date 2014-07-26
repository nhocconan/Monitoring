<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
    protected function checkAuthentication()
    {
        $identity = $this->session->get('auth');
        if(is_null($identity))
        {
            return $this->response->redirect('/clients/login', true);
        }
    }

    protected function checkAuthenticationAdmin()
    {
        $identity = $this->session->get('authAdmin');
        if(is_null($identity))
        {
            return $this->response->redirect('/admin/login', true);
        }
    }

    protected function isAdmin()
    {
        $identity = $this->session->get('auth');
        $user = Users::findFirst(array(
            'id = :id:',
            'bind' => array(
                'id' => $identity

            )
        ));
        return ($user->is_admin == 1);
    }

    protected function getServersByUser($userId)
    {
        return Servers::find(array(
            "conditions" => "owner = :owner:",
            "bind"       => array("owner" => $userId),
            "order"      => "id"
        ));
    }
    protected function getApplicationsByUser($userId)
    {
        return Applications::find(array(
            "conditions" => "owner = :owner:",
            "bind"       => array("owner" => $userId),
            "order"      => "id"
        ));
    }
    protected function getServerStats($serverId)
    {
        $statsUnit = StatsUnit::find(array(
            "conditions" => "server_id = :id:",
            "bind"       => array("id" => $serverId),
            "order"      => "timestamp"
        ));
        $statsHour = StatsHour::find(array(
            "conditions" => "server_id = :id:",
            "bind"       => array("id" => $serverId),
            "order"      => "timestamp"
        ));
        $statsDay = StatsDay::find(array(
            "conditions" => "server_id = :id:",
            "bind"       => array("id" => $serverId),
            "order"      => "timestamp"
        ));

        return [
            'Unit' => $this->reformatStats($statsUnit),
            'Hour' => $this->reformatStats($statsHour),
            'Day'  => $this->reformatStats($statsDay)
        ];
    }
    protected function arrayPropertyExists($value, $property, $array)
    {
        foreach ($array as $item) {
            if ($item->$property == $value) {
                return true;
            }
        }
        return false;
    }
    protected function setMonitoringData(&$entity, $data, $server_id)
    {
        $entity->mem_used_mb   = $data->system->mem_used - $data->system->mem_cached;
        $entity->mem_cached_mb = $data->system->mem_cached;
        $entity->mem_free_mb   = $data->system->mem_free;
        $entity->load          = $data->system->load_5min;
        $entity->disks         = serialize($data->system->disk);
        $entity->networks      = serialize($data->system->iface);
        $entity->server_id     = $server_id;
        $entity->timestamp     = (new \DateTime())->format('Y-m-d H:i:s');
    }
    protected function getDisksAsList($stats)
    {
        return (isset($stats['Unit'][0])) ? array_keys(get_object_vars($stats['Unit'][0]->disks)) : [];
    }
    protected function getInterfacesAsList($stats)
    {
        return (isset($stats['Unit'][0])) ? array_keys(get_object_vars($stats['Unit'][0]->networks)) : [];
    }
    protected function sendEmail($email, $subject, $recipient, $replacements = [])
    {
        $config  = $this->getDI()->getServices()['config']->resolve();
        $content = file_get_contents(sprintf('%s/../emails/%s.txt', __DIR__, $email));
        foreach($replacements as $old => $new) $content = str_replace(sprintf('__%s__', $old), $new, $content);

        $postmark = new \Postmark($config->postmark->key, $config->postmark->from);
        $postmark->to($recipient)
            ->subject($subject)
            ->plain_message($content)
            ->send();
    }
    protected function getApplicationStats($appId)
    {
        $statsUnit = AStatsUnit::find(array(
            "conditions" => "application_id = :id:",
            "bind"       => array("id" => $appId),
            "order"      => "timestamp"
        ));
        $statsHour = AStatsHour::find(array(
            "conditions" => "application_id = :id:",
            "bind"       => array("id" => $appId),
            "order"      => "timestamp"
        ));
        $statsDay = AStatsDay::find(array(
            "conditions" => "application_id = :id:",
            "bind"       => array("id" => $appId),
            "order"      => "timestamp"
        ));

        return [
            'Unit' => $this->reformatAStats($statsUnit),
            'Hour' => $this->reformatAStats($statsHour),
            'Day'  => $this->reformatAStats($statsDay)
        ];
    }
    private function reformatAStats($stats)
    {
        foreach($stats as $stat) {
            $data['app'][$stat->probe][] = [
                $stat->epoch => $stat->get_time
            ];
            $data['tcp'][$stat->probe][] = [
                $stat->epoch => $stat->connect_time
            ];
        }
        return $data;
    }
    private function reformatStats($stats)
    {
        $disks  = $this->getDisksAsList(['Unit' => $stats]);
        $ifaces = $this->getInterfacesAsList(['Unit' => $stats]);

        foreach($stats as $stat)
        {
            $data['mem_used_mb'][] = [
                $stat->epoch => number_format(($stat->mem_used_mb / ($stat->mem_used_mb + $stat->mem_cached_mb + $stat->mem_free_mb)) * 100)
            ];
            $data['load'][]        = [
                $stat->epoch => $stat->load]
            ;

            foreach($disks as $disk)
            {
                $data['disks'][$disk]['read'][]  = [
                    $stat->epoch => $stat->disks->$disk->write_kb ?: 0
                ];
                $data['disks'][$disk]['write'][] = [
                    $stat->epoch => $stat->disks->$disk->read_kb ?: 0
                ];
                $data['disks'][$disk]['inode'][] = [
                    $stat->epoch => number_format((($stat->disks->$disk->total_inodes_k - $stat->disks->$disk->free_inodes_k) / $stat->disks->$disk->total_inodes_k) * 100)
                ];
                $data['disks'][$disk]['space'][] = [
                    $stat->epoch => number_format((($stat->disks->$disk->total_space_mb - $stat->disks->$disk->free_space_mb) / $stat->disks->$disk->total_space_mb) * 100)
                ];
            }
            foreach($ifaces as $iface)
            {
                $data['networks'][$iface]['ingress'][]  = [
                    $stat->epoch => $stat->networks->$iface->ingress_mb ?: 0
                ];
                $data['networks'][$iface]['egress'][]   = [
                    $stat->epoch => $stat->networks->$iface->egress_mb ?: 0
                ];
            }
        }
        return $data;
    }
}