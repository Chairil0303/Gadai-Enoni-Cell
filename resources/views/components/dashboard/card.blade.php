@props(['title', 'value', 'prefix' => ''])

<div class="bg-white rounded-xl shadow p-5">
    <div class="text-sm text-gray-500">{{ $title }}</div>
    <div class="text-2xl font-bold mt-1">{{ $prefix }}{{ $value }}</div>
</div>