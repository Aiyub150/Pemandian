document.getElementById("showPopupBtn").addEventListener("click", function() {
    document.getElementById("loginPopup").style.display = "block";
    document.body.style.overflow = "hidden"; // Menonaktifkan scroll pada body
  });
  
  document.getElementById("closePopupBtn").addEventListener("click", function() {
    document.getElementById("loginPopup").style.display = "none";
    document.body.style.overflow = "auto"; // Mengaktifkan kembali scroll pada body
  });

window.onscroll = () => {
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

