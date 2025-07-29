export interface DescriptionItem {
  title: string;
  icon?: string;
  description: string;
}

export interface Tab {
  title: string;
  content: DescriptionItem[];
}

export interface ProductI {
  id: string;
  is_new?: boolean;
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
}
