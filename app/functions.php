<?php

/**
 * Here is your custom functions.
 */

/**
 * assign function
 *
 * @param string|array $name
 * @param mixed $value
 * @param string|null $plugin
 * @return void
 */
function assign(string|array $name, mixed $value = null, string $plugin = null)
{
    $request = \request();
    $plugin = $plugin === null ? ($request->plugin ?? '') : $plugin;
    $handler = \config($plugin ? "plugin.$plugin.view.handler" : 'view.handler');
    $handler::assign($name, $value);
}
