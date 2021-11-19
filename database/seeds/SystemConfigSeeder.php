<?php

use Illuminate\Database\Seeder;
use App\SystemConfig;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (SystemConfig::where("type", "CAT")->first())
            return;

        $cfg = new SystemConfig();
        $cfg->{"type"} = "CAT";
        $cfg->{"key"} = "A01";
        $cfg->{"value"} = "電腦";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "CAT";
        $cfg->{"key"} = "A02";
        $cfg->{"value"} = "電話";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "INIT_STATUS";
        $cfg->{"key"} = "B00";
        $cfg->{"value"} = "初始";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "STATUS";
        $cfg->{"key"} = "B01";
        $cfg->{"value"} = "設備回收中";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "STATUS";
        $cfg->{"key"} = "B02";
        $cfg->{"value"} = "評定為可重用電腦，準備維修及安裝";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "STATUS";
        $cfg->{"key"} = "B03";
        $cfg->{"value"} = "評定為不可重用電腦";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "STATUS";
        $cfg->{"key"} = "B04";
        $cfg->{"value"} = "準備給申請人提取電腦";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "STATUS";
        $cfg->{"key"} = "B05";
        $cfg->{"value"} = "出售給回收商";
        $cfg->save();

        $cfg = new SystemConfig();
        $cfg->{"type"} = "RECYCLE_STATUS";
        $cfg->{"key"} = "B03";
        $cfg->{"value"} = "B03";
        $cfg->save();

    }
}
