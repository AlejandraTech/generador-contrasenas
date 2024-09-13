<?php

namespace App\Http\Controllers;

use App\Models\Password;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function showForm()
    {
        return view('form');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'length' => 'required|integer|min:1',
            'special_chars' => 'nullable|boolean',
            'numbers' => 'nullable|boolean',
            'uppercase' => 'nullable|boolean',
            'lowercase' => 'nullable|boolean',
        ]);

        $length = $validated['length'];
        $includeSpecialChars = $request->has('special_chars');
        $includeNumbers = $request->has('numbers');
        $includeUppercase = $request->has('uppercase');
        $includeLowercase = $request->has('lowercase');

        $password = $this->generatePassword($length, $includeSpecialChars, $includeNumbers, $includeUppercase, $includeLowercase);

        $this->savePassword($password);

        return view('result', ['password' => $password]);
    }

    public function showHistory()
    {
        $passwords = Password::latest()->take(10)->get();
        return view('history', ['passwords' => $passwords]);
    }

    private function savePassword($password)
    {
        Password::create(['value' => $password]);
    }

    public function delete($id)
    {
        Password::destroy($id);
        return redirect()->route('password.history');
    }

    private function generatePassword($length, $includeSpecialChars, $includeNumbers, $includeUppercase, $includeLowercase)
    {
        $characters = '';
        if ($includeLowercase) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($includeUppercase) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($includeNumbers) {
            $characters .= '0123456789';
        }
        if ($includeSpecialChars) {
            $characters .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }

        if (empty($characters)) {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }

        return substr(str_shuffle($characters), 0, $length);
    }
}
