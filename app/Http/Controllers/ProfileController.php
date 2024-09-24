<?php 

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

  public function index()
  {
    return response()->json(Profile::all(), 200);
  }

  public function show($id)
  {
    return response()->json(Profile::find($id), 200);
  }

  public function store(Request $request)
  {
      $validatedData = $request->validate([
          'firstname' => 'required|string|max:255',
          'lastname' => 'required|string|max:255',
          'picture' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
          'status' => 'required',
      ]);
  
      if ($request->hasFile('picture')) {
          $extension = $request->file('picture')->getClientOriginalExtension();
  
          $fileName = time() . '_' . uniqid() . '.' . $extension;
  
          //Images stockés dans le dossier storage/app/private/profiles-pictures
          $imagePath = $request->file('picture')->storeAs('profiles-pictures', $fileName);
  
          $profile = Profile::create([
              'firstname' => $validatedData['firstname'],
              'lastname' => $validatedData['lastname'],
              'picture' => $imagePath,  // Chemin de l'image stockée enregistré en base de donnée
              'status' => $validatedData['status'],
              'created_at' => now(),
          ]);
  
          return response()->json([
              'profile' => $profile,
          ], 201);
      } else {
          return response()->json(['error' => 'No file provided'], 400);
      }
  }
  

  public function getActiveProfiles()
  {
    $profiles = Profile::where('status', 'active')->get(['firstname', 'lastname', 'picture', 'created_at']);
    $formattedProfiles = $profiles->map(function ($profile) {
      return [
          'firstname' => $profile->firstname,
          'lastname' => $profile->lastname,
          'picture' => $profile->picture,
          //On formate la date de création du profile pour qu'elle lisible
          'created_at' => $profile->created_at->format('d/m/Y H:i:s'),
      ];
  });
    return response()->json($formattedProfiles, 200);
  }

  public function update(Request $request, $id)
  {
    $profil = Profile::findOrFail($id);

    //Nullable sur chacun des champs pour autoriser un partial update
    $validatedData = $request->validate([
      'firstname' => 'nullable|string|max:255',
      'lastname' => 'nullable|string|max:255',
      //Impossible de récupérer les données depuis un PATCH ou PUT sur un form-data et donc de récupérer le fichier
      'picture' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
      'status' => 'nullable',
    ]);

    // if($request->hasFile('picture')) {
    //   $extension = $request->file('picture')->getClientOriginalExtension();
    //   $fileName = time() . '_' . uniqid() . '.' . $extension;
    //   $imagePath = $request->file('picture')->storeAs('profiles-pictures', $fileName);
    // }

    $profil->update([
      'firstname' => $validatedData['firstname'] ?? $profil->firstname,
      'lastname' => $validatedData['lastname'] ?? $profil->lastname,
      //'picture' => $imagePath ?? $profil->picture,
      'status' => $validatedData['status'] ?? $profil->status,
      'updated_at' => time(),
    ]);
    return response()->json($profil, 200);
  }

  public function destroy($id)
  {
    $profil = Profile::findOrFail($id);
    $profil->delete();

    return response()->json(['message' => 'succesfully deleted'], 200);
  }
}