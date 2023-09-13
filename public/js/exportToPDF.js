function exportToPDF() {
    const doc = new jsPDF();
    const table = document.getElementById("dataTable");

    const columns = [];
    const rows = [];

    // Menyimpan nama kolom
    for (let i = 0; i < table.rows[0].cells.length; i++) {
        columns.push(table.rows[0].cells[i].innerText);
    }

    // Menyimpan data baris
    for (let i = 1; i < table.rows.length; i++) {
        const row = [];
        for (let j = 0; j < table.rows[i].cells.length; j++) {
            row.push(table.rows[i].cells[j].innerText);
        }
        rows.push(row);
    }

    // Menambahkan data ke dokumen PDF
    doc.autoTable({
        head: [columns],
        body: rows
    });

    // Simpan file PDF
    doc.save('data.pdf');
}
