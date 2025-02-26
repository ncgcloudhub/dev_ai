<?php

use App\Http\Controllers\Api\ImageGenerationController;
use App\Http\Controllers\Backend\AI\GenerateImagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PromptLibraryController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EducationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/prompt/manage', [PromptLibraryController::class, 'PromptManageApi'])
    ->middleware('hex.auth'); 

Route::post('/register', function (Request $request) {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Ensures `password_confirmation` is required
        ]);
    
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    });


    Route::post('/login', function (Request $request) {
        Log::info("Inside Login Api function");
    
        // Validate the request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();
    
            Log::info("Iine 59");
            // Create a token for the user
            $token = $user->createToken('auth-token')->plainTextToken;
            Log::info("Iine 62");
            
            // Return a success response with the token
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        }
    
        // If authentication fails, return an error response
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    });

Route::get('/education/manage/tools', [EducationController::class, 'manageToolsapi'])->middleware('hex.auth');

Route::post('/image', [ImageGenerationController::class, 'generateImage'])->name('generate.images')->middleware('hex.auth');