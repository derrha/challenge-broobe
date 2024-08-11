<nav class="w-full h-24 bg-[#503FE0] flex items-center">
    <img class=" sm:ml-10 h-12" src="https://www.broobe.com/wp-content/uploads/2022/12/logo-broobe.svg" alt="broobe-logo">
    <div class="w-full h-24 bg-[#503FE0] flex justify-center items-center gap-4">
        <a href="{{ route('home.metrics') }}" class="text-lg sm:text-xl font-medium text-white uppercase @if(Route::currentRouteName() === 'home.metrics') border-b-2 border-white @endif">Correr Métricas</a>
        <span class="text-2xl sm:text-3xl text-white">/</span>
        <a href="{{ route('home.history') }}" class="text-lg sm:text-xl font-medium uppercase text-white @if(Route::currentRouteName() === 'home.history') border-b-2 border-white @endif">Historial</a>
    </div>
</nav>
