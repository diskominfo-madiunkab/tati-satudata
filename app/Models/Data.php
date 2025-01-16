<?php

namespace App\Models;

use Dompdf\Image\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Data extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    const STATUS_DRAFT = 3;
    const STATUS_SETUJU = 1;
    const STATUS_TOLAK = 2;
    const STATUS_PENGAJUAN_STANDART_DATA = 13;
    const STATUS_TOLAK_STANDART_DATA = 11;
    const STATUS_REVISI_STANDART_DATA = 12;
    const STATUS_SETUJU_STANDART_DATA = 10;
    const STATUS_PROSES_PENGUMPULAN = 4;
    const STATUS_LENGKAP = 5;
    const STATUS_PROSES_VERIFIKASI = 6;
    const STATUS_REVISI = 7;
    const STATUS_SIAP_PUBLIKASI = 8;
    const STATUS_TERPUBLIKASI = 9;


    protected $table = 'data';
    protected $fillable = [
        'id',
        'nama_data',
        'opd_id',
        'jenis_data',
        'sumber_data',
        'status_id',
        'user_id',
        'alasan',
        'progress',
        'tahun',
        'jadwal_rilis',
        'jadwal_pemutakhiran',
        'data_prioritas',
        'kodeindikator',
        'value_sipd',
    ];

    protected $guarded = [];
    public $timestamps = true;

    public function data()
    {
        return Data::select('*')
            ->get();
    }

    public function boxValues()
    {
        return $this->hasMany(BoxValue::class, 'data_id');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }

    public function visual()
    {
        return $this->hasMany(VisualData::class, 'id_data', 'id');
    }

    public function visualtable()
    {
        return $this->hasMany(VisualTable::class, 'id_data', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function ActivityLog()
    {
        return $this->belongsTo(ActivityLog::class);
    }

    public function standar(): HasOne
    {
        return $this->hasOne(StandarData::class);
    }

    public function meta()
    {
        return $this->hasMany(strtolower($this->jenis_data) == 'Indikator' ? MetadataIndikator::class : MetadataVariabel::class, 'data_id');
    }

    public function indikator()
    {
        return $this->hasOne(MetadataIndikator::class);
    }

    public function variabel()
    {
        return $this->hasOne(MetadataVariabel::class);
    }

    public function kegiatan()
    {
        return $this->hasOne(MetadataKegiatan::class);
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(Berkas::class);
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class);
    }

    public function publikasi()
    {
        return $this->hasOne(Publikasi::class);
    }

    public function calculateProgress(): int
    {
        // dd($this->progress);
        $progress = $this->progress ?? 0;

        if ($progress >= 100) {
            return min(100, $progress);
        }

        if (!empty($this->standar)) {
            $progress += 15;
        }

        if (!blank($this->indikator) && blank($this->variabel)) {
            $progress += 25;
        }

        if (blank($this->indikator) && !blank($this->variabel)) {
            $progress += 25;
        }

        if ($this->berkas->isNotEmpty()) {
            $progress += 50;
        }

        return min(100, $progress);
    }

    public static function calculateProgressNew($progress, $standar, $indikator, $variabel, $berkas)
    {
        // dd($this->progress);
        $progress = !empty($progress) ? $progress : 0;

        if ($progress >= 100) {
            return min(100, $progress);
        }

        if (!empty($standar)) {
            dd($progress);
            $progress += 15;
        }

        if (!empty($indikator) && empty($variabel)) {
            $progress += 25;
        }

        if (empty($indikator) && !empty($variabel)) {
            $progress += 25;
        }

        if (!empty($berkas)) {
            $progress += 50;
        }

        return min(100, $progress);
    }

    public static function data_nonprodusen()
    {
        $year = date('Y');
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id")
            ->where('tahun', '=', $year)
            ->get();
    }

    public static function data_nonprodusen_tahun($tahun, $opd)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id");
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun);
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd);
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd);
        }
        return $data->get();
    }


    public static function data_draft_walidata()
    {
        $year = date('Y');
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("data_prioritas", "tahun", "nama_opd", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', '=', Data::STATUS_DRAFT)
            ->where('tahun', '=', $year)
            ->get();
    }

    public static function data_draft_walidata_tahun($tahun, $opd)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', '=', Data::STATUS_DRAFT);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun);
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd);
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd);
        }
        return $data->get();
    }

    public static function data_draft_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', '=', Data::STATUS_DRAFT);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_setuju_walidata_tahun($tahun, $opd)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            // ->where('status_id', '=', Data::STATUS_SETUJU);
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK]);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun);
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd);
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd);
        }
        // dd($data->get());
        return $data->get();
    }

    public static function data_setuju_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            // ->where('status_id', '=', Data::STATUS_SETUJU);
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK]);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_tolak_walidata_tahun($tahun, $opd)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', '=', Data::STATUS_TOLAK);
        // ->where('opds.id', $opd);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun);
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd);
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd);
        }
        return $data->get();
    }

    public static function data_tolak_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', '=', Data::STATUS_TOLAK);
        // ->where('opds.id', $opd);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }


    public static function data_tolak_walidata()
    {
        $year = date('Y');
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("data_prioritas", "tahun", "nama_opd", "nama_data", "jenis_data", "progress", "sumber_data", "status_id", "status", "alasan", "name", "user_id", "opds.id", "data.id")
            ->where('status_id', '=', Data::STATUS_TOLAK)
            ->where('tahun', '=', $year)
            ->get();
    }


    public static function selesai_konfirmasi_walidata()
    {
        $year = date('Y');
        // $year = '2022';
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("data_prioritas", "tahun", "nama_opd", "nama_data", "progress", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('tahun', '=', $year)

            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK])
            ->get();
    }


    public static function data_produsen()
    {
        // return Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $year = date('Y');
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', Data::STATUS_DRAFT)
            ->where('opds.id', '=', Auth::user()->opd_id)
            ->where('tahun', '=', $year)
            ->get();
    }

    public static function data_produsen_tahun($tahun)
    {
        // return Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', Data::STATUS_DRAFT)
            ->where('opds.id', '=', Auth::user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun);
        }
        return $data->get();
    }

    public static function data_setuju_produsen_tahun($tahun)
    {
        // $data = Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK])
            ->where('opds.id', '=', Auth::user()->opd_id);
        // dd($tahun);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun);
        }
        return $data->get();
    }

    public static function data_tolak_produsen_tahun($tahun)
    {
        // $data = Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', Data::STATUS_TOLAK)
            ->where('opds.id', '=', Auth::user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun);
        }
        return $data->get();
    }


    public static function selesai_konfirmasi()
    {
        $year = date('Y');
        // $year = '200';
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('opds.id', '=', auth()->user()->opd_id)
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK])
            ->where('tahun', $year)
            ->get();
    }


    public static function tolak_konfirmasi()
    {
        $year = date('Y');
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "alasan", "user_id", "opds.id", "data.id", "data_prioritas")
            ->where('status_id', '=', Data::STATUS_TOLAK)
            ->where('opds.id', '=', Auth::user()->opd_id)
            ->where('tahun', $year)
            ->get();
    }


    public static function verifikasi_data()
    {
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->select("data.id", "nama_opd", "nama_data", "jenis_data", "sumber_data", "status_id", "status")
            ->where("status_id", "=", 1)
            ->get();
    }

    public static function causer_id($subject = null)
    {
        return DB::table("users")
            ->join("activity_log", function ($join) {
                $join->on("users.id", "=", "activity_log.causer_id");
            })
            ->join("data", function ($join) {
                $join->on("data.id", "=", "activity_log.subject_id");
            })
            ->when(is_numeric($subject), fn($q) => $q->where('subject_id', $subject))
            ->select("users.name", "activity_log.description", "activity_log.created_at", "subject_id", "data.nama_data")
            ->orderby("activity_log.created_at", "DESC")
            ->get();
    }


    public function verifikasi_opd()
    {
        return DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->select("data.id", "nama_opd", "nama_data", "jenis_data", "sumber_data", "status_id", "status")
            ->where("status_id", "=", 1)
            ->where('opd_id', '=', Auth::user()->opd_id)
            ->get();
    }


    public static function get_draft()
    {
        return Data::where('opd_id', '=', Auth::user()->opd_id)->where('status_id', '=', 3)->get();
        // return Data::select(opd_id)
    }

    public function data_produsen_setuju()
    {
        return Data::where('opd_id', '=', Auth::user()->opd_id)->whereNotIn('status_id', [Data::STATUS_TOLAK, Data::STATUS_DRAFT])->orderBy('created_at')->get();
    }


    public function data_produsen_setuju_all()
    {
        return Data::where('status_id', '=', 1)->get();
    }

    public function data_produsen_setuju_opd()
    {
        return Data::where('status_id', '=', 1)->get();
        // where('opd_id', '=', $id)->
    }

    public function scopeSetuju($query)
    {
        return $query->where('status_id', '=', 1);
    }

    public function scopePrioritas($query)
    {
        return $query->whereNotIn('status_id', [Data::STATUS_TOLAK, Data::STATUS_DRAFT])->orderBy('created_at');
    }

    public function scopeOPD($query, $id)
    {
        return $query->where('opd_id', '=', $id);
    }

    public static function data_pengumpulan_walidata_search($tahun, $opd, $searchQuery)
    {
        // $data = DB::table("data")
        //     ->join("opds", function ($join) {
        //         $join->on("data.opd_id", "=", "opds.id");
        //     })
        //     ->join("status", function ($join) {
        //         $join->on("data.status_id", "=", "status.id");
        //     })
        //     ->join("users", function ($join) {
        //         $join->on("data.user_id", "=", "users.id");
        //     })
        //     ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id",)
        //     ->whereIn('status_id', '=', Data::STATUS_SETUJU);
        $data = Data::whereIn('status_id', [Data::STATUS_SETUJU])
            // ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        // ->latest();
        // ->where('opds.id', $opd);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_pengumpulan_lengkap_walidata_search($tahun, $opd, $searchQuery)
    {
        // $data = DB::table("data")
        //     ->join("opds", function ($join) {
        //         $join->on("data.opd_id", "=", "opds.id");
        //     })
        //     ->join("status", function ($join) {
        //         $join->on("data.status_id", "=", "status.id");
        //     })
        //     ->join("users", function ($join) {
        //         $join->on("data.user_id", "=", "users.id");
        //     })
        //     ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id",)
        //     ->whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI]);
        $data = Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            // ->when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        // ->latest();
        // ->where('opds.id', $opd);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_verif_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = Data::where('status_id', Data::STATUS_PROSES_VERIFIKASI)->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_revisi_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = Data::where('status_id', Data::STATUS_REVISI)
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_siap_publikasi_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);

        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_publikasi_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = Data::where('status_id', Data::STATUS_SIAP_PUBLIKASI)->with(['opd', 'status', 'berkas', 'publikasi', 'indikator', 'variabel', 'standar', 'kegiatan']);

        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_terpublikasi_walidata_search($tahun, $opd, $searchQuery)
    {
        $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)
            ->with(['opd', 'berkas', 'status', 'indikator', 'publikasi', 'variabel', 'standar', 'kegiatan']);

        if (!empty($tahun) && empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && !empty($opd)) {
            $data = $data->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (!empty($tahun) && !empty($opd)) {
            $data = $data->where('tahun', $tahun)->where('opds.id', $opd)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } elseif (empty($tahun) && empty($opd)) {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_draft_produsen_search($tahun, $searchQuery)
    {
        // return Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id")
            ->where('status_id', Data::STATUS_DRAFT)
            ->where('opds.id', '=', Auth::user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }


    public static function data_setuju_produsen_search($tahun, $searchQuery)
    {
        // $data = Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id")
            ->whereNotIn('status_id', [Data::STATUS_DRAFT, Data::STATUS_TOLAK])
            ->where('opds.id', '=', Auth::user()->opd_id);
        // dd($tahun);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_tolak_produsen_search($tahun, $searchQuery)
    {
        // $data = Data::where('opd_id', '=', Auth::user()->opd_id)->get();
        $data = DB::table("data")
            ->join("opds", function ($join) {
                $join->on("data.opd_id", "=", "opds.id");
            })
            ->join("status", function ($join) {
                $join->on("data.status_id", "=", "status.id");
            })
            ->join("users", function ($join) {
                $join->on("data.user_id", "=", "users.id");
            })
            ->select("nama_opd", "tahun", "nama_data", "jenis_data", "sumber_data", "status_id", "status", "name", "user_id", "opds.id", "data.id")
            ->where('status_id', Data::STATUS_TOLAK)
            ->where('opds.id', '=', Auth::user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_pengumpulan_produsen_search($tahun, $searchQuery)
    {
        $data = Data::whereIn('status_id', [Data::STATUS_SETUJU])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_pengumpulan_lengkap_produsen_search($tahun, $searchQuery)
    {
        $data = Data::whereIn('status_id', [Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_REVISI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan']);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_verif_produsen_search($tahun, $searchQuery)
    {
        $data = Data::where('status_id', Data::STATUS_PROSES_VERIFIKASI)
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('opd_id', auth()->user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_revisi_produsen_search($tahun, $searchQuery)
    {
        $data = Data::where('status_id', Data::STATUS_REVISI)
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('opd_id', auth()->user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }

    public static function data_siap_publikasi_produsen_search($tahun, $searchQuery)
    {
        $data = Data::whereIn('status_id', [Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])
            ->with(['opd', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan'])
            ->where('opd_id', auth()->user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }
    public static function data_publikasi_produsen_search($tahun, $searchQuery)
    {
        $data =
            Data::where('status_id', Data::STATUS_SIAP_PUBLIKASI)
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'publikasi'])
            ->where('opd_id', auth()->user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }
    public static function data_terpublikasi_produsen_search($tahun, $searchQuery)
    {
        $data =
            Data::where('status_id', Data::STATUS_TERPUBLIKASI)
            ->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))
            ->with(['opd', 'status', 'publikasi'])
            ->where('opd_id', auth()->user()->opd_id);
        if (!empty($tahun)) {
            $data = $data->where('tahun', $tahun)->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        } else {
            $data = $data->where('nama_data', 'LIKE', '%' . $searchQuery . '%');
        }
        return $data->get();
    }
}
