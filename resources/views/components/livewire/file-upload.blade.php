<div>
    <input type="file" wire:model="file">

    <div wire:loading wire:target="file">
        Uploading...
    </div>

    @if ($file)
        <div>
            {{ $file->getClientOriginalName() }}
        </div>
    @endif

    @error('file')
        <span class="text-red-500">{{ $message }}</span>
    @enderror
</div>