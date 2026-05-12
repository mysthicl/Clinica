<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Consult;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $payments = Payment::with(['consult.patient'])
        ->when($status, fn($q) => $q->where('status', $status))
        ->when($search, function ($q) use ($search){
            $q->whereHas('consult.patient', function ($q2) use ($search){
                $q2->where('name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('dui', 'like', "%{$search}%");
            });
        })->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('secretaria.payments.index', compact('payments', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Consult $consult)
    {
        // Solo consultas cerradas pueden recibir pagos
        if($consult->status !== 'Cerrada'){
            return redirect()->with('error', 'Sole se puede registrar el pago de consultas cerradas');
        }

        // Evitar doble pago
        if($consult->payments()->where('status', '!=', 'Anulado')->exists()){
            return back()->with('error', 'Esta consulta ya tiene un pago registrado');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        Payment::create([
            'id_consult' => $consult->id_consult,
            'amount' => $request->amount,
            'status' => 'Pagado',
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('shared.consults.show', $consult->id_consult)->with('success', 'Pago registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function void(Payment $payment)
    {
        if($payment->status === 'Anulado'){
            return back()->with('error', 'Este pago ya esta anulado');
        }

        $payment->update(['status' => 'Anulado']);

        return back()->with('success', 'Pago anulado correctamente');
    }
}
