<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;

class QRController extends Controller
{
    //
    public function storeResult(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
        ]);

        session(['table_number' => $request->table_number]);

        return response()->json(['status' => 'success']);
    }

    public function checkCode($code)
    {
        if (preg_match('/^[a-zA-Z]\d{4}$/', $code)) {
            $exsist = Barcode::where('table_number', $code)->exists();

            if ($exsist) {
                session(['table_number' => $code]);
                return view('home', ['message' => 'welcome to mejaorder']);
            } else {
                return view('invalid', ['message' => 'Invalid QR Code']);
            }
        }
    }
}
