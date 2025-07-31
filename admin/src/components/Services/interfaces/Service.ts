// admin/src/components/Services/interfaces/Service.ts

export interface ServiceImage {
  src: string;
  description: string;
}

export interface Service {
  id: string | number;
  name: string;
  description: string;
  image: ServiceImage;
  href: string;
  services: string;
  cost: number;
  currency: string;
}

export interface AddedService {
  id: string | number;
  title: string;
  price: number;
}
