export interface DescriptionItem {
  title: string;
  "path-icon"?: string;
  description: string;
}

export interface Tab {
  title: string;
  content: DescriptionItem[];
}

export interface PriceItem {
  title: string;
  productPrice: string;
  currency: string;
  installationPrice: string;
  description: string;
  id?: string | number | null;
}

export interface ProductI {
  id: string;
  is_new?: boolean;
  is_published: boolean;
  model: string;
  title: string;
  description: string;
  price: number;
  link: string;
  is_popular: boolean;
  is_special: boolean;
  gallery: string[];
  category: string;
  functions: string[];
  options: string[];
  'options-filters': string[];
  autosygnals: string[];
  tabs?: Tab[];
  prices?: PriceItem[] | string;
  price_list?: {
    title: string;
    price: string;
    currency: string;
    content: string;
  }[];
}
