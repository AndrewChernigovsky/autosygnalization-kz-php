export interface ServiceImage {
  src: string;
  description: string;
}

export interface Service {
  id: string;
  name: string;
  description: string;
  image: ServiceImage;
  href: string;
  services: string; // Changed back from service_list_html, type is now string
  cost: number;
  currency: string;
}
