<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Bien;
use Illuminate\Http\Request;

class BienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $biens = Bien::where('statut', 'accepte')->get();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Liste des biens récupérée avec succès',
                'biens' => $biens,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 500,
                'status_message' => 'Erreur lors de la récupération des biens',
            ]);
        }
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

    public function store(Request $request)
    {
        try {
            $bien = new Bien();
            
            $bien->libelle = $request->libelle;
            $bien->description = $request->description;
            $bien->date = $request->date;
            $bien->lieu = $request->lieu;
            $bien->image = $this->storeImage($request->image);
            $bien->user_id = auth()->user()->id;
            $bien->save();
    
            return response()->json([
                'status_code' => 201,
                'status_message' => 'Bien trouvé ajouté avec succès',
                'bien' => $bien,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 500,
                'status_message' => 'Erreur lors de l\'ajout du bien',
            ]);
        }
    }
    
    private function storeImage($image)
    {
        return $image->store('imageBien', 'public');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bien $bien)
    {
        try {

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Bien récupéré avec succès',
                'bien' => $bien,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 404, // Not Found
                'status_message' => 'Bien non trouvé',
            ]);
        }
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
    public function update(Request $request, Bien $bien)
    {   
        // try {
            
            $bien->libelle = $request->libelle;
            $bien->description = $request->description;
            $bien->date = $request->date;
            $bien->lieu = $request->lieu;
            if ($request->hasFile("image")) {
                $bien->image = $this->storeImage($request->image);
            }           
             $bien->save();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Bien mis à jour avec succès',
                'bien' => $bien,
            ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'error' => $e->getMessage(),
        //         'status_code' => 404, // Not Found
        //         'status_message' => 'Bien non trouvé ou erreur lors de la mise à jour',
        //     ]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bien $bien)
    {
        try {

            $bien->delete();

            return response()->json([
                'status_code' => 204,
                'status_message' => 'Bien supprimé avec succès',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 404, // Not Found
                'status_message' => 'Bien non trouvé ou erreur lors de la suppression',
            ]);
        }
    }

    public function acceptBien(Bien $bien){
        $bien->statut = 'accepte';
        $bien->update();
        return response()->json([
            'status code'=>200,
            'status message'=>"Le bien a été validé",
        ]);
    }

    public function refuseBien(Bien $bien){
        $bien->statut = 'refuse';
        $bien->update();
        return response()->json([
            'status code'=>200,
            'status message'=>"Le bien a été refusé",
        ]);
    }
}
