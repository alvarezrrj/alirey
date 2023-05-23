@php($therapist = App\Models\Role::where('role', SD::admin)->first()->users()->first())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Load webp feature detection by modernizer -->
    @vite('resources/libraries/modernizr/modernizr-webp.js')

    <!-- Remove noscript class from <html> (stays on on no JS browsers) -->
    <script>
      document.documentElement.classList.remove('noscript');
    </script>

    {{-- Loading atribute polyfill --}}
    @vite(['resources/libraries/loading-attr/loading-attribute-polyfill.css'])

    @stack('css_libraries')
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @livewireScripts
  </head>

  <body class="bg-gray-100 dark:bg-gray-900 grid grid-rows-[auto_1fr_auto] text-gray-800 dark:text-gray-300">

    <header class="sticky top-0 z-50 shrink-0 grow-0">
      @includeWhen(Auth::user(), 'layouts.navigation')
      @includeUnless(Auth::user(), 'layouts.guest-navigation')
    </header>

      {{ $slot }}
    </main>

    <!-- ======= Footer ======= -->
    <footer class="w-full text-sm text-gray-300 bg-black">
      <div class="pb-8 bg-gray-900 pt-14">
        <div class="grid grid-cols-3 p-8">
          {{-- <div class="flex"> --}}

            <div class="col-span-3 p-4 pt-0 mb-8 border-l md:col-span-1">
              <noscript class="loading-lazy">
                <picture>
                  <source srcset="{{ Vite::asset('resources/img/sathy/biodescodificacion_constelaciones_en_cordoba_alicia_rey_logo_footer.webp') }}"
                  type="image/webp">
                  <img
                    alt="Biodescodificación y Constelaciones | Alicia Rey"
                    class="h-auto max-w-[4rem]"
                    height="507"
                    loading="lazy"
                    src="{{ Vite::asset('resources/img/sathy/biodescodificacion_constelaciones_en_cordoba_alicia_rey_logo_footer.png') }}"
                    title="Biodescodificación y Constelaciones | Alicia Rey"
                    width="507"
                    />
                </picture>
              </noscript>
              <p class="my-4 text-sm leading-6 text-gray-300">
              Alicia Rey. Abordaje terapéutico para mejorar tu ser. <strong>Bioconstelaciones, biodescodificación y constelaciones</strong>
              </p>
            </div>

            <div class="col-span-3 p-4 pt-0 mb-8 border-l md:col-span-1">
              <h4 class="mb-4">Enlaces</h4>
              <ul class="space-y-2 list-none">
                <li class="pt-0">
                  <a href="/">
                    🛖 Inicio
                  </a>
                </li>
                <li>
                  <a href="{{ route('bookings.create', $therapist) }}">
                    👉 Reservá tu sesión de bioconstelación
                  </a>
                </li>
                <li>
                  <a href="#biodescodificacion-testimonios">
                    🗨 <strong>Testimonios de Biodescodificación y constelaciones.</strong>
                  </a>
                </li>
                <li>
                  <a href="{{ route('terms') }}">
                    📜 Términos y condiciones
                  </a>
                </li>
                <li>
                  <a href="{{ route('privacy-policy') }}">
                    🙊 Política de privacidad
                  </a>
                </li>
              </ul>
            </div>

            <div class="col-span-3 p-4 pt-0 mb-8 border-l md:col-span-1">
              <h4>Contacto</h4>
              <address>
              <p class="leading-6">
              Fleming 180 <br>
              Cosquin 5166 <br>
              Argentina
              </p>
              </address>
              <span><strong>Email:</strong> <a href="mailto:alisonrey.ar@gmail.com">alisonrey.ar@gmail.com</a><br></span>

              <div class="pt-3 social-links">
                <a href="https://web.facebook.com/ali.rey.73"
                  class="inline-flex items-center justify-center mr-1 text-lg transition-all rounded-full facebook bg-brown w-9 h-9"
                  data-tooltip="facebook"
                  data-placement="top"
                  target="_blank">
                  <x-antdesign-facebook class="w-7 h-7"/>
                </a>
                <a href="https://www.instagram.com/bioconsteladora/"
                  class="inline-flex items-center justify-center mr-1 text-lg transition-all rounded-full instagram bg-brown w-9 h-9"
                  data-tooltip="instagram"
                  data-placement="bottom"
                  target="_blank">
                  <x-antdesign-instagram class="w-7 h-7"/>

                </a>
              </div>

            </div>

          {{-- </div> --}}
        </div>
      </div>

      <div class="w-full p-8">
        <div class="text-center">
          &copy; Copyright <strong>Pania</strong>. Todos los derechos reservados
        </div>
        <div class="text-sm text-center text-gray-200">
          Hecho con 💚 por <a href="https://rodrigoalvarez.co.uk/">Pania</a>
        </div>
        <div class="mt-4 text-center text-gray-300">
          ¿Ves un error? ¿Tenés una sugerencia? <a href="{{ route('contact.webmaster') }}">Contactanos</a>
        </div>
      </div>
    </footer><!-- End Footer -->

    <!-- Loading attribute polyfill -->
    @vite('resources/libraries/loading-attr/loading-attribute-polyfill.umd.js')

    @stack('js_libraries')
    @stack('scripts')

  </body>
</html>
