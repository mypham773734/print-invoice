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
            font-size: 14px;
            font-weight: bold;
        }

        .invoice {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        .invoice-header {
            text-align: center;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 20px;
        }

        .invoice-header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .invoice-details p {
            margin: 0px;
            font-weight: bold;
            font-size: 13px;
            line-height: 18px;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding-left: 4px;
            padding-right: 4px;
            text-align: left;
            font-size: 14px;
            text-align: right;
            list-style: 18px !important;
            border: 1px solid;
        }

        .invoice-items th {
            font-size: 13px;
            text-align: center;
        }

        .invoice-total {
            text-align: right;
            font-size: 14px;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        @media print {
            @page {
                size: A5 landscape;
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
    $date = date('d');
    $month = date('m');
    $year = date('Y');
    $totalThanhTien = 0;
    $totalKhuyenMai = 0;
    $list_vat_tu = [
        'vatTu_C1' => [
            'ten' => 'Vitamin C',
            'quy_cach' => '1kg/túi',
            'gia'   => '60000',
        ],
        'vatTu_M' => [
            'ten' => 'Men HT85',
            'quy_cach' => '3kg/túi',
            'gia'   => '150000',
        ],        
        'vatTu_ZEO' => [
            'ten' => 'Yucca Zeo',
            'quy_cach' => '10kg/bao',
            'gia'  => '160000',
        ],
        'vatTu_EDTA' => [
            'ten' => 'EDTA',
            'quy_cach' => '10kg/bao',
            'gia'   => '160000',
        ],
        'vatTu_DAM' => [
            'ten' => 'Viên Đạm Hữu Cơ',
            'quy_cach' => '5kg/bao',
            'gia'   => '160000',
        ],
        'vatTu_NM' => [
            'ten' => 'Nước Mắm',
            'quy_cach' => '6chai/Thùng',
            'gia'   => '0',
        ],
        // 'vatTu_TAO' => [
        //     'ten' => 'Thức ăn Vèo',
        //     'quy_cach' => '1kg/thùng',
        //     'gia'   => '150000',
        // ],
        // 'mauSu_CONG' => [
        //     'ten' => 'Mẫu Sú Tân Nguyên +',
        //     'quy_cach' => '100con/bao',
        //     'gia'   => '0',
        // ],
        // 'mauSu_LON' => [
        //     'ten' => 'Mẫu Sú Tân Nguyên 68',
        //     'quy_cach' => '100con/bao',
        //     'gia'   => '0',
        // ],'mauThe_LON' => [
        //     'ten' => 'Mẫu Thẻ Tân Nguyên +',
        //     'quy_cach' => '100con/bao',
        //     'gia'   => '0',
        // ],
    ];

    $total_thung = $invoice['soThung'];
    $nhan_vien = [
        'Huỳnh Thị Mỷ Hạnh 0949 262 281',
        'Nguyễn Thị Kim Hồng 0941 092 044 ',
        'Huỳnh Nguyễn Thúy Quỳnh 0946 089 010',
        'Đỗ Thị Bích Châm 0949 811',
        'Phan Diệp Kim Xuân 0943 478 017',
        'Nguyễn Thùy Linh',
        'Thạch Thị Thu Nga',
        // 'Đồ Thị Phương Trinh',
        // 'Tăng Thị Bé Ngọc',
        // 'Mai N.Duyên',
        // 'Trần Thị Thanh Tâm',

    ];

    $ten_lap_phieu = $invoice['lapPhieu'];

    $ho_ten_lap_phieu = get_name($nhan_vien, $ten_lap_phieu);
?>
    <div class="invoice no-page-break-inside">
        <div class="invoice-header">
            <!-- <div class="d-flex justify-content-between gap-3"> -->
            <!-- <h6 class="fw-bold" style="white-space: nowrap">TÔM GIỐNG TÂN NGUYÊN</h6> -->
            <div style="text-align: center;">
                <h6 class="fw-bold" style="white-space: nowrap;">TÔM GIỐNG TÂN NGUYÊN</h6>
                <h6 class="fw-bold m-0" style="font-size: 13px;">ĐỊA CHỈ: 246, YÊN THẠNH, THƯỜNG THẠNH, CÁI RĂNG, TP CẦN THƠ</h6>
                <p class="m-0">ĐT: 0973 819 819</p>
            </div>

        </div>
        <h6 style="text-align: center;" class="fw-bold my-2">PHIẾU GIAO HÀNG KÈM HÓA ĐƠN THU TIỀN</h6>
        <h6 style="text-align: center;">
            <i>(Phiếu giao hàng này không có giá trị thay thế hóa đơn tài chính)</i>
        </h6>


        <div class="invoice-details">
            <div class="row">
                <div class="col-6">
                    <p style="margin-left: 20px;font-size: 16px; white-space: nowrap;">TÊN KHÁCH HÀNG: <?= $invoice['tenKhachHang'] ?? '' ?></p>
                   <p style="margin-left: 20px;font-size: 16px; white-space: nowrap;">SỐ ĐIỆN THOẠI: <?= $invoice['soDienThoai'] ?? '' ?></p>

                    <!-- <p>Phương thức thanh toán: Tiền mặt</p> -->
                </div>
                <div class="col-6">
                    <p style="font-size: 16px; white-space: nowrap;">NGÀY GIAO HÀNG: <?= $invoice['ngayIn'] ?? '' ?></p>
                    <p style="font-size: 16px;">ĐỊA CHỈ NHẬN HÀNG: <?= $invoice['diaChi'] ?? '' ?></p>

                </div>
            </div>
        </div>

        <table class="invoice-items ">
            <thead>
                <tr>
                    <th>TT</th>
                    <th style="width: 240px;">TÊN SẢN PHẨM</th>
                    <th>QUY CÁCH</th>
                    <th style="width: 70px;">SỐ LƯỢNG</th>
                    <th>GIÁ</th>
                    <!-- <th>LƯỢNG TÍNH TIỀN</th> -->
                    <th style="width: 110px;">THÀNH TIỀN</th>
                    <th style="width: 110px;">KHUYẾN MÃI</th>
                    <!-- <th>MẶN AO</th> -->
                    <!-- <th>THÀNH TIỀN</th> -->
                    <th style="width: 120px;">TIỀN THỰC TRẢ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-left"><?= $invoice['tenHang'] ?? '' ?></td>
                    <td class="text-center"><?= $invoice['mau'] ?? '' ?></td>
                    <td class="text-center"><?= $invoice['soThung'] ?? '' ?></td>
                    <td class="text-center"><?= $invoice['giaTien'] ?? '' ?></td>
                    <td class="text-center"><?= $invoice['luongTinhTien'] ?? '' ?></td>
                    <td class="text-center"><?= $invoice['luongKhuyenMai'] ?></td>
                    <td class="text-center"><?= $invoice['thanhTien'] ?? '' ?></td>
                    <!-- <td><?= $invoice['manAo'] ?? '' ?></td> -->
                   
                   
                </tr>
                <?php
                $index = 1;
                $totalThanhTien = $invoice['luongTinhTien'] ?? 0;
                $totalThanhTien = (int) str_replace('.', '', $totalThanhTien);

                $totalKhuyenMai = $invoice['luongKhuyenMai'] ?? 0;
                $totalKhuyenMai = (int) str_replace('.', '', $totalKhuyenMai);
                foreach ($list_vat_tu as $key => $vat_tu) {
                    if ($invoice[$key] != 0) {
                        $index++;
                        $total_thung += $invoice[$key];

                        $khuyen_mai_VT = !empty($invoice[$key] * $list_vat_tu[$key]['gia']) ? number_format($invoice[$key] * $list_vat_tu[$key]['gia'], 0, ',', '.') : '0';
                        $thanh_tien_VT = '0';
                        $totalThanhTien += (int)!empty($invoice[$key] * $list_vat_tu[$key]['gia']) ? $invoice[$key] * $list_vat_tu[$key]['gia'] : 0;
                        $totalKhuyenMai += (int)!empty($invoice[$key] * $list_vat_tu[$key]['gia']) ? $invoice[$key] * $list_vat_tu[$key]['gia'] : 0;
                ?>
                        <tr>
                            <td class="text-center"><?= $index ?></td>
                            <td class="text-left"><?= $list_vat_tu[$key]['ten'] ?? '' ?></td>
                            <td class="text-center">
                                <?= $list_vat_tu[$key]['quy_cach'] ?? '' ?>

                            </td>
                            <!-- <td></td> -->
                            <td class="text-center"><?= $invoice[$key] ?? '' ?></td>
                            <td class="text-center"><?= !empty($list_vat_tu[$key]['gia']) ? number_format($list_vat_tu[$key]['gia'], 0, ',', '.') : '0' ?></td>
                            <!-- <td class="text-center"><?= $thanh_tien_VT ?></td> -->
                            <td class="text-center"><?= $khuyen_mai_VT ?></td>
                            <td class="text-center"><?= $khuyen_mai_VT ?></td>
                            <td class="text-center">0</td>
                            <!-- <td></td> -->
                        </tr>
                <?php }
                }
                ?>

                <tr>
                    <td class="text-center text-uppercase fw-bold" colspan="2">TỔNG</td>
                    <!-- <td class="text-center"><?= $invoice['luongTinhTien'] ?? '' ?></td> -->
                    <!-- <td class="text-center"><?= $invoice['luongKhuyenMai'] ?></td> -->
                    <td></td>
                    <td class="text-center"><?= $total_thung ?></td>
                    <td></td>
                    <!-- <td class="text-center">12</td> -->
                    <td class="text-center"><b><?= number_format($totalThanhTien, 0, ',', '.') ?> </b></td>
                    <td class="text-center"><b><?= number_format($totalKhuyenMai, 0, ',', '.') ?> </b></td>
                    <td class="text-center"><?= $invoice['thanhTien'] ?? '' ?></td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-footer">
            <!-- <p class="text-uppercase fw-bold p-0 m-0">Ghi chú: </p> -->
            <!-- <p class="text-end fw-bold fst-italic p-0 m-0">Ngày <?= $date ?> tháng <?= $month ?> năm <?= $year ?></p> -->
            <div>
            <p style="font-style: italic; text-align: right; padding-right: 100px;">
    (Số tiền ghi bằng chữ: <?= htmlspecialchars($invoice['tienChu'] ?? 'Không có dữ liệu') ?>)
</p>            </div>
            <div class="d-flex fw-bold justify-content-between gap-3">

                <div>
                    <p  style="margin-left: 50px;">NGƯỜI LẬP PHIẾU</p>
                    <!-- <p class="text-center text-uppercase" style="margin-top: 46px"><?= $ho_ten_lap_phieu ?></p> -->
                </div>

                <div>
                    <p style="margin-right: 50px;">TRƯỞNG PHÒNG KINH DOANH</p>
                    <!-- <p class="text-center text-uppercase" style="margin-top: 46px;">NGUYỄN THÙY LINH</p> -->
                </div>
            </div>
        </div>
    </div>
<?php
}

function get_name($nhan_vien, $lap_phieu)
{
    $ten_lap_phieu = mb_strtolower($lap_phieu, 'UTF-8');
    foreach ($nhan_vien as $ho_ten) {
        $ho_ten_lowercase = $ho_ten ? mb_strtolower($ho_ten, 'UTF-8') : '';
        if (strpos($ho_ten_lowercase, $ten_lap_phieu)) return $ho_ten;
    }
    return true;
}
