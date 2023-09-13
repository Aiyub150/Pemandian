function exportToExcel(tableID, filename = ''){
    let downloadLink;
    const dataType = 'application/vnd.ms-excel';
    const tableSelect = document.getElementById(tableID).cloneNode(true); // Clone tabel untuk menghindari modifikasi tabel asli
    const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Menghapus kolom tertentu (misalnya kolom dengan indeks 2 dan 3)
    for(let i = 0; i < tableSelect.rows.length; i++){
        tableSelect.rows[i].deleteCell(2); // Kolom 2
        tableSelect.rows[i].deleteCell(2); // Kolom 3 (setelah menghapus kolom 2, indeks 3 sekarang adalah indeks 2)
    }

    // Spesifikasi nama file
    filename = filename?filename+'.xls':'excel_data.xls';

    // Membuat link unduh
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if(navigator.msSaveOrOpenBlob){
        const blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    } else {
        // Membuat link unduh
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Nama file yang akan diunduh
        downloadLink.download = filename;

        // Jalankan klik pada link unduh
        downloadLink.click();
    }
}
    