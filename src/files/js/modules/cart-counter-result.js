export function updateCartCounterResult() {
  let products = JSON.parse(sessionStorage.getItem('cart')) || [];
  const counter = document.getElementById('cart-count');
  if (counter) {
    counter.textContent = products.length;
  }
}
