<?php

class MainTask extends \Phalcon\CLI\Task
{
    const HOLD_UNIT_STATS_FOR = 12; // Hours
    const HOLD_HOUR_STATS_FOR = 48; // Hours
    const HOLD_DAY_STATS_FOR  = 336; // Hours
    const HOLD_ALERTS_FOR     = 336; // Hours
    const RAISE_ALARM_NO_RESPONSE = 15; // Minutes

    public function mainAction() {
        /* Deleting old server monitoring records */
        $discardUnitsAfter = (new \DateTime(sprintf('%d hours ago', self::HOLD_UNIT_STATS_FOR)))->format('Y-m-d H:i:s');
        $discardHoursAfter = (new \DateTime(sprintf('%d hours ago', self::HOLD_HOUR_STATS_FOR)))->format('Y-m-d H:i:s');
        $discardDaysAfter  = (new \DateTime(sprintf('%d hours ago', self::HOLD_DAY_STATS_FOR)))->format('Y-m-d H:i:s');
        $statsUnit = StatsUnit::find(array(
            "conditions" => "timestamp < :timestamp:",
            "bind"       => array("timestamp" => $discardUnitsAfter)
        ));
        $statsHour = StatsHour::find(array(
            "conditions" => "timestamp < :timestamp:",
            "bind"       => array("timestamp" => $discardHoursAfter)
        ));
        $statsDay  = StatsDay::find(array(
            "conditions" => "timestamp < :timestamp:",
            "bind"       => array("timestamp" => $discardDaysAfter)
        ));

        foreach($statsUnit as $statUnit) $statUnit->delete();
        foreach($statsHour as $statHour) $statHour->delete();
        foreach($statsDay as $statDay) $statDay->delete();

        /* Deleting old app monitoring records */
        $statsUnit = AStatsUnit::find(array(
            "conditions" => "timestamp < :timestamp:",
            "bind"       => array("timestamp" => $discardUnitsAfter)
        ));
        $statsHour = AStatsHour::find(array(
            "conditions" => "timestamp < :timestamp:",
            "bind"       => array("timestamp" => $discardHoursAfter)
        ));
        $statsDay  = AStatsDay::find(array(
            "conditions" => "timestamp < :timestamp:",
            "bind"       => array("timestamp" => $discardDaysAfter)
        ));

        foreach($statsUnit as $statUnit) $statUnit->delete();
        foreach($statsHour as $statHour) $statHour->delete();
        foreach($statsDay as $statDay) $statDay->delete();

        /* We conclude with a smiley face */
        echo "All done :)\n";
    }
}