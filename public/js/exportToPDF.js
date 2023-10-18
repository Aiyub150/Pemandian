// Function to export table data to PDF
function exportToPDF(tableId, title = ''){
  var doc = new jsPDF();
  var specialElementHandlers = {
      '#editor': function (element, renderer) {
          return true;
      }
  };
  var table = document.getElementById(tableId);
  var tableHTML = table.outerHTML;
  
  // Add some styling to the title
  doc.text(title, 10, 10);
  
  doc.fromHTML(tableHTML, 15, 15, {
      'width': 190,
      'elementHandlers': specialElementHandlers
  });
  
  // Save the PDF
  doc.save(title + '.pdf');
}
