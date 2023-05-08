<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Crud</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
    <script src="<?php echo base_url(); ?>jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="page-header text-center">Crud</h1>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <form>
                    <div class="w-full overflow-hidden rounded-lg shadow-md">
                        <table class="w-full">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th rowspan="2" class="px-4 py-2 text-left">Report Date</th>
                                    <th rowspan="2" class="px-4 py-2 text-left">Employee Name</th>
                                    <th rowspan="2" class="px-4 py-2 text-left">Observation</th>
                                    <th rowspan="2" class="px-4 py-2 text-left">Comments/ Additional Symptoms</th>
                                    <th rowspan="2" class="px-4 py-2 text-left">Date Returned to Work</th>
                                    <th colspan="2" class="px-4 py-2 text-center">Diagnosed with a Pathogen?</th>
                                    <th colspan="2" class="px-4 py-2 text-center">If diagnosed, is a local health agency contacted?</th>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 text-center">YES</th>
                                    <th class="px-4 py-2 text-center">NO</th>
                                    <th class="px-4 py-2 text-center">YES</th>
                                    <th class="px-4 py-2 text-center">NO</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="border-gray-200">
                                    <td class="px-4 py-2"><input class="w-full"></td>
                                    <td class="px-4 py-2"><input class="w-full"></td>
                                    <td class="px-4 py-2"><input class="w-full"></td>
                                    <td class="px-4 py-2"><input class="w-full"></td>
                                    <td class="px-4 py-2"><input class="w-full"></td>
                                    <td class="px-4 py-2 text-center"><input name="pathogen" id="pathogen" type="radio"></td>
                                    <td class="px-4 py-2 text-center"><input type="radio" name="pathogen" id="pathogen"></td>
                                    <td class="px-4 py-2 text-center"><input type="radio" name="contacted" id="contacted"></td>
                                    <td class="px-4 py-2 text-center"><input type="radio" name="contacted" id="contacted"></td>
                                </tr>
                                <tr class="border-gray-200">
                                    <td class="px-4 py-2">April 11, 2023</td>
                                    <td class="px-4 py-2">Jane Smith</td>
                                    <td class="px-4 py-2">Loss of taste, fatigue</td>
                                    <td class="px-4 py-2">Headache</td>
                                    <td class="px-4 py-2">Yes</td>
                                    <td class="px-4 py-2 text-center">Yes</td>
                                    <td class="px-4 py-2 text-center">Yes</td>
                                    <td class="px-4 py-2 text-center">Yes</td>
                                    <td class="px-4 py-2 text-center">Yes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        <?php echo $modal; ?>

        <script type="text/javascript">
            $(document).ready(function() {
                //create a global variable for our base url
                var url = '<?php echo base_url(); ?>';

                //fetch table data
                showTable();

                //show add modal
                $('#add').click(function() {
                    $('#addnew').modal('show');
                    $('#addForm')[0].reset();
                });

                //submit add form
                $('#addForm').submit(function(e) {
                    e.preventDefault();
                    var user = $('#addForm').serialize();
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/insert',
                        data: user,
                        success: function() {
                            $('#addnew').modal('hide');
                            showTable();
                        }
                    });
                });

                //show edit modal
                $(document).on('click', '.edit', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/getuser',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response);
                            $('#email').val(response.email);
                            $('#password').val(response.password);
                            $('#fname').val(response.fname);
                            $('#userid').val(response.id);
                            $('#editmodal').modal('show');
                        }
                    });
                });

                //update selected user
                $('#editForm').submit(function(e) {
                    e.preventDefault();
                    var user = $('#editForm').serialize();
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/update',
                        data: user,
                        success: function() {
                            $('#editmodal').modal('hide');
                            showTable();
                        }
                    });
                });

                //show delete modal
                $(document).on('click', '.delete', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/getuser',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response);
                            $('#delfname').html(response.fname);
                            $('#delid').val(response.id);
                            $('#delmodal').modal('show');
                        }
                    });
                });

                $('#delid').click(function() {
                    var id = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/delete',
                        data: {
                            id: id
                        },
                        success: function() {
                            $('#delmodal').modal('hide');
                            showTable();
                        }
                    });
                });

            });

            function showTable() {
                var url = '<?php echo base_url(); ?>';
                $.ajax({
                    type: 'POST',
                    url: url + 'user/show',
                    success: function(response) {
                        $('#tbody').html(response);
                    }
                });
            }
        </script>
    </div>
</body>

</html>