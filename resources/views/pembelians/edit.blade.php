<x-layouts.app>
    @php
    $formColumns = collect($columns)->filter(fn($c) => $c['inCreateForm'] ?? false);
    $hasFile = $formColumns->contains(fn($c) => in_array($c['type'], ['file', 'image']));
    @endphp

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
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
        <a href="{{ route($table . '.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $title }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Create') }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create') }} {{ $title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $subheading ?? __('Fill in the details below') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 p-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="p-6">
            <form action="{{ route($table . '.update', $data->id) }}" method="POST" class="max-w-2xl"
                @if ($hasFile) enctype="multipart/form-data" @endif>
                @csrf
                @method('PUT')

                @foreach ($formColumns as $column)
                @php
                $name = $column['name'];
                $label = __($column['label'] ?? ucfirst(str_replace('_', ' ', $name)));
                $required = $column['required'] ?? false;
                $type = $column['type'] ?? 'string';
                $current = old($name, $data->{$name} ?? null);
                @endphp

                @switch($type)
                @case('email')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="email"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('password')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="password" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Leave blank to keep current password') }}
                    </p>
                </div>
                <div class="mb-4">
                    <x-forms.input :label="__('Confirm') . ' ' . $label" :name="$name . '_confirmation'"
                        type="password" />
                </div>
                @break

                @case('number')
                @case('integer')
                @case('decimal')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="number"
                        step="{{ $type === 'integer' ? '1' : '0.01' }}"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('date')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="date"
                        :value="$current instanceof \Carbon\CarbonInterface ? $current->format('Y-m-d') : $current"
                        :required="$required" />
                </div>
                @break

                @case('textarea')
                <div class="mb-4">
                    <x-forms.textarea :label="$label" :name="$name"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('select')
                <div class="mb-4">
                    <x-forms.select :label="$label" :name="$name"
                        :options="$column['options'] ?? []"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('enum')
                <div class="mb-4">
                    <x-forms.enum :label="$label" :name="$name"
                        :options="$column['options'] ?? []"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('file')
                @case('image')
                <div class="mb-4">
                    <x-forms.file :label="$label" :name="$name"
                        :accept="$type === 'image' ? 'image/*' : null"
                        :required="false" />
                    @if (!empty($data->{$name}))
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Current file') }}:
                        <a href="{{ asset('storage/' . $data->{$name}) }}" target="_blank"
                            class="text-blue-600 dark:text-blue-400 hover:underline">{{ basename($data->{$name}) }}</a>
                    </p>
                    @endif
                </div>
                @break

                @case('checkbox')
                @case('boolean')
                <div class="mb-4">
                    <x-forms.checkbox :label="$label" :name="$name"
                        :checked="(bool) $current" />
                </div>
                @break

                @default
                {{-- string / text --}}
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="text"
                        :value="$current" :required="$required" />
                </div>
                @endswitch
                @endforeach

                {{-- Pembelian Detail --}}
                <div
                    x-data="{
        items: @js(
            $data->details->map(fn ($detail) => [
                'id' => $detail->id,
                'emas_asal' => (string) $detail->emas_asal,
                'kadar' => $detail->kadar,
                'satuan' => $detail->satuan,
                'jumlah' => $detail->jumlah,
                'harga_per_satuan' => $detail->harga_per_satuan,
                'total_harga' => $detail->total_harga,
            ])->values(),
        ),
        modal: false,
        editingIndex: null,

        asalEmasData: @js($asal_emas_data ?? []),

        form: {
            pilih_emas: '',
            emas_asal: '',
            kadar: '',
            satuan: '',
            jumlah: '',
            harga_per_satuan: '',
            total_harga: '',
        },

        resetForm() {
            this.form = {
                pilih_emas: '',
                emas_asal: '',
                kadar: '',
                satuan: '',
                jumlah: '',
                harga_per_satuan: '',
                total_harga: '',
            };
        },

        openCreate() {
            this.editingIndex = null;
            this.resetForm();
            this.modal = true;
        },

        openEdit(index) {
            this.editingIndex = index;
            this.form = { ...this.items[index] };
            this.modal = true;
        },

        selectAsalEmas() {
            const data = this.asalEmasData[this.form.pilih_emas];

            if (!data) {
                this.form.emas_asal = '';
                this.form.kadar = 0;
                this.form.satuan = '';
                this.form.jumlah = 0;
                this.form.harga_per_satuan = 0;
                this.form.total_harga = 0;
                return;
            }

            this.form.emas_asal = data.emas_asal;
            this.form.kadar = data.kadar;
            this.form.satuan = data.satuan;
            this.form.harga_per_satuan = data.harga_per_satuan;

            this.calculateTotal();
        },

        calculateTotal() {
            const jumlah = Number(this.form.jumlah) || 0;
            const harga = Number(this.form.harga_per_satuan) || 0;

            this.form.total_harga = jumlah * harga;
        },

        save() {
            if (!this.form.pilih_emas) {
                alert('Silakan pilih  Emas.');
                return;
            }

            const item = { ...this.form };

            if (this.editingIndex !== null) {
                this.items.splice(this.editingIndex, 1, item);
            } else {
                this.items.push(item);
            }

            this.modal = false;
        },

        remove(index) {
            if (confirm('Hapus detail pembelian ini?')) {
                this.items.splice(index, 1);
            }
        },

        formatNumber(value) {
            return new Intl.NumberFormat('id-ID').format(value || 0);
        },

        get grandTotal() {
            return this.items.reduce(
                (total, item) => total + Number(item.total_harga || 0),
                0
            );
        }
    }"
                    class="mb-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <label class="font-medium text-gray-700 dark:text-gray-300">
                            Pembelian Detail
                        </label>

                        <button
                            type="button"
                            @click="openCreate()"
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                            + Tambah Detail
                        </button>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="p-3 text-left">Asal Emas</th>
                                    <th class="p-3 text-left">Kadar</th>
                                    <th class="p-3 text-left">Satuan</th>
                                    <th class="p-3 text-left">Jumlah</th>
                                    <th class="p-3 text-left">Harga per Satuan</th>
                                    <th class="p-3 text-left">Total Harga</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-t dark:border-gray-700">

                                        <td class="p-3">
                                            <span x-text="item.emas_asal"></span>
                                            <input type="hidden"
                                                :name="`details[${index}][emas_asal]`"
                                                :value="item.emas_asal">
                                        </td>

                                        <td class="p-3">
                                            <span x-text="item.kadar"></span>
                                            <input type="hidden"
                                                :name="`details[${index}][kadar]`"
                                                :value="item.kadar">
                                        </td>

                                        <td class="p-3">
                                            <span x-text="item.satuan"></span>
                                            <input type="hidden"
                                                :name="`details[${index}][satuan]`"
                                                :value="item.satuan">
                                        </td>

                                        <td class="p-3">
                                            <span x-text="item.jumlah"></span>
                                            <input type="hidden"
                                                :name="`details[${index}][jumlah]`"
                                                :value="item.jumlah">
                                        </td>

                                        <td class="p-3">
                                            <span x-text="formatNumber(item.harga_per_satuan)"></span>
                                            <input type="hidden"
                                                :name="`details[${index}][harga_per_satuan]`"
                                                :value="item.harga_per_satuan">
                                        </td>

                                        <td class="p-3">
                                            <span x-text="formatNumber(item.total_harga)"></span>
                                            <input type="hidden"
                                                :name="`details[${index}][total_harga]`"
                                                :value="item.total_harga">
                                        </td>

                                        <td class="p-3 text-center whitespace-nowrap">
                                            <button
                                                type="button"
                                                @click="openEdit(index)"
                                                class="text-blue-600 mr-2">
                                                Edit
                                            </button>

                                            <button
                                                type="button"
                                                @click="remove(index)"
                                                class="text-red-600">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="items.length === 0">
                                    <td colspan="7" class="p-5 text-center text-gray-500">
                                        Belum ada detail pembelian.
                                    </td>
                                </tr>
                            </tbody>

                            {{-- Grand Total --}}
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="5" class="p-3 text-right font-semibold">
                                        Grand Total
                                    </td>
                                    <td class="p-3 font-bold text-blue-600">
                                        <span x-text="formatNumber(grandTotal)"></span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Modal --}}
                    <div
                        x-show="modal"
                        x-cloak
                        @keydown.escape.window="modal = false"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                        <div
                            @click.outside="modal = false"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6">
                            <h3
                                class="text-lg font-semibold mb-5 text-gray-800 dark:text-gray-100"
                                x-text="editingIndex !== null ? 'Edit Detail Pembelian' : 'Tambah Detail Pembelian'"></h3>

                            {{-- Pilih Emas --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Pilih Emas
                                </label>

                                <select
                                    x-model="form.pilih_emas"
                                    @change="selectAsalEmas()"
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Asal Emas</option>

                                    @foreach ($asal_emas_options as $key => $value)
                                    <option value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kadar --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Asal Emas
                                </label>

                                <input
                                    type="text"
                                    x-model="form.emas_asal"
                                    readonly
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            {{-- Kadar --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Kadar
                                </label>

                                <input
                                    type="text"
                                    x-model="form.kadar"
                                    readonly
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            {{-- Satuan --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Satuan
                                </label>

                                <input
                                    type="text"
                                    x-model="form.satuan"
                                    readonly
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            {{-- Jumlah --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Jumlah
                                </label>

                                <input
                                    type="text"
                                    x-model="form.jumlah"
                                    @change="form.total_harga = form.jumlah * form.harga_per_satuan"
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            {{-- Harga per Satuan --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Harga per Satuan
                                </label>

                                <input
                                    type="text"
                                    x-model="form.harga_per_satuan"
                                    readonly
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            {{-- Total Harga --}}
                            <div class="mb-4">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    Total Harga
                                </label>

                                <input
                                    type="text"
                                    :value="formatNumber(form.total_harga)"
                                    readonly
                                    class="w-full px-4 py-2 rounded-lg border bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-semibold">
                            </div>

                            {{-- Modal Actions --}}
                            <div class="flex justify-end gap-3 mt-6">
                                <button
                                    type="button"
                                    @click="modal = false"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-lg">
                                    Batal
                                </button>

                                <button
                                    type="button"
                                    @click="save()"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <x-button type="primary">{{ __('Update') }}</x-button>
                    <a href="{{ route($table . '.index') }}"
                        class="text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors flex items-center justify-center cursor-pointer bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>