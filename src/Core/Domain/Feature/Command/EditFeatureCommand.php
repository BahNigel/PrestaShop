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

namespace PrestaShop\PrestaShop\Core\Domain\Feature\Command;

use PrestaShop\PrestaShop\Core\Domain\Feature\Exception\FeatureConstraintException;
use PrestaShop\PrestaShop\Core\Domain\Feature\ValueObject\FeatureId;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopId;

/**
 * Edit feature with given data.
 */
class EditFeatureCommand
{
    /**
     * @var FeatureId
     */
    private $featureId;

    /**
     * @var string[]|null
     */
    private $localizedNames;

    /**
     * @var ShopId[]|null
     */
    private $associatedShopIds;

    /**
     * @param int $featureId
     */
    public function __construct(int $featureId)
    {
        $this->featureId = new FeatureId($featureId);
    }

    /**
     * @return FeatureId
     */
    public function getFeatureId(): FeatureId
    {
        return $this->featureId;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedNames(): ?array
    {
        return $this->localizedNames;
    }

    /**
     * @param string[] $localizedNames
     *
     * @return EditFeatureCommand
     */
    public function setLocalizedNames(array $localizedNames): self
    {
        if (empty($localizedNames)) {
            throw new FeatureConstraintException(
                'Feature name cannot be empty',
                FeatureConstraintException::INVALID_NAME
            );
        }

        $this->localizedNames = $localizedNames;

        return $this;
    }

    /**
     * @return ShopId[]|null
     */
    public function getAssociatedShopIds(): ?array
    {
        return $this->associatedShopIds;
    }

    /**
     * @param int[] $associatedShopIds
     *
     * @return EditFeatureCommand
     */
    public function setAssociatedShopIds(array $associatedShopIds): self
    {
        if (empty($associatedShopIds)) {
            throw new FeatureConstraintException('Shop association cannot be empty', FeatureConstraintException::INVALID_SHOP_ASSOCIATION);
        }

        $this->associatedShopIds = array_map(static function (int $shopId): ShopId {
            return new ShopId($shopId);
        }, $associatedShopIds);

        return $this;
    }
}
