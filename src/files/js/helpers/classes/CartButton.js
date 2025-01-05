import { ProductAPI } from "../../modules/api/getProduct.js";
export class CartButton {
  PRODUCTION
  productApi

  constructor() {
    this.PRODUCTION = window.location.href.includes('/dist/');
    this.productApi = new ProductAPI();
  }
}