@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            {{-- @if (trim($slot) === 'Laravel') --}}
            <img src="{{ asset('/logo-a.png') }}" style="width: 150px" class="logo" alt="Emerji Mentors">
            {{-- @else
{{ $slot }}
@endi --}}
        </a>
    </td>
</tr>
