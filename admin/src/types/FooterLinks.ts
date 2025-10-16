export interface LinkData {
  links_data_id: number;
  name: string;
  link: string;
  type: 'link' | 'phone' | 'email';
  source_table: 'Sections-Product' | 'Services' | 'Contacts' | 'Navigation';
}

export interface IFooterLink {
  footer_id: number;
  section: SectionKey;
  name: string;
  link: string;
  position: number;
  visible: boolean;
  source_table: 'Sections-Product' | 'Services' | 'custom';
  source_id: number | null;
}

export type SectionKey = 'shop' | 'installation' | 'client';
