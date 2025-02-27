<?php

namespace App\Http\Controllers;

use App\Models\BoxValue;
use App\Models\Data;
use App\Models\FileDownloadCount;
use App\Models\GrafikData;
use App\Models\Infografis;
use App\Models\Opd;
use App\Models\PublikasiGuest;
use App\Models\UsulanData;
use App\Models\Visitor;
use App\Models\VisualHeader;
use App\Models\VisualIsi;
use App\Models\VisualTable;
use App\Models\Wilayah;
use App\Services\CkanApi\Facades\CkanApi;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PortalController extends Controller
{
    public function storeDataByFilter(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        // dd($request->all());
        $id_data = $request->input('id_data');
        $id_table = $request->input('id_table');
        $selectedAxisX = $request->input('axis_x');
        $selectedAxisY = $request->input('axis_y');
        $selectedCategory = $request->input('kategori');

        $grafik = GrafikData::where('id_table', $id_table)->first();
        // dd($grafik);
        if ($grafik) {
            // dd('s');
            $grafik->update([
                'id_data' => $id_data,
                'axis_x' => $selectedAxisX,
                'axis_y' => $selectedAxisY,
                'kategori' => $selectedCategory,
            ]);
        } else {
            $grafik = GrafikData::create([
                'id_data' => $id_data,
                'id_table' => $id_table,
                'axis_x' => $selectedAxisX,
                'axis_y' => $selectedAxisY,
                'kategori' => $selectedCategory,
            ]);
        }
        return redirect()->back()->with('active_tab', 'grafik');
    }

    public function index()
    {
        // return redirect()->route('login');
        // visitor

        // $ch = curl_init();
        // curl_setopt(
        //     $ch,
        //     CURLOPT_URL,
        //     "https://katalog-data.madiunkab.go.id/api/3/action/package_search?include_private=1&rows=20&start=0"
        // );
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // // curl_setopt($ch, CURLOPT_CAINFO, "/etc/ssl/certs/ca-certificates.crt");

        // $output = curl_exec($ch);
        // if ($output === false) {
        //     echo "cURL Error: " . curl_error($ch);
        // } else {
        //     echo "Connection Successful: " . $output;
        // }
        // curl_close($ch);

        if (!isset($_COOKIE['newComer'])) {
            setcookie("newComer", "uwu", time() + 7200, '/');

            $day = date("j");
            $month = date("n");
            $year = date("Y");
            $cek_visitor = Visitor::where('tgl', $day)->where('bln', $month)->where('thn', $year)->first();

            if (!empty($cek_visitor)) {
                Visitor::where('tgl', $day)->where('bln', $month)->where('thn', $year)->update([
                    'jumlah' => $cek_visitor->jumlah + 1
                ]);
            } else {
                Visitor::create([
                    'nama' => 'pengunjung',
                    'tgl' => $day,
                    'bln' => $month,
                    'thn' => $year,
                    'jumlah' => 1
                ]);
            }
        }
        $infografis = Infografis::count();
        $publikasi = PublikasiGuest::count();
        $data = CkanApi::dataset()->all();
        $demografis = Wilayah::all();
        // dd($data);
        $org = Opd::count();
        $opd = $org - 2;
        // dd($data);
        $groups = Cache::remember('front', 3600, fn() => CkanApi::group()->all(['limit' => 100]));
        if (empty($groups) || !$groups) {
            Cache::forget('front');
        }
        // dd($groups);

        $groups = $groups['result'] ?? [];
        $boxvalue = BoxValue::with('data.publikasi')->get();
        // dd($boxvalue);
        // return view('portal.landingpage.beranda', compact('groups'));
        return view('guest.beranda', compact('groups', 'infografis', 'publikasi', 'data', 'opd', 'demografis', 'boxvalue'));
    }

    public function tentang()
    {
        return view('guest.tentang');
    }

    public function infografis()
    {
        $infografis = Infografis::orderBy('created_at', 'desc')->get();
        return view('guest.infografis', compact('infografis'));
    }


    public function send_usulan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pekerjaan' => 'required|string|max:255',
            'kelamin' => 'required|string|max:255',
            'no_hp' => 'required|string',
            'usulan' => 'required|string',
            'captcha' => 'required|captcha'
        ]);

        UsulanData::create([
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'pekerjaan' => $request->input('pekerjaan'),
            'kelamin' => $request->input('kelamin'),
            'no_wa' => $request->input('no_hp'),
            'usulan' => $request->input('usulan'),
            'tahun' => date('Y'),
        ]);

        return redirect()->back()->with('success', 'Usulan berhasil dikirim.');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }


    public function infografis_detail($id)
    {
        $id = decrypt($id);
        $infografis = Infografis::findOrFail($id);
        $pop = Infografis::orderBy('created_at', 'desc')->limit('5')->get();
        return view('guest.detail-infografis', compact('infografis', 'pop'));
    }

    public function downloadImage($id)
    {
        $id = decrypt($id);
        $getinfos = Infografis::find($id);
        $filePath = storage_path('app/public/blogs/' . $getinfos->image);

        if (file_exists($filePath)) {
            // Mendapatkan ekstensi file
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            // Membuat nama file berdasarkan title dan ekstensi asli
            $fileName = $getinfos->title . '.' . $extension;
            return response()->download($filePath, $fileName);
        } else {
            abort(404);
        }
    }

    public function downloadPdf($id)
    {
        $id = decrypt($id);
        $getinfos = Infografis::find($id);
        $konten = $getinfos->content;
        $imagePath = storage_path('app/public/blogs/' . $getinfos->image);
        // Menggunakan title sebagai nama file PDF
        $pdfFileName = $getinfos->title . '.pdf';
        $pdfPath = storage_path('app/public/blogs/' . encrypt($getinfos->id) . '.pdf');

        if (file_exists($imagePath)) {
            // Read image file and encode it to base64
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $data = file_get_contents($imagePath);
            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);

            // Create PDF
            $dompdf = new Dompdf();
            $dompdf->setPaper('A4');

            // Load HTML
            $html = '<html><body style="margin: 0; padding: 0;">';
            $html .= '<img src="' . $base64Image . '" style="max-width: 100%; height: auto;">';
            $html .= '<h3 class="mt-0">' . $getinfos->title . '</h3>';
            $html .= $getinfos->content;
            $html .= '</body></html>';

            $dompdf->loadHtml($html);
            $dompdf->render();
            $output = $dompdf->output();

            // Save PDF
            file_put_contents($pdfPath, $output);

            // Download PDF
            return response()->download($pdfPath, $pdfFileName)->deleteFileAfterSend(true);
        } else {
            abort(404);
        }
    }

    public function publikasi()
    {
        $publikasi = PublikasiGuest::orderBy('created_at', 'desc')->get();
        return view('guest.publikasi', compact('publikasi'));
    }


    public function publikasi_detail($id)
    {
        $id = decrypt($id);
        $publikasi = PublikasiGuest::findOrFail($id);
        $pop = PublikasiGuest::orderBy('created_at', 'desc')->limit('5')->get();
        $link = Storage::url($publikasi->pdf_path);

        return view('guest.detail-publikasi', compact('publikasi', 'pop', 'link'));
    }

    // public function download($id)
    // {
    //     $id = decrypt($id);
    //     // dd('sss');
    //     $publication = PublikasiGuest::findOrFail($id);
    //     // dd($publication->pdf_path);
    //     if (Storage::exists($publication->pdf_path)) {
    //         return Storage::download($publication->pdf_path);
    //     }
    // }
    public function download($id)
    {
        $decryptedPath = decrypt($id);
        $link = Storage::url($decryptedPath);
        return redirect()->away($link);
    }

    public function data(Request $request)
    {
        $limit = 15;
        $page = max(intval($request->get('page', 0)), 1);
        $start = $page > 1 ? ($page * $limit) - $limit : 0;
        $searchQuery = [
            'rows' => $limit,
            'start' => $start,
            'q' => $request->get('q'),
            'sort' => $request->get('sort', 'score desc, metadata_modified desc'),
            'include_private' => false
        ];

        $searchQuery['fq'] = [];
        if ($request->filled('group')) {
            $searchQuery['fq'][] = 'groups:' . $request->get('group');
        }
        if ($request->filled('org')) {
            $searchQuery['fq'][] = 'organization:' . $request->get('org');
        }
        if ($request->filled('tag')) {
            $searchQuery['fq'][] = 'tags:' . $request->get('tag');
        }
        $searchQuery['fq'] = implode(' AND ', $searchQuery['fq']);

        $hash = null;
        try {
            $hash = sha1(serialize($searchQuery));
        } catch (Exception $e) {
        }

        if ($hash) {
            // dd("atas");
            // $data = Cache::remember('front:data:' . $hash, 180, fn () => CkanApi::dataset()->all($searchQuery));
            // $orgs = Cache::remember('front:orgs:' . $hash, 180, fn () => CkanApi::organization()->all(['limit' => 1000]));
            // $groups = Cache::remember('front:groups:' . $hash, 180, fn () => CkanApi::group()->all(['limit' => 1000]));

            $data =  CkanApi::dataset()->all($searchQuery);
            $orgs = CkanApi::organization()->all(['limit' => 1000]);
            $groups = CkanApi::group()->all(['limit' => 1000]);
        } else {
            // dd("bawah");
            $data = CkanApi::dataset()->all($searchQuery);
            $orgs = CkanApi::organization()->all(['limit' => 1000]);
            $groups = CkanApi::group()->all(['limit' => 1000]);
        }

        $result = $data['result'] ?: [];
        $data = $data['success'] ? $data['result']['results'] : [];
        $orgs = $orgs['success'] ? $orgs['result'] : [];
        $groups = $groups['success'] ? $groups['result'] : [];

        $pages = ceil(($result['count'] ?? 0) / $limit);
        $hasNextPage = $page < $pages;
        $hasPrevPage = $page > 1;



        return view('guest.data', compact('data', 'orgs', 'groups', 'hasNextPage', 'hasPrevPage', 'page', 'pages', 'limit'));
        // return view('portal.landingpage.data', compact('data', 'orgs', 'groups', 'hasNextPage', 'hasPrevPage', 'page', 'pages', 'limit'));
    }

    public function showDataset($name, Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $client = new Client([
            'verify' => false, // Set to false to ignore SSL certificate verification
        ]);
        $data = Cache::remember('data:detail:' . $name, 60, fn() => CkanApi::dataset()->show($name));
        // dd($data);
        if (!$data || isset($data['error'])) {
            Cache::forget('data:detail:' . $name);
            return response()->json(['error' => 'Dataset tidak ditemukan'], 404);
        }

        $data = $data['result'];
        if ($data['private']) {
            return response()->json(['error' => 'Dataset bersifat prifat'], 403);
        }

        if ($data['state'] != 'active') {
            return response()->json(['error' => 'Dataset belum dipublikasi'], 401);
        }

        $resources = [];
        foreach ($data['resources'] as $resource) {
            if ($resource['state'] !== 'active') continue;
            $fileDownload = FileDownloadCount::where('file_name', $resource['url'])->first();
            $downloadCount = $fileDownload ? $fileDownload->download_count : 0;


            $resources[] = [
                'id' => $resource['id'],
                'name' => $resource['name'],
                'description' => $resource['description'],
                'created' => !empty($resource['created']) ? Carbon::parse($resource['created'])->translatedFormat('d F Y') : '-',
                'format' => $resource['format'],
                'url_download' => $resource['url'],
                'url_preview' => rtrim(config('ckan_api.url'), '/') . '/dataset/' . $data['url'] . '/resource/' . $resource['id'],
                'size' => $resource['size'],
                'download_count' => $downloadCount,
            ];
        }
        $dataset = [
            'title' => $data['title'],
            'description' => $data['notes'],
            'created' => !empty($data['metadata_created']) ? Carbon::parse($data['metadata_created'])->translatedFormat('d F Y') : '-',
            'modified' => !empty($data['metadata_modified']) ? Carbon::parse($data['metadata_modified'])->translatedFormat('d F Y') : '-',
            'link' => rtrim(config('ckan_api.url'), '/') . '/dataset/' . $data['url'],
            'organization' => !empty($data['organization']) ? [
                'title' => $data['organization']['title'],
                'link' => rtrim(config('ckan_api.url'), '/') . '/organization/' . $data['organization']['name'],
                'image' => $data['organization']['image_url'] ?: null
            ] : [],
            'resources' => $resources
        ];

        $getDataforGrafik = Data::where('nama_data', $dataset['title'])->where('status_id', Data::STATUS_TERPUBLIKASI)->latest()->first();
        // dd($getDataforGrafik);
        //definisi
        $axis_x = [];
        $axis_y = [];
        $kategori = [];
        $seriesData = [];
        $existingData = [];
        $axis_y_name = [];
        $tables = [];
        // end
        if ($getDataforGrafik != null) {

            $namaTabels = VisualTable::all();
            $tables = []; // Initialize an empty array
            $headers = []; // Initialize an empty array
            $rows = []; // Initialize an empty array
            if ($namaTabels->isNotEmpty()) {
                foreach ($namaTabels as $namaTabel) {

                    $getIdData = Data::where('nama_data', $getDataforGrafik->nama_data)->where('status_id', Data::STATUS_TERPUBLIKASI)->latest()->first();
                    $table = VisualTable::where('id', $namaTabel->id)->where('id_data', $getIdData->id)->first();

                    // dd($getIdData->id);
                    if ($table) {
                        // Mengambil data header terurut berdasarkan urutan menyamping
                        $headers = VisualHeader::where('id_namatabel', $table->id)
                            ->orderBy('urutan_menyamping')
                            ->get();
                        // Mengambil data isi terurut berdasarkan urutan kebawah
                        $rows = VisualIsi::where('id_namatabel', $table->id)
                            ->orderBy('urutan_kebawah')
                            ->get()
                            ->groupBy('urutan_kebawah');
                        // Mengambil data isi terurut berdasarkan urutan kebawah
                        $rows_grafik = VisualIsi::where('id_namatabel', $table->id)
                            ->orderBy('id_header')
                            ->get()
                            ->groupBy('id_header');
                        // Mengambil tahun-tahun dalam kolom isi
                        // $years = $rows->pluck('isi')->unique()->sort();
                        $tables[] = [
                            'table' => $table,
                            'headers' => $headers,
                            'rows_grafik' => $rows_grafik,
                            'rows' => $rows,
                        ];
                    }
                }
            }
            $cek_axis_x = GrafikData::where('id_data', $getDataforGrafik->id)->first();
            // dd($cek_axis_x);

            if ($cek_axis_x == null) {
                // dd('a');
                $existingData = GrafikData::where('id_data', $getDataforGrafik->id)
                    ->join('visual_headers', 'grafik_data.kategori', '=', 'visual_headers.id')
                    ->join('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                    ->join('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                    ->select('grafik_data.*', 'visual_headers.header', 'vh_axis_x.header as axis_x_header', 'vh_axis_y.header as axis_y_header')

                    ->get();
            } else {
                if ($cek_axis_x->axis_x == 0) {
                    // dd('b');
                    $existingData = GrafikData::where('id_data', $getDataforGrafik->id)
                        // ->join('visual_headers', 'grafik_data.kategori', '=', 'visual_headers.id')
                        // ->join('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                        // ->join('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                        // ->select('grafik_data.*', 'kategoriHeader.header', 'axisXHeader.header as axis_x_header', 'axisYHeader.header as axis_y_header')

                        ->get();
                } else {
                    // dd($getDataforGrafik->id);
                    $existingData = GrafikData::where('id_data', $getDataforGrafik->id)
                        ->join('visual_headers', 'grafik_data.kategori', '=', 'visual_headers.id')
                        ->join('visual_headers as vh_axis_x', 'grafik_data.axis_x', '=', 'vh_axis_x.id')
                        ->join('visual_headers as vh_axis_y', 'grafik_data.axis_y', '=', 'vh_axis_y.id')
                        ->select('grafik_data.*', 'visual_headers.header', 'vh_axis_x.header as axis_x_header', 'vh_axis_y.header as axis_y_header')
                        ->get();
                }
            }

            // dd($existingData);
            $axis_x = [];
            $axis_y = [];
            $kategori = [];
            $seriesData = [];
            $seriesDataLine = [];

            foreach ($existingData as $item) {
                // dd($item->kategori);
                if ($item->header == "Tahun" || $item->axis_x == 0) {
                    $kategori = VisualIsi::where(
                        'id_header',
                        $item->kategori
                    )
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->unique()
                        ->values()
                        ->toArray();
                } else {
                    $kategori = VisualIsi::where('id_header', $item->kategori)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->toArray();
                }

                if ($item->axis_x_header == "Tahun") {
                    $axis_x = VisualIsi::where('id_header', $item->axis_x)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->unique()
                        ->toArray();
                } else {
                    $axis_x = VisualIsi::where('id_header', $item->axis_x)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->unique()
                        ->toArray();
                }

                if ($item->axis_y_header == "Tahun") {
                    $axis_y = VisualIsi::where('id_header', $item->axis_y)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->unique()
                        ->toArray();
                } else {
                    $axis_y = VisualIsi::where('id_header', $item->axis_y)
                        ->orderBy('id_header')
                        ->pluck('isi')
                        ->toArray();
                }

                // dd($kategori, $axis_x, $axis_y);

                foreach ($kategori as $index => $name) {
                    // dd($name);
                    if ($item->header == "Tahun") {
                        $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                        $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                        $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                        $seriesData[$item->id][] = [
                            'name' => $name,
                            'data' => $data1
                        ];
                    } else {
                        $seriesData[$item->id][] = [
                            'name' => $name,
                            'data' => array_map('intval', explode(',', $axis_y[$index]))
                        ];
                    }
                }

                foreach ($axis_x as $index => $name) {
                    if ($item->header == "Tahun") {
                        $noUrut = VisualIsi::where('id_namatabel', $item->id_table)->pluck('urutan_kebawah')->unique()->toArray();
                        $noUrutTahun = VisualIsi::where('id_namatabel', $item->id_table)->whereIn('urutan_kebawah', $noUrut)->where('isi', $name)->pluck('urutan_kebawah');
                        $data1 = VisualIsi::where('id_namatabel', $item->id_table)->where('id_header', $item->axis_y)->whereIn('urutan_kebawah', $noUrutTahun)->pluck('isi')->toArray();

                        $seriesDataLine[$item->id][] = [
                            'name' => $name,
                            'data' => $data1
                        ];
                    } else {
                        $seriesDataLine[$item->id][] = [
                            'name' => $name,
                            'data' => array_map('intval', explode(',', $axis_y[$index]))
                        ];
                    }
                }
                // dd($seriesData);
            }

            $kategori = json_encode($kategori);
            $axis_y = json_encode($axis_y);
            $axis_x = json_encode($axis_x);
            // dd($kategori);
        }
        // dd($existingData);

        // menampilkan meta data
        $getmeta = Data::with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('nama_data', $dataset['title'])
            ->where('status_id', Data::STATUS_TERPUBLIKASI)
            ->latest()
            ->first();
        // dd($getmeta);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();

        return view('guest.detail-dataset', compact('dataset', 'existingData', 'axis_x', 'axis_y', 'kategori', 'seriesData', 'tables', 'axis_y_name', 'getmeta', 'opd', 'seriesDataLine'));
    }

    public function downloadFileCount(Request $request)
    {

        $urlDownload = $request->input('url_download');
        $fileDownload = FileDownloadCount::where('file_name', $urlDownload)->first();

        if ($fileDownload) {
            $fileDownload->increment('download_count');

            return response()->json(['download_count' => $fileDownload->download_count]);
        } else {
            $fileDownload = FileDownloadCount::create([
                'file_name' => $urlDownload,
                'download_count' => 1,
            ]);
            return response()->json(['download_count' => $fileDownload->download_count]);
        }
    }


    public function detail($name, Request $request)
    {
        abort_unless($request->wantsJson(), 400, 'Bad Request');

        $data = Cache::remember('data:detail:' . $name, 60, fn() => CkanApi::dataset()->show($name));
        if (!$data || isset($data['error'])) {
            Cache::forget('data:detail:' . $name);
            return response()->json(['error' => 'Dataset tidak ditemukan'], 404);
        }

        $data = $data['result'];
        if ($data['private']) {
            return response()->json(['error' => 'Dataset bersifat prifat'], 403);
        }

        if ($data['state'] != 'active') {
            return response()->json(['error' => 'Dataset belum dipublikasi'], 401);
        }

        $resources = [];
        foreach ($data['resources'] as $resource) {
            if ($resource['state'] !== 'active') continue;

            $resources[] = [
                'id' => $resource['id'],
                'name' => $resource['name'],
                'description' => $resource['description'],
                'created' => !empty($resource['created']) ? Carbon::parse($resource['created'])->translatedFormat('d F Y') : '-',
                'format' => $resource['format'],
                'url_download' => $resource['url'],
                'url_preview' => rtrim(config('ckan_api.url'), '/') . '/dataset/' . $data['url'] . '/resource/' . $resource['id'],
                'size' => $resource['size']
            ];
        }

        return response()->json([
            'title' => $data['title'],
            'description' => $data['notes'],
            'created' => !empty($data['metadata_created']) ? Carbon::parse($data['metadata_created'])->translatedFormat('d F Y') : '-',
            'modified' => !empty($data['metadata_modified']) ? Carbon::parse($data['metadata_modified'])->translatedFormat('d F Y') : '-',
            'link' => rtrim(config('ckan_api.url'), '/') . '/dataset/' . $data['url'],
            'organization' => !empty($data['organization']) ? [
                'title' => $data['organization']['title'],
                'link' => rtrim(config('ckan_api.url'), '/') . '/organization/' . $data['organization']['name'],
                'image' => $data['organization']['image_url'] ?: null
            ] : [],
            'resources' => $resources
        ]);
    }

    public function berita()
    {
        return view('portal.landingpage.berita');
    }
}
