@props(['isHeader' => false])

@if($isHeader)
    <th {{ $attributes->merge(['class' => 'px-6 py-4 font-semibold text-gray-700']) }}>
        {{ $slot }}
    </th>
@else
    <td {{ $attributes->merge(['class' => 'px-6 py-4 text-gray-600']) }}>
        {{ $slot }}
    </td>
@endif