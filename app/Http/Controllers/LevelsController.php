<?php

namespace App\Http\Controllers;

use App\Imports\LevelImport;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LevelsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $email = $user->email;

        // If admin, show all
        if ($email === 'customdenlie@gmail.com') {
            $levels = Level::all();
            return view('levels.index', compact('levels'));
        }

        // Otherwise, show only rows that match the logged-in user's email
        $levels = Level::where('email', $email)->get();

        if ($levels->isEmpty()) {
            return view('levels.not-found');
        }

        return view('levels.index', compact('levels'));
    }

    public function showForm()
    {
        return view('levels.import');
    }

    public function import(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'file' => 'required|file|max:20480', // up to 20MB
//        ]);
//
//        if ($validator->fails()) {
//            Log::error('Validation failed:', $validator->errors()->all());
//            return back()->with('error', 'Validation failed. Check logs.');
//        }
//
//        try {
//            Level::truncate();
//            Excel::import(new LevelImport, $request->file('file'));
//            return back()->with('success', 'Levels imported and table replaced!');
//        } catch (\Throwable $e) {
//            Log::error('Import failed: ' . $e->getMessage());
//            return back()->with('error', 'Import failed. Check the log.');
//        }
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            // Delete all existing levels
            Level::truncate();

            Excel::import(new LevelImport, $request->file('file'));

            return back()->with('success', 'Levels imported and table replaced!');
        } catch (\Exception $e) {
            \Log::error('Import failed: ' . $e->getMessage());
            return back()->with('error', 'Import failed. Check logs.');
        }
    }
}
