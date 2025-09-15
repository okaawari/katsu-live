@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-400/50 focus:bg-white/20 transition-all duration-200 backdrop-blur-sm']) !!}>
