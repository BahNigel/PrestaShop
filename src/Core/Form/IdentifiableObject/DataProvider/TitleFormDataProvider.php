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

declare(strict_types=1);

namespace PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Domain\Title\Query\GetTitleForEditing;
use PrestaShop\PrestaShop\Core\Domain\Title\QueryResult\EditableTitle;
use PrestaShop\PrestaShop\Core\Domain\Title\TitleSettings;
use PrestaShop\PrestaShop\Core\Domain\Title\ValueObject\Gender;

/**
 * Provides data for title add/edit form.
 */
class TitleFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var CommandBusInterface
     */
    protected $queryBus;

    /**
     * @param CommandBusInterface $queryBus
     */
    public function __construct(CommandBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($id): array
    {
        /** @var EditableTitle $result */
        $result = $this->queryBus->handle(new GetTitleForEditing($id));

        return [
            'id' => $id,
            'name' => $result->getLocalizedNames(),
            'gender_type' => $result->getGender(),
            'img_height' => TitleSettings::DEFAULT_IMAGE_HEIGHT,
            'img_width' => TitleSettings::DEFAULT_IMAGE_WIDTH,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData(): array
    {
        return [
            'name' => [],
            'gender_type' => Gender::TYPE_MALE,
            'img_height' => TitleSettings::DEFAULT_IMAGE_HEIGHT,
            'img_width' => TitleSettings::DEFAULT_IMAGE_WIDTH,
        ];
    }
}
