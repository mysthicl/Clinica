<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Consult;
use App\Models\Patient;
use App\Models\User;

class ConsultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $consults = Consult::with(['patient', 'user', 'appointment'])->when($search, function($q) use ($search){
            $q->whereHas('patient', function($q2) use ($search){
                $q2->where('name', 'like', "{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('dui', 'like', "%{$search}%");
            });
        })->when($status, fn($q) => $q->where('status', $status))->orderBy('date_register', 'desc')->paginate(10)->withQueryString();

        return view('shared.consults.index', compact('consults', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Si viene desde una cita, prellenamos datos
        $appointment = null;
        if($request->has('id_appointment')){
            $appointment = Appointment::with('patient')->where('id_appointment', $request->id_appointment)->where('status', 'Agendada')->firstOrFail();
        }

        $patients = Patient::orderBy('last_name')->get();
        $doctors = User::whereHas('role', fn($q) => $q->where('rol', 'Doctor'))->where('active', true)->orderBy('name')->get();

        return view('shared.consults.create', compact('patients', 'doctors', 'appointment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_patient'     => 'required|exists:patients,id_patient',
            'id_user'        => 'required|exists:users,id_user',
            'date_register'  => 'required|date',
            'notes'          => 'nullable|string|max:150',
            'id_appointment' => 'nullable|exists:appointments,id_appointment',
        ]);

        $consult = Consult::create([
            'id_patient'     => $request->id_patient,
            'id_user'        => $request->id_user,
            'date_register'  => $request->date_register,
            'total'          => 0,
            'status'         => 'Abierta',
            'notes'          => $request->notes,
            'id_appointment' => $request->id_appointment ?: null,
        ]);

        // Marcar la cita como Completada si viene de una
        if ($request->id_appointment) {
            Appointment::where('id_appointment', $request->id_appointment)
                ->update(['status' => 'Completada']);
        }

        return redirect()->route('shared.consults.show', $consult->id_consult)
            ->with('success', 'Consulta abierta correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consult $consult)
    {
        $consult->load(['patient', 'user', 'appointment', 'services.service', 'payments']);
        return view('shared.consults.show', compact('consult'));
    }

    public function close(Consult $consult)
    {
        if($consult->status !== 'Abierta'){
            return back()->with('error', 'Solo se pueden cerrar consultas Abiertas.');
        }

        if($consult->services()->count() === 0){
            return back()->with('error', 'No se puede cerrar una consulta sin servicios registrados.');
        }

        $consult->update(['status' => 'Cerrada']);

        return back()->with('success', 'Consulta cerrada correctamente.');
    }


    public function cancel(Consult $consult)
    {
        if($consult->status !== 'Abierta'){
            return back()->with('error', 'Solo se pueden cancelar consultas Abiertas.');
        }

        $consult->update(['status' => 'Cancelada']);

        return back()->with('success', 'Consulta cancelada');
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
}
