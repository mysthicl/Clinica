<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input("date");
        $status = $request->input("status");

        $appointment = Appointment::with(['patient', 'user'])
        ->when($date, fn($q) => $q->where('scheduled_at', $date))
        ->when($status, fn($q) => $q->where('status', $status))
        ->orderBy('scheduled_at', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('secretaria.appointment.index', compact('appointments', 'date', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        $doctors = User::whereHas('role', fn($q) => $q->where('rol', 'Doctor'))
        ->where('active', true)
        ->orderBy('name')
        ->get();

        return view('secretaria.appointments.create', compact('patients', 'doctors'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_patient' => 'required|exists:patients,id_patient',
            'id_user' => 'required|exists:users,id_user',
            'scheduled_at' => 'requried|date|after_or_equal:today',
            'notes' => 'nullable|string|max:150'
        ], [
            'scheduled_at.after_or_equal' => 'No se puede agendar una cita en una fecha pasada.'
        ]);

        Appointment::create([
            'id_patient' => $request->id_patient,
            'id_user' => $request->id_user,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'Agendada',
            'notes' => $request->notes,
        ]);

        return redirect('secretaria.appointments.index')->with('success', 'Cita agendada correctamente');
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
    public function edit(Appointment $appointment)
    {
        // Citas canceladas o completadas no se podran editar
        if($appointment->status !== 'Agendada'){
            return back()->with('error', 'Solo se pueden editar citar con estado Agendada');
        }

        $patients = Patient::orderBy('last_name')->get();
        $doctors = User::whereHas('role', fn($q)=> $q->where('rol', 'Doctor'))
        ->where('active', true)
        ->orderBy('name')
        ->get();

        return view('secretaria.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if($appointment->status !== 'Agendada'){
            return back()->with('error', 'Solo se pueden editar citar con estado agendada.');
        }

        $request->validate([
            'id_patient' => 'required|exists:patients,id_patient',
            'id_user' => 'required|exists:users,id_user',
            'scheduled_at' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:150',
        ],[
            'scheduled_at.after_or_equal' => 'No se puede agendar una cita en una fecha pasada',
        ]);

        $appointment->update([
            'id_patient' => $request->id_patient,
            'id_user' => $request->id_user,
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
        ]);

        return redirect()->route('secretaria.appointments.index')->with('success', 'Cita actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cancel(Appointment $appointment){
        if($appointment->status !== 'Agendada'){
            return back()->with('error', 'Solo se puede cancelar citas con estado Agendada');
        }

        $appointment->update([
            'status' => 'Cancelada'
        ]);
        
        return back()->with('success', 'Cita cancelada correctamente');
    }
}
