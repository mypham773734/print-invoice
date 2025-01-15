<!DOCTYPE html>
<html lang="vi-VN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .invoice {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 20px;
        }

        .invoice-header p {
            margin: 5px 0;
            font-size: 13px;
        }

        .invoice-details p {
            margin: 0px;
            font-weight: bold;
            font-size: 12px;
            line-height: 22px;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding-left: 4px;
            padding-right: 4px;
            text-align: left;
            font-size: 12px;
            text-align: right;
        }

        .invoice-items th {
            font-size: 12px;
            text-align: center;
        }

        .invoice-total {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        @media print {
            @page {
                size: A5 portrait;
                /* Hoặc 'A5 landscape' 'portrait nếu muốn in ngang */
                background-color: red;
                padding: 0;
            }

            .no-page-break-inside {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <?php
    foreach ($dataExcel as $invoice) {
        if (!empty($invoice['tenKhachHang'])) render_invoice($invoice);
    }
    ?>
</body>

</html>

<?php
function render_invoice($invoice)
{

    $list_vat_tu = ['vatTu_M' => 'Men TN69', 'vatTu_C1' => 'Vitamin C', 'vatTu_TAO' => 'Thức ăn tự nhiên', 'vatTu_ZEO' => 'Yucca Zeo'];
    $total_thung = $invoice['soThung'];
?>
    <div class="invoice no-page-break-inside">
        <div class="invoice-header">
            <div class="d-flex justify-content-between gap-3">
                <h6 class="fw-bold" style="white-space: nowrap">TÔM GIỐNG <span style="color: #367d55">TÂN NGUYÊN</span></h6>
                <h6 class="fw-bold" style="font-size: 13px;">Địa chỉ: 246, Yên Thạnh, Thường Thạnh, Cái Răng, Cần Thơ</h6>
            </div>
            <h1 class="fw-bold my-4" style="color: #367d55">HÓA ĐƠN BÁN HÀNG</h1>
        </div>

        <div class="invoice-details">
            <div class="row">
                <div class="col-6">
                    <p>Tên khách hàng: <?= $invoice['tenKhachHang'] ?? '' ?></p>
                    <p>Địa chỉ: <?= $invoice['diaChi'] ?? '' ?></p>
                    <p>Phương thức thanh toán: Tiền mặt</p>
                </div>
                <div class="col-6">
                    <p>Số điện thoại: <?= $invoice['soDienThoai'] ?? '' ?></p>
                    <p>Nơi nhận: <?= $invoice['diaChi'] ?? '' ?></p>
                </div>
            </div>
        </div>

        <table class="invoice-items my-3">
            <thead>
                <tr>
                    <th>TT</th>
                    <th style="width: 160px;">TÊN HÀNG</th>
                    <th>LƯỢNG TÍNH TIỀN</th>
                    <th>LƯỢNG K.MÃI</th>
                    <th>SỐ THÙNG</th>
                    <th>Mấu (con/bao)</th>
                    <th>MẶN AO</th>
                    <th>GIÁ</th>
                    <th>THÀNH TIỀN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-left"><?= $invoice['tenHang'] ?? '' ?></td>
                    <td><?= $invoice['luongTinhTien'] ?? '' ?></td>
                    <td><?= $invoice['luongKhuyenMai'] ?></td>
                    <td><?= $invoice['soThung'] ?? '' ?></td>
                    <td><?= $invoice['mau'] ?? '' ?></td>
                    <td><?= $invoice['manAo'] ?? '' ?></td>
                    <td><?= $invoice['giaTien'] ?? '' ?></td>
                    <td><?= $invoice['thanhTien'] ?? '' ?></td>
                </tr>
                <?php
                $index = 1;
                foreach ($list_vat_tu as $key => $vat_tu) {
                    if ($invoice[$key] != 0) { 
                        $index++;
                        $total_thung += $invoice[$key];
                        ?>
                        <tr>
                            <td class="text-center"><?= $index ?></td>
                            <td class="text-left"><?= $list_vat_tu[$key] ?? '' ?></td>
                            <td></td>
                            <td></td>
                            <td><?= $invoice[$key] ?? '' ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                <?php }
                }
                ?>

                <tr>
                    <td class="text-center text-uppercase fw-bold" colspan="2">Tổng cộng</td>
                    <td><?= $invoice['luongTinhTien'] ?? '' ?></td>
                    <td><?= $invoice['luongKhuyenMai'] ?></td>
                    <td><?= $total_thung ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= $invoice['thanhTien'] ?? '' ?></td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-footer">
            <p class="text-uppercase fw-bold">Ghi chú: </p>
            <p class="text-end fw-bold fst-italic">Ngày ___ tháng ___ năm 2025</p>
            <div class="d-flex fw-bold justify-content-between gap-3">
                <div>
                    <p class="text-uppercase">Người lập phiếu</p>
                    <p class="text-center text-uppercase" style="margin-top: 46px"><?= $invoice['lapPhieu'] ?></p>
                </div>

                <div>
                    <p class="text-uppercase text-center">CHỦ DOANH NGHIỆP</p>
                    <p class="text-center text-uppercase" style="margin-top: 46px;">Trần Kim Huệ</p>
                </div>
            </div>
        </div>
    </div>
<?php
}
