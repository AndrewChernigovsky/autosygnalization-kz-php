export interface Slide {
  id: number | undefined;
  title: string;
  advantages: string[];
  position: number;
  video_path: string;
  video_path_mob: string;
  poster_path: string;
  button_text: string;
  srcMob: string;
  src: string[];
  type: string[];
  video_filename: string;
  button_link: string;
}

export interface UploadButtonInstance {
  clearInput: () => void;
}
