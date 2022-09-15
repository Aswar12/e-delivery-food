<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Food &raquo; {{ $food->name }}&raquo; Gallery
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('food.gallery.create', $food->id) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                    + Upload Photos
                </a>
            </div>
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable" class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="border px-2 py-4">ID</th>
                                <th class="border px-6 py-4">Photo</th>
                                <th class="border px-6 py-4">Featured</th>
                                <th class="border px-6 py-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gallery as $item )
                            <tr>
                                <td class="border px-6 py-4">{{ $item->id }}</td>
                                <td class="border px-6 py-4">
                                    <img style="max-width: 150px" src="{{ $item->url   }}" />
                                </td>
                                <td class="border px-6 py-4">{{ $item->id }}</td>
                                <td class="border px-6 py-4">
                                    <form class="inline-block" action=" {{ route('gallery.destroy', $item->id ) }}"
                                        method="POST">
                                        {!! method_field('delete') . csrf_field() !!}
                                        <button
                                            class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                            Hapus
                                        </button>
                                    </form>
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