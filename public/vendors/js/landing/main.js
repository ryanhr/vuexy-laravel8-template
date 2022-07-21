const btns = document.querySelectorAll('.nav-btn');
const slides = document.querySelectorAll('.video-slider');
const contents = document.querySelectorAll('.content');

var sliderNav = function(index) {
  btns.forEach((btn) => {
    btn.classList.remove('active');
  });
  slides.forEach((slide) => {
    slide.classList.remove('active');
  });
  contents.forEach((content) => {
    content.classList.remove('active');
  });
    btns[index].classList.add('active');
    slides[index].classList.add('active');
    contents[index].classList.add('active');
  }

  btns.forEach((btn,i) => {
    btn.addEventListener('click', () => {
      sliderNav(i);
    })
  })

var typing=new Typed(".text", {
   strings: ["Command & Control (C2)"],
   typeSpeed: 150,
   backSpeed: 100,
   loop: true,
});

var typing=new Typed(".text2", {
   strings: ["Command & Control (C2)"],
   typeSpeed: 150,
   backSpeed: 100,
   loop: true,
});

var typing=new Typed(".text3", {
   strings: ["Command & Control (C2)"],
   typeSpeed: 150,
   backSpeed: 100,
   loop: true,
});

