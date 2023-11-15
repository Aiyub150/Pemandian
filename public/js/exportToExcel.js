function exportToExcel(tableId, filename = '') {
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableId);

    // Simpan kolom "action"
    var actionColumn = tableSelect.querySelector('.action-column');
    var actionColumnIndex = actionColumn.cellIndex;

    // Sembunyikan kolom "action"
    actionColumn.style.display = 'none';

    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Kembalikan tampilan kolom "action"
    actionColumn.style.display = '';

    filename = filename ? filename + '.xls' : 'excel_data.xls';

    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}