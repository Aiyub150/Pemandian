setTimeout(function() {
    var loaderContainer = document.getElementById('loader-container');
    loaderContainer.classList.add('fade-out'); // Tambahkan kelas fade-out

    setTimeout(function() {
        loaderContainer.style.display = 'none'; // Sembunyikan loader setelah transisi selesai
    }, 1000); // Waktu transisi fade out
}, 3000); // Waktu jeda sebelum memulai transisi