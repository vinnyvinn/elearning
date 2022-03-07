<?php
// Created by Bennito254 (https://www.bennito254.com)

function getInstalledPlugins()
{
    return \App\Core\Modules::getInstance()->scan();
}

function loadActiveModules()
{
    $plugins = get_parent_option('system', '_active_plugins', FALSE);
    $plugins = json_decode($plugins, true);

    if ($plugins && is_array($plugins) && count($plugins) > 0) {
        foreach ($plugins as $plugin) {

            if (file_exists(MODULES_PATH . $plugin['fn'])) {
                require_once(MODULES_PATH . $plugin['fn']);
            } else {
                log_message('error', "Module {$plugin['fn']} does not exist");
            }
        }
    }
}

function activateModule($id)
{
    $plugins = getInstalledPlugins();

    if (isset($plugins[$id])) {
        $activeModules = activeModules();
        $activeModules = is_array($activeModules) ? $activeModules : [];
        $activeModules = array_merge($activeModules, [$id => $plugins[$id]]);

        update_parent_option('system', '_active_plugins', json_encode($activeModules));
        return true;
    }

    return false;
}

function deactivateModule($id)
{
    $activeModules = activeModules();
    unset($activeModules[$id]);

    update_parent_option('system', '_active_plugins', json_encode($activeModules));

    return true;
}

function activeModules($id = FALSE)
{
    $plugins = get_parent_option('system', '_active_plugins', FALSE);
    $plugins = json_decode($plugins, true);
    if ($id) {
        if (isset($plugins[$id])) {
            return true;
        }
        return false;
    }

    return $plugins;
}

function deleteModule($id)
{
    //Deactivate
    deactivateModule($id);
    //Delete
    helper('filesystem');
    $plugin = getInstalledPlugins();
    if (delete_files(dirname(MODULES_PATH . $plugin[$id]['fn']), true)) {
        @rmdir(dirname(MODULES_PATH . $plugin[$id]['fn']));
        return true;
    }

    return false;
}

/**
 * @param $request \CodeIgniter\HTTP\Request
 */
function installPlugin($request)
{
    /** @var \CodeIgniter\HTTP\Files\UploadedFile $file */
    if ($file = @$request->getFile('module')) {
        if ($file->isValid()) {
            $zip = new ZipArchive();
            if ($zip->open($file->getTempName())) {
                if ($zip->extractTo(MODULES_PATH)) {
                    $zip->close();
                    @chmod(MODULES_PATH . '*', 0777);
                    return true;
                } else {
                    $zip->close();
                    return "Failed to install plugin";
                }
            } else {
                $zip->close();
                return "Invalid ZIP Archive";
            }
        } else {
            return $file->getErrorString();
        }
    }
    return "Invalid file";
}

function modules_url($path = '', $plugin = '')
{
    $base = @explode(MODULES_PATH, $plugin)[1];

    $base = $base ? base_url('modules/' . dirname($base . '/')) : base_url('modules/');

    return $base . '/' . $path;
}

function normalize_path($path)
{
    $wrapper = '';

    // Standardise all paths to use '/'.
    $path = str_replace('\\', '/', $path);

    // Replace multiple slashes down to a singular, allowing for network shares having two slashes.
    $path = preg_replace('|(?<=.)/+|', '/', $path);

    // Windows paths should uppercase the drive letter.
    if (':' === substr($path, 1, 1)) {
        $path = ucfirst($path);
    }

    return $wrapper . $path;
}

function plugin_basename($file)
{
    return $file;
}