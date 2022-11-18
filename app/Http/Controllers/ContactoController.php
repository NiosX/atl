<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactoFormRequest;
use App\Models\Contacto;
use App\Models\TelefonoContacto;
use Exception;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactos = Contacto::all()->toArray();
        return json_encode($contactos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactoFormRequest $request)
    {
        try {
            $contacto = Contacto::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response(['message' => 'Contacto Agregado!',
                        'contacto' => $contacto], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function show(Contacto $contacto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function edit(Contacto $contacto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contacto $contacto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $contacto = Contacto::findOrFail($id);
            $contacto->delete();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
        
        return json_encode(['Contacto Eliminado '].$id);

    }

    public function add_phone(Request $request, $id)
    {
        $validated = $request->validate([
            'tipo_telefono' => 'required',
            'telefono' => 'required'
        ], 
        [
            'tipo_telefono.required' => "El tipo de telefono es requerido, ej. 'Casa, Celular'",
            'telefono.required' => 'El número de teléfono es requerido'
        ]);

        $contacto = Contacto::where('id', $id)->first();
        if ($contacto) {
                
            $tc = new TelefonoContacto();
            
            $tc->contacto_id = $contacto->getKey();
            $tc->tipo = $request->tipo_telefono;
            $tc->telefono = $request->telefono;

            try {
                $tc->save();
                return response(['message' => 'Teléfono Agregado!',
                                'telefono' => $tc], 201);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        else
        {
            return response()->json(['error' => 'No existe ese contacto'], 404);
        }
    }
}
