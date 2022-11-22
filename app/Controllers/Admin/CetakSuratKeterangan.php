<?php


namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Suratkeluarmodel;
use App\Models\Templatesuratketeranganmodel;
use App\Models\ProfilSekolahModel;
use TCPDF;

class Cetaksuratketerangan extends BaseController
{
    protected $suratKeluarModel;
    protected $templateSuratKeterangan;

    public function __construct()
    {
        $this->suratKeluarModel = new Suratkeluarmodel;
        $this->templateSuratKeterangan = new Templatesuratketeranganmodel;
    }
    public function tahunAjaran()
    {
        $bulanTahunAjarBaru = "July";
        $bulanSekarang = date("F");
        $tambahTahun = date("Y") + 1;
        $kurangTahun = date("Y") - 1;
        $tahunAjar = "";
        if ($bulanSekarang < $bulanTahunAjarBaru) {
            $tahunAjar = '(' . $kurangTahun . '/' . date("Y") . ')';
        } else {
            $tahunAjar = '(' . date("Y") . '/' . $tambahTahun . ')';
        }
        return $tahunAjar;
    }
    public function cetakSurat()
    {
        setlocale(LC_ALL, 'id-ID', 'id_ID');
        $id_surat = $_GET['id_surat'];
        $suratKeluarModel = $this->suratKeluarModel;
        $templateSuratKeterangan = $this->templateSuratKeterangan;
        $dataSuratKeluar = $suratKeluarModel->find($id_surat);
        $dataSuratKeterangan = $templateSuratKeterangan->getData($id_surat);
        $tanggal = strftime("%d %B %Y", strtotime($dataSuratKeluar['tanggal_surat']));
        $data = [
            'title' => 'Surat Keluar Keterangan',
            'dataSuratKeluar' => $suratKeluarModel->find($id_surat),
            'dataSuratKeterangan' => $templateSuratKeterangan->getData($id_surat),
            'tahunAjar' => $this->tahunAjaran(),
            'tanggal' => $tanggal

        ];
        $html = view('admin/suratketerangan', $data);

        $pdf = new PDF('P', 'mm', array(215.9, 329.946), true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Surat Keterangan Siswa Aktif - ' . $dataSuratKeluar['no_surat']);
        $pdf->SetSubject('Surat Keterangan Siswa Aktif');
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
        $pdf->setPrintFooter(false);
        $pdf->addPage();
        $pdf->Ln(11);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(189, 4, 'SURAT KETERANGAN', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(189, 4, 'Nomor : ' . $dataSuratKeluar['no_surat'], 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        // $html = '<style>
        // pre {
        //     font-family: Consolas,monospace;
        // }
        // </style>';
        // $html .= '<pre>
        // Yang bertanda tangan di bawah ini Kepala SMA N 1 Tanjung Jabung Barat, dengan ini menerangkan bahwa :
        //     Nama			:
        //     NIS/NISN		:
        //     Tempat Tgl Lahir	:
        //     Kelas			:
        //     Nama Orang Tua	:
        //     Alamat			:

        // Benar nama yang tersebut diatas terdaftar sebagai peserta didik kelas 7 di SMA N 1 Tanjung Jabung Barat pada tahun ajaran .
        // Demikian Surat Keterangan ini kami buat dengan sebenarnya untuk dapat diproses sebagaimana mestinya.
        // </pre>
        // ';
        // $html = '
        // <p>Yang bertanda tangan di bawah ini Kepala SMA N 1 Tanjung Jabung Barat, dengan ini menerangkan bahwa :</p>
        // <table style="padding-left: 20p;">
        //     <tr>
        //         <td style="width: 30%;">Nama</td>
        //         <td style="width: 5%;">:</td>
        //         <td style="width: 65%;">Arbrian Abdul Jamal</td>
        //     </tr>
        //     <tr>
        //         <td style="width: 30%;">Tempat, tanggal lahir</td>
        //         <td style="width: 5%;">:</td>
        //         <td style="width: 65%;">Grobogan, 3 Maret 1993</td>
        //     </tr>
        //     <tr>
        //         <td style="width: 30%; vertical-align: top;">Alamat</td>
        //         <td style="width: 5%; vertical-align: top;">:</td>
        //         <td style="width: 65%;">Kampung Sambak RT 01 RW 09 Kelurahan Danyang 
        //             Kecamatan Purwodadi Kabupaten Grobogan</td>
        //     </tr>
        //     <tr>
        //         <td style="width: 30%;">Pekerjaan</td>
        //         <td style="width: 5%;">:</td>
        //         <td style="width: 65%;">Guru</td>
        //     </tr>
        // </table>
        // </div>';
        $pdf->writeHTML($html, true, false, true, false, '');
        // output the HTML content
        // $pdf->writeHTML($html, true, false, true, false, "');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('SuratKeteranganSiswaAktif.pdf', 'I');
    }
}

class PDF extends TCPDF
{
    public function Header()
    {
        $profilSekolahModel = new ProfilSekolahModel();
        $data = $profilSekolahModel->getData();
        $this->Image('uploads/logojambi.jpg', 20, 3, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Image('uploads/logo sma.jpg', 165, 3, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 14);
        $this->Ln(3);
        $this->Cell(189, 4, 'PEMERINTAH PROVINSI JAMBI', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(189, 4, 'DINAS PENDIDIKAN', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(189, 4, $data['nama_sekolah'], 0, 1, 'C');
        $this->SetAutoPageBreak(true, 0);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(189, 5, $data['alamat_sekolah'], 0, 1, 'C');
        $this->Cell(189, 5, 'Telp: ' . $data['telpon'] . ' Kode Pos: ' . $data['kode_pos'], 0, 1, 'C');
        $this->Ln(3);
        // $this->writeHTML("<hr>", true, false, false, false, '');
        $this->SetLineWidth(1);
        $this->Line(16, 36, 199, 36);
        $this->SetLineWidth(0);
        $this->Line(16, 37, 199, 37);
    }
}
