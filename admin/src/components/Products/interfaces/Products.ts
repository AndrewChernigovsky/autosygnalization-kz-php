export interface Tab {
  title: string;
  description: string;
  'path-icon'?: string;
}

export interface ProductI {
  id: string;
  is_new?: boolean;
  model: string;
  title: string;
  description: string;
  price: number;
  is_popular: boolean;
  is_special: boolean;
  gallery: string[];
  category_key: string;
  functions: string[];
  options: string[];
  'options-filters': string[];
  autosygnals: string[];
  tabs?: Tab[];
}
