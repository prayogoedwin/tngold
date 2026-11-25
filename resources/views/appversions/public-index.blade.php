<x-layouts.tanpa-login>


    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $title }}</h1>
        </div>
        <div class="flex gap-2">

        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Section Wrapper Form Filter -->
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">



            <div class="flex flex-col sm:flex-row sm:items-end gap-4 max-w-3xl mb-4">
                <!-- Type Select Group -->
                <div class="flex-1">
                    <label for="tipe" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                        Tipe
                    </label>
                    <div class="relative">
                        <select id="tipe" name="tipe" class="block w-full px-3 py-2 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 transition-colors">
                            <option value="">Semua Tipe</option>

                            <option value="Pembelian" {{ request('tipe') == 'Pembelian' ? 'selected' : '' }}>
                                Pembelian
                            </option>
                            <option value="Internal" {{ request('tipe') == 'Internal' ? 'selected' : '' }}>
                                Internal
                            </option>
                        </select>
                    </div>
                </div>
            </div>





            <div class="flex flex-col sm:flex-row sm:items-end gap-4 max-w-3xl">

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 pt-2 sm:pt-0">
                    <button type="submit" onclick="tableReload()"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all cursor-pointer h-[38px]">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 8.293A1 1 0 013 7.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <a href="{{ route('app-versions.index') }}"
                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none transition-all h-[38px] hidden" id="reset-filter-button">
                        Reset
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 overflow-x-auto">
            <table id="dynamic-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                        @foreach ($columns as $column)
                        @if ($column['inTable'] ?? false)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __($column['label']) }}</th>
                        @endif
                        @endforeach




                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <div id="download-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Enter Access Code</h2>
            <form id="download-form" method="POST" action="{{ route('app-versions.download') }}">
                @csrf
                <input type="hidden" name="_method" value="GET">
                <input type="hidden" name="id" id="download-id">
                <div class="mb-4">
                    <label for="kode_akses" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Access Code</label>
                    <input type="text" name="kode_akses" id="kode_akses" class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeDownloadModal()" class="mr-2 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Download</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDownloadModal(id) {
            document.getElementById('download-id').value = id;
            document.getElementById('download-modal').classList.remove('hidden');
        }

        function closeDownloadModal() {
            document.getElementById('download-modal').classList.add('hidden');
        }
    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- hidden variable for js -->
    <input type="hidden" id="table" value="{{ $table }}">
    <input type="hidden" id="title" value="{{ $title }}">
    <input type="hidden" id="actions" value="{{ $table_has_actions ?? false }}">
    <input type="hidden" id="columns" value="{{ collect($columns) }}">



    <script>
        $(document).ready(function() {
            const columnscollect = JSON.parse($('#columns').val())
            const columns = [{
                'name': 'id',
                'data': 'id',
                defaultContent: '-'
            }]

            for (let c of columnscollect) {
                if (c.inTable) columns.push({
                    data: c.data,
                    name: c.display_value,
                    defaultContent: '-'
                });
            }





            console.log(columns)

            $('#dynamic-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route($table . '.indexTable') }}",
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

        function tableReload() {
            $('#dynamic-table').DataTable().ajax.reload();



            // Show the reset button
            document.getElementById('reset-filter-button').classList.remove('hidden');

        }
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
</x-layouts.tanpa-login>