export interface Product {
  id: string;
  model: string;
  cart: boolean;
  popular: boolean;
  is_popular: boolean;
  gallery: string[];
  title: string;
  description: string;
  price: number;
  currency: string;
  quantity: number;
  link: string;
  functions: string[];
  options: string[];
  'options-filters': string[];
  special: boolean;
  autosygnals: string[];
  category: string;
  category_key: string;
  is_new?: boolean;
}
