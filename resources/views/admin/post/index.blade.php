<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post List') }}
            <a href="{{ route('post.create') }}" style="border: 2px solid maroon; border-radius: 5px;padding: 10px; text-transform: uppercase;">Add New</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table style="width: 100%;">
                        <thead style="text-align: left;">
                        <tr>
                            <th style="width: 55%;">Title</th>
                            <th>Writer</th>
                            <th>Posted Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td style="display: flex; align-items: center; width: 100%; word-break: break-word;">
                                <div style="display: flex; align-items: center; height: 80px; width: 80px; margin-right: 15px;">
                                    <img style="display: block; max-height: 100%; max-width: 100%" src="{{ $post->image }}" alt="{{ $post->title }}">
                                </div>
                                {{ $post->title }}
                            </td>
                            <td>{{ $post?->user?->name }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('post.show', $post->id) }}">Edit</a>
                                <a style="color: red;" onclick="onDelete(this)" data-url="{{ route('post.delete', $post->id) }}" href="javascript:void(0)">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
