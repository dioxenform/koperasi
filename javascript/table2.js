$(document).ready(function() {
    $('#example').DataTable({
        "columnDefs": [
            {
                "targets": 0,    // Kolom pertama
                "width": "300px"  // Menentukan lebar kolom pertama
            },
            {
                "targets": 1,    // Kolom kedua
                "width": "200px"  // Menentukan lebar kolom kedua
            },
            {
                "targets": 2,    // Kolom ketiga
                "width": "120px"  // Menentukan lebar kolom ketiga
            }
        ]
    });
});