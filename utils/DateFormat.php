<?php

namespace ApiFestiplan\utils;

class DateFormat {

    /** List of all French days names */
    private const DAYS_NAMES = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

    /** List of all French months names */
    private const MONTHS_NAMES = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    /**
     * Get the French day name of a date
     * @param string $date a date
     * @return string the French day name
     */
    public static function getDayName(string $date):string {
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return "Erreur";
        }
        $day = date("N", $timestamp);
        return self::DAYS_NAMES[$day-1];
    }

    /**
     * Get the day of a date
     * @param string $date a date
     * @return string the day of a date
     */
    public static function getDay(string $date):string {
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return "Erreur";
        }

        $day = date("d", $timestamp);
        return $day;
    }

    /**
     * Get the French month name of a date
     * @param string $date a date
     * @return string the French month name
     */
    public static function getMonthName(string $date):string {
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return "Erreur";
        }
        $month = date("n", $timestamp);
        return self::MONTHS_NAMES[$month-1];
    }

    /**
     * Get the year of a date
     * @param string $date a date
     * @return string the year of a date
     */
    public static function getYear(string $date): string {
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return "Erreur";
        }
        $year = date("Y", $timestamp);
        return $year;
    }

    /**
     * Return a hour in French format without seconds ex: 12h34
     * @param string $hour time to convert
     * @return string the time in French format
     */
    public static function getHour(string $hour):string {
        $explode = explode(":", $hour);
        return $explode[0] . "h" . $explode[1];
    }

    /**
     * Return a date in French
     * @param string $date date to convert
     * @return string the date in French format (ex: samedi 12 janvier 2024)
     */
    public static function getFullStringDate($date):string {
        return DateFormat::getDayName($date) . " " . DateFormat::getDay($date) . " " . DateFormat::getMonthName($date) . " " . DateFormat::getYear($date);
    }

}