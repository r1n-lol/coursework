const slides = document.querySelector(".slides");
const slide = document.querySelectorAll(".slide");
const dotsContainer = document.querySelector(".dots");

let index = 0;

/* создаём точки */
slide.forEach((_, i) => {

  const dot = document.createElement("div");
  dot.classList.add("dot");

  if(i === 0) dot.classList.add("active");

  dot.addEventListener("click", () => {
    index = i;
    updateSlider();
  });

  dotsContainer.appendChild(dot);

});

const dots = document.querySelectorAll(".dot");


function updateSlider(){

  slides.style.transform = `translateX(-${index * 100}%)`;

  dots.forEach(dot => dot.classList.remove("active"));
  dots[index].classList.add("active");

}