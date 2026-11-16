
@props([
    'name',
    'label',
    'fields' => [],
])

<div
    x-data="{
        items: [],
        modal: false,
        editingIndex: null,
        form: {},

        openCreate() {
            this.editingIndex = null;
            this.form = {};
            this.modal = true;
        },

        openEdit(index) {
            this.editingIndex = index;
            this.form = { ...this.items[index] };
            this.modal = true;
        },

        save() {
            if (this.editingIndex !== null) {
                this.items[this.editingIndex] = { ...this.form };
            } else {
                this.items.push({ ...this.form });
            }

            this.modal = false;
        },

        remove(index) {
            this.items.splice(index, 1);
        }
    }"
    class="mb-6"
>
    <div class="flex items-center justify-between mb-3">
        <label class="font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>

        <button type="button"
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
                    @foreach ($fields as $field)
                        <th class="p-3 text-left">
                            {{ $field['label'] }}
                        </th>
                    @endforeach
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(item, index) in items" :key="index">
                    <tr class="border-t dark:border-gray-700">
                        @foreach ($fields as $field)
                            <td class="p-3">
                                <span x-text="item['{{ $field['name'] }}']"></span>

                                <input type="hidden"
                                    :name="'{{ $name }}[' + index + '][{{ $field['name'] }}]'"
                                    :value="item['{{ $field['name'] }}']">
                            </td>
                        @endforeach

                        <td class="p-3 text-center whitespace-nowrap">
                            <button type="button"
                                @click="openEdit(index)"
                                class="text-blue-600 mr-2">
                                Edit
                            </button>

                            <button type="button"
                                @click="remove(index)"
                                class="text-red-600">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </template>

                <tr x-show="items.length === 0">
                    <td colspan="{{ count($fields) + 1 }}"
                        class="p-5 text-center text-gray-500">
                        Belum ada detail.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div x-show="modal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

        <div @click.outside="modal = false"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6">

            <h3 class="text-lg font-semibold mb-5 text-gray-800 dark:text-gray-100"
                x-text="editingIndex !== null ? 'Edit Detail' : 'Tambah Detail'">
            </h3>

            @foreach ($fields as $field)
                <div class="mb-4">
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        {{ $field['label'] }}
                    </label>

                    @if ($field['type'] === 'select')
                        <select
                            x-model="form['{{ $field['name'] }}']"
                            class="w-full px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih {{ $field['label'] }}</option>
                            @foreach ($field['options'] as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    @else
                    <input
                        type="{{ $field['type'] ?? 'text' }}"
                        x-model="form['{{ $field['name'] }}']"
                        class="w-full px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                    @endif
                </div>
            @endforeach

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                    @click="modal = false"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-lg">
                    Batal
                </button>

                <button type="button"
                    @click="save()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
