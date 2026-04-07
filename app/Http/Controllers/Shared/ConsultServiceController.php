<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consult;
use App\Models\ConsultService;
use App\Models\Service;

class ConsultServiceController extends Controller
{
   public function store(Request $request, Consult $consult)
   {
    if($consult->status !== 'Abierta'){
        return back()->with('error', 'No se pueden agregar servicios a una consulta que cerrada o cancelada.');
    }

    $request->validate([
        'id_service' => 'required|exists:services,id_service',
        'discount' => 'nullable|numeric|min:0'
    ]);

    $service = Service::findOrFail($request->id_service);
    $discount = $request->discount ?? 0;
    $final = max(0, $service->price - $discount);

    ConsultService::create([
        'id_consult' => $consult->id_consult,
        'id_service' => $service->id_service,
        'price' => $service->price,
        'discount' => $discount,
        'final_price' => $final
    ]);

    // Recalcular total de la consulta
        $this->recalcularTotal($consult);

        return back()->with('success', 'Servicio agregado correctamente.');
    }

    public function destroy(Consult $consult, ConsultService $consultService)
    {
        if ($consult->status !== 'Abierta') {
            return back()->with('error', 'No se pueden eliminar servicios de una consulta cerrada o cancelada.');
        }

        $consultService->delete();

        // Recalcular total de la consulta
        $this->recalcularTotal($consult);

        return back()->with('success', 'Servicio eliminado.');
    }

    private function recalcularTotal(Consult $consult): void
    {
        $total = $consult->services()->sum('final_price');
        $consult->update(['total' => $total]);
    }
}
