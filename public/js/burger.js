const burger = document.getElementById('burger');
const nav = document.getElementById('sideMenu');

burger.addEventListener('click', () => {
  nav.classList.toggle('active');  // показывает/скрывает side-menu
  burger.classList.toggle('active'); // крестик
});
