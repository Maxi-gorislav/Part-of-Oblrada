<?php

namespace App\Observers;

use App\Models\Menu;
use Artisan;

class MenuObserver
{
    /**
     * Listen to the Menu retrieved event.
     *
     * @param Menu $setting
     */
    public function retrieved(Menu $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the Menu created event.
     *
     * @param Menu $setting
     */
    public function created(Menu $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the Menu updated event.
     *
     * @param Menu $setting
     */
    public function updated(Menu $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the Menu saved event.
     *
     * @param Menu $setting
     */
    public function saved(Menu $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the Menu deleted event.
     *
     * @param Menu $setting
     */
    public function deleted(Menu $setting)
    {
        $this->clearCache($setting);
    }

    /**
     * Listen to the Menu restored event.
     *
     * @param Menu $setting
     */
    public function restored(Menu $setting)
    {
        $this->clearCache($setting);
    }

    public function clearCache($setting)
    {
        $setting->forgetCache();
        Artisan::call('view:clear');
    }
}
