@props(['headers' => []])

<div class="overflow-x-auto border border-gray-200 rounded-sm">
    <table class="w-full text-sm text-left">
        @if(count($headers) > 0)
        <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-4 font-semibold">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody class="divide-y divide-gray-200 bg-white">
            {{ $slot }}
        </tbody>
    </table>
</div>