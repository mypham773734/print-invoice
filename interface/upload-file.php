<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="excel_file">Upload Excel file:</label>
        <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls">
        <button type="submit" name="submit">Upload</button>
    </form>
</body>
</html>
