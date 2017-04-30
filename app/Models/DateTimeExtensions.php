<?php

namespace App\Models;

class DateTimeExtensions
{
    public static function getDate() {
        return date('Y-m-d H:i:s');
    }

    public static function add30Minutes($numberOfMinutes) {
        if ($numberOfMinutes > 30) {
            return $numberOfMinutes - 30;
        } else if ($numberOfMinutes == 30) {
            return 0;
        }

        return $numberOfMinutes + 30;
    }

    public static function add20Minutes($numberOfMinutes) {
        $numberOfMinutes += 20;

        if ($numberOfMinutes == 60) {
            $numberOfMinutes = 0;
        } else if ($numberOfMinutes > 60) {
            $numberOfMinutes -= 60;
        }

        return $numberOfMinutes;
    }

    public static function subtract20Minutes($numberOfMinutes) {
        $numberOfMinutes -= 20;

        if ($numberOfMinutes < 0) {
            $numberOfMinutes += 60;
        }

        return $numberOfMinutes;
    }

    public static function getUnitedStatesDay() {
        return date('Y-m-d', strtotime('-5 hours'));
    }

    // Inclusive
    public static function isWithinRange($numberToCheck, $lowerNumber, $higherNumber) {
        return $numberToCheck >= $lowerNumber && $numberToCheck <= $higherNumber;
    }

    public static function toRelative($date) {
        $diff = time() - strtotime($date);
        if ($diff < 60)
            return $diff . " second" . self::plural($diff) . " ago";
        $diff = round($diff / 60);
        if ($diff < 60)
            return $diff . " minute" . self::plural($diff) . " ago";
        $diff = round($diff / 60);
        if ($diff < 24)
            return $diff . " hour" . self::plural($diff) . " ago";
        $diff = round($diff / 24);
        if ($diff < 7)
            return $diff . " day" . self::plural($diff) . " ago";
        $diff = round($diff / 7);
        if ($diff < 4)
            return $diff . " week" . self::plural($diff) . " ago";
        return "on " . date("F j, Y", strtotime($date));
    }

    public static function plural($num) {
        if ($num != 1)
            return "s";
    }
}