function exportToPDF(tableId, title = '') {
    var doc = new jsPDF('p', 'pt', 'a4');
    doc.autoTable({
        html: '#' + tableId,
        theme: 'grid',
        startY: 60,
        margin: { top: 60 },
        styles: {
            fontSize: 8,
            cellPadding: 4,
            textColor: [0, 0, 0],
            overflow: 'linebreak'
        },
        columnStyles: {
            0: { halign: 'center' },
            1: { halign: 'center' },
            2: { halign: 'center' },
            3: { halign: 'center' },
            4: { halign: 'center' },
            5: { halign: 'center' },
            6: { halign: 'center' }
        },
        drawCell: function (cell, opts) {
            if (opts.column.dataKey === 6) {
                var text = cell.text.split(',');
                cell.text = text[0];
                doc.autoTableText(text[1], cell.x, cell.y + 10, {
                    fontSize: 6
                });
            }
        }
    });

    // Dapatkan kolom "action"
    var actionColumn = document.querySelector('#' + tableId + ' .action-column');

    // Sembunyikan kolom "action"
    actionColumn.style.display = 'none';

    doc.save(title + '.pdf');

    // Kembalikan tampilan kolom "action"
    actionColumn.style.display = '';
}