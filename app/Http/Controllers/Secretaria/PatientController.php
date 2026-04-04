<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::when($search, function($query, $search){
            $query->where(function ($q) use ($search){
                $q->where('name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('dui', 'like', "%{$search}%");
            });
        })->orderBy('last_name')->paginate(10)->withQueryString();

        return view('secretaria.patients.index', compact('patients', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('secretaria.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'dui' => 'required|string|max:20|unique:patients,dui',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:100',
            'birthdate' => 'required|date|before:today',
        ],[
            'dui.unique' => 'El número de DUI ya está registrado para otro paciente.',
            'birthdate.before' => 'La fecha de nacimiento debe ser anterior a la fecha actual.',
        ]);

        Patient::create($request->only(['name', 'last_name', 'dui', 'phone', 'address', 'birthdate']));

        return redirect()->route('secretaria.patients.index')->with('success', 'Paciente registrado correctamente.');
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
    public function edit(Patient $patient)
    {
        return view('secretaria.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'dui' => 'required|string|max:20|unique:patients,dui,' . $patient->id_patient . ',id_patient',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:100',
            'birthdate' => 'required|date|before:today',
        ],[
            'dui.unique'       => 'Ya existe un paciente registrado con ese DUI.',
            'birthdate.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ]);

        $patient->update($request->only(['name', 'last_name', 'dui', 'phone', 'address', 'birthdate']));

        return redirect()->route('secretaria.patients.index')->with('success', 'Paciente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // Verificar que no tenga citas o consultas antes de eliminar
        if($patient->consults()->exists() || $patient->appointments()->exists()){
            return back()->with('error', 'No se puede eliminar el paciente porque tiene citas o consultas asociadas.');
        }

        $patient->delete();

        return redirect()->route('secretaria.patients.index')->with('success', 'Paciente eliminado correctamente.');
    }
}
