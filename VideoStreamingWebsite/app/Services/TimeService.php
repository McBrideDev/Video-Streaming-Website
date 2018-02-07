<?php namespace App\Services;
class TimeService{

	public static function setTimeZoneCurrentUser($timeZoneUser, $timeZoneCreate){

		$time = strtotime($timeZoneCreate) - intval($timeZoneUser*60);

		return $time;
	}

    public static function dateWithTimeZone($dateInput)
    {
        $time_zone = !empty($_COOKIE['time-zone-user']) ? $_COOKIE['time-zone-user'] : 0;
        $result = date("Y-m-d H:i:s", TimeService::setTimeZoneCurrentUser( $time_zone, $dateInput));
        return $result;
    }
}