<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLECS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/popper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

    <style>
        .signature-pad {
            border-radius: 2px;
            border: 1px dashed #ccc;
            cursor: crosshair;
            width: 300px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
            <div class="modal-dialog modal-m">
                <div class="modal-content">
                    <form id="mlecs_add_list_form">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Add List</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <div class="form-group">
                                    <label for="equipmentId">Equipment ID:</label>
                                    <input type="text" class="form-control" id="equipmentId">
                                </div> -->
                            <div class="form-group">
                                <label for="equipmentDesc">Equipment Description:</label>
                                <input type="text" class="form-control" id="equipmentDesc" required>
                            </div>
                            <div class="form-group">
                                <label for="equipmentManu">Equipment Manufacturer:</label>
                                <input type="text" class="form-control" id="equipmentManu" required>
                            </div>
                            <div class="form-group">
                                <label for="serialNum">Serial Number:</label>
                                <input type="text" class="form-control" id="serialNum" required>
                            </div>
                            <div class="form-group">
                                <label for="calibrationPeriod">Calibration Period:</label>
                                <select class="form-control" id="calibrationPeriod" required>
                                    <option value="Annually">Annually</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Semi-annually">Semi-annually</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lastCalibrationDate">Last Calibration Date:</label>
                                <input type="date" class="form-control" id="lastCalibrationDate" required>
                            </div>
                            <div class="form-group">
                                <label for="calibrationDueDate">Calibration Due Date:</label>
                                <input type="date" class="form-control" id="calibrationDueDate" required>
                            </div>
                            <div class="form-group">
                                <label for="calibratingBody">Calibrating Body/Organization:</label>
                                <input type="text" class="form-control" id="calibratingBody" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="card shadow" style="padding:40px;">
                <center><img width="15%" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" srcset=""></center>
                <br>
                <center>
                    <h5>Master List of Equipment Calibration Schedule</h5>
                </center>
                <br>
                <div class="row" style="padding:30px 30px 0px 30px">
                    <div class="col-md-2">
                        <a class="btn btn-lg btn-primary" data-toggle="modal" data-target="#largeModal">Add List</a>
                    </div>
                </div>
                <div class="row" style="padding:30px 30px 0px 30px">
                    <div>
                        <label for="calibrationDate">As of (Date):</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="asofdate" required>
                    </div>
                </div>
                <br>
                <table class="table table-bordered table-hover">
                    <thead class="bg-gray-200" style="font-size:14px;text-align:center;">
                        <tr>
                            <th> Equipment ID</th>
                            <th>Equipment Description</th>
                            <th>Equipment Manufacturer</th>
                            <th>Serial Number</th>
                            <th>Calibration Period</th>
                            <th>Last Calibration Date</th>
                            <th>Calibration Due Date</th>
                            <th>Calibrating Body/Organization</th>
                        </tr>
                    </thead>
                    <tbody id="mlecs_form_data_list">

                    </tbody>
                </table>
                <table class="table table-bordered" style=" font-size: 13px!important;">
                    <tr>
                        <td class="text-center fw-bold">Reviewed by</td>
                        <td class="text-center fw-bold">Approved by</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="m-3 mb-5" style="display:flex; justify-content:center;">
                                <div>
                                    <select id="main-sig-selection1" class="mb-1 p-1">
                                        <option value="D1">Select Signature Option</option>
                                        <option value="D1">Draw Signature</option>
                                        <option value="U1">Upload Signature</option>
                                    </select>
                                    <div id="showD1" class="signature1" style="display:flex; justify-content:center">
                                        <div class="signature-pad-container">
                                            <div style="" class="signature-pad" id="signature-pad-1"></div><br>
                                            <button type="button" class="border-1 bg-success text-light rsig-submitBtn" id="">Confirm Signature</button>
                                            <button type="button" class="clear-btn1 border-1" id="">Clear</button>
                                            <textarea type="text" class="signature-data-text1 d-none" name="reviewer_sign" value="" readonly></textarea>
                                        </div>
                                    </div><br>
                                    <div id="showU1" class="signature1 d-none">
                                        <input type="file" id="m-actual-image1" name="reviewer_sign_img" onchange="dataURLv(this,1)" style="margin-bottom:7px;" /><br>
                                        <img id="m-actual-image-res1" width="220" height="80" src="#" /><br>
                                        <button class="border-1 mt-1" type="button" id="reset-image-val1">Remove</button>
                                    </div>

                                    <div id="image-sig-r" class="d-none">
                                        <div class="rimg-signature"></div><br>
                                        <button class="border-1 mt-1" type="button" id="rclear-image">Remove</button>
                                    </div><br>

                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control mb-1" required name="reviewer_name" placeholder="Name">
                                    </div>

                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control mb-1" required name="r_position" placeholder="Position">
                                    </div>

                                    <div class="input-group input-group-sm">
                                        <input type="datetime-local" class="form-control" required name="reviewed_date">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="m-3 mb-5" style="display:flex; justify-content:center;">
                                <div>
                                    <select id="main-sig-selection2" class="mb-1 p-1">
                                        <option value="D2">Select Signature Option</option>
                                        <option value="D2">Draw Signature</option>
                                        <option value="U2">Upload Signature</option>
                                    </select>
                                    <div id="showD2" class="signature2" style="display:flex; justify-content:center">
                                        <div class="signature-pad-container">
                                            <div style="" class="signature-pad" id="signature-pad-2"></div><br>
                                            <button type="button" class="border-1 bg-success text-light asig-submitBtn" id="">Confirm Signature</button>
                                            <button type="button" class="clear-btn2 border-1" id="">Clear</button>
                                            <textarea type="text" class="signature-data-text2 d-none" name="approver_sign" value="" readonly></textarea>
                                        </div>
                                    </div><br>
                                    <div id="showU2" class="signature2 d-none">
                                        <input type="file" id="m-actual-image2" name="approver_sign_img" onchange="dataURLv(this,2)" style="margin-bottom:7px;" /><br>
                                        <img id="m-actual-image-res2" width="220" height="80" src="#" /><br>
                                        <button class="border-1 mt-1" type="button" id="reset-image-val2">Remove</button>
                                    </div>
                                    <div id="image-sig-a" class="d-none">
                                        <div class="aimg-signature"></div><br>
                                        <button class="border-1 mt-1" type="button" id="aclear-image">Remove</button>
                                    </div><br>

                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control mb-1" required name="approver_name" placeholder="Name">
                                    </div>

                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control mb-1" required name="a_position" placeholder="Position">
                                    </div>

                                    <div class="input-group input-group-sm">
                                        <input type="datetime-local" class="form-control" required name="approved_date">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <hr>
                <br>
                <input type="submit" name="save_record" style="padding:5px;width:100px;" class="btn btn-success" value="Save">
                <br>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script src="https://cdn.datatables.net/responsive/1.0.4/js/dataTables.responsive.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jSignature/2.1.3/jSignature.min.js"></script>
    <script>
        $(document).ready(function() {
            var url = '<?php echo base_url(); ?>';
            mlecs_table();

            function mlecs_table() {
                var url = '<?php echo base_url(); ?>';
                $.ajax({
                    type: 'POST',
                    url: url + 'forms/mlecs_show',
                    success: function(response) {
                        $('#mlecs_form_data_list').html(response);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            }

            $("#mlecs_add_list_form").submit(function(event) {
                event.preventDefault(); // prevent form submission
                var equipmentDesc = $("#equipmentDesc").val();
                var equipmentManu = $("#equipmentManu").val();
                var serialNum = $("#serialNum").val();
                var calibrationPeriod = $("#calibrationPeriod").val();
                var lastCalibrationDate = $("#lastCalibrationDate").val();
                var calibrationDueDate = $("#calibrationDueDate").val();
                var calibratingBody = $("#calibratingBody").val();

                $.ajax({
                    url: url + 'forms/mlecs_insert_form',
                    type: "POST",
                    data: {
                        equipmentDesc: equipmentDesc,
                        equipmentManu: equipmentManu,
                        serialNum: serialNum,
                        calibrationPeriod: calibrationPeriod,
                        lastCalibrationDate: lastCalibrationDate,
                        calibrationDueDate: calibrationDueDate,
                        calibratingBody: calibratingBody
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: 'Record Updated Successfully!',
                            padding: '4em',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $("#mlecs_add_list_form")[0].reset(); //reset the form
                        $("#largeModal").modal("hide"); //hide the modal
                        location.reload();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });

            for (let id = 0; id < 4; id++) {
                $('#main-sig-selection' + id).on('change', function() {
                    var demovalue = $(this).val();
                    $('div.signature' + id).addClass('d-none');
                    $('#show' + demovalue).removeClass('d-none');
                    $('#sign_type' + id).val(demovalue);
                });

                $('#reset-image-val' + id).on('click', function() {
                    $('#m-actual-image' + id).val('');
                    $('#m-actual-image-res' + id).removeAttr('src');
                    $('#main-sig-selection' + id).attr('disabled', false)
                });
            }

            $('#signature-pad-1').jSignature();
            $('.clear-btn1').click(function() {
                $(this).siblings('#signature-pad-1').jSignature('clear');
                $(this).siblings('.signature-data-text1').val('');
                $('#main-sig-selection1').attr('disabled', false)
            });
            $('#signature-pad-1').on('change', function() {
                var signatureData = $(this).jSignature('getData', 'default');
                $(this).siblings('.signature-data-text1').val(signatureData);
                $('#main-sig-selection1').attr('disabled', true)
            });

            $('.rsig-submitBtn').on('click', function() {
                $('#image-sig-r').toggleClass('d-none')
                $('#showD1').toggleClass('d-none')
                var data = $('#signature-pad-1').jSignature('getData', 'default');
                var image = new Image();
                image.src = data;
                $('.rimg-signature').append(image);
            })

            $('#rclear-image').on('click', function() {
                $('#showD1').toggleClass('d-none')
                $('#image-sig-r').toggleClass('d-none')
                $('#signature-pad-1').jSignature('clear');
                $('.signature-data-text1').val('');
                $('.rimg-signature').empty();
            })


            // Approver
            $('#signature-pad-2').jSignature();
            $('.clear-btn2').click(function() {
                $(this).siblings('#signature-pad-2').jSignature('clear');
                $(this).siblings('.signature-data-text2').val('');
                $('#main-sig-selection2').attr('disabled', false)
            });
            $('#signature-pad-2').on('change', function() {
                var signatureData = $(this).jSignature('getData', 'default');
                $(this).siblings('.signature-data-text2').val(signatureData);
                $('#main-sig-selection2').attr('disabled', true)
            });

            $('.asig-submitBtn').on('click', function() {
                $('#image-sig-a').toggleClass('d-none')
                $('#showD2').toggleClass('d-none')
                var data = $('#signature-pad-2').jSignature('getData', 'default');
                var image = new Image();
                image.src = data;
                $('.aimg-signature').append(image);
            })

            $('#aclear-image').on('click', function() {
                $('#showD2').toggleClass('d-none')
                $('#image-sig-a').toggleClass('d-none')
                $('#signature-pad-2').jSignature('clear');
                $('.signature-data-text2').val('');
                $('.aimg-signature').empty();
            })

            function dataURLv(input, id) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#m-actual-image-res" + id).attr('src', e.target.result);
                        $('#main-sig-selection' + id).attr('disabled', true)
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
</body>

</html>