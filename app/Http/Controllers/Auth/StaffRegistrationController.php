<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class StaffRegistrationController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/StaffRegister');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'invitation_code' => ['required', 'string'],
            'role' => ['required', 'in:doctor,staff']
        ]);

        // Buscar la clínica por el código de invitación
        $clinic = Clinic::where('invitation_code', $validated['invitation_code'])
            ->where('is_active', true)
            ->firstOrFail();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'clinic_id' => $clinic->id
        ]);

        auth()->login($user);

        return redirect()->route('filament.clinic.pages.dashboard');
    }

    // Método para validar el código de invitación vía API
    public function validateCode(Request $request)
    {
        $validated = $request->validate([
            'invitation_code' => ['required', 'string']
        ]);

        $clinic = Clinic::where('invitation_code', $validated['invitation_code'])
            ->where('is_active', true)
            ->first();

        return response()->json([
            'valid' => (bool) $clinic,
            'clinic' => $clinic ? [
                'name' => $clinic->name,
            ] : null
        ]);
    }
}
