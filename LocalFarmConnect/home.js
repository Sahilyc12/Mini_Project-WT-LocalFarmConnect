// Typewriter Welcome
let i = 0;
const txt = "LocalFarmConnect";
const speed = 100;
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("welcome").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}

// Page load features
window.onload = () => {
  typeWriter();

  // Notification popup
  const notif = document.getElementById('notification');
  notif.style.display = 'block';
  setTimeout(() => { notif.style.display = 'none'; }, 3000);

  // Carousel rotation
  let index = 0;
  const slides = document.querySelectorAll('.carousel-image');
  setInterval(() => {
    slides[index].style.display = 'none';
    index = (index + 1) % slides.length;
    slides[index].style.display = 'block';
  }, 3000);

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
};