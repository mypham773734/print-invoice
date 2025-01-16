<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all"
        rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mt-4">
                <div class="col d-flex justify-content-center">
                    <div style="width:100%; max-width: 900px">
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls"
                            data-show-preview="false" data-msg-placeholder="Chọn file Excel (.xlsx, .xls)" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col d-flex justify-content-center">
                    <button type="submit" name="submit" class="btn btn-primary" style="min-width: 100px">Tạo</button>
                </div>
            </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#excel_file").fileinput({
                'showUpload': false,
                'previewFileType': 'any',
                browseLabel: 'Chọn file',

            });
        });
    </script>
</body>

</html>