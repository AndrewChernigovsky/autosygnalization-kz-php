export interface DescriptionItem {
  title: string;
  'path-icon'?: string;
  description: string;
}

export interface Tab {
  title: string;
  description: DescriptionItem[];
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
