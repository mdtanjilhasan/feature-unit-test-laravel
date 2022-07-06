<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" style="display: flex; align-items: center; justify-content: center;">
                    <div class="left-form" style="width: 50%;">
                        <form action="{{ isset($post) ? route('post.update', $post?->id) : route('post.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if(isset($post))
                                @method('PUT')
                            @endif

                            @php
                                $title = $shortDescription = $fullDetails = $image = '';
                                if (isset($post)) {
                                    $title = $post->title;
                                    $shortDescription = $post->short_description;
                                    $fullDetails = $post->full_details;
                                    $image = $post->image ?? '';
                                }
                            @endphp
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror " id="title" name="title" value="{{ old('title') ?? $title }}">

                                @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror " id="short_description" name="short_description">{{ old('short_description') ?? $shortDescription }}</textarea>
                                @error('short_description')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="full_details" class="form-label">Long Description</label>
                                <textarea class="form-control @error('full_details') is-invalid @enderror " id="full_details" name="full_details">{{ old('full_details') ?? $fullDetails }}</textarea>

                                @error('full_details')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                                @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="right-section" style="width: 50%;">
                        @if ($image)
                        <div style="display: flex; align-items: center; margin-right: 15px;">
                            <img style="display: block; max-height: 100%; max-width: 100%" src="{{ $image }}" alt="{{ $title }}">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
