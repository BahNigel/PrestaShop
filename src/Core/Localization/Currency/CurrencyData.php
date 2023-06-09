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

namespace PrestaShop\PrestaShop\Core\Localization\Currency;

/**
 * Localization Currency data object.
 *
 * This class is only used internally, it is mutable and overridable until fully built. It can then be used as
 * an intermediary data bag to build a real Localization/Currency (immutable) object.
 */
class CurrencyData
{
    /**
     * Is this currency active ?
     *
     * @var bool
     */
    protected $isActive;

    /**
     * Conversion rate of this currency against the default shop's currency.
     *
     * Price in currency A * currency A's conversion rate = price in default currency
     *
     * Example:
     * Given the Euro as default shop's currency,
     * If 1 dollar = 1.31 euros,
     * Then conversion rate for Dollar will be 1.31
     *
     * @var float
     */
    protected $conversionRate;

    /**
     * Currency's alphabetic ISO code (ISO 4217).
     *
     * @see https://www.iso.org/iso-4217-currency-codes.html
     *
     * @var string
     */
    protected $isoCode;

    /**
     * Currency's numeric ISO code (ISO 4217).
     *
     * @see https://www.iso.org/iso-4217-currency-codes.html
     *
     * @var string
     */
    protected $numericIsoCode;

    /**
     * Currency's symbols, by locale code.
     *
     * eg.: $symbolsUSD = [
     *     'en-US' => '$',
     *     'es-CO' => 'US$', // In Colombia, colombian peso's symbol is "$". They have to differentiate foreign dollars.
     * ]
     *
     * @var string[]|null
     */
    protected $symbols;

    /**
     * Number of decimal digits to use with this currency.
     *
     * @var int
     */
    protected $precision;

    /**
     * the currency's name, by locale code.
     *
     * @var string[]|null
     */
    protected $names;

    /**
     * Currency's patterns, by locale code.
     *
     * eg.: $patternsUSD = [
     *     'fr-FR' => '#,##0.00 ¤',
     *     'en-EN' => '¤#,##0.00',
     * ]
     *
     * @var string[]|null
     */
    protected $patterns;

    public function overrideWith(CurrencyData $currencyData)
    {
        if ($currencyData->isActive() !== null) {
            $this->isActive = $currencyData->isActive();
        }

        if ($currencyData->getConversionRate() !== null) {
            $this->conversionRate = $currencyData->getConversionRate();
        }

        if ($currencyData->getIsoCode() !== null) {
            $this->isoCode = $currencyData->getIsoCode();
        }

        if ($currencyData->getNumericIsoCode() !== null) {
            $this->numericIsoCode = $currencyData->getNumericIsoCode();
        }

        if ($currencyData->getSymbols() !== null) {
            $this->symbols = array_merge($this->symbols ?? [], $currencyData->getSymbols());
        }

        if ($currencyData->getPrecision() !== null) {
            $this->precision = $currencyData->getPrecision();
        }

        if ($currencyData->getNames() !== null) {
            $this->names = array_merge($this->names ?? [], $currencyData->getNames());
        }

        if ($currencyData->getPatterns() !== null) {
            $this->patterns = array_merge($this->patterns ?? [], $currencyData->getPatterns());
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return float
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * @param float $conversionRate
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;
    }

    /**
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * @param string $isoCode
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
    }

    /**
     * @return string
     */
    public function getNumericIsoCode()
    {
        return $this->numericIsoCode;
    }

    /**
     * @param string $numericIsoCode
     */
    public function setNumericIsoCode($numericIsoCode)
    {
        $this->numericIsoCode = $numericIsoCode;
    }

    /**
     * @return string[]
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @param string[] $symbols
     */
    public function setSymbols($symbols)
    {
        $this->symbols = $symbols;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        $this->precision = (int) $precision;
    }

    /**
     * @return string[]
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param string[] $names
     */
    public function setNames($names)
    {
        $this->names = $names;
    }

    /**
     * Currency patterns, indexed by locale code
     *
     * @return string[]|null
     */
    public function getPatterns(): ?array
    {
        return $this->patterns;
    }

    /**
     * Currency patterns, indexed by locale code
     *
     * @param string[] $patterns
     */
    public function setPatterns(array $patterns)
    {
        $this->patterns = $patterns;
    }
}
