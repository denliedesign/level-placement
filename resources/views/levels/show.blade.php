@extends('layouts.app')

@section('content')
    <style>
        .placement-page {
            background:
                radial-gradient(circle at top left, rgba(220, 53, 69, 0.18), transparent 34rem),
                radial-gradient(circle at bottom right, rgba(13, 110, 253, 0.18), transparent 32rem),
                #f7f8fc;
            min-height: calc(100vh - 150px);
            padding: 2.5rem 1rem;
        }

        .placement-certificate {
            background: #fff;
            border: 1px solid rgba(16, 24, 40, 0.12);
            box-shadow: 0 24px 70px rgba(16, 24, 40, 0.14);
            margin: 0 auto 2rem;
            max-width: 980px;
            overflow: hidden;
            position: relative;
        }

        .placement-certificate::before {
            border: 2px solid rgba(220, 53, 69, 0.28);
            content: "";
            inset: 1rem;
            pointer-events: none;
            position: absolute;
        }

        .placement-ribbon {
            background: linear-gradient(135deg, #0d6efd, #dc3545);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            padding: 0.8rem 1rem;
            position: relative;
            text-transform: uppercase;
            z-index: 1;
        }

        .placement-body {
            padding: clamp(2rem, 5vw, 4.5rem);
            position: relative;
            z-index: 1;
        }

        .placement-logo {
            height: 84px;
            width: auto;
        }

        .placement-name {
            color: #c7203f;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(2.4rem, 8vw, 5rem);
            line-height: 1;
            margin: 1rem 0 0.75rem;
        }

        .placement-subtitle {
            color: #263044;
            font-size: clamp(1.05rem, 2.5vw, 1.4rem);
            margin: 0 auto;
            max-width: 720px;
        }

        .placement-list {
            display: grid;
            gap: 0.85rem;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            list-style: none;
            margin: 2rem auto 0;
            max-width: 760px;
            padding: 0;
        }

        .placement-list li {
            background: #f4f8ff;
            border: 1px solid rgba(13, 110, 253, 0.16);
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .placement-style {
            color: #596274;
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .placement-value {
            color: #111827;
            display: block;
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1.15;
            margin-top: 0.25rem;
        }

        .placement-empty {
            background: #fff8e8;
            border: 1px solid rgba(255, 193, 7, 0.35);
            border-radius: 0.5rem;
            color: #5f4b16;
            margin: 2rem auto 0;
            max-width: 640px;
            padding: 1rem;
        }

        .placement-section {
            margin: 2rem auto 0;
            max-width: 760px;
        }

        .placement-section-title {
            border-radius: 999px;
            color: #fff;
            display: inline-block;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            margin-bottom: 0.85rem;
            padding: 0.55rem 1rem;
            text-transform: uppercase;
        }

        .placement-section-title.blue {
            background: #0d6efd;
        }

        .placement-section-title.red {
            background: #dc3545;
        }

        .placement-section-title.gold {
            background: #f0ad00;
            color: #2d2300;
        }

        .placement-specialties {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .placement-specialties li,
        .placement-note {
            background: #fff;
            border: 1px solid rgba(16, 24, 40, 0.12);
            border-radius: 0.5rem;
            color: #263044;
            padding: 0.9rem 1rem;
        }

        .placement-class-table {
            border: 1px solid rgba(16, 24, 40, 0.12);
            border-radius: 0.5rem;
            margin: 0 auto;
            overflow: hidden;
            text-align: left;
        }

        .placement-class-table table {
            margin-bottom: 0;
        }

        .placement-class-table th {
            background: #f4f8ff;
            color: #263044;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .placement-enrollment-note {
            background: #fff8e8;
            border: 1px solid rgba(183, 121, 31, 0.3);
            border-radius: 0.5rem;
            color: #67440f;
            margin: 0 auto 1rem;
            padding: 0.75rem 1rem;
            text-align: center;
        }

        .placement-footer {
            color: #596274;
            font-size: 0.95rem;
            margin-top: 2rem;
        }

        @media print {
            nav {
                display: none;
            }

            .placement-page {
                background: #fff;
                padding: 0;
            }

            .placement-certificate {
                box-shadow: none;
                break-after: page;
                margin: 0;
                max-width: none;
                min-height: 100vh;
            }
        }
    </style>

    <main class="placement-page">
        @foreach($levels as $level)
            @php($placements = $level->placementEntries())
            @php($specialtyClasses = $level->specialtyClasses())
            @php($eligibleDanceClasses = $level->matchingDanceClasses())

            <section class="placement-certificate text-center">
                <div class="placement-ribbon">Level Placements 2026-2027</div>

                <div class="placement-body">
                    <img src="/images/logo-mdu.png" class="placement-logo" alt="MDU logo">

                    <p class="text-uppercase fw-bold text-muted mt-4 mb-0">Congratulations</p>
                    <h1 class="placement-name">{{ $level->fullName() }}</h1>
                    <p class="placement-subtitle">
                        You are eligible for the following classes this season.
                    </p>

                    @if(count($placements) > 0)
                        <ul class="placement-list">
                            @foreach($placements as $placement)
                                <li>
                                    <span class="placement-style">{{ $placement['label'] }}</span>
                                    <span class="placement-value">{{ $placement['value'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="placement-empty">
                            No class placements are listed for this dancer yet.
                        </div>
                    @endif

                    @if(count($specialtyClasses) > 0)
                        <div class="placement-section">
                            <h2 class="placement-section-title blue">Also Eligible For</h2>
                            <ul class="placement-specialties">
                                @foreach($specialtyClasses as $specialtyClass)
                                    <li>{{ $specialtyClass }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($eligibleDanceClasses->isNotEmpty())
                        <div class="placement-section">
                            <h2 class="placement-section-title blue">Exact Class Options</h2>
                            <p class="placement-enrollment-note">
                                Enrollment opens Saturday, June 6, 2026 at 8:00 AM. You can save favorites now in the
                                <a href="{{ route('dance-classes.scheduler') }}" class="brand-link">class scheduler</a>.
                            </p>
                            <div class="placement-class-table table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Level</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($eligibleDanceClasses as $danceClass)
                                        <tr>
                                            <td><strong>{{ $danceClass->dance_style }}</strong></td>
                                            <td>{{ $danceClass->level }}</td>
                                            <td>{{ $danceClass->day_of_week }}</td>
                                            <td>{{ $danceClass->time }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($level->hasTeacherRecommendation())
                        <div class="placement-section">
                            <h2 class="placement-section-title red">Teacher Recommendation</h2>
                            <p class="placement-note">{{ $level->teacher_recommendation }}</p>
                        </div>
                    @endif

                    @if($level->hasTeacherComments())
                        <div class="placement-section">
                            <h2 class="placement-section-title gold">Teacher Comments</h2>
                            <p class="placement-note">{{ $level->teacher_comments }}</p>
                        </div>
                    @endif

                    <p class="placement-footer">
                        Please contact the studio if anything looks different from what you expected.
                    </p>
                </div>
            </section>
        @endforeach
    </main>
@endsection
