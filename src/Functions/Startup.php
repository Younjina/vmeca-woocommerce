<?php

namespace Ivy\Vmeca\Functions;

use Ivy\Mu\Launchers\AutoDiscoverLauncher;

function checkEnvironment()
{
    if (!defined('IVY_MU_VERSION') || version_compare(IVY_MU_VERSION, '0.5.3', '<')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>IWISH 플러그인은 Ivy-MU 0.5.3이상을 요구합니다. 현재 Ivy-MU가 설치되어 있지 않거나, 버전이 낮습니다.</p></div>';
        });

        return false;
    }

    return true;
}


function getAutoDiscoverLauncher($mainFile = null)
{
    static $launcher = null;

    if (is_null($launcher)) {
        $launcher = AutoDiscoverLauncher::factory(
            $mainFile,
            [
                'rootNamespace'  => 'Ivy\\Vmeca',
                'directory'      => dirname($mainFile) . '/src',
                'slug'           => 'vmeca',
                'customContexts' => [],
            ]
        );
    }

    return $launcher;
}

