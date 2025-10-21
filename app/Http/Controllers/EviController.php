<?php

namespace App\Http\Controllers;

use App\Models\Evi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EviController extends Controller
{
    public function index()
    {
        $data = Evi::all(); 
        return view('evi', compact('data'));
    }

    public function fetch(Request $request)
    {
        $query = Evi::query()->orderBy('id', 'desc');

        if ($request->has('from') && $request->from) {
            $query->whereDate('TGL_ISSUED', '>=', $request->from);
        }
        if ($request->has('to') && $request->to) {
            $query->whereDate('TGL_ISSUED', '<=', $request->to);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'TGL_ISSUED' => 'required|date',
            'TOP_UP' => 'nullable|numeric',
            'JAM' => 'nullable|string',
            'KODEBOKING' => 'nullable|string',
            'AIRLINES' => 'nullable|string',
            'NAMA' => 'nullable|string',
            'RUTE1' => 'nullable|string',
            'TGL_FLIGHT1' => 'nullable|date',
            'RUTE2' => 'nullable|string',
            'TGL_FLIGHT2' => 'nullable|date',
            'HARGA' => 'nullable|numeric',
            'NTA' => 'nullable|numeric',
            'KETERANGAN' => 'nullable|string',
            'USR' => 'nullable|string',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $last = Evi::orderBy('id', 'desc')->first();
        $lastSaldo = $last ? floatval($last->SALDO) : 0.0;

        $topUp = $request->input('TOP_UP') ? floatval($request->input('TOP_UP')) : 0.0;

        $newSaldo = $lastSaldo + $topUp;

        $data = $request->only([
            'TGL_ISSUED','JAM','KODEBOKING','AIRLINES','NAMA','RUTE1','TGL_FLIGHT1',
            'RUTE2','TGL_FLIGHT2','HARGA','NTA','TOP_UP','KETERANGAN','USR'
        ]);

        $data['TOP_UP'] = $topUp;
        $data['SALDO'] = $newSaldo;

        $evi = Evi::create($data);

        return response()->json(['message' => 'EVI saved', 'data' => $evi]);
    }

    public function destroy($id)
    {
        $evi = Evi::find($id);
        if (!$evi) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $evi->delete();

        return response()->json(['message' => 'Data deleted']);
    }

    public function exportCsv(Request $request)
    {
        $fileName = 'evi_export_'.date('Ymd_His').'.csv';
        $response = new StreamedResponse(function() use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'TGL_ISSUED','JAM','KODEBOKING','AIRLINES','NAMA','RUTE1','TGL_FLIGHT1',
                'RUTE2','TGL_FLIGHT2','HARGA','NTA','TOP_UP','SALDO','KETERANGAN','USR'
            ]);

            $query = Evi::query()->orderBy('id','desc');
            if ($request->has('from') && $request->from) {
                $query->whereDate('TGL_ISSUED','>=',$request->from);
            }
            if ($request->has('to') && $request->to) {
                $query->whereDate('TGL_ISSUED','<=',$request->to);
            }

            $query->chunk(500, function($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->TGL_ISSUED,
                        $r->JAM,
                        $r->KODEBOKING,
                        $r->AIRLINES,
                        $r->NAMA,
                        $r->RUTE1,
                        $r->TGL_FLIGHT1,
                        $r->RUTE2,
                        $r->TGL_FLIGHT2,
                        $r->HARGA,
                        $r->NTA,
                        $r->TOP_UP,
                        $r->SALDO,
                        $r->KETERANGAN,
                        $r->USR,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }
}
