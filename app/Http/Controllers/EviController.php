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
            $query->whereDate('tgl', '>=', $request->from);
        }
        if ($request->has('to') && $request->to) {
            $query->whereDate('tgl', '<=', $request->to);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'topup' => 'nullable|integer',
            'jam' => 'nullable',
            'kodeBooking' => 'nullable|string|max:45',
            'airlines' => 'nullable|string|max:45',
            'nama' => 'nullable|string|max:45',
            'rute1' => 'nullable|string|max:45',
            'tglFlight1' => 'nullable|date',
            'rute2' => 'nullable|string|max:45',
            'tglFlight2' => 'nullable|date',
            'harga' => 'nullable|integer',
            'nta' => 'nullable|integer',
            'keterangan' => 'nullable|string|max:300',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $last = Evi::orderBy('id', 'desc')->first();
        $lastSaldo = $last ? (int)$last->saldo : 0;

        $topUp = $request->input('topup') ? (int)$request->input('topup') : 0;

        $newSaldo = $lastSaldo + $topUp;

        $data = $request->only([
            'tgl', 'jam', 'kodeBooking', 'airlines', 'nama', 'rute1', 'tglFlight1',
            'rute2', 'tglFlight2', 'harga', 'nta', 'keterangan'
        ]);

        $data['topup'] = $topUp;
        $data['saldo'] = $newSaldo;
        $data['username'] = auth()->user()->username ?? auth()->user()->name;

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
                'tgl','jam','kodeBooking','airlines','nama','rute1','tglFlight1',
                'rute2','tglFlight2','harga','nta','topup','saldo','keterangan','username'
            ]);

            $query = Evi::query()->orderBy('id','desc');
            if ($request->has('from') && $request->from) {
                $query->whereDate('tgl','>=',$request->from);
            }
            if ($request->has('to') && $request->to) {
                $query->whereDate('tgl','<=',$request->to);
            }

            $query->chunk(500, function($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->tgl,
                        $r->jam,
                        $r->kodeBooking,
                        $r->airlines,
                        $r->nama,
                        $r->rute1,
                        $r->tglFlight1,
                        $r->rute2,
                        $r->tglFlight2,
                        $r->harga,
                        $r->nta,
                        $r->topup,
                        $r->saldo,
                        $r->keterangan,
                        $r->username,
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
