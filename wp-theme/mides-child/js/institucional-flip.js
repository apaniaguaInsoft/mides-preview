document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.card-nosotros').forEach(function (card) {
    card.addEventListener('click', function () {
      card.classList.toggle('flipped');
    });
  });
});
