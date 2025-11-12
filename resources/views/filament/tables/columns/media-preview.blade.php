@php
$record = $getRecord();
@endphp

<div x-data="{ open: false }" class="cursor-pointer relative">
    {{-- Thumbnail Preview --}}
    @if($record->type === 'image')
    <img
        src="{{ asset($record->file_path) }}"
        alt="Image"
        class="rounded-md"
        style="width: 80px; height: 80px; object-fit: cover;"
        @click="open = true"
        x-show="!open"
        x-cloak>
    @elseif($record->type === 'video')
    <video
        src="{{ asset($record->file_path) }}"
        class="rounded-md"
        style="width: 80px; height: 80px; object-fit: cover;"
        muted
        @click="open = true"
        x-show="!open"
        x-cloak></video>
    @else
    <span x-show="!open">{{ $record->file_path }}</span>
    @endif

    <!-- Modal -->
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
        @click="
            if ($refs.modalVideo) { 
                $refs.modalVideo.pause(); 
                $refs.modalVideo.currentTime = 0; 
            }
            open = false
        ">
        <div class="relative max-w-4xl max-h-full p-4" @click.stop>
            <button
                @click="
                    if ($refs.modalVideo) { 
                        $refs.modalVideo.pause(); 
                        $refs.modalVideo.currentTime = 0; 
                    }
                    open = false
                "
                class="absolute top-2 right-2 text-white text-3xl hover:text-gray-300 z-10">&times;</button>

            {{-- Konten Modal --}}
            @if($record->type === 'image')
            <img
                src="{{ asset($record->file_path) }}"
                alt="Image"
                class="max-w-full max-h-full object-contain"
                style="width: 300px; height: 300px;">
            @elseif($record->type === 'video')
            <video
                x-ref="modalVideo"
                src="{{ asset($record->file_path) }}"
                controls
                class="max-w-full max-h-full object-contain"
                style="width: 500px; height: 300px;"
                x-show="open"
                x-init="
        $watch('open', value => {
            if (value && $refs.modalVideo) {
                $refs.modalVideo.play();
            } else if ($refs.modalVideo) {
                $refs.modalVideo.pause();
                $refs.modalVideo.currentTime = 0;
                        }
                    })
                ">
            </video>
            @endif



        </div>
    </div>
</div>