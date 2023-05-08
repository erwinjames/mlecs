<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css">
</head>
<style>
    body {
        font-size: 140%;
    }

    h2 {
        text-align: center;
        padding: 20px 0;
    }

    table caption {
        padding: .5em 0;
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    .p {
        text-align: center;
        padding-top: 140px;
        font-size: 14px;
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
                <div class="col-xs-12">
                    <table class="table table-bordered table-hover dt-responsive">

                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Languages</th>
                                <th>Population</th>
                                <th>Median Age</th>
                                <th>Area (Km²)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Argentina</td>
                                <td>Spanish (official), English, Italian, German, French</td>
                                <td>41,803,125</td>
                                <td>31.3</td>
                                <td>2,780,387</td>
                            </tr>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="https://cdn.datatables.net/responsive/1.0.4/js/dataTables.responsive.js"></script>
<script>
    $(document).ready(function() {
        $('table').DataTable();
    });
</script>

</html>