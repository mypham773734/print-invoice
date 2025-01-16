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
            if (in_array(strtolower($fileExtension), ['xlsx', 'xls'])) {
                try {
                    $spreadsheet = IOFactory::load($fileTmpPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();
                    $dataExcel = [];
                    foreach ($data as $key => $row) {
                        if($key == 0 || empty($row[6])) continue;
                        $dataExcel[] = [
                            'tenKhachHang'  => $row[6] ?? '',
                            'soDienThoai'   => $row[7] ?? '', 
                            'diaChi'        => $row[5] ?? '', 
                            'thanhToan'     => $row[33] ?? 0,
                            'tenHang'       => $this->tinhTenHang($row),
                            'luongTinhTien' => $this->tinhLuongTinhTien($row),
                            'luongKhuyenMai'     => $this->tinhLuongKhuyenMai($row),
                            'soThung'       => (int) ($row[8] ?? $row[9] ?? $row[10] ?? $row[11] ?? $row[12] ?? $row[13] ?? 0), 
                            'mau'           => $this->tinhMau($row), 
                            'manAo'         => $row[14],
                            'giaTien'           => $row[15],
                            'thanhTien'     => $row[17],
                            'vatTu_M'       => (int) $row[18] ?? 0,
                            'vatTu_C1'       => (int) $row[19] ?? 0,
                            'vatTu_TAO'       => (int) $row[20] ?? 0,
                            'vatTu_ZEO'       => (int) $row[21] ?? 0,
                            'vatTu_EDTA'       => (int) $row[22] ?? 0,
                            'lapPhieu'      => $row[2] ?? '',
                            'phuongThucThanhToan'      => $row[34] ?? '',
                        ];
                    }
                    // echo "<pre>";
                    // print_r($dataExcel);
                    // echo "</pre>";
                    $this->render_hoa_don($dataExcel);
                    
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

    public function tinhTenHang($row){
        if(!empty($row[8])) return 'Tôm sú giống Tân Nguyên';
        if(!empty($row[9])) return 'Tôm sú giống Tân Nguyên cộng';
        if(!empty($row[10])) return 'Tôm sú giống Tân Nguyên 68';
        if(!empty($row[11])) return 'Tôm thẻ giống Tân Nguyên';
        if(!empty($row[12])) return 'Tôm thẻ giống Tân Nguyên cộng';
        if(!empty($row[13])) return 'Cua';
    }

    public function tinhMau($row){
        if(!empty($row[8])) return '2000';
        if(!empty($row[9])) return '2000';
        if(!empty($row[10])) return '1000';
        if(!empty($row[11])) return '2000';
        if(!empty($row[12])) return '1800';
        if(!empty($row[13])) return '500';
    }

    public function tinhLuongKhuyenMai($row){
        $result = 0;
        $character_search = str_split(',. ');
        $khuyenMai = 0;
        if(!empty($row[16])){
            $khuyenMai = strval(str_replace($character_search, '', $row[16]));
        }
        
        $gia = 0;
        if(!empty($row[15])){
            $gia = strval(str_replace($character_search, '', $row[15]));
        }

        $result = ($khuyenMai != 0 && $gia != 0) ? (int) round($khuyenMai/$gia) : 0; 
        
        return $result;
    }

    public function tinhLuongTinhTien($row){
        $luongKhuyenMai = $this->tinhLuongKhuyenMai($row);
        $tongThung = $row[8] ?? $row[9] ?? $row[10] ?? $row[11] ?? $row[12] ?? $row[13] ?? 0;

        if($tongThung == 0) return 0;
        $luongTinhTien = (int) $tongThung - $luongKhuyenMai;
        return $luongTinhTien;
    }

    public function render_hoa_don($dataExcel){
        require './interface/invoice.php';
    }
}
