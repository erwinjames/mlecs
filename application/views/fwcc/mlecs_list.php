<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $('#table_record').DataTable();
    </script>
</head>
<style>
    table td {
        border: 1px solid black;
    }
    h2 {
        text-align: center;
        padding: 20px 0;
    }

    table caption {
        padding: .5em 0;
    }

    .table-image thead td,
    .table-image thead th {
        border: 0;
        color: #666;
        font-size: 1.2rem;
    }

    .table-image td,
    .table-image th {
        vertical-align: middle;
        text-align: center;
    }

    .modal-footer {
        padding-top: 1rem;
    }
</style>

<body>
    <center><img width="15%" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" srcset="">
        <h4>Master List of Equipment Calibration Schedule Record</h4>
    </center>
    <br>
    <br>
    <div class="container">
        <div class="card shadow">
            <div class="row">
                <!-- Modal -->
                <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div id="mlecs_record" class="modal-content" style="padding:5rem;">


                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <table id="table_record" class="table table-bordered table-hover dt-responsive">

                        <thead>
                            <tr>
                                <th>Record #</th>
                                <th>Date Record</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="mlecs_list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
<script src="<?php echo base_url(); ?>jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="https://cdn.datatables.net/responsive/1.0.4/js/dataTables.responsive.js"></script>
<script>
    $(document).ready(function() {
        $('#table_record').DataTable();

        show_list();

        function show_list() {
            var url = '<?php echo base_url(); ?>';
            $.ajax({
                type: 'POST',
                url: url + 'forms/mlecs_show_list',
                success: function(response) {
                    $('#mlecs_list').html(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }
        $(document).on('click', '.select-record', function() {
            var url = '<?php echo base_url(); ?>';
            var record_id = $(this).attr('id');
            console.log(record_id);
            $.ajax({
                type: 'POST',
                url: url + 'forms/mlecs_show_record_data',
                data: {
                    record_id: record_id
                },
                success: function(response) {
                    console.log(response);
                    $('#mlecs_record').html(response);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
        $(document).on('click', '.pdfDownload', function(event) {
            event.preventDefault();
            var url = '<?php echo base_url(); ?>';
            var p_id = $(this).attr('id');
            // Make an AJAX request to the PDF generation endpoint
            $.ajax({
                url: url + 'forms/pdf',
                method: 'POST',
                data: {
                    id: p_id
                },
                xhrFields: {
                    responseType: 'blob' // Set the response type to blob
                },
                success: function(response) {
                    // Create a new blob object
                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });

                    // Create a URL for the blob object
                    var url = URL.createObjectURL(blob);

                    // Use the URL to download the file
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'example.pdf';
                    document.body.appendChild(a);
                    a.click();
                },
                error: function(error) {
                    console.log(JSON.stringify(error));
                }
            });
        });
        $(document).on('click', '.pdfPrint', function(event) {
            event.preventDefault();
            var url = '<?php echo base_url(); ?>';
            var p_id = $(this).attr('id');

            // Make an AJAX request to the PDF generation endpoint
            $.ajax({
                url: url + 'forms/pdf',
                method: 'POST',
                data: {
                    id: p_id
                },
                xhrFields: {
                    responseType: 'blob' // Set the response type to blob
                },
                success: function(response) {
                    console.log(response);
                    // Create a new blob object
                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });

                    // Create a URL for the blob object
                    var url = URL.createObjectURL(blob);

                    // Open the PDF in a new window
                    var newWindow = window.open(url, '_blank');

                    // Wait for the PDF to load and trigger the print dialog
                    newWindow.addEventListener('load', function() {
                        newWindow.print();
                    });
                },
                error: function(error) {
                    console.log(JSON.stringify(error));
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        var $modal = $('#myModal');
        $modal.find('.modal-content')
            .css({
                width: 625,
                height: 175,
            })
            .resizable({
                minWidth: 625,
                minHeight: 175,
                handles: 'n, e, s, w, ne, sw, se, nw',
            })
            .draggable({
                handle: '.modal-header'
            });

        var launch = function() {
            $modal.modal();
        }
    });
</script>

</html>