<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\Data;
use App\Models\Visitor;
use App\Models\MasterTahun;
use Illuminate\Http\Request;
use App\Exports\RekapOPDExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('administrator')) {
            return redirect()->route('d_administrator');
        } elseif ($user->hasRole('walidata') || $user->hasRole('pembina') || $user->hasRole('walidatapendukung')) {
            return redirect()->route('d_walidata');
        } elseif ($user->hasRole('produsen')) {
            return redirect()->route('d_produsen');
        }
        return redirect()->to('/');
    }

    public function dashboardAdmin(Request $request)
    {

        $tahun = MasterTahun::where('is_active', 1)->get();
        $selectedTahun = $request->input('tahun', '');
        $dataPrioritas = Data::where('data_prioritas', 1)->count();
        $daftardata = Data::count();
        $dataStandarData = Data::whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->count();

        $dataPengumpulan = Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])->count();
        // $dataTelahLengkap = Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])->count();
        $dataTelahLengkap = Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI])->count();
        $dataRevisi = Data::whereIn('status_id', [Data::STATUS_REVISI])->count();
        $dataSiapPublish = Data::where('status_id', '=', Data::STATUS_SIAP_PUBLIKASI)->count();
        $dataTerpublikasi = Data::where('status_id', '=', Data::STATUS_TERPUBLIKASI)->count();
        $dataTerbaru = Data::with('opd')->latest()->take(10)->get();
        $lastActivities = Activity::with('causer')->latest()->take(20)->get();
        if (!empty($selectedTahun)) {
            $daftardata = Data::where('tahun', $selectedTahun)->count();

            $dataPrioritas = Data::where('data_prioritas', 1)->where('tahun', $selectedTahun)->count();
            $dataStandarData = Data::whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->where('tahun', $selectedTahun)->count();

            $dataPengumpulan = Data::whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])->where('tahun', $selectedTahun)->count();
            $dataTelahLengkap = Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI])->where('tahun', $selectedTahun)->count();
            $dataRevisi = Data::whereIn('status_id', [Data::STATUS_REVISI])->where('tahun', $selectedTahun)->count();
            $dataSiapPublish = Data::where('status_id', '=', Data::STATUS_SIAP_PUBLIKASI)->where('tahun', $selectedTahun)->count();
            $dataTerpublikasi = Data::where('status_id', '=', Data::STATUS_TERPUBLIKASI)->where('tahun', $selectedTahun)->count();
            $dataTerbaru = Data::with('opd')->latest()->where('tahun', $selectedTahun)->take(10)->get();
            $lastActivities = Activity::with('causer')->whereYear('created_at', $selectedTahun)->latest()->take(20)->get();
        }
        $day = date("j");
        $month = date("n");
        $year = date("Y");
        $harian = Visitor::where('nama', 'pengunjung')->where('tgl', $day)->where('bln', $month)->where('thn', $year)->sum('jumlah');
        $bulanan = Visitor::where('nama', 'pengunjung')->where('bln', $month)->where('thn', $year)->sum('jumlah');
        $tahunan = Visitor::where('nama', 'pengunjung')->where('thn', $year)->sum('jumlah');
        $totalan = Visitor::where('nama', 'pengunjung')->sum('jumlah');
        // dd($harian);
        return view('pages.contents.walidata.dashboard', compact('dataStandarData', 'harian', 'bulanan', 'tahunan', 'totalan', 'dataRevisi', 'dataPrioritas', 'daftardata', 'selectedTahun', 'tahun', 'dataPengumpulan', 'dataTelahLengkap', 'dataSiapPublish', 'dataTerpublikasi', 'dataTerbaru', 'lastActivities'));
    }

    public function dashboardWalidata(Request $request)
    {
        $tahun = MasterTahun::where('is_active', 1)->get();
        $selectedTahun = $request->input('tahun', '');
        $daftardata = Data::count();
        $dataPrioritas = Data::where('data_prioritas', 1)->count();
        $dataStandarData = Data::whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->count();
        $dataPengumpulan = Data::where('status_id', [Data::STATUS_SETUJU_STANDART_DATA])->count();
        $dataRevisi = Data::whereIn('status_id', [Data::STATUS_REVISI])->count();

        $dataTelahLengkap = Data::where('status_id', [Data::STATUS_PROSES_VERIFIKASI])->count();
        $dataSiapPublish = Data::where('status_id',  [Data::STATUS_SIAP_PUBLIKASI])->count();
        $dataTerpublikasi = Data::where('status_id', [Data::STATUS_TERPUBLIKASI])->count();
        // dd($dataTerpublikasi);
        $dataTerbaru = Data::with('opd')->latest()->take(10)->get();
        $lastActivities = Activity::with('causer')->latest()->take(20)->get();
        if (!empty($selectedTahun)) {

            // Filter data berdasarkan tahun yang dipilih
            $daftardata = Data::where('tahun', $selectedTahun)->count();
            $dataRevisi = Data::whereIn('status_id', [Data::STATUS_REVISI])->where('tahun', $selectedTahun)->count();

            $dataPrioritas = Data::where('data_prioritas', 1)->where('tahun', $selectedTahun)->count();
            $dataStandarData = Data::whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->where('tahun', $selectedTahun)->count();

            $dataPengumpulan = Data::where('status_id', [Data::STATUS_SETUJU_STANDART_DATA])->where('tahun', $selectedTahun)->count();
            $dataTelahLengkap = Data::where('status_id', [Data::STATUS_PROSES_VERIFIKASI])->where('tahun', $selectedTahun)->count();
            $dataSiapPublish = Data::where('status_id', [Data::STATUS_SIAP_PUBLIKASI])->where('tahun', $selectedTahun)->count();
            $dataTerpublikasi = Data::where('status_id', [Data::STATUS_TERPUBLIKASI])->where('tahun', $selectedTahun)->count();
            $dataTerbaru = Data::with('opd')->where('tahun', $selectedTahun)->latest()->take(10)->get();
            $lastActivities = Activity::with('causer')->whereYear('created_at', $selectedTahun)->latest()->take(20)->get();
        }

        $day = date("j");
        $month = date("n");
        $year = date("Y");
        $harian = Visitor::where('nama', 'pengunjung')->where('tgl', $day)->where('bln', $month)->where('thn', $year)->sum('jumlah');
        $bulanan = Visitor::where('nama', 'pengunjung')->where('bln', $month)->where('thn', $year)->sum('jumlah');
        $tahunan = Visitor::where('nama', 'pengunjung')->where('thn', $year)->sum('jumlah');
        $totalan = Visitor::where('nama', 'pengunjung')->sum('jumlah');

        return view('pages.contents.walidata.dashboard', compact('dataStandarData', 'harian', 'bulanan', 'tahunan', 'totalan', 'dataRevisi', 'dataPrioritas', 'daftardata', 'dataPengumpulan', 'dataTelahLengkap', 'dataSiapPublish', 'dataTerpublikasi', 'dataTerbaru', 'lastActivities', 'tahun', 'selectedTahun'));
    }

    public function dashboardProdusen(Request $request)
    {
        //
        $tahun = MasterTahun::where('is_active', 1)->get();
        $selectedTahun = $request->input('tahun', '');
        $opdId = auth()->user()->opd_id;
        $daftardata = Data::where('opd_id', $opdId)->count();
        $dataRevisi = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_REVISI])->count();

        $data = Data::where('data_prioritas', 1)->where('opd_id', $opdId)->count();
        $dataStandarData = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->count();
        // dd($dataStandarData);
        $dataPengumpulan = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])->count();
        $dataTelahLengkap = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI])->count();
        $dataTidakLengkap = Data::where('opd_id', $opdId)->where('status_id', '>=', Data::STATUS_PROSES_PENGUMPULAN)->count();
        $dataSiapPublish = Data::where('opd_id', $opdId)->where('status_id', '=', Data::STATUS_SIAP_PUBLIKASI)->count();
        $dataTerpublikasi = Data::where('opd_id', $opdId)->where('status_id', '=', Data::STATUS_TERPUBLIKASI)->count();
        $dataTerbaru = Data::where('opd_id', $opdId)->with('opd', 'status')->latest()->take(10)->get();
        if (!empty($selectedTahun)) {
            $opdId = auth()->user()->opd_id;
            $dataRevisi = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_REVISI])->where('tahun', $selectedTahun)->count();

            $daftardata = Data::where('opd_id', $opdId)->where('tahun', $selectedTahun)->count();
            $data = Data::where('data_prioritas', 1)->where('opd_id', $opdId)->where('tahun', $selectedTahun)->count();
            $dataStandarData = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->where('tahun', $selectedTahun)->count();
            $dataPengumpulan = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_SETUJU_STANDART_DATA])->where('tahun', $selectedTahun)->count();
            $dataTelahLengkap = Data::where('opd_id', $opdId)->whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI])->where('tahun', $selectedTahun)->count();
            $dataTidakLengkap = Data::where('opd_id', $opdId)->where('status_id', '>=', Data::STATUS_PROSES_PENGUMPULAN)->where('tahun', $selectedTahun)->count();
            $dataSiapPublish = Data::where('opd_id', $opdId)->where('status_id', '=', Data::STATUS_SIAP_PUBLIKASI)->where('tahun', $selectedTahun)->count();
            $dataTerpublikasi = Data::where('opd_id', $opdId)->where('status_id', '=', Data::STATUS_TERPUBLIKASI)->where('tahun', $selectedTahun)->count();
            $dataTerbaru = Data::where('opd_id', $opdId)->with('opd', 'status')->where('tahun', $selectedTahun)->latest()->take(10)->get();
        }

        return view('pages.contents.produsen.dashboard', compact('dataStandarData', 'dataRevisi', 'dataPengumpulan', 'daftardata', 'tahun', 'selectedTahun',  'data', 'dataTelahLengkap', 'dataTidakLengkap', 'dataSiapPublish', 'dataTerpublikasi', 'dataTerbaru'));
    }


    public function dashboardRekapOPD(Request $request)
    {
        // $years = Data::selectRaw('DISTINCT tahun as years')->orderByDesc('years')->pluck('years');
        $years = MasterTahun::where('is_active', 1)->orderByDesc('tahun')->get();
        // dd($years);
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        // $opds = $opdsQuery->get();
        if (!empty($request->filled('opd_id'))) {
            $opdsQuery->when($request->filled('opd_id'), fn($q) => $q->where('id', $request->get('opd_id')));
        }

        $opds = $opdsQuery->get();

        $opdData = Data::query()->selectRaw('opds.nama_opd, status.status, data.opd_id, data.status_id, data.tahun, count(data.id) as total')
            ->join('opds', 'opds.id', '=', 'data.opd_id')
            ->join('status', 'status.id', '=', 'data.status_id')
            ->when($request->filled('y') && $request->get('y') !== '', fn($q) => $q->where('data.tahun', $request->get('y')), fn($q) => $q)
            // ->when($request->filled('y'), fn ($q) => $q->whereRaw(' data.tahun = ?', $request->get('y')), fn ($q) => $q->whereRaw(' data.tahun = ?', date('Y')))
            ->when($request->filled('opd_id'), fn($q) => $q->where('data.opd_id', $request->get('opd_id')))
            ->groupByRaw('opds.nama_opd, status.status, data.opd_id, data.status_id , data.tahun')
            ->orderByRaw('opds.nama_opd asc, status.status asc')
            ->get();
        // dd($opdData);
        // $dataQuery = Data::query()
        //     ->selectRaw('opds.nama_opd, status.status, data.opd_id, data.status_id, data.tahun, count(data.id) as total')
        //     ->join('opds', 'opds.id', '=', 'data.opd_id')
        //     ->join('status', 'status.id', '=', 'data.status_id')
        //     ->groupByRaw('opds.nama_opd, status.status, data.opd _id, data.status_id , data.tahun')
        //     ->orderByRaw('opds.nama_opd asc, status.status asc');

        // if (!empty($request->filled('y'))) {
        //     $dataQuery->when($request->filled('y'), fn ($q) => $q->whereRaw('data.tahun = ?', $request->get('y')), fn ($q) => $q->whereRaw('data.tahun = ?', date('Y')));
        // }

        // if (!empty($request->filled('opd_id'))) {
        //     $dataQuery->when($request->filled('opd_id'), fn ($q) => $q->where('data.opd_id', $request->get('opd_id')));
        // }

        // $opdData = $dataQuery->get();

        return view('pages.contents.walidata.rekap-opd', ['opdData' => $opdData, 'opds' => $opds, 'years' => $years]);
    }

    public function dashboardRekapOPDExcel(Request $request)
    {
        // dd($request->all());
        $year = $request->filled('y') ? $request->get('y') : date('Y');

        $opds = Opd::select('id', 'nama_opd')
            ->when($request->filled('opd_id'), fn($q) => $q->where('id', $request->get('opd_id')))
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI'])
            ->get();

        $dataQuery = Data::query()
            ->selectRaw('opds.nama_opd, status.status, data.opd_id, data.status_id, count(data.id) as total')
            ->join('opds', 'opds.id', '=', 'data.opd_id')
            ->join('status', 'status.id', '=', 'data.status_id')
            // ->when($request->filled('y'), fn ($q) => $q->whereRaw(' data.tahun = ?', $request->get('y')), fn ($q) => $q->whereRaw(' data.tahun = ?', date('Y')))
            ->when($request->filled('y'), fn($q) => $q->where('data.tahun', $year))
            ->when($request->filled('opd_id'), fn($q) => $q->where('data.opd_id', $request->get('opd_id')))
            ->groupByRaw('opds.nama_opd, status.status, data.opd_id, data.status_id')
            ->orderByRaw('opds.nama_opd asc, status.status asc');

        // if (!empty($request->filled('y'))) {
        //     $dataQuery->when($request->filled('y'), fn ($q) => $q->whereRaw('YEAR(data.created_at) = ?', $request->get('y')), fn ($q) => $q->whereRaw('YEAR(data.created_at) = ?', date('Y')));
        // }

        // if (!empty($request->filled('opd_id'))) {
        //     $dataQuery->when($request->filled('opd_id'), fn ($q) => $q->where('data.opd_id', $request->get('opd_id')));
        // }

        $opdData = $dataQuery->get();
        // dd($opdData);

        return Excel::download(new RekapOPDExport($opdData, $opds), 'Rekap-OPD-' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function account()
    {
        return view('pages.contents.account', ['user' => auth()->user()]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:2',
            'password' => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal!', join("\n", $validator->getMessageBag()->all()));
            return redirect()->back();
        }

        if (!password_verify($request->get('old_password'), auth()->user()->password)) {
            Alert::error('Gagal!', 'Password Anda yang sekarang tidak valid');
            return redirect()->back();
        }

        auth()->user()->update([
            'password' => app('hash')->make($request->get('password'))
        ]);

        Alert::success('Berhasil', 'Password Anda berhasil diubah');
        return redirect()->back();
    }
}
