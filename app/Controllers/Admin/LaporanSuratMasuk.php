<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Laporansuratmasukmodel;
use App\Models\Disposisimodel;
use App\Models\ProfilSekolahModel;
use TCPDF;

class Laporansuratmasuk extends BaseController
{
    protected $laporanSuratMasukModel;
    protected $disposisiModel;

    public function __construct()
    {
        $this->laporanSuratMasukModel = new LaporanSuratMasukModel();
        $this->disposisiModel = new Disposisimodel();
        $this->profilSekolahModel = new ProfilSekolahModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan Surat Masuk',
        ];
        return view('admin/laporansuratmasuk', $data);
    }

    public function getTabelLaporan()
    {
        $start_date = $_POST['startDate'];
        $end_date = $_POST['endDate'];
        $disposisiModel = $this->disposisiModel;
        $laporanSuratMasukModel = $this->laporanSuratMasukModel;
        $column_order = array('', 'tanggal_surat', 'no_surat', 'no_agenda', 'sifat_surat', 'pengirim', 'status');
        $column_search = array('no_surat', 'no_agenda', 'pengirim');
        $order = array('tanggal_surat' => 'ASC');
        $list = $laporanSuratMasukModel->get_datatables('surat_masuk', $column_order, $column_search, $order, $start_date, $end_date);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $countDisposisi = $disposisiModel->countDisposisi($lists->id_surat);
            if ($countDisposisi == 0) {
                $status = 'Belum Disposisi';
            } else {
                $status = 'Sudah Disposisi';
            }
            $row = array();
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($lists->tanggal_surat));
            $row[] = $lists->no_surat;
            $row[] = $lists->no_agenda;
            $row[] = $lists->sifat_surat;
            $row[] = $lists->pengirim;
            $row[] = $status;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $laporanSuratMasukModel->count_all('surat_masuk', $start_date, $end_date),
            "recordsFiltered" => $laporanSuratMasukModel->count_filtered('surat_masuk', $column_order, $column_search, $order, $start_date, $end_date),
            "data" => $data,
            "start_date" => $start_date,
            "end_date" => $end_date,
        );
        echo json_encode($output);
    }
    public function cetakLaporan()
    {
        $laporanSuratMasukModel = $this->laporanSuratMasukModel;
        $disposisiModel = $this->disposisiModel;
        $startDate = $this->request->getPost('startDate');
        $endDate = $this->request->getPost('endDate');
        $dataSurat = $laporanSuratMasukModel->getLaporan($startDate, $endDate);

        $pdf = new PDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Laporan Surat Masuk - ' . date('d-F-Y'));
        $pdf->SetSubject('Laporan Surat Masuk');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);
        // $pdf->setPrintHeader(false);
        // $pdf->setPrintFooter(false);
        $pdf->addPage();

        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(277, 4, 'LAPORAN SURAT MASUK', 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(277, 4, 'Periode : ' . date("d-F-Y", strtotime($startDate)) . ' s/d ' . date("d-F-Y", strtotime($endDate)), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFillColor(255, 255, 255);
        $html = '
            <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            hr {
                border: 1px solid black;
            }

            td,
            th {
                border: 1px solid #000000;
                text-align: center;
                height: 20px;
                margin: 8px;
            }
            </style>
            <table>
                <thead>
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Tanggal Surat</strong></th>
                        <th><strong>Nomor Surat</strong></th>
                        <th><strong>Nomor Agenda</strong></th>
                        <th><strong>Sifat Surat</strong></th>
                        <th><strong>Perihal</strong></th>
                        <th><strong>Pengirim</strong></th>
                        <th><strong>Status</strong></th>
                    </tr>
                </thead>
                <tbody>';
        $no = 1;
        $max = 7;
        foreach ($dataSurat as $data) {
            if (($no % $max) == 0) {
                $html .= '
                <br pagebreak="true"/>';
            }
            $html .= '
                    <tr>
                        <td>' . $no . '</td>
                        <td>' . date("d - F - Y", strtotime($data->tanggal_surat)) . '</td>
                        <td>' . $data->no_surat . '</td>
                        <td>' . $data->no_agenda . '</td>
                        <td>' . $data->sifat_surat . '</td>
                        <td>' . $data->perihal . '</td>
                        <td>' . $data->pengirim . '</td>';
            $countDisposisi = $disposisiModel->countDisposisi($data->id_surat);
            if ($countDisposisi == 0) {
                $status = 'Belum Disposisi';
            } else {
                $status = 'Sudah Disposisi';
            }
            $html .= '
                        <td>' . $status . '</td>
                    </tr>
                ';
            $no++;
        };
        $html .= '
                </tbody>
            </table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('laporan surat masuk.pdf', 'I');
    }
    public function cetakDisposisi()
    {
        $laporanSuratMasukModel = $this->laporanSuratMasukModel;
        $disposisiModel = $this->disposisiModel;
        $startDate = $this->request->getPost('startDateDisposisi');
        $endDate = $this->request->getPost('endDateDisposisi');
        $dataSurat = $laporanSuratMasukModel->getLaporanDisposisi($startDate, $endDate);

        $pdf = new PDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Laporan Disposisi Surat Masuk - ' . date('d-F-Y'));
        $pdf->SetSubject('Laporan Surat Masuk');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);
        // $pdf->setPrintHeader(false);
        // $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->addPage();

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(277, 4, 'LAPORAN DISPOSISI SURAT MASUK', 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(277, 4, 'Periode : ' . date("d-F-Y", strtotime($startDate)) . ' s/d ' . date("d-F-Y", strtotime($endDate)), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', '', 10);
        $html = '
            <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            hr {
                border: 1px solid black;
            }

            td {
                border: 1px solid #000000;
                text-align: center;
                height: 20px;
                margin: 8px;
            }
            th {
                border: 1px solid #000000;
                text-align: center;
                font-weight: bold;
                height: 20px;
                margin: 8px;
            }
            </style>
            <table>
                <thead>
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Tanggal Surat</strong></th>
                        <th><strong>Nomor Surat</strong></th>
                        <th><strong>Nomor Agenda</strong></th>
                        <th><strong>Sifat Surat</strong></th>
                        <th><strong>Perihal</strong></th>
                        <th><strong>Pengirim</strong></th>
                    </tr>
                </thead>
                <tbody>';
        $no = 1;
        $max = 5;
        foreach ($dataSurat as $data) {
            if (($no % $max) == 0) {
                $html .= '
                <br pagebreak="true"/>';
            }
            $html .= '
                    <tr>
                        <td>' . $no . '</td>
                        <td>' . date("d - F - Y", strtotime($data->tanggal_surat)) . '</td>
                        <td>' . $data->no_surat . '</td>
                        <td>' . $data->no_agenda . '</td>
                        <td>' . $data->sifat_surat . '</td>
                        <td>' . $data->perihal . '</td>
                        <td>' . $data->pengirim . '</td>
                    </tr>
                    <tr>
                        <th>Tanggal Disposisi</th>
                        <th>Dari</th>
                        <th>Isi Disposisi</th>
                        <th>Kepada</th>
                    </tr>';
            $disposisi = $disposisiModel->getLaporan($data->id_surat);
            foreach ($disposisi as $d) {
                $html .= '
                    <tr>
                        <td>' . date("d - F - Y", strtotime($d['tanggal_disposisi'])) . '</td>
                        <td>' . $d['dari'] . '</td>
                        <td>' . $d['isi'] . '</td>
                        <td>' . $d['kepada'] . '</td>
                    </tr>';
            }
            $no++;
        }
        $html .= '</tbody>
        </table>';

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('laporan disposisi surat masuk.pdf', 'I');
    }
}

class PDF extends TCPDF
{


    public function Header()
    {
        $profilSekolahModel = new ProfilSekolahModel();
        $data = $profilSekolahModel->getData();
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(277, 8, $data['nama_sekolah'], 0, 1, 'C');
        $this->SetAutoPageBreak(true, 0);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(277, 4, $data['alamat_sekolah'], 0, 1, 'C');
        $this->Cell(277, 4, 'Telp: ' . $data['telpon'] . ' Kode Pos: ' . $data['kode_pos'], 0, 1, 'C');
        $this->Ln(3);
        $this->writeHTML("<hr>", true, false, false, false, '');
    }
    public function Footer()
    {
        $this->Cell(277, 5, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
