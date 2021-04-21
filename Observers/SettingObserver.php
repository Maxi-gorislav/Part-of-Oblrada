<?php

namespace App\Observers;

use App\Models\KeyValue;
use Artisan;

class SettingObserver
{
    /**
     * Listen to the KeyValue retrieved event.
     *
     * @param KeyValue $setting
     */
    public function retrieved(KeyValue $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the KeyValue created event.
     *
     * @param KeyValue $setting
     */
    public function created(KeyValue $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the KeyValue updated event.
     *
     * @param KeyValue $setting
     */
    public function updated(KeyValue $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the KeyValue saved event.
     *
     * @param KeyValue $setting
     */
    public function saved(KeyValue $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the KeyValue deleted event.
     *
     * @param KeyValue $setting
     */
    public function deleted(KeyValue $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the KeyValue restored event.
     *
     * @param KeyValue $setting
     */
    public function restored(KeyValue $setting)
    {
        $this->clearCache($setting);
    }

    private function clearCache($setting)
    {
        $setting->forgetCache();
        Artisan::call('view:clear');
    }
}
