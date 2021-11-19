<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $table = 'system_config';

    public static function getInitStatus() {
        $initStatus = SystemConfig::where('type', 'INIT_STATUS')
            ->first();
        return $initStatus;
    }

    public static function getUsableStatus() {
        return SystemConfig::where('type', 'STATUS')->get();
    }

    public static function getRecycleStatus() {
        $status = SystemConfig::where('type', 'RECYCLE_STATUS')
            ->first();
        $status = SystemConfig::where('type', 'STATUS')
            ->where('key', $status->{'key'})
            ->first()->{'value'};
        return $status;
    }

    public static function getAllStatus() {
        $all = array();

        $list = SystemConfig::where('type', 'STATUS')->get();
        foreach ($list as $status) {
            $all[$status->{'key'}] = $status->{'value'};
        }

        $list = SystemConfig::where('type', 'INIT_STATUS')->get();
        foreach ($list as $status) {
            $all[$status->{'key'}] = $status->{'value'};
        }
        return $all;
    }

    public static function isValidStatus($status) {
        $s = SystemConfig::where('type', 'STATUS')
            ->where('key', $status)
            ->first();
        if ($s) {
            return true;
        } else {
            return false;
        }
    }
}
