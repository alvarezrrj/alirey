@php($therapist = App\Models\Role::where('role', SD::admin)->first()->users()->first())
<x-site-layout>
  @push('css_libraries')
    <!-- flickity -->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <!-- aos -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  @endpush

  @push('styles')
    <!-- Home styles -->
    @vite('resources/css/home-styles.css')
  @endpush

  <main class="lg:flex lg:items-start">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-20 hidden w-16 h-full p-6 transition-all duration-300 bg-transparent lg:w-80 lg:shrink-0 lg:flex lg:justify-end lg:items-start 2xl:max-w-lg 2xl:w-full">
      <ul class="absolute space-y-4 list-none dark:bg-inherit dark:text-gray-200 text-end lg:sticky lg:top-10"
        x-data="{
          links: $el.querySelectorAll('a'),
          toggleActiveClass() {
            position = window.scrollY;
            this.links.forEach(link => {
              if (!link.hash) return;
              section = document.querySelector(link.hash);
              if (!section) return;
              if (position >= section.offsetTop
                && position < section.offsetTop + section.offsetHeight) {
                link.classList.add('text-skin', 'font-bold', 'transition-all');
              } else {
                link.classList.remove('text-skin', 'font-bold');
              }
            })
          }
        }"
        @scroll.document="toggleActiveClass"
        @load.window="toggleActiveClass">
        <span class="text-lg font-semibold">Contenidos</span>
        <li><a href="#biodescodificacion-inicio">Inicio</a></li>
        <li><a href="#biodescodificacion-testimonios">Testimonios</a></li>
        <li><a href="#biodescodificacion-que-es">Qué es la constelación</a></li>
        <li><a href="#biodescodificacion-para-que">Para qué constelar</a></li>
        <li><a href="#biodescodificacion-contacto">Contacto</a></li>
    </nav>

    {{-- Content --}}
    <div class="relative z-20 max-w-screen-lg px-8 space-y-20 dark:text-gray-200 sm:px-16 lg:px-18 xl:px-22 2xl:px-24">

      <section id="biodescodificacion-inicio" class="pb-20 border-b border-skin"
        class="mt-12">

        <div class="flex flex-col items-center lg:flex-row justify-evenly" data-aos="fade-up">
          {{-- Hero image --}}
          <div class="flex items-center justify-center w-4/5 md:w-1/2 lg:order-2"
            data-aos-delay="200"
            data-aos="zoom-out"
            >
            <picture>
              <source
                srcset="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_en_cordoba_alicia_rey_perfil.webp') }}"
                type="image/webp">
              <img
                alt="Biodescodificación y Constelaciones | Alicia Rey"
                class="w-[320px] max-w-full h-auto rounded-[5px_7rem_/_5px_7rem] border-4 border-skin"
                height="717"
                src="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_en_cordoba_alicia_rey_perfil.jpg') }}"
                title="Biodescodificación y Constelaciones | Alicia Rey"
                width="528"
                />
            </picture>
          </div>

          {{-- Hero content --}}
          <div class="flex flex-col items-center justify-center w-4/5 text-center lg:order-1 md:text-start md:w-1/2"
            data-aos-delay="100"
            data-aos="zoom-in"
              >
            <picture>
              <source srcset="{{ Vite::asset('resources/img/sathy/biodescodificacion_constelaciones_en_cordoba_alicia_rey_logo_vertical.webp') }}" type="image/webp">
              <img
                alt="Biodescodificación y Constelaciones | Alicia Rey"
                class="h-auto"
                height="1778"
                id="hero-logo"
                src="{{ Vite::asset('resources/img/sathy/biodescodificacion_constelaciones_en_cordoba_alicia_rey_logo_vertical.png') }}"
                title="Biodescodificación y Constelaciones | Alicia Rey"
                width="1778"
                />
            </picture>
            <p class="dark:text-gray-200">
              Mi nombre es Alicia Rey, y mediante <strong>terapias de Biodescodificación y Constelaciones</strong>, colaboro para tu autoconocimiento, brindándote herramientas que te permiten resolver tus conflictos de manera independiente, disfrutando el Aquí y Ahora.
            </p>
          </div>
        </div>

        {{-- Hero CTA --}}
        <div class="flex justify-center mt-12">
          <a target="_blank" href="https://api.whatsapp.com/send/?phone=5493541221161&text&type=phone_number&app_absent=1">
            <x-primary-button>
              Reservá tu consulta
            </x-primary-button>
          </a>
        </div>

        <div class="flex justify-center">
          <x-phosphor-flower-lotus class="relative w-6 h-6 top-20"/>
        </div>
      </section>

      {{-- Testimonials --}}
      <section id="biodescodificacion-testimonios" class="pb-20 border-b border-skin">
        <header>
          <h3 class="home-section-header">Testimonios</h3>
        </header>

        <div class="flex justify-center">
          <div class="w-full xl:w-4/5">

            <div
              class="main-carousel"
              data-flickity='{"prevNextButtons": false, "autoPlay": 4000}'
              data-aos="fade-up"
              >

              <div class="carousel-cell">
                <div class="flex flex-wrap justify-center lg:flex-nowrap testimonial-item lg:justify-start">
                  <noscript class="loading-lazy">
                    <picture>
                      <source srcset="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_testimonio1_que_es_biodescodificacion.webp') }}" type="image/webp">
                      <img
                          alt="Biodescodificación y Constelaciones | Alicia Rey"
                          class="h-auto testimonial-img"
                          height="693"
                          loading="lazy"
                          src="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_testimonio1_que_es_biodescodificacion.jpg') }}"
                          title="Biodescodificación y Constelaciones | Alicia Rey"
                          width="694"
                          />
                    </picture>
                  </noscript>
                  <div class="w-full md:ms-4 md:w-auto">
                    <h3 class="testimonial-title">
                      Caro Guil Lopez
                    </h3>
                    <h4 class="testimonial-subtitle">No sabía <strong>para qué sirve constelar</strong> y ahora lo entiendo perfectamente</h4>
                    <p class="testimonial-text">
                    Una amiga me explicó por encima <strong>qué es constelar y para qué sirve</strong>, y me convenció. En mi primera <strong>sesión de constelación con Alicia Rey</strong>, tratamos un tema puntual, y ahí mismo pude observar y darme cuenta que, no solo esa, sino varias cuestiones estaban limitándome. Me gustó, y me sirvió mucho el proceso. A los pocos días pude experimentar cambios desde mi percepción y de las personas a mi alrededor, gracias a la <strong>terapia de constelaciones</strong> que hicimos juntas.
                    </p>
                  </div>
                </div>
              </div>

              <div class="carousel-cell">
                <div class="flex flex-wrap justify-center lg:flex-nowrap testimonial-item lg:justify-start">
                  <noscript class="loading-lazy">
                    <picture>
                      <source srcset="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_testimonio3_para_que_sirve_biodescodificacion.webp') }}" type="image/webp">
                      <img
                          alt="Biodescodificación y Constelaciones | Testimonios"
                          class="h-auto testimonial-img"
                          height="523"
                          loading="lazy"
                          src="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_testimonio3_para_que_sirve_biodescodificacion.jpg') }}"
                          title="Biodescodificación y Constelaciones | Testimonios"
                          width="540"
                          />
                    </picture>
                  </noscript>
                  <div class="w-full md:ms-4 md:w-auto">
                    <div class="flex flex-wrap items-end justify-center lg:justify-start">
                      <h3 class="inline-block w-full testimonial-title lg:w-auto">
                        Guillermo Alejandro Pagura
                      </h3>
                      <a
                        class="inline-block px-4"
                        href="https://www.facebook.com/guillermoalejandropagura"
                        target="_blank"
                        title="Ver perfil en facebook">
                        <x-antdesign-facebook class="w-10"/>
                      </a>
                      <a
                        class="inline-block px-4"
                        href="https://youtube.com/c/DrGuillermoPagura"
                        target="_blank"
                        title="Ver canal de YouTube">
                        <x-antdesign-youtube  class="w-10"/>
                      </a>
                    </div>
                    <h4 class="testimonial-subtitle">
                      Médico, homeópata y pediatra. Especialista en <strong>Biodescodificación y Constelaciones</strong>
                    </h4>
                    <p class="testimonial-text">
                      Como especialista, conozco las <strong>ventajas de la biodescodificación</strong>. La experiencia con Alicia fue maravillosa. No sólo por su calidez humana, si no también por la exactitud de sus observaciones. Lo que dio como resultado, que pudiera solucionar el problema que me aquejaba. Quedé muy conforme y agradecido.😊
                    </p>
                  </div>
                </div>
              </div>

              <div class="carousel-cell">
                <div class="flex flex-wrap justify-center lg:flex-nowrap testimonial-item lg:justify-start">
                  <noscript class="loading-lazy">
                    <picture>
                      <source srcset="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_testimonio2_beneficios_de_biodescodificacion.webp') }}" type="image/webp">
                      <img
                          alt="Biodescodificación y Constelaciones | Testimoniosy"
                          class="h-auto testimonial-img"
                          height="814"
                          loading="lazy"
                          src="{{ Vite::asset('resources/img/biodescodificacion_constelaciones_testimonio2_beneficios_de_biodescodificacion.jpg') }}"
                          title="Biodescodificación y Constelaciones | Testimonios"
                          width="813"
                          />
                    </picture>
                  </noscript>
                  <div class="w-full md:ms-4 md:w-auto">
                    <h3 class="inline-block w-full testimonial-title lg:w-auto">
                      Bibi Occhetti
                    </h3>
                    <h4 class="testimonial-subtitle">
                      Ahora sé <strong>cómo puede ayudarme la biodescodificación</strong>
                    </h4>
                    <p class="testimonial-text">
                      Al principio no sabía donde ni con quién hacer <strong>una sesión de constelaciones</strong>. Por una amiga, llegué a Alicia. Luego de mi primera sesión juntas, se movieron muchas cosas en mi vida, me pude comunicar con mi papá y perdonarlo. Ahora ya sé <strong>para qué sirve constelar</strong> y estoy muy satisfecha con el resultado de la terapia.
                    </p>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
        <div class="flex justify-center">
          <x-phosphor-flower-lotus class="relative w-6 h-6 top-20"/>
        </div>
      </section>

      {{-- What is it --}}
      <section id="biodescodificacion-que-es" class="pb-20 border-b border-skin"
        data-aos="fade-up" >

        <header>
          <h3 class="home-section-header">¿Que es la Bioconstelación?</h3>
          <p class="m-auto text-center text-gray-700 pb-14 dark:text-gray-300">
          <strong>Bioconstelación es una nueva metodología</strong> que, a través del reconocimiento del orden ancestral, permite restablecer conflictos emocionales, sociales o físicos, acelerando los procesos de equilibrio en nuestro interior y entorno familiar. La <strong>bioconstelación</strong> está basada en dos conceptos:
          </p>
        </header>

        <div class="flex flex-wrap justify-center">

          <div class="w-full md:w-1/2" data-aos="zoom-in" data-aos-delay="100"
            >
            <div class="home-box">
              <h4 class="box-title">
                Biodescodificación
              </h4>
              <p class="box-desc">
                El concepto que responde a <strong>qué es la biodescodificación</strong> es que todo síntoma físico es el reflejo de un conflicto no resuelto. Abordando dicho conflicto la solución aparece.
              </p>
            </div>
          </div>

          <div class="w-full md:w-1/2" data-aos="zoom-in" data-aos-delay="200"
            >
            <div class="home-box">
              <h4 class="box-title">
                Constelación
              </h4>
              <p class="box-desc">
                La manera sintética de responder a <strong>qué es constelar</strong> es poder ver desde "afuera" y objetivamente tal y como es, lo que desde nuestro interior, vemos como creemos que es.
              </p>
            </div>
          </div>

          <div class="w-full md:w-3/5" data-aos="zoom-in" data-aos-delay="100"
            >
            <div class="home-box">
              <h4 class="box-title">
                ¿Cómo se desarrolla una sesión de&nbsp;bioconstelación?
              </h4>
              <p class="box-desc">
                <strong>Una sesión de bioconstelación</strong> es diferente para cada persona. <strong>La terapia de bioconstelación</strong> se va construyendo encuentro tras encuentro, en base a la necesidad del consultante disponiendo de los elementos y recursos que la <strong>biodescodificación y constelaciones</strong> nos ofrecen para trabajar la situación o contexto de cada individuo.
              </p>
              <p class="box-desc">
                <strong>Una sesión de bioconstelación</strong> dura entre una hora y una hora y media. Debido a las <strong>características de la terapia de bioconstelación</strong>, es posible realizar una nueva sesión si la persona asi lo siente, pero siempre debe ser al menos 21 dias luego de la primera.
              </p>
            </div>
          </div>

        </div>

        {{-- CTA --}}
        <div class="flex justify-center mt-12">
          <a target="_blank" href="https://api.whatsapp.com/send/?phone=5493541221161&text&type=phone_number&app_absent=1">
            <x-primary-button>
              Reservá tu consulta
            </x-primary-button>
          </a>
        </div>

        <div class="flex justify-center">
          <x-phosphor-flower-lotus class="relative w-6 h-6 top-20"/>
        </div>
      </section>

      {{-- Why --}}
      <section id="biodescodificacion-para-que" class="pb-20 border-b border-skin">
        <header>
          <h3 class="home-section-header">¿Para qué hacer una sesión?</h3>
          <p class="m-auto text-center text-gray-700 pb-14 dark:text-gray-300">
            Son multiples las <strong>ventajas de la bioconstelación</strong>. Bioconstelar sirve para generar un montón de herramientas que podés usar de manera independiente:
          </p>
        </header>

        <div class="grid grid-rows-3 gap-4 md:grid-cols-5 lg:grid-rows-2 lg:grid-cols-12">

          <div class="row-start-1 row-end-2 md:col-start-2 md:col-span-3 lg:col-start-2 lg:col-span-5"
            data-aos="fade-right" data-aos-delay="200" >
            {{-- <div class="w-full mb-4 md:w-3/4 lg:w-1/2"> --}}
            <div class="h-full home-card">
              <p class="flex justify-center">
                <x-bi-balloon-heart class="w-12 h-12"/>
              </p>
              <div class="p-4">
                <h4 class="mb-6 text-xl font-bold">
                  Para vivir relajados el día a día
                </h4>
                  <ul class="text-center list-disc md:text-start ps-8">
                    <li>Para quitarle importancia al afuera</li>
                    <li>Para amigarte con el dinero</li>
                    <li>Para mejorar las relaciones</li>
                  </ul>
              </div>
            </div>
          </div>

          <div class=" md:col-start-2 md:col-span-3 lg:col-span-5"
            data-aos="fade-left" data-aos-delay="200" >
            <div class="home-card">
              <p class="flex justify-center">
                <x-bi-chat-square-text class="w-12 h-12"/>
              </p>
              <div class="p-4">
                <h4 class="mb-6 text-xl font-bold">
                  Para aprender a verte y registrarte
                </h4>
                  <ul class="text-center list-disc md:text-start ps-8">
                    <li>Para accionar en base a la realidad</li>
                    <li>Para cambiar patrones de conducta</li>
                    <li>Para aprender a verte reflejado/a en el entorno</li>
                    <li>Para ser coherente con tu necesidad</li>
                  </ul>
              </div>
            </div>
          </div>

          <div class="md:col-start-2 md:col-span-3 lg:col-span-8 lg:col-start-3"
            data-aos="fade-up"  data-aos-delay="200">
            <div class="home-card">
              <p class="flex justify-center">
                <x-bi-arrow-clockwise class="w-12 h-12"/>
              </p>
              <div class="p-4">
                <h4 class="mb-6 text-xl font-bold">
                  Para conectar con las emociones
                </h4>
                  <ul class="text-center list-disc md:text-start ps-8">
                    <li>Para reconocerlas</li>
                    <li>Para sanar</li>
                  </ul>
              </div>
            </div>
          </div>

        </div>

        {{-- CTA --}}
        <div class="flex justify-center mt-12">
          <a target="_blank" href="https://api.whatsapp.com/send/?phone=5493541221161&text&type=phone_number&app_absent=1">
            <x-primary-button>
              Reservá tu consulta
            </x-primary-button>
          </a>
        </div>

        <div class="flex justify-center">
          <x-phosphor-flower-lotus class="relative w-6 h-6 top-20"/>
        </div>
      </section>

      {{-- Contact --}}
      <section id="biodescodificacion-contacto" class="pb-20 border-b border-skin"
        data-aos="fade-up">
        <header>
          <h3 class="home-section-header">¡Hagamos Contacto!</h3>
          <p class="m-auto text-center text-gray-700 pb-14 dark:text-gray-300">
            Empezá hoy a entender <strong>para qué sirve la biodescodificación y las constelaciones</strong>
          </p>
        </header>


        <div class="flex justify-center">
          <div class="w-full px-4 py-8 rounded-lg shadow sm:px-16 bg-gray-300/50 dark:bg-gray-800/50 md:w-3/4 lg:w-3/5 xl:w-1/2">

            <x-alert-message key="message" class="mb-4"/>

            <form
              method="POST"
              action="{{ route('contact') }}">
              @csrf

              <!-- Name -->
              <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                  :value="old('name')" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
              </div>

              <!-- Email Address -->
              <div class="mt-4">
                  <x-input-label for="email" :value="__('Email')" />
                  <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required />
                  <x-input-error :messages="$errors->get('email')" class="mt-2" />
              </div>

              <!-- About -->
              <div class="mt-4">
                <x-input-label for="subject" :value="__('Subject')" />
                <x-text-input id="subject" class="block w-full mt-1" type="text" name="subject" :value="old('subject')" placeholder="{{ __('Optional') }}"/>
                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
              </div>

              <!-- Message -->
              <div class="mt-4">
                <x-input-label for="message" :value="__('Message')" />
                <x-text-area id="message" class="block w-full mt-1" type="text" name="message" :value="old('message')" required></x-text-area>
                <x-input-error :messages="$errors->get('message')" class="mt-2" />
              </div>

              <div class="flex justify-center">
                <x-primary-button class="mt-4" type="submit">
                    {{ __('Send') }}
                </x-primary-button>
              </div>

            </form>
          </div>
        </div>

        <div class="flex justify-center">
          <x-phosphor-flower-lotus class="relative w-6 h-6 top-20"/>
        </div>
      </section>

    </div>

    {{-- WhatsApp button --}}
    <a
      class="z-20 fixed h-10 w-10 bottom-6 right-6 rounded-full bg-[#32bb46] flex justify-center items-center"
      href="https://api.whatsapp.com/send/?phone=5493541221161&text&type=phone_number&app_absent=1">
      <x-antdesign-whats-app-o
        class="w-8 h-8 text-white dark:text-gray-900 "
        role="img"
        aria-label="Contactar por WhatsApp"/>
    </a>
  </main>

  @push('js_libraries')
    <!-- aos -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  @endpush

  @push('scripts')
    <script>
      AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        offset: -500,
        anchorPlacement: 'center-center',
      })
    </script>
  @endpush
</x-site-layout>
