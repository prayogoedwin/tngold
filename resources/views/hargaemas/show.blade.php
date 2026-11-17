<x-layouts.app>
    @php
    $showColumns = collect($columns)->filter(fn($c) => ($c['inShowPage'] ?? false) && ($c['type'] ?? 'string') !== 'password');
    @endphp

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        @if (!empty($parent))
        <span class="text-gray-500 dark:text-gray-400">{{ $parent }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        @endif
        <a href="{{ route($table . '.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ $title }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Detail') }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $title }} {{ __('Detail') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View') }} {{ $title }} {{ __('details') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route($table . '.index') }}">
                <x-button type="secondary">{{ __('Back') }}</x-button>
            </a>

            @if (auth()->user()->hasPermission('edit-' . $table) && ($canEdit ?? true) !== false)
            <a href="{{ route($table . '.edit', $data->id) }}">
                <x-button type="primary">{{ __('Edit') }}</x-button>
            </a>
            @endif
        </div>
    </div>

    {{-- Detail --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($showColumns as $column)
            @php
            $name = $column['name'];
            $label = __($column['label'] ?? ucfirst(str_replace('_', ' ', $name)));
            $type = $column['type'] ?? 'string';
            $value = $data->{$name} ?? null;
            @endphp

            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                    @switch($type)
                    @case('select')
                    @php
                    $option = collect($column['options'] ?? [])->firstWhere('value', $value);
                    @endphp
                    {{ $option['label'] ?? ($value ?? '-') }}
                    @break

                    @case('checkbox')
                    @case('boolean')
                    @if ($value)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">{{ __('Yes') }}</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ __('No') }}</span>
                    @endif
                    @break

                    @case('image')
                    @if (!empty($value))
                    <img src="{{ asset('storage/' . $value) }}" alt="{{ $label }}"
                        class="h-32 w-auto rounded border border-gray-200 dark:border-gray-700">
                    @else
                    -
                    @endif
                    @break

                    @case('file')
                    @if (!empty($value))
                    <a href="{{ asset('storage/' . $value) }}" target="_blank"
                        class="text-blue-600 dark:text-blue-400 hover:underline">{{ basename($value) }}</a>
                    @else
                    -
                    @endif
                    @break

                    @case('date')
                    {{ $value ? \Carbon\Carbon::parse($value)->format('d M Y') : '-' }}
                    @break

                    @case('number')
                    @case('integer')
                    @case('decimal')
                    {{ $value !== null ? $value : '-' }}
                    @break

                    @case('textarea')
                    {!! $value ? nl2br(e($value)) : '-' !!}
                    @break

                    @default
                    {{ $value !== null && $value !== '' ? $value : '-' }}
                    @endswitch
                </dd>
            </div>
            @endforeach

            @if ($table_has_timestamps ?? false)
            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created At') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                    {{ optional($data->created_at)->format('d M Y H:i') ?? '-' }}
                </dd>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated At') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                    {{ optional($data->updated_at)->format('d M Y H:i') ?? '-' }}
                </dd>
            </div>
            @endif
        </dl>
    </div>


    {{-- History Chart --}}
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                History Perubahan Harga
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Grafik perubahan harga emas berdasarkan waktu
            </p>
        </div>

        <div class="p-4">
            <div class="relative w-full" style="height: 350px;">
                <canvas id="harga-history-chart"></canvas>
            </div>
        </div>
    </div>


    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
            <table id="dynamic-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Perbedaan dari Harga Sebelumnya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Perbedaan dari Harga Rata-rata</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>


                    </tr>
                </thead>
            </table>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- hidden variable for js -->
    <input type="hidden" id="table" value="{{ $table }}">
    <input type="hidden" id="title" value="{{ $title }}">
    <input type="hidden" id="harga-emas-id" value="{{ $data->id }}">
    <input type="hidden" id="harga-history-data" value="{{ json_encode($hargaHistoryData) }}">


    <script>
        function initHargaHistoryChart() {
            const ctx = document.getElementById('harga-history-chart');

            hargaHistoryChart = new Chart(ctx, {
                type: 'line',

                data: {
                    labels: [],
                    datasets: [{
                        label: 'Harga Emas',
                        data: [],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.10)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },

                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },

                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(
                                        context.parsed.y
                                    );
                                }
                            }
                        }
                    },

                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },

                        y: {
                            beginAtZero: false,

                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateHargaHistoryChart(data) {
            if (!hargaHistoryChart) {
                return;
            }

            const labels = data.map(item => {
                const date = new Date(item.tanggal.replace(' ', 'T'));

                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            });

            const prices = data.map(item => item.harga_beli);

            hargaHistoryChart.data.labels = labels;
            hargaHistoryChart.data.datasets[0].data = prices;

            hargaHistoryChart.update();
        }
    </script>

    <script>
        $(document).ready(function() {
            initHargaHistoryChart();

            const hargaHistoryData = JSON.parse($('#harga-history-data').val());
            console.log(hargaHistoryData);

            updateHargaHistoryChart(hargaHistoryData)


            const columns = [{
                    'name': 'id',
                    'data': 'id',
                    defaultContent: '-'
                },
                {
                    'name': 'harga_beli',
                    'data': 'harga_beli',
                    defaultContent: '-'
                },
                {
                    'name': 'diffBefore',
                    'data': 'diffBefore',
                    defaultContent: '-'
                },
                {
                    'name': 'diffAverage',
                    'data': 'diffAverage',
                    defaultContent: '-'
                },
                {
                    'name': 'tanggal',
                    'data': 'tanggal',
                    defaultContent: '-'
                }
            ]





            if ($('#actions').val() == '1') {
                columns.push({
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-right'
                });
            }

            console.log(columns)
            const hargaEmasId = $('#harga-emas-id').val();

            $('#dynamic-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('harga-emas.history.indexTable', $data->id) }}",
                    
                },
                columns: columns,
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search  {{ strtolower($title) }}",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ {{ strtolower($title) }}",
                    infoEmpty: "No {{ strtolower($title) }} found",
                    infoFiltered: "(filtered from _MAX_ total {{ strtolower($title) }})",
                    zeroRecords: "No matching {{ strtolower($title) }} found",
                    emptyTable: "No {{ strtolower($title) }} available"
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                stripeClasses: ['bg-white dark:bg-gray-800', 'bg-gray-50 dark:bg-gray-900']
            });
        });
    </script>

    <style>
        #dynamic-table {
            border-collapse: separate !important;
            border-spacing: 0;
        }

        #dynamic-table thead th {
            border-bottom: 2px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }

        .dark #dynamic-table thead th {
            border-bottom-color: #374151;
            border-right-color: #374151;
            background-color: #1f2937;
        }

        #dynamic-table thead th:last-child {
            border-right: none;
        }

        #dynamic-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .dark #dynamic-table tbody tr {
            border-bottom-color: #374151;
        }

        #dynamic-table tbody tr.odd {
            background-color: #ffffff;
        }

        #dynamic-table tbody tr.even {
            background-color: #f9fafb;
        }

        .dark #dynamic-table tbody tr.odd {
            background-color: #1f2937;
        }

        .dark #dynamic-table tbody tr.even {
            background-color: #111827;
        }

        #dynamic-table tbody tr:hover {
            background-color: #e5e7eb !important;
        }

        .dark #dynamic-table tbody tr:hover {
            background-color: #374151 !important;
        }

        #dynamic-table tbody td {
            border-right: 1px solid #e5e7eb;
            padding: 12px 24px;
        }

        .dark #dynamic-table tbody td {
            border-right-color: #374151;
        }

        #dynamic-table tbody td:last-child {
            border-right: none;
        }

        #dynamic-table tbody td a,
        #dynamic-table tbody td form {
            display: inline;
            white-space: nowrap;
        }
    </style>
</x-layouts.app>