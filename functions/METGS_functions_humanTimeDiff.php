<?php defined('ABSPATH') or die('Not today.');
/**
 * Helpers for humanTimeDiff
 */

class METGS_functions_humanTimeDiff
{
    function __construct()
    {

    }
    
    static function getHumanTimeDiff($date){
        $now = time();
        $humanTimeDiffTxt='';
        if(!empty($date)) {
            $humanTimeDiff = human_time_diff($date, $now);
            if ($date < $now) {
                $humanTimeDiffTxt = sprintf(_x('%s ago', '%s = human-readable time difference', 'meetings'), $humanTimeDiff);
            } else {
                $humanTimeDiffTxt = sprintf(_x('in %s', '%s = human-readable time difference', 'meetings'), $humanTimeDiff);
            }
        }
        return $humanTimeDiffTxt;
    }
}