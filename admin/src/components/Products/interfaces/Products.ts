export interface Tab {
  title: string;
  description: string;
  'path-icon'?: string;
}

export interface ProductI {
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
  is_special: boolean;
  autosygnals: string[];
  category: string;
  category_key: string;
  tabs?: Tab[];
  is_new?: boolean;
}
