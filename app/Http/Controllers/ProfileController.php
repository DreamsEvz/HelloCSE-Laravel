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
      'picture' => 'required|file',
      'status' => 'required',
    ]);
    
    //Permet de sauvegarder l'image dans le dossier public/images
    $imagePath = $request->file('image')->store('images', 'public');

    $profil = Profile::create([
      'firstname' => $validatedData['firstname'],
      'lastname' => $validatedData['lastname'],
      'picture' => $imagePath,
      'status' => $validatedData['status'],
      'created_at' => time(),
    ]);

    return response()->json($profil, 201);
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

    $validatedData = $request->validate([
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      'picture' => 'required|file',
      'status' => 'required',
    ]);

    //Permet de sauvegarder l'image dans le dossier public/images
    $imagePath = $request->file('image')->store('images', 'public');

    $profil->update([
      'firstname' => $validatedData['firstname'],
      'lastname' => $validatedData['lastname'],
      'picture' => $imagePath,
      'status' => $validatedData['status'],
      'updated_at' => time(),
    ]);

    return response()->json($profil, 200);
  }

  public function delete($id)
  {
    $profil = Profile::findOrFail($id);
    $profil->delete();

    return response()->json(null, 204);
  }
}