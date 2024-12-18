import { Fancybox } from "@fancyapps/ui";

export function initFancybox() {
  Fancybox.bind('[data-fancybox]', {
    infinite: false,
  })
}