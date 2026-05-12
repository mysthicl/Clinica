<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class HistoryController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load([
            'consults' => function($q) {
                $q->with(['user', 'services.service', 'payments'])->orderBy('date_register', 'desc');
            }
        ]);
        return view('shared.history.show', compact('patient'));
    }
}
