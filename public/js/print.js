function printTable(tableId) {
    // Dapatkan elemen tabel
    var table = document.getElementById(tableId);

    // Salin elemen tabel ke objek baru untuk memanipulasinya
    var clonedTable = table.cloneNode(true);

    // Hapus tiga kolom pertama dari tabel
    for (var i = 0; i < 3; i++) {
        clonedTable.deleteColumn(0);
    }

    // Hapus judul kolom bagian kanan
    clonedTable.deleteColumn(clonedTable.rows[0].cells.length - 1);

    // Buat konten cetakan dari tabel yang sudah dimanipulasi
    var printContents = clonedTable.outerHTML;

    // Simpan konten asli dari body
    var originalContents = document.body.innerHTML;

    // Gantikan konten body dengan konten cetakan
    document.body.innerHTML = printContents;

    // Lakukan pencetakan
    window.print();

    // Kembalikan konten asli ke dalam body setelah pencetakan
    document.body.innerHTML = originalContents;
}
