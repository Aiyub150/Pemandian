document.getElementById('searchInput').addEventListener('keyup', function() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById('searchInput');
    filter = input.value.toUpperCase();
    table = document.getElementById('dataTable');
    tr = table.getElementsByTagName('tr');
  
    for (i = 1; i < tr.length; i++) {
      var display = 'none';
      td = tr[i].getElementsByTagName('td');
      for (j = 0; j < td.length; j++) {
        txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          display = '';
          break;
        }
      }
      tr[i].style.display = display;
    }
  });