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

import Grid from '@components/grid/grid';
import ExportToSqlManagerExtension from '@components/grid/extension/export-to-sql-manager-extension';
import ReloadListExtension from '@components/grid/extension/reload-list-extension';
import BulkActionCheckboxExtension from '@components/grid/extension/bulk-action-checkbox-extension';
import SubmitBulkExtension from '@components/grid/extension/submit-bulk-action-extension';
import SubmitRowActionExtension from '@components/grid/extension/action/row/submit-row-action-extension';
import FormSubmitButton from '@components/form-submit-button';

const {$} = window;

$(() => {
  const taxRuleGrid = new Grid('tax_rule');

  taxRuleGrid.addExtension(new ExportToSqlManagerExtension());
  taxRuleGrid.addExtension(new ReloadListExtension());
  taxRuleGrid.addExtension(new BulkActionCheckboxExtension());
  taxRuleGrid.addExtension(new SubmitBulkExtension());
  taxRuleGrid.addExtension(new SubmitRowActionExtension());

  new FormSubmitButton();
});
