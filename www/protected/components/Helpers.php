<?php


class Helpers
{

    const SECONDS_IN_YEAR       = 31557600;
    const SECONDS_IN_MONTY      = 2678400;
    const SECONDS_IN_WEEK       = 604800;
    const SECONDS_IN_DAY        = 86400;
    const SECONDS_IN_MINUTE     = 60;
    const SECONDS_IN_HOUR       = 3600;

    const FORMAT_YEAR       = 'Y';
    const FORMAT_MONTY      = 'm';
    const FORMAT_WEEK       = 'd';
    const FORMAT_DAY        = 'd';
    const FORMAT_HOUR       = 'H';
    const FORMAT_MINUTE     = 'i';
    const FORMAT_SECOND     = 's';

    /**
     * @param integer $time time (in seconds) to process
     * @param integer $module time will be rounded by this amount of specified item of measure (seconds, minutes, hours etc.)
     * @param string $itemFormat date format that represents item of measure to be rounded (seconds, minutes, hours etc.)
     * @param string $method roun up or down. Default is 'up'
     * @return integer rounded time in seconds
     * @throws InvalidArgumentException if format is not allowed
     */
    public static function roundTime($time, $module, $itemFormat, $method = 'up')
    {
        $itemValue = date($itemFormat, $time);
        $rounded = ceil($itemValue / $module) + ($method == 'up') ? 1 : 0;
        $itemValueSeconds = 0;
        switch ($itemFormat) {
            case 's':
                $itemValueSeconds = $itemValue;
                break;
            case 'i':
                $itemValueSeconds = $itemValue * self::SECONDS_IN_MINUTE;
                $rounded *= self::SECONDS_IN_MINUTE;
                break;
            case 'H':
                $itemValueSeconds = $itemValue * self::SECONDS_IN_HOUR;
                $rounded *= self::SECONDS_IN_HOUR;
                break;
            case 'd':
                $itemValueSeconds = $itemValue * self::SECONDS_IN_DAY;
                $rounded *= self::SECONDS_IN_DAY;
                break;
            case 'm':
                $itemValueSeconds = $itemValue * self::SECONDS_IN_MONTY;
                $rounded *= self::SECONDS_IN_MONTY;
                break;
            case 'Y':
                $itemValueSeconds = $itemValue * self::SECONDS_IN_YEAR;
                $rounded *= self::SECONDS_IN_YEAR;
                break;
            default:
                throw new InvalidArgumentException('Invalid format parameter. Acceptable formats are one of [Y,m,d,H,i,s]\n See php date() documentation for details');
        }
        $timeWithoutItem = $time - $itemValueSeconds;
        $resultTime = $timeWithoutItem + $rounded;
        return $resultTime;
    }

    public static function roundTimeDown($time, $module, $itemFormat)
    {
        return self::roundTimeUp($time, $module, $itemFormat, 'down');
    }

    public static function roundTimeUp($time, $module, $itemFormat)
    {
        return self::roundTimeUp($time, $module, $itemFormat, 'up');
    }

    public static function roundSecondsUp   ($time, $module )   {  return self::roundTimeUp($time, $module,     self::FORMAT_SECOND );  }
    public static function roundMinutesUp   ($time, $module )   {  return self::roundTimeUp($time, $module,     self::FORMAT_MINUTE );  }
    public static function roundHoursUp     ($time, $module )   {  return self::roundTimeUp($time, $module,     self::FORMAT_HOUR );    }
    public static function roundDaysUp      ($time, $module )   {  return self::roundTimeUp($time, $module,     self::FORMAT_DAY );     }
    public static function roundWeeksUp     ($time, $module )   {  return self::roundTimeUp($time, $module * 7, self::FORMAT_WEEK );}
    public static function roundMonthUp     ($time, $module )   {  return self::roundTimeUp($time, $module,     self::FORMAT_MONTY );   }
    public static function roundYearsUp     ($time, $module )   {  return self::roundTimeUp($time, $module,     self::FORMAT_YEAR );    }
}