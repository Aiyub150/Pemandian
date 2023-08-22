document.getElementById("showPopupBtn").addEventListener("click", function() {
    document.getElementById("loginPopup").style.display = "block";
    document.body.style.overflow = "hidden"; // Menonaktifkan scroll pada body
  });
  
  document.getElementById("closePopupBtn").addEventListener("click", function() {
    document.getElementById("loginPopup").style.display = "none";
    document.body.style.overflow = "auto"; // Mengaktifkan kembali scroll pada body
  });

  let sections = document.querySelectorAll('section');
  let navLinks = document.querySelectorAll('header nav a');
window.onscroll = () => {
    sections.forEach(sec => {
          let top = window.scrollY;
          let offset = sec.offsetTop - 150;
          let height = sec.offsetHeight; // Menggunakan offsetHeight bukan offsetheight
          let id = sec.getAttribute('id');
  
          if (top >= offset && top < offset + height) {
              navLinks.forEach(link => {
                  link.classList.remove('active');
              });
              document.querySelector(`header nav a[href*='${id}']`).classList.add('active'); // Perbaikan tanda petik
          }
      });
  
      let header = document.querySelector('.header');
  
      header.classList.toggle('sticky', window.scrollY > 100);

var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 50,
    loop: true,
    grabCursor: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
};

