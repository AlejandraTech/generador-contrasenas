<?php

namespace App\Http\Controllers;

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

        $request->session()->put('password_config', $validated);

        return view('result', ['password' => $password]);
    }

    public function showHistory()
    {
        $passwords = session('generated_passwords', []);
        return view('history', ['passwords' => $passwords]);
    }

    private function savePassword($password)
    {
        $passwords = session('generated_passwords', []);
        array_unshift($passwords, $password);
        session(['generated_passwords' => array_slice($passwords, 0, 10)]);
    }

    public function delete($index)
    {
        $passwords = session('generated_passwords', []);
        if (isset($passwords[$index])) {
            unset($passwords[$index]);
            session(['generated_passwords' => array_values($passwords)]);
        }
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
