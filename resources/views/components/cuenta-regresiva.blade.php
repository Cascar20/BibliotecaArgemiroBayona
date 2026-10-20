@props(['segundos', 'fin' => 'Vencida'])

<span {{ $attributes->merge(['class' => 'fw-semibold']) }}
      data-cuenta-regresiva="{{ max(0, (int) $segundos) }}"
      data-texto-fin="{{ $fin }}">--:--:--</span>