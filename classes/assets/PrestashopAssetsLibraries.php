<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
class PrestashopAssetsLibraries
{
    public const css = 'registerStylesheet';
    public const js = 'registerJavascript';

    /**
     * List of libraries available.
     *
     * @var array
     */
    private static $assetsLibraries = [
        'font-awesome' => [
            // array of array because a library can have js & css
            [
                'id' => 'font-awesome-css',
                'type' => self::css,
                'path' => '/themes/_libraries/font-awesome/css/font-awesome.min.css',
                'params' => ['media' => 'all', 'priority' => 10],
            ],
        ],
        // can imagine others libraries
    ];

    /**
     * Get Library files from name.
     *
     * @param string $name
     *
     * @return bool|mixed
     */
    public static function getAssetsLibraries($name)
    {
        return array_key_exists($name, self::$assetsLibraries) ? self::$assetsLibraries[$name] : false;
    }
}
