<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\CkanApi\Facades\CkanApi;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $data = CkanApi::tag()->all(['limit' => 100]);
        $result = $data['result'];
        // $data = CkanApi::dataset()->all(['start' => 100]);
        // dd($data);
        return view('pages.contents.administrator.kelolatag.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.kelolatag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tagData = [
            [
                'name' => $request->display_name,
                'vocabulary_id' => null,
            ],
        ];

        // URL API CKAN
        $apiUrl = config('ckan_api.container') . '/api/3/action/tag_create';

        // API Key CKAN (ganti dengan API key yang sesuai)
        $apiKey = config('ckan_api.api_key');

        // Buat instance Guzzle client
        $client = new Client();

        try {
            // Kirim permintaan POST ke API CKAN
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Authorization' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $tagData,
                'verify' => false, // Abaikan verifikasi sertifikat SSL
            ]);

            // Ambil respons dari API
            $responseBody = json_decode($response->getBody(), true);

            // Periksa status respons
            if ($responseBody['success']) {
                // Tag berhasil dibuat di CKAN
                return $responseBody['result'];
            } else {
                // Gagal membuat tag di CKAN
                return $responseBody['error'];
            }
        } catch (\Exception $e) {
            // Tangani kesalahan saat mengirim permintaan
            return ['error' => $e->getMessage()];
        }
        // if (!$data['success']) {
        //     return back()->withErrors(['message' => 'Error creating tag: ' . $data['error']['__type']])->withInput();
        // }

        return redirect()->route('tag.index')->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
