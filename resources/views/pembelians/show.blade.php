<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route($table . '.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ $title }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">View {{ $title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $title }} details</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('edit-' . $table))
            <a href="{{ route($table . '.edit', $pembelian->id) }}">
                <x-button type="primary">{{ __('Edit Pembelian') }}</x-button>
            </a>
            @endif

            <a href="{{ route($table . '.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b border-gray-100 dark:border-gray-700 pb-6">
                <div>
                    <h3 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Informasi Pembelian</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">No. Invoice</label>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $pembelian->no_invoice }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customer</label>
                            <div class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $pembelian->nama_pembeli }}</div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal Transaksi</label>
                            <div class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $pembelian->tanggal->format('d F Y H:i') }}</div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Keterangan</label>
                            <div class="text-md text-gray-900 dark:text-gray-100">{{ $pembelian->keterangan ?? '-' }}</div>
                        </div>
                    </div>
                </div>


                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase mb-4">
                        Transaksi Pembelian
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Nominal
                            </label>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Tanggal Transaksi
                            </label>
                            <div class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                {{ $transaksi->tanggal->format('d F Y H:i') ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Kategori Transaksi
                            </label>
                            <div class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                {{ $transaksi->transaksiKategori?->name ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Toko
                            </label>
                            <div class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                {{ $transaksi->toko?->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <h3 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Daftar Produk</h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Asal Emas</th>
                                <th class="px-4 py-3 text-center">Kadar</th>
                                <th class="px-4 py-3 text-center">Satuan</th>
                                <th class="px-4 py-3 text-center">Harga Per Satuan</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3 text-center">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pembelian->details as $detail)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $detail->emas_asal }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $detail->kadar }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $detail->satuan }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $detail->jumlah }}
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($detail->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr class="font-semibold text-gray-900 dark:text-white">
                                <td class="px-4 py-3 text-right" colspan="4">Total:</td>
                                <td class="px-4 py-3 text-center">{{ number_format($pembelian->details->sum('jumlah'), 0) }}</td>
                                <td class="px-4 py-3 text-center font-bold">
                                    Rp {{ number_format($pembelian->totalHarga(), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <a href="{{ route($table . '.index') }}">
                    <x-button type="primary">Kembali ke Daftar</x-button>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>