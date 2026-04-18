const slides = document.querySelector(".slides");
const slide = document.querySelectorAll(".slide");
const dotsContainer = document.querySelector(".dots");

if (slides && slide.length > 0 && dotsContainer) {
  let index = 0;
  let autoplayId = null;
  const AUTOPLAY_DELAY = 4500;

  /* создаём точки */
  slide.forEach((_, i) => {
    const dot = document.createElement("div");
    dot.classList.add("dot");

    if (i === 0) dot.classList.add("active");

    dot.addEventListener("click", () => {
      index = i;
      updateSlider();
      restartAutoplay();
    });

    dotsContainer.appendChild(dot);
  });

  const dots = document.querySelectorAll(".dot");

  function updateSlider() {
    slides.style.transform = `translateX(-${index * 100}%)`;

    dots.forEach(dot => dot.classList.remove("active"));
    dots[index].classList.add("active");
  }

  function nextSlide() {
    index = (index + 1) % slide.length;
    updateSlider();
  }

  function startAutoplay() {
    stopAutoplay();
    autoplayId = window.setInterval(nextSlide, AUTOPLAY_DELAY);
  }

  function stopAutoplay() {
    if (autoplayId !== null) {
      window.clearInterval(autoplayId);
      autoplayId = null;
    }
  }

  function restartAutoplay() {
    stopAutoplay();
    startAutoplay();
  }

  const slider = document.querySelector(".slider");

  slider?.addEventListener("mouseenter", stopAutoplay);
  slider?.addEventListener("mouseleave", startAutoplay);

  updateSlider();
  startAutoplay();
}
