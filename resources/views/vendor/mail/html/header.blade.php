@props(['url'])
<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
    @if (trim($slot) === 'Laravel')
      <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
    @else
      <img src="{{ Vite::asset('resources/img/sathy/biodescodificacion_constelaciones_en_cordoba_alicia_rey_logo_vertical.png') }}" class="logo" alt="{{ config('app.name') }}">
    @endif
    </a>
  </td>
</tr>
