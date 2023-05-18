// Import utils
import files from '@utils/files';
import helper from '@utils/helpers';
import testContext from '@utils/testContext';

// Import commonTests
import loginCommon from '@commonTests/BO/loginBO';
import {
  disableNewProductPageTest,
  resetNewProductPageAsDefault,
} from '@commonTests/BO/advancedParameters/newFeatures';

// Import pages
// FO
import {homePage} from '@pages/FO/home';
import searchResultsPage from '@pages/FO/searchResults';
import productPage from '@pages/FO/product';
import cartPage from '@pages/FO/cart';

// BO
import boDashboardPage from '@pages/BO/dashboard';
import boProductsPage from '@pages/BO/catalog/products';
import boAddProductPage from '@pages/BO/catalog/products/add';

// Import data
import Products from '@data/demo/products';
import ProductData from '@data/faker/product';
import type {ProductAttribute} from '@data/types/product';

import {expect} from 'chai';
import type {BrowserContext, Page} from 'playwright';

const baseContext: string = 'functional_FO_classic_productPage_addToCart';

/*
Check product details
Change product quantity
Choose combination (size, color)
Edit combination and add to cart
Check product details on the cart
Change image from product page
Check share links
 */

describe('FO - product page : Add product to cart', async () => {
  const quantity: number = 6;
  const totalPrice: number = 137.66;
  const firstCombination: ProductAttribute[] = [
    {
      name: 'size',
      value: 'XL',
    },
    {
      name: 'color',
      value: 'White',
    },
  ];
  const secondCombination: ProductAttribute[] = [
    {
      name: 'size',
      value: 'M',
    },
    {
      name: 'color',
      value: 'Black',
    },
  ];
  const productData: ProductData = new ProductData({
    type: 'Standard product',
    productHasCombinations: false,
    coverImage: 'cover.jpg',
    thumbImage: 'thumb.jpg',
  });

  let browserContext: BrowserContext;
  let page: Page;

  // Pre-condition: Disable new product page
  disableNewProductPageTest(`${baseContext}_disableNewProduct`);

  // before and after functions
  before(async function () {
    browserContext = await helper.createBrowserContext(this.browser);
    page = await helper.newTab(browserContext);
  });

  after(async () => {
    await helper.closeBrowserContext(browserContext);
  });

  it('should go to FO home page', async function () {
    await testContext.addContextItem(this, 'testIdentifier', 'goToFoToCreateAccount', baseContext);

    await homePage.goToFo(page);

    const isHomePage = await homePage.isHomePage(page);
    await expect(isHomePage).to.be.true;
  });

  // 1 - Check details and choose combination
  describe('Add product to cart and check details', async () => {
    it('should go to product page', async function () {
      await testContext.addContextItem(this, 'testIdentifier', 'goToProductPage', baseContext);

      await homePage.goToProductPage(page, 1);

      const pageTitle = await productPage.getPageTitle(page);
      await expect(pageTitle).to.contains(Products.demo_1.name);
    });

    it('should check product details', async function () {
      await testContext.addContextItem(this, 'testIdentifier', 'checkProductInformation', baseContext);

      let result = await productPage.getProductInformation(page);
      await Promise.all([
        await expect(result.name).to.equal(Products.demo_1.name),
        await expect(result.price).to.equal(Products.demo_1.finalPrice),
        await expect(result.summary).to.equal(Products.demo_1.summary),
        await expect(result.description).to.equal(Products.demo_1.description),
      ]);

      result = await productPage.getRegularPrice(page);
      await expect(result).to.equal(Products.demo_1.retailPrice);

      result = await productPage.getDiscountPercentage(page);
      await expect(result).to.contains(`${Products.demo_1.specificPrice.discount}%`);

      const productAttributes = await productPage.getProductAttributes(page);
      await Promise.all([
        await expect(productAttributes[0].name).to.equal(Products.demo_1.attributes[0].name),
        await expect(productAttributes[0].value).to.equal(Products.demo_1.attributes[0].values.join(' ')),
        await expect(productAttributes[1].name).to.equal(Products.demo_1.attributes[1].name),
        await expect(productAttributes[1].value).to.equal(Products.demo_1.attributes[1].values.join(' ')),
      ]);

      result = await productPage.getProductImageUrls(page);
      await Promise.all([
        await expect(result.coverImage).to.contains(Products.demo_1.coverImage),
        await expect(result.thumbImage).to.contains(Products.demo_1.coverImage),
      ]);
    });

    it('should choose combination and check product details', async function () {
      await testContext.addContextItem(this, 'testIdentifier', 'chooseCombination', baseContext);

      await productPage.selectAttributes(page, 1, firstCombination);

      let result = await productPage.getProductInformation(page);
      await Promise.all([
        await expect(result.name).to.equal(Products.demo_1.name),
        await expect(result.price).to.equal(Products.demo_1.finalPrice),
        await expect(result.summary).to.equal(Products.demo_1.summary),
        await expect(result.description).to.equal(Products.demo_1.description),
      ]);

      result = await productPage.getDiscountPercentage(page);
      await expect(result).to.contains(Products.demo_1.specificPrice.discount);

      result = await productPage.getRegularPrice(page);
      await expect(result).to.equal(Products.demo_1.retailPrice);

      result = await productPage.getProductImageUrls(page);
      await Promise.all([
        await expect(result.coverImage).to.contains(Products.demo_1.coverImage),
        await expect(result.thumbImage).to.contains(Products.demo_1.coverImage),
      ]);

      const selectedProductAttributes = await productPage.getSelectedProductAttributes(page);
      await Promise.all([
        await expect(selectedProductAttributes.length).to.equal(2),
        await expect(selectedProductAttributes[0].name).to.equal(firstCombination[0].name),
        await expect(selectedProductAttributes[0].value).to.equal(firstCombination[0].value),
        await expect(selectedProductAttributes[1].name).to.equal(firstCombination[1].name),
        await expect(selectedProductAttributes[1].value).to.equal(firstCombination[1].value),
      ]);
    });
  });

  // 2 - Modify combination, add to cart and check details
  describe('Add product to cart and check details', async () => {
    it('should modify combination and add product to the cart', async function () {
      await testContext.addContextItem(this, 'testIdentifier', 'addProductToCart', baseContext);

      await productPage.addProductToTheCart(page, quantity, secondCombination);

      const pageTitle = await cartPage.getPageTitle(page);
      await expect(pageTitle).to.equal(cartPage.pageTitle);
    });

    it('should check the ordered product details in cart page', async function () {
      await testContext.addContextItem(this, 'testIdentifier', 'checkOrderedProduct', baseContext);

      const result = await cartPage.getProductDetail(page, 1);
      await Promise.all([
        expect(result.name).to.equal(Products.demo_1.name),
        expect(result.price).to.equal(Products.demo_1.finalPrice),
        expect(result.totalPrice).to.equal(totalPrice),
        expect(result.quantity).to.equal(quantity),
      ]);

      const cartProductAttributes = await cartPage.getProductAttributes(page, 1);
      await Promise.all([
        await expect(cartProductAttributes.length).to.equal(2),
        await expect(cartProductAttributes[0].name).to.equal(secondCombination[0].name),
        await expect(cartProductAttributes[0].value).to.equal(secondCombination[0].value),
        await expect(cartProductAttributes[1].name).to.equal(secondCombination[1].name),
        await expect(cartProductAttributes[1].value).to.equal(secondCombination[1].value),
      ]);
    });
  });

  // 3 - Change image from product page
  describe('Change image from product page', async () => {
    describe('Go to BO and create product with 2 images', async () => {
      before(async () => {
        page = await helper.newTab(browserContext);

        // Create products images
        await Promise.all([
          files.generateImage(productData.coverImage ?? ''),
          files.generateImage(productData.thumbImage ?? ''),
        ]);
      });

      it('should login in BO', async function () {
        await loginCommon.loginBO(this, page);
      });

      it('should go to Products page', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'goToProductsPageToCreate', baseContext);

        await boDashboardPage.goToSubMenu(
          page,
          boDashboardPage.catalogParentLink,
          boDashboardPage.productsLink,
        );

        await boProductsPage.closeSfToolBar(page);

        const pageTitle = await boProductsPage.getPageTitle(page);
        await expect(pageTitle).to.contains(boProductsPage.pageTitle);
      });

      it('should create Product', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'createProduct', baseContext);

        await boProductsPage.goToAddProductPage(page);

        const createProductMessage = await boAddProductPage.createEditBasicProduct(page, productData);
        await expect(createProductMessage).to.equal(boAddProductPage.settingUpdatedMessage);
      });

      it('should logout from BO', async function () {
        await loginCommon.logoutBO(this, page);
      });

      after(async () => {
        page = await boAddProductPage.closePage(browserContext, page, 0);

        /* Delete the generated images */
        await Promise.all([
          files.deleteFile(productData.coverImage ?? ''),
          files.deleteFile(productData.thumbImage ?? ''),
        ]);
      });
    });

    describe('Check change product images on quick view modal', async () => {
      it('should search for the created product and go to product page', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'searchForProductAndQuickView', baseContext);

        await homePage.searchProduct(page, productData.name);
        await searchResultsPage.goToProductPage(page, 1);
      });

      it('should change product image', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'changeImage', baseContext);

        const coverSecondImageURL = await productPage.selectThumbImage(page, 2);
        const coverFirstImageURL = await productPage.selectThumbImage(page, 1);

        await expect(coverSecondImageURL).to.not.equal(coverFirstImageURL);
      });
    });

    describe('Delete the created product', async () => {
      before(async () => {
        page = await helper.newTab(browserContext);
      });

      it('should login in BO', async function () {
        await loginCommon.loginBO(this, page);
      });

      it('should go to Products page', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'goToProductsPageToDelete', baseContext);

        await boDashboardPage.goToSubMenu(
          page,
          boDashboardPage.catalogParentLink,
          boDashboardPage.productsLink,
        );

        await boProductsPage.closeSfToolBar(page);

        const pageTitle = await boProductsPage.getPageTitle(page);
        await expect(pageTitle).to.contains(boProductsPage.pageTitle);
      });

      it('should delete product', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'deleteProduct', baseContext);

        await boProductsPage.resetFilter(page);
        const testResult = await boProductsPage.deleteProduct(page, productData);
        await expect(testResult).to.equal(boProductsPage.productDeletedSuccessfulMessage);
      });

      it('should reset all filters', async function () {
        await testContext.addContextItem(this, 'testIdentifier', 'resetFilter', baseContext);

        await boProductsPage.resetFilterCategory(page);
        const numberOfProducts = await boProductsPage.resetAndGetNumberOfLines(page);
        await expect(numberOfProducts).to.be.above(0);
      });

      after(async () => {
        page = await boAddProductPage.closePage(browserContext, page, 0);
      });
    });
  });

  // 4 - Check share links from product page
  describe('Check share links from product page', async () => {
    const tests = [
      {
        args:
          {
            name: 'Facebook',
          },
        result:
          {
            url: 'https://www.facebook.com/',
          },
      },
      {
        args:
          {
            name: 'Twitter',
          },
        result:
          {
            url: 'https://twitter.com/',
          },
      },
      {
        args:
          {
            name: 'Pinterest',
          },
        result:
          {
            url: 'https://www.pinterest.com/',
          },
      },
    ];

    tests.forEach((test, index: number) => {
      it(`should check share link of '${test.args.name}'`, async function () {
        await testContext.addContextItem(this, 'testIdentifier', `checkShareLink${index}`, baseContext);

        const url = await productPage.getSocialSharingLink(page, test.args.name);
        await expect(url).to.contain(test.result.url);
      });
    });
  });

  // Post-condition: Reset initial state
  resetNewProductPageAsDefault(`${baseContext}_resetNewProduct`);
});
