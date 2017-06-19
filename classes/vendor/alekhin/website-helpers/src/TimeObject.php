<?php

namespace Alekhin\WebsiteHelpers;

if (!class_exists(__NAMESPACE__ . '\TimeObject')) {

    class TimeObject {

        const format_year = 'Y';
        const format_month = 'n';
        const format_day = 'j';
        const format_hour = 'G';
        const format_hour12 = 'g';
        const format_minute = 'i';
        const format_second = 's';
        const format_meridian = 'A';
        const format_date_long = 'F j, Y';
        const format_date_medium = 'M j, Y';
        const format_date_short = 'm/d/Y';
        const format_time_long = 'G:i:s';
        const format_time_medium = 'g:i:s A';
        const format_time_short = 'g:i A';

        var $timestamp = 0;
        var $year = -1;
        var $month = -1;
        var $day = -1;
        var $hour = -1;
        var $hour12 = -1;
        var $minute = -1;
        var $second = -1;
        var $meridian = '';
        var $date_long = '';
        var $date_medium = '';
        var $date_short = '';
        var $time_long = '';
        var $time_medium = '';
        var $time_short = '';

        function __construct($timestamp) {
            $this->timestamp = intval(trim($timestamp));
            $this->year = intval(date(self::format_year, $this->timestamp));
            $this->month = intval(date(self::format_month, $this->timestamp));
            $this->day = intval(date(self::format_day, $this->timestamp));
            $this->hour = intval(date(self::format_hour, $this->timestamp));
            $this->hour12 = intval(date(self::format_hour12, $this->timestamp));
            $this->minute = intval(date(self::format_minute, $this->timestamp));
            $this->second = intval(date(self::format_second, $this->timestamp));
            $this->meridian = date(self::format_meridian, $this->timestamp);
            $this->date_long = date(self::format_date_long, $this->timestamp);
            $this->date_medium = date(self::format_date_medium, $this->timestamp);
            $this->date_short = date(self::format_date_short, $this->timestamp);
            $this->time_long = date(self::format_time_long, $this->timestamp);
            $this->time_medium = date(self::format_time_medium, $this->timestamp);
            $this->time_short = date(self::format_time_short, $this->timestamp);
        }

        public function display($format = self::format_date_long) {
            switch ($format) {
                case self::format_year: return $this->year;
                case self::format_month: return $this->month;
                case self::format_day: return $this->day;
                case self::format_hour: return $this->hour;
                case self::format_hour12: return $this->hour12;
                case self::format_minute: return $this->minute;
                case self::format_second: return $this->second;
                case self::format_meridian: return $this->meridian;
                case self::format_date_long: return $this->date_long;
                case self::format_date_medium: return $this->date_medium;
                case self::format_date_short: return $this->date_short;
                case self::format_time_long: return $this->time_long;
                case self::format_time_medium: return $this->time_medium;
                case self::format_time_short: return $this->time_short;
                default: return date($format, $this->timestamp);
            }
        }

    }

}
