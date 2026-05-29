@extends('layouts.app')

@section('content')
    <style>
        [v-cloak] {
            display: none;
        }

        .scheduler-panel {
            background: #fff;
            border: 1px solid rgba(16, 24, 40, 0.12);
            border-radius: 0.5rem;
            box-shadow: 0 18px 45px rgba(16, 24, 40, 0.08);
            padding: 1.25rem;
        }

        .scheduler-filter-list {
            display: grid;
            gap: 0.5rem;
            max-height: 260px;
            overflow: auto;
        }

        .scheduler-filter {
            align-items: center;
            background: #f8fafc;
            border: 1px solid rgba(16, 24, 40, 0.08);
            border-radius: 0.5rem;
            display: flex;
            gap: 0.55rem;
            padding: 0.65rem 0.75rem;
        }

        .scheduler-table th {
            background: #f4f8ff;
            color: var(--mdu-ink);
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .scheduler-recommended-row {
            --bs-table-bg: #fff8e8;
            box-shadow: inset 4px 0 0 var(--mdu-red);
        }

        .recommended-badge {
            align-items: center;
            background: var(--mdu-red);
            border-radius: 999px;
            color: #fff;
            display: inline-flex;
            font-size: 0.72rem;
            font-weight: 700;
            gap: 0.25rem;
            letter-spacing: 0.06em;
            margin-left: 0.35rem;
            overflow: visible;
            padding: 0.2rem 0.85rem 0.2rem 0.85rem;
            position: relative;
            text-transform: uppercase;
        }

        .recommended-badge ion-icon {
            filter: drop-shadow(0 0 0.1rem rgba(255, 214, 102, 0.35)) drop-shadow(0 0 0.1rem rgba(255, 214, 102, 0.35));
            font-size: 1.25rem;
            position: absolute;
            right: -0.5rem;
            top: -0.25rem;
            transform: rotate(8deg);
            color: #EBE82E;
        }

        .recommended-badges {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-left: 0.35rem;
            vertical-align: middle;
        }

        .favorite-summary {
            background: #f7f8fc;
            border: 1px solid rgba(0, 118, 182, 0.14);
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .enrollment-note {
            background: #fff8e8;
            border: 1px solid rgba(183, 121, 31, 0.3);
            border-radius: 0.5rem;
            color: #67440f;
            padding: 0.75rem 1rem;
        }

        .enrollment-disabled {
            cursor: not-allowed;
            opacity: 0.72;
        }

        .scheduler-message {
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.85rem 1rem;
        }

        .scheduler-message.success {
            background: #eaf7ef;
            border: 1px solid #b7e2c5;
            color: #23633a;
        }

        .scheduler-message.error {
            background: #fff1f2;
            border: 1px solid #f3b8c0;
            color: #9f1239;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printFavorites,
            #printFavorites * {
                visibility: visible;
            }

            #printFavorites {
                left: 0;
                position: absolute;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <main id="schedulerApp" class="container my-5" v-cloak>
        <div class="text-center mb-4">
            <h1 class="fw-bold">Family Flex Scheduler</h1>
            <p class="text-muted">
                Filter the schedule and save your favorite class options before enrollment opens.
            </p>
            <p class="enrollment-note d-inline-block mb-2">
                Enrollment opens Saturday, June 6, 2026 at 8:00 AM.
            </p>
            @if(count($recommendedPlacements) > 0)
                <p class="text-muted mb-0">
                    Highlighted rows are exact options from your family's placement results.
                </p>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>

        <div class="row g-4">
            <aside class="col-lg-4">
                <div class="scheduler-panel mb-3">
                    <h2 class="h5 fw-bold">Narrow Results</h2>

                    <label for="schedulerSearch" class="form-label mt-2">Search</label>
                    <input id="schedulerSearch" v-model="search" type="search" class="form-control" placeholder="Style, level, day, time">

                    <label for="schedulerSort" class="form-label mt-3">Sort</label>
                    <div class="d-flex gap-2">
                        <select id="schedulerSort" v-model="sortBy" class="form-select">
                            <option value="dance_style">Style</option>
                            <option value="level">Level</option>
                            <option value="day_of_week">Day</option>
                            <option value="time">Time</option>
                        </select>
                        <button type="button" class="btn-brand-secondary py-2" @click="toggleSortDirection">
                            <span v-text="sortDirection === 'asc' ? 'Asc' : 'Desc'"></span>
                        </button>
                    </div>

                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <button type="button" class="btn-brand-secondary py-2" @click="clearFilters">Clear Filters</button>
                        <button type="button" class="btn-brand-secondary py-2" @click="selectVisible">Select Visible</button>
                    </div>
                </div>

                <div class="scheduler-panel mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="h6 fw-bold mb-0">Levels</h3>
                        <button type="button" class="brand-link border-0 bg-transparent" @click="toggleAll('levels', levelOptions)">All</button>
                    </div>
                    <div class="scheduler-filter-list mt-3">
                        <label v-for="level in levelOptions" :key="level" class="scheduler-filter">
                            <input type="checkbox" v-model="filters.levels" :value="level" class="form-check-input">
                            <span v-text="level"></span>
                        </label>
                    </div>
                </div>

                <div class="scheduler-panel mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="h6 fw-bold mb-0">Styles</h3>
                        <button type="button" class="brand-link border-0 bg-transparent" @click="toggleAll('styles', styleOptions)">All</button>
                    </div>
                    <div class="scheduler-filter-list mt-3">
                        <label v-for="style in styleOptions" :key="style" class="scheduler-filter">
                            <input type="checkbox" v-model="filters.styles" :value="style" class="form-check-input">
                            <span v-text="style"></span>
                        </label>
                    </div>
                </div>

                <div class="scheduler-panel">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="h6 fw-bold mb-0">Days</h3>
                        <button type="button" class="brand-link border-0 bg-transparent" @click="toggleAll('days', dayOptions)">All</button>
                    </div>
                    <div class="scheduler-filter-list mt-3">
                        <label v-for="day in dayOptions" :key="day" class="scheduler-filter">
                            <input type="checkbox" v-model="filters.days" :value="day" class="form-check-input">
                            <span v-text="day"></span>
                        </label>
                    </div>
                </div>
            </aside>

            <section class="col-lg-8">
                <div class="scheduler-panel mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h4 fw-bold mb-1">Class Results</h2>
                            <p class="text-muted mb-0">
                                <span v-text="filteredClasses.length"></span> classes shown,
                                <span v-text="selectedClasses.length"></span> favorites selected.
                            </p>
                        </div>
                        <button type="button" class="btn-brand-primary enrollment-disabled" disabled>
                            Enrollment Opens June 6
                        </button>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table scheduler-table align-middle">
                            <thead>
                            <tr>
                                <th>Select</th>
                                <th>Style</th>
                                <th>Level</th>
                                <th>Day</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="danceClass in filteredClasses" :key="danceClass.id" :class="{ 'scheduler-recommended-row': isRecommended(danceClass) }">
                                <td>
                                    <input type="checkbox" v-model="selectedClassIds" :value="danceClass.id" class="form-check-input">
                                </td>
                                <td>
                                    <strong v-text="danceClass.dance_style"></strong>
                                    <span v-if="recommendationDancers(danceClass).length > 0" class="recommended-badges">
                                        <span
                                            v-for="dancer in recommendationDancers(danceClass)"
                                            :key="dancer.name"
                                            class="recommended-badge"
                                            :style="{ backgroundColor: dancer.color }"
                                        >
                                            <span v-text="`For ${dancer.name}`"></span>
                                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                                        </span>
                                    </span>
                                </td>
                                <td v-text="danceClass.level"></td>
                                <td v-text="danceClass.day_of_week"></td>
                                <td v-text="danceClass.time"></td>
                            </tr>
                            <tr v-if="filteredClasses.length === 0">
                                <td colspan="5" class="text-center text-muted py-4">
                                    No classes match those filters yet.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="favorite-summary" id="printFavorites">
                    <h2 class="h4 fw-bold">Your Favorites</h2>
                    <p class="text-muted" v-if="selectedClasses.length === 0">Select classes above to build your shortlist.</p>

                    <div v-if="selectedClasses.length > 0" class="table-responsive">
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
                            <tr v-for="danceClass in selectedClasses" :key="danceClass.id" :class="{ 'scheduler-recommended-row': isRecommended(danceClass) }">
                                <td>
                                    <strong v-text="danceClass.dance_style"></strong>
                                    <span v-if="recommendationDancers(danceClass).length > 0" class="recommended-badges">
                                        <span
                                            v-for="dancer in recommendationDancers(danceClass)"
                                            :key="dancer.name"
                                            class="recommended-badge"
                                            :style="{ backgroundColor: dancer.color }"
                                        >
                                            <span v-text="`For ${dancer.name}`"></span>
                                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                                        </span>
                                    </span>
                                </td>
                                <td v-text="danceClass.level"></td>
                                <td v-text="danceClass.day_of_week"></td>
                                <td v-text="danceClass.time"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="scheduler-panel mt-3">
                    <h2 class="h5 fw-bold">Save Favorites</h2>
                    <p class="text-muted">
                        Save, print, or email your favorites now so you are ready when enrollment opens Saturday, June 6, 2026 at 8:00 AM.
                    </p>
                    <div v-if="emailMessage" class="scheduler-message" :class="emailStatus" v-text="emailMessage"></div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="button" class="btn-brand-primary enrollment-disabled" disabled>
                            Enrollment Opens June 6
                        </button>
                        <button type="button" class="btn-brand-secondary" :disabled="selectedClasses.length === 0" @click="printFavorites">Print</button>

                        <form action="{{ route('dance-classes.favorites.download') }}" method="POST">
                            @csrf
                            <input v-for="id in selectedClassIds" :key="'download-' + id" type="hidden" name="selected_classes[]" :value="id">
                            <button type="submit" class="btn-brand-secondary" :disabled="selectedClasses.length === 0">Download CSV</button>
                        </form>
                    </div>

                    <form action="{{ route('dance-classes.favorites.email') }}" method="POST" class="row g-2 align-items-end" @submit.prevent="sendFavoritesEmail">
                        @csrf
                        <div class="col-md">
                            <label for="favoriteEmail" class="form-label">Email favorites</label>
                            <input id="favoriteEmail" v-model="email" type="email" name="email" class="form-control" placeholder="you@example.com" required>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn-brand-primary" :disabled="selectedClasses.length === 0 || emailSending">
                                <span v-text="emailSending ? 'Sending...' : 'Send Email'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js"></script>
    <script>
        const schedulerClasses = @json($classes);
        const schedulerLevelOptions = @json($levels);
        const schedulerStyleOptions = @json($danceStyles);
        const schedulerDayOptions = @json($daysOfWeek);
        const schedulerRecommendedPlacements = @json($recommendedPlacements);
        const schedulerDefaultEmail = @json($defaultEmail);
        const schedulerEmailUrl = @json(route('dance-classes.favorites.email'));
        const schedulerCsrfToken = @json(csrf_token());
        const schedulerPlacementKey = (style, level) => {
            const normalizedStyle = schedulerPlacementStyle(style);
            const normalizedLevel = normalizedStyle === 'pointe'
                ? schedulerNormalize(String(schedulerPointeLevelOptions(level)[0] || level || ''))
                : schedulerNormalize(level);

            return normalizedStyle && normalizedLevel ? `${normalizedStyle}|${normalizedLevel}` : '';
        };
        const schedulerNormalize = (value) => String(value || '').trim().toLowerCase().replace(/\s+/g, ' ');
        const schedulerPlacementStyle = (style) => {
            const normalizedStyle = schedulerNormalize(style);

            return ['point', 'pointe', 'pre pointe', 'pre-pointe'].includes(normalizedStyle) ? 'pointe' : normalizedStyle;
        };
        const schedulerPointeLevelOptions = (level) => {
            const rawLevel = String(level || '').trim();

            if (rawLevel === '') {
                return [];
            }

            if (rawLevel.includes('/')) {
                return [...new Set(rawLevel.split(/\s*\/\s*/).flatMap((levelPart) => schedulerPointeLevelOptions(levelPart)).filter(Boolean))];
            }

            const normalizedLevel = schedulerNormalize(rawLevel).replace(/^-+|-+$/g, '');

            if (['pre', 'pre pointe', 'pre-pointe'].includes(normalizedLevel)) {
                return ['Pre-Pointe'];
            }

            const pointeMatch = normalizedLevel.match(/^pointe\s*(\d+)$/);

            if (pointeMatch) {
                return [pointeMatch[1]];
            }

            return [];
        };
        const schedulerLevelOptionsFor = (level) => {
            const normalizedLevel = String(level || '').trim();

            if (normalizedLevel === '') {
                return [];
            }

            const pointeOptions = schedulerPointeLevelOptions(normalizedLevel);

            if (pointeOptions.length > 0) {
                return pointeOptions;
            }

            const rangeMatch = normalizedLevel.match(/^(\d+)\s*(?:-|\u2013)\s*(\d+)$/);

            if (rangeMatch) {
                let start = Number(rangeMatch[1]);
                let end = Number(rangeMatch[2]);

                if (start > end) {
                    [start, end] = [end, start];
                }

                if (end - start <= 12) {
                    return Array.from({ length: end - start + 1 }, (_, index) => String(start + index));
                }
            }

            if (/^\d+([\s/,]+\d+)+$/.test(normalizedLevel)) {
                return [...new Set(normalizedLevel.split(/[\s/,]+/).filter(Boolean))];
            }

            return [normalizedLevel];
        };
        const schedulerPlacementKeysFor = (style, level) => schedulerLevelOptionsFor(level).map((levelOption) => schedulerPlacementKey(style, levelOption));
        const schedulerRecommendedPlacementMap = schedulerRecommendedPlacements.reduce((placements, placement) => {
            placements[schedulerPlacementKey(placement.style, placement.level)] = placement.dancers || [];

            return placements;
        }, {});

        Vue.createApp({
            data() {
                return {
                    classes: schedulerClasses,
                    levelOptions: schedulerLevelOptions,
                    styleOptions: schedulerStyleOptions,
                    dayOptions: schedulerDayOptions,
                    filters: {
                        levels: [],
                        styles: [],
                        days: [],
                    },
                    search: '',
                    selectedClassIds: [],
                    sortBy: 'dance_style',
                    sortDirection: 'asc',
                    recommendedPlacementMap: schedulerRecommendedPlacementMap,
                    email: schedulerDefaultEmail || '',
                    emailMessage: '',
                    emailSending: false,
                    emailStatus: 'success',
                };
            },
            computed: {
                filteredClasses() {
                    const search = this.search.trim().toLowerCase();
                    const filtered = this.classes.filter((danceClass) => {
                        const matchesLevel = this.filters.levels.length === 0
                            || schedulerLevelOptionsFor(danceClass.level)
                                .some((level) => this.filters.levels.includes(level));
                        const matchesStyle = this.filters.styles.length === 0 || this.filters.styles.includes(danceClass.dance_style);
                        const matchesDay = this.filters.days.length === 0 || this.filters.days.includes(danceClass.day_of_week);
                        const haystack = [
                            danceClass.name,
                            danceClass.dance_style,
                            danceClass.level,
                            danceClass.day_of_week,
                            danceClass.time,
                        ].join(' ').toLowerCase();
                        const matchesSearch = search === '' || haystack.includes(search);

                        return matchesLevel && matchesStyle && matchesDay && matchesSearch;
                    });

                    return filtered.sort((a, b) => {
                        const aValue = String(a[this.sortBy] || '').toLowerCase();
                        const bValue = String(b[this.sortBy] || '').toLowerCase();
                        const comparison = aValue.localeCompare(bValue, undefined, { numeric: true });

                        return this.sortDirection === 'asc' ? comparison : comparison * -1;
                    });
                },
                selectedClasses() {
                    return this.classes
                        .filter((danceClass) => this.selectedClassIds.includes(danceClass.id))
                        .sort((a, b) => String(a.day_of_week + a.time + a.name).localeCompare(String(b.day_of_week + b.time + b.name), undefined, { numeric: true }));
                },
            },
            methods: {
                clearFilters() {
                    this.filters.levels = [];
                    this.filters.styles = [];
                    this.filters.days = [];
                    this.search = '';
                },
                printFavorites() {
                    if (this.selectedClasses.length > 0) {
                        window.print();
                    }
                },
                async sendFavoritesEmail() {
                    if (this.selectedClassIds.length === 0 || this.emailSending) {
                        return;
                    }

                    this.emailSending = true;
                    this.emailMessage = '';

                    try {
                        const response = await fetch(schedulerEmailUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': schedulerCsrfToken,
                            },
                            body: JSON.stringify({
                                email: this.email,
                                selected_classes: this.selectedClassIds,
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Unable to send favorites.');
                        }

                        this.emailStatus = 'success';
                        this.emailMessage = data.message || 'Favorites sent.';
                    } catch (error) {
                        this.emailStatus = 'error';
                        this.emailMessage = error.message || 'Unable to send favorites.';
                    } finally {
                        this.emailSending = false;
                    }
                },
                isRecommended(danceClass) {
                    return this.recommendationDancers(danceClass).length > 0;
                },
                recommendationDancers(danceClass) {
                    return this.placementKeysFor(danceClass.dance_style, danceClass.level)
                        .flatMap((placementKey) => this.recommendedPlacementMap[placementKey] || [])
                        .filter((dancer, index, dancers) => dancer?.name && dancers.findIndex((item) => item.name === dancer.name) === index);
                },
                placementKey(style, level) {
                    return schedulerPlacementKey(style, level);
                },
                placementKeysFor(style, level) {
                    return schedulerPlacementKeysFor(style, level);
                },
                selectVisible() {
                    const visibleIds = this.filteredClasses.map((danceClass) => danceClass.id);
                    this.selectedClassIds = Array.from(new Set([...this.selectedClassIds, ...visibleIds]));
                },
                toggleAll(filterName, options) {
                    this.filters[filterName] = this.filters[filterName].length === options.length ? [] : [...options];
                },
                toggleSortDirection() {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                },
            },
        }).mount('#schedulerApp');
    </script>
@endsection
