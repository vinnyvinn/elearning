<?php


namespace App\Controllers\Admin;


use App\Libraries\Updater;
use Psr\SimpleCache\InvalidArgumentException;

class Updates extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Admin/Settings/Updates/index', $this->data);
    }

    public function runUpdates()
    {
        $updater = new \App\Libraries\Updater(WRITEPATH.'cache', FCPATH, 120);

        $cache = new \Desarrolla2\Cache\File(WRITEPATH . 'cache');
        $updater->setCache($cache, 3600);

        $current_version = get_option('_application_version', '0.0.1');

        $updater->setCurrentVersion($current_version);
        $latest_version = $updater->getLatestVersion();

        $updater->onEachUpdateFinish([$this, '_updaterCallback']);
        //d($updater->checkUpdate(10));
        //d($updater->updates);

        try {
            if ($updater->checkUpdate(10) && is_array($updater->updates) && count($updater->updates) > 0) {
                //Simulate first
                if ($updater->update()) {
                    //Maybe its safe to install?
                    if ($updater->update(false, false)) {
                        return redirect()->back()->with('success', "Application updated to version {$latest_version} successfully");
                    } else {
                        return redirect()->back()->with('error', "RED ALERT! We may have broken the system");
                    }
                } else {
                    return redirect()->back()->with('error', "Simulation failed! This update may cause problems. Please contact the developer!");
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('error', "There is no update available");
    }

    public function _updaterCallback($version)
    {
        //update_option('_application_version', $version);
    }
}