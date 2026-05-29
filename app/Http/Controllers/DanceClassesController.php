<?php

namespace App\Http\Controllers;

use App\Imports\DanceClassesImport;
use App\Mail\FavoritesEmail;
use App\Models\DanceClass;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DanceClassesController extends Controller
{
    private const ADMIN_EMAIL = 'customdenlie@gmail.com';
    private const ENROLL_URL = 'https://app.thestudiodirector.com/mistysdance/portal.sd?page=Login';
    private const DANCER_COLORS = [
        '#7c3aed',
        '#0f766e',
        '#be185d',
        '#d71945',
        '#0076b6',
    ];

    public function scheduler()
    {
        return view('dance-classes.scheduler', [
            'classes' => DanceClass::query()
                ->orderBy('name')
                ->orderBy('day_of_week')
                ->orderBy('time')
                ->get(),
            'levels' => $this->levelOptions(),
            'danceStyles' => $this->options('dance_style'),
            'daysOfWeek' => $this->options('day_of_week'),
            'enrollUrl' => self::ENROLL_URL,
            'recommendedPlacements' => $this->recommendedPlacements(),
            'defaultEmail' => auth()->user()?->email,
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'level' => ['nullable', 'array'],
            'level.*' => ['string'],
            'dance_style' => ['nullable', 'array'],
            'dance_style.*' => ['string'],
            'day_of_week' => ['nullable', 'array'],
            'day_of_week.*' => ['string'],
        ]);

        $classes = DanceClass::query()
            ->when($request->filled('dance_style'), fn ($query) => $query->whereIn('dance_style', $request->dance_style))
            ->when($request->filled('day_of_week'), fn ($query) => $query->whereIn('day_of_week', $request->day_of_week))
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->orderBy('name')
            ->get()
            ->when($request->filled('level'), fn ($classes) => $classes
                ->filter(fn (DanceClass $danceClass) => collect(DanceClass::levelOptions($danceClass->level))->intersect($request->level)->isNotEmpty())
                ->values());

        return view('dance-classes.results', compact('classes'));
    }

    public function showImportForm()
    {
        abort_unless($this->isAdmin(), 403);

        return view('dance-classes.import');
    }

    public function import(Request $request)
    {
        abort_unless($this->isAdmin(), 403);

        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv'],
        ]);

        DanceClass::truncate();
        Excel::import(new DanceClassesImport(), $request->file('file'));

        return back()->with('success', 'Dance classes imported and table replaced!');
    }

    public function downloadFavorites(Request $request): StreamedResponse
    {
        $classes = $this->selectedClasses($request);

        return response()->streamDownload(function () use ($classes) {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['Style', 'Level', 'Day', 'Time']);

            foreach ($classes as $class) {
                fputcsv($output, [
                    $class->dance_style,
                    $class->level,
                    $class->day_of_week,
                    $class->time,
                ]);
            }

            fclose($output);
        }, 'mdu-class-favorites.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function emailFavorites(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $classes = $this->selectedClasses($request);

        Mail::to($request->email)->send(new FavoritesEmail($classes));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Favorites sent to '.$request->email.'.',
            ]);
        }

        return back()->with('success', 'Favorites sent to '.$request->email.'.');
    }

    private function options(string $column): array
    {
        return DanceClass::query()
            ->whereNotNull($column)
            ->select($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->all();
    }

    private function levelOptions(): array
    {
        return DanceClass::query()
            ->whereNotNull('level')
            ->pluck('level')
            ->flatMap(fn (string $level) => DanceClass::levelOptions($level))
            ->unique()
            ->sort(fn (string $first, string $second) => strnatcasecmp($first, $second))
            ->values()
            ->all();
    }

    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->email === self::ADMIN_EMAIL;
    }

    private function selectedClasses(Request $request)
    {
        $request->validate([
            'selected_classes' => ['required', 'array', 'min:1'],
            'selected_classes.*' => ['integer'],
        ]);

        return DanceClass::query()
            ->whereIn('id', $request->selected_classes)
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get();
    }

    private function recommendedPlacements(): array
    {
        if (! auth()->check()) {
            return [];
        }

        $levels = Level::where('email', auth()->user()->email)->get();
        $dancerColors = $levels
            ->pluck('first_name')
            ->filter()
            ->unique()
            ->values()
            ->mapWithKeys(fn (string $name, int $index) => [
                $name => self::DANCER_COLORS[$index % count(self::DANCER_COLORS)],
            ]);

        return $levels
            ->flatMap(fn (Level $level) => collect($level->eligibleClassPlacements())
                ->map(fn (array $placement) => [
                    'style' => $placement['style'],
                    'level' => $placement['level'],
                    'dancer' => [
                        'name' => $level->first_name,
                        'color' => $dancerColors[$level->first_name] ?? self::DANCER_COLORS[0],
                    ],
                ]))
            ->groupBy(fn (array $placement) => DanceClass::placementKey($placement['style'], $placement['level']))
            ->map(fn ($placements) => [
                'style' => $placements->first()['style'],
                'level' => $placements->first()['level'],
                'dancers' => $placements
                    ->pluck('dancer')
                    ->filter(fn (array $dancer) => trim((string) ($dancer['name'] ?? '')) !== '')
                    ->unique('name')
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }
}
