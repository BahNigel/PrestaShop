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

/**
 * Determine the best existing template.
 *
 * @since 1.7.0.0
 */
class TemplateFinderCore
{
    private $directories;
    private $extension;
    private $productListEntities = ['category', 'manufacturer', 'supplier'];
    private $productListSearchEntities = ['search', 'price-drop', 'best-sale', 'prices-drop', 'best-sales', 'new-products'];
    private $productEntities = ['product'];
    private $brandListEntities = ['manufacturers', 'suppliers'];

    public function __construct(array $directories, $extension)
    {
        $this->directories = $directories;
        $this->extension = $extension;
    }

    public function getTemplate($template, $entity, $id, $locale)
    {
        $locale = (Validate::isLocale($locale)) ? $locale : '';

        $templates = $this->getTemplateHierarchy($template, $entity, $id);

        foreach ($this->directories as $dir) {
            foreach ($templates as $tpl) {
                if (!empty($locale) && is_file($dir . $locale . DIRECTORY_SEPARATOR . $tpl . $this->extension)) {
                    return $locale . DIRECTORY_SEPARATOR . $tpl . $this->extension;
                }
                if (is_file($dir . $tpl . $this->extension)) {
                    return $tpl . $this->extension;
                }
                if (is_file($dir . $tpl) && false !== strpos($tpl, $this->extension)) {
                    return $tpl;
                }
            }
        }

        throw new PrestaShopException('No template found for ' . $template);
    }

    private function getTemplateHierarchy($template, $entity, $id)
    {
        $entity = basename($entity ?? '');
        $id = (int) $id;

        if (in_array($entity, $this->getProductListEntities())) {
            $templates = [
                'catalog/listing/' . $entity . '-' . $id,
                'catalog/listing/' . $entity,
                $template,
                'catalog/listing/product-list',
            ];
        } elseif (in_array($entity, $this->getProductListSearchEntities())) {
            $templates = [
                'catalog/listing/' . $entity,
                $template,
                'catalog/listing/product-list',
            ];
        } elseif (in_array($entity, $this->getProductEntities())) {
            $templates = [
                'catalog/' . $entity . '-' . $id,
                $template,
                'catalog/product',
            ];
        } elseif (in_array($entity, $this->getBrandListEntities())) {
            $templates = [
                $template,
                'catalog/brands',
            ];
        } elseif ('cms' === $entity) {
            $templates = [
                'cms/page-' . $id,
                $template,
                'cms/page',
            ];
        } elseif ('cms_category' === $entity) {
            $templates = [
                'cms/category-' . $id,
                $template,
                'cms/category',
            ];
        } else {
            $templates = [$template];
        }

        return array_unique($templates);
    }

    /**
     * Get productListEntities.
     *
     * @return array
     */
    public function getProductListEntities()
    {
        return $this->productListEntities;
    }

    /**
     * Set productListEntities.
     *
     * @param array $productListEntities
     *
     * @return TemplateFinderCore
     */
    public function setProductListEntities($productListEntities)
    {
        $this->productListEntities = $productListEntities;

        return $this;
    }

    /**
     * Get productListSearch.
     *
     * @return array
     */
    public function getProductListSearchEntities()
    {
        return $this->productListSearchEntities;
    }

    /**
     * Set productListSearch.
     *
     * @param array $productListSearchEntities
     *
     * @return TemplateFinderCore
     */
    public function setProductListSearchEntities($productListSearchEntities)
    {
        $this->productListSearchEntities = $productListSearchEntities;

        return $this;
    }

    /**
     * Get productEntities.
     *
     * @return array
     */
    public function getProductEntities()
    {
        return $this->productEntities;
    }

    /**
     * Set productEntities.
     *
     * @param array $productEntities
     *
     * @return TemplateFinderCore
     */
    public function setProductEntities($productEntities)
    {
        $this->productEntities = $productEntities;

        return $this;
    }

    /**
     * Get brandListEntities.
     *
     * @return array
     */
    public function getBrandListEntities()
    {
        return $this->brandListEntities;
    }

    /**
     * Set brandListEntities.
     *
     * @param array $brandListEntities
     *
     * @return TemplateFinderCore
     */
    public function setBrandListEntities($brandListEntities)
    {
        $this->brandListEntities = $brandListEntities;

        return $this;
    }
}
