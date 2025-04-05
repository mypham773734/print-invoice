<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$toolUploadFile = new uploadFileExcel();

$data = [];
if(!isset($_POST['submit'])) $toolUploadFile->render_ui();
if (isset($_POST['submit'])) $toolUploadFile->handleFile();

class uploadFileExcel
{
    public function __construct() {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function render_ui()
    {
        require './interface/upload-file.php';
    }

    public function handleFile(){
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['excel_file']['tmp_name'];
            $fileName = $_FILES['excel_file']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $totalThanhTien = 0;
            $totalKhuyenMai = 0;

            if (in_array(strtolower($fileExtension), ['xlsx', 'xls'])) {
                try {
                    
                    $spreadsheet = IOFactory::load($fileTmpPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();
                    $dataExcel = [];
                  
                    foreach ($data as $key => $row) {
                        if ($key == 0 || empty($row[7])) continue;

                    // Cộng dồn giá trị cho từng dòng trong bảng
                    $totalThanhTien += isset($row[14]) ? (int) str_replace(['.', ','], '', $row[14]) : 0;
                    $totalKhuyenMai += isset($row[13]) ? (int) str_replace(['.', ','], '', $row[13]) : 0;


                        $dataExcel[] = [
                            'tenKhachHang'  => $row[7] ?? '',
                            'ngayIn'  => $row[4] ?? '',
                            'soDienThoai'   => $row[8] ?? '',
                            'diaChi'        => $row[6] ?? '',
                            'tienChu'        => $row[23] ?? '',
                            // 'thanhToan'     => $row[33] ?? 0,
                            'tenHang'       => $this->tinhTenHang($row),
                            'luongTinhTien' => $this->tinhLuongTinhTien($row),
                            'luongKhuyenMai'     => $row[13] ?? '',
                            // 'soThung'       => (int) ($row[8] ?? $row[9] ?? $row[10] ?? $row[11] ?? $row[12] ?? $row[13] ?? 0),
                            'soThung'   => $row[10] ?? '',

                            'mau'           => $this->tinhMau($row),
                            // 'canNangVatTu'           => $this->luongVattu($row),
                            'manAo'         => $row[14],
                            'giaTien'           => $row[12],
                            'thanhTien'     => $row[14],
                            'vatTu_M'       => (int) $row[16] ?? 0,
                            'vatTu_C1'       => (int) $row[15] ?? 0,
                            // 'vatTu_TAO'       => (int) $row[22] ?? 0,
                            'vatTu_ZEO'       => (int) $row[17] ?? 0,
                            'vatTu_EDTA'       => (int) $row[18] ?? 0,
                            'mauSu_CONG'       => (int) $row[23] ?? 0,
                            'mauSu_LON'       => (int) $row[24] ?? 0,
                            'mauThe_LON'       => (int) $row[25] ?? 0,
                            'lapPhieu'      => $row[2] ?? '',
                            'tienChu'      => $row[23] ?? '',
                        ];
                    }
                    // echo "<pre>";
                    // print_r($dataExcel);
                    // echo "</pre>";
                    // die();
                    
                    $this->render_hoa_don($dataExcel, $totalThanhTien, $totalKhuyenMai);

                    // $this->render_hoa_don($dataExcel);
                } catch (Exception $e) {
                    echo "Lỗi khi đọc file Excel: " . $e->getMessage();
                }
            } else {
                echo "Chỉ được phép upload file Excel (.xlsx, .xls).";
            }
        } else {
            echo "Lỗi khi tải file lên.";
        }
    }

    public function tinhTenHang($row)
    {
        if (!empty($row[9]) && in_array(strtoupper($row[9]), ['STN', 'SRTN'])) {
            return 'Sú Tân Nguyên';
        }
        if (!empty($row[9]) && in_array(strtoupper($row[9]), ['STN+', 'SRTN+'])) {
            return 'Sú Tân Nguyên+';
        }
                
        // if (isset($row[9]) && $row[9] === 'STN') return 'Sú Tân Nguyên';
        // if (isset($row[9]) && $row[9] === 'STN+') return 'Sú Tân Nguyên +';
        if (isset($row[9]) && $row[9] === 'STN68') return 'Sú Tân Nguyên 68';
        if (isset($row[9]) && $row[9] === 'TTN') return 'Thẻ Tân Nguyên';
        if (isset($row[9]) && $row[9] === 'TTN+') return 'Thẻ Tân Nguyên +';
        if (isset($row[9]) && $row[9] === 'SAĐ') return 'Sú Ao Đất';
        if (isset($row[9]) && $row[9] === 'TSB') return 'Thẻ Sạch Bệnh';
        if (isset($row[9]) && $row[9] === 'C1') return 'Vitamin C';
        if (isset($row[9]) && $row[9] === 'C3') return 'Vitamin C';
        if (isset($row[9]) && $row[9] === 'MEN 0,5') return 'Men HT85';
        if (isset($row[9]) && $row[9] === 'MEN HT85') return 'Men HT85';
        if (isset($row[9]) && $row[9] === 'Y.ZEO') return 'Yucca Zeo';
        if (isset($row[9]) && $row[9] === 'EDTA') return 'EDTA';
        if (isset($row[9]) && $row[9] === 'MSTN') return 'Mẫu Sú Tân Nguyên';
        if (isset($row[9]) && $row[9] === 'MSTN+') return 'Mẫu Sú Tân Nguyên +';
        if (isset($row[9]) && $row[9] === 'MS68') return 'Mẫu Sú Tân Nguyên 68';
        if (isset($row[9]) && $row[9] === 'MTTN') return 'Mẫu Thẻ Tân Nguyên';
        if (isset($row[9]) && $row[9] === 'MTTN+') return 'Mẫu Thẻ Tân Nguyên +';
        if (isset($row[9]) && $row[9] === 'CUA') return 'Cua Tiêu 2';
      
    }

    public function tinhMau($row): int|string
    {
        if (!empty($row[9]) && in_array(strtoupper($row[9]), ['STN', 'SRTN'])) {
            return '14.000con/thùng';
        }
        if (!empty($row[9]) && in_array(strtoupper($row[9]), ['STN+', 'SRTN+'])) {
            return '12.000con/thùng';
        }
        // if (isset($row[9]) && $row[9] === 'STN') return '14.000con/thùng';
        // if (isset($row[9]) && $row[9] === 'STN+') return '12.000con/thùng';      
        if (isset($row[9]) && $row[9] === 'STN68') return '6.000con/thùng';
        if (isset($row[9]) && $row[9] === 'TTN') return '14.000con/thùng';
        if (isset($row[9]) && $row[9] === 'TTN+') return '12.000con/thùng';      
        if (isset($row[9]) && $row[9] === 'SAĐ') return '7.000con/thùng';
        if (isset($row[9]) && $row[9] === 'TSB') return '7.000con/thùng';
        if (isset($row[9]) && $row[9] === 'C1') return '1kg/túi';
        if (isset($row[9]) && $row[9] === 'C3') return '3kg/túi';
        if (isset($row[9]) && $row[9] === 'MEN 0,5') return '0,5kg/túi';
        if (isset($row[9]) && $row[9] === 'MEN HT85') return '3kg/túi';
        if (isset($row[9]) && $row[9] === 'Y.ZEO') return '10kg/bao';
        if (isset($row[9]) && $row[9] === 'EDTA') return '10kg/bao';
        if (isset($row[9]) && $row[9] === 'MSTN') return '100con/bao';
        if (isset($row[9]) && $row[9] === 'MSTN+') return '100con/bao';
        if (isset($row[9]) && $row[9] === 'MS68') return '100con/bao';
        if (isset($row[9]) && $row[9] === 'MTTN') return '100con/bao';
        if (isset($row[9]) && $row[9] === 'MTTN+') return '100con/bao';
        if (isset($row[9]) && $row[9] === 'CUA') return '500con/khây';













        // if (!empty($row[8])) return '14.000con/thùng';
        // if (!empty($row[9])) return '12.000con/thùng';
        // if (!empty($row[10])) return '6.000con/thùng';
        // if (!empty($row[11])) return '14.000con/thùng';
        // if (!empty($row[12])) return '12.000con/thùng';
        // if (!empty($row[13])) return '500';
    }

    public function tenVatTu($row)
    {
        if (!empty($row[16])) return 'Men HT85';
        if (!empty($row[15])) return 'Vitamin C';
        // if (!empty($row[22])) return 'Thức ăn Vèo';
        if (!empty($row[17])) return 'Yucca Zeo';
        if (!empty($row[18])) return 'EDTA';
        if (!empty($row[23])) return 'Mẫu Sú Tân Nguyên +';
        if (!empty($row[24])) return 'Mẫu Sú Tân Nguyên 68';
        if (!empty($row[25])) return 'Mẫu Thẻ Tân Nguyên +';
    }
    // public function luongVattu($row): int|string
    //  {
    //     if (!empty($row[18])) return '3kg/túi';
    //     if (!empty($row[19])) return '1kg/túi';
    //     if (!empty($row[20])) return '5kg/thùng';
    //     if (!empty($row[21])) return '10kg/bao';
    //     if (!empty($row[22])) return '10kg/bao';
    // }

    // public function tinhLuongKhuyenMai($row)
    // {
    //     $result = 0;
    //     $character_search = str_split(',. ');
    //     $khuyenMai = 0;
    //     if (!empty($row[16])) {
    //         $khuyenMai = strval(str_replace($character_search, '', $row[16]));
    //     }

    //     $gia = 0;
    //     if (!empty($row[15])) {
    //         $gia = strval(str_replace($character_search, '', $row[15]));
    //     }

    //     $result = ($khuyenMai != 0 && $gia != 0) ? (int) round($khuyenMai / $gia) : 0;

    //     return $result;
    // }
    

    public function tinhLuongTinhTien($row)
    {
        $soGiaTien = isset($row[12]) ? (int) str_replace([',', '.'], '', $row[12]) : 0;        $tongThung =  $row[10] ?? 0;

        if ($tongThung == 0) return 0;
        $luongTinhTien = (int) $tongThung * $soGiaTien;
        return number_format($luongTinhTien, 0, ',', '.'); // Định dạng số tiền
    }
    // public function tinhLuongTinhTien($row)
    // {
    //     $luongKhuyenMai = $this->tinhLuongKhuyenMai($row);
    //     $tongThung = $row[8] ?? $row[9] ?? $row[10] ?? $row[11] ?? $row[12] ?? $row[13] ?? 0;

    //     if ($tongThung == 0) return 0;
    //     $luongTinhTien = (int) $tongThung - $luongKhuyenMai;
    //     return $luongTinhTien;
    // }

    // public function render_hoa_don($dataExcel)
    // {
    //     require './interface/invoice.php';
    // }
    public function render_hoa_don($dataExcel, $totalThanhTien, $totalKhuyenMai)
{
    require './interface/invoice.php';
}

}
