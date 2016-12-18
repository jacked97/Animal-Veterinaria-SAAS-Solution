<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 12/4/16
 * Time: 4:23 PM
 */

namespace app\staticcontent;


class StaticContentPrivateSlot
{
    public static function getAllHourInDay()
    {
        $result = array();
        $date = new \DateTime("2016-10-10 00:00");
        for ($i = 1; $i <= 24; $i++) {
            $hour = $date->format("H:i");
            $result[$hour] = $hour;
            $date->add(new \DateInterval('PT1H'));
        }
        return $result;
    }
}