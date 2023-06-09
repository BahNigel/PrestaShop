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

namespace PrestaShop\PrestaShop\Core\Domain\Product\ValueObject;

use PrestaShop\PrestaShop\Core\Domain\Product\Exception\ProductConstraintException;

/**
 * Product identity.
 */
class ProductId
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @param int $productId
     *
     * @throws ProductConstraintException
     */
    public function __construct(int $productId)
    {
        $this->assertIntegerIsGreaterThanZero($productId);
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    private function assertIntegerIsGreaterThanZero(int $productId): void
    {
        if ($productId > 0) {
            return;
        }

        throw new ProductConstraintException(
            sprintf('Product id %s is invalid. Product id must be an integer greater than zero.', $productId),
            ProductConstraintException::INVALID_ID
        );
    }
}
