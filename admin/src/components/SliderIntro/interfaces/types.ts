export interface Slide {
  id?: number;
  poster: string;
  srcMob: string;
  src: string[];
  type: string[];
  title: string;
  advantages: string[];
  link: string;
  position: number;
  video_path?: string;
  button_text: string;
}

export interface UploadButtonInstance {
  clearInput: () => void;
}
