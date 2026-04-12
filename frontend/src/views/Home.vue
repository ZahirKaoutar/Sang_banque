<template>
    <div id="app">

  <!-- ====== NAVBAR ====== -->
  <nav class="fixed top-0 w-full z-50 backdrop-blur-md bg-white/90 border-b border-red-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center" style="background: var(--red-mid);">
          <svg viewBox="0 0 24 24" class="w-5 h-5 fill-white">
            <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0016 0c0-3.5-4-7-8-12z"/>
          </svg>
        </div>
        <span class="font-display text-xl font-bold" style="color: var(--red-deep);">HemoLife</span>
      </div>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center gap-8">
        <a v-for="item in navItems" :key="item.label" :href="item.href"
           class="nav-link text-sm font-medium text-gray-600 hover:text-red-700 transition-colors">
          {{ item.label }}
        </a>
      </div>

      <!-- CTA + Mobile toggle -->
      <div class="flex items-center gap-3">
        <button @click="showModal = true"
                class="cta-btn hidden md:block text-sm font-semibold px-5 py-2.5 rounded-full text-white transition-all hover:opacity-90"
                style="background: var(--red-mid);">
          Donner maintenant
        </button>
        <button @click="menuOpen = !menuOpen" class="md:hidden p-2 rounded-lg hover:bg-red-50">
          <div class="w-5 h-0.5 bg-gray-700 mb-1 transition-all" :class="menuOpen ? 'rotate-45 translate-y-1.5' : ''"></div>
          <div class="w-5 h-0.5 bg-gray-700 mb-1 transition-all" :class="menuOpen ? 'opacity-0' : ''"></div>
          <div class="w-5 h-0.5 bg-gray-700 transition-all" :class="menuOpen ? '-rotate-45 -translate-y-1.5' : ''"></div>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="menuOpen" class="md:hidden bg-white border-t border-red-50 px-6 py-4 flex flex-col gap-4 mobile-menu">
      <a v-for="item in navItems" :key="item.label" :href="item.href"
         class="text-sm font-medium text-gray-700 hover:text-red-700">{{ item.label }}</a>
      <button @click="showModal = true"
              class="cta-btn text-sm font-semibold px-5 py-3 rounded-full text-white"
              style="background: var(--red-mid);">
        Donner maintenant
      </button>
    </div>
  </nav>

  <!-- ====== HERO ====== -->
  <section class="blood-bg min-h-screen flex items-center pt-24 pb-16 px-6 relative overflow-hidden">
    <!-- Background decorative drops -->
    <div class="absolute top-20 right-10 opacity-5 pointer-events-none">
      <svg viewBox="0 0 200 280" class="w-48 h-48">
        <path d="M100 10C60 70 10 100 10 160a90 90 0 00180 0c0-60-50-90-90-150z" fill="#C0392B"/>
      </svg>
    </div>
    <div class="absolute bottom-20 left-8 opacity-5 pointer-events-none">
      <svg viewBox="0 0 120 160" class="w-28 h-28">
        <path d="M60 5C35 40 5 60 5 95a55 55 0 00110 0c0-35-30-55-55-90z" fill="#C0392B"/>
      </svg>
    </div>

    <div class="max-w-6xl mx-auto w-full grid md:grid-cols-2 gap-12 items-center">
      <!-- Left Content -->
      <div>
        <div class="hero-text-reveal delay-1 inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium mb-6"
             style="background: var(--red-pale); color: var(--red-mid);">
          <span class="w-2 h-2 rounded-full pulse-ring inline-block" style="background: var(--red-light);"></span>
          Programme actif — Collecte en cours
        </div>

        <h1 class="hero-text-reveal delay-2 font-display text-5xl md:text-6xl lg:text-7xl leading-tight mb-6" style="color: var(--dark);">
          Votre sang,<br/>
          <span style="color: var(--red-mid);">une vie</span><br/>
          sauvée.
        </h1>

        <p class="hero-text-reveal delay-3 text-lg text-gray-500 leading-relaxed mb-10 max-w-md">
          Chaque don de sang peut sauver jusqu'à <strong class="text-gray-700">3 vies</strong>. Rejoignez notre réseau de donneurs et participez à l'un des actes humanitaires les plus importants.
        </p>

        <div class="hero-text-reveal delay-4 flex flex-wrap gap-4">
          <button @click="showModal = true"
                  class="cta-btn flex items-center gap-2 px-7 py-4 rounded-full text-white font-semibold text-base shadow-lg"
                  style="background: var(--red-mid);">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0016 0c0-3.5-4-7-8-12z"/>
            </svg>
            Faire un don
          </button>
          <a href="#about"
             class="flex items-center gap-2 px-7 py-4 rounded-full font-semibold text-base border-2 border-red-200 hover:border-red-400 transition-colors"
             style="color: var(--red-mid);">
            En savoir plus
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Right — Animated Blood Drop + Stats -->
      <div class="flex flex-col items-center gap-8">
        <!-- Central drop -->
        <div class="relative drop-anim">
          <div class="w-52 h-52 rounded-full flex items-center justify-center shadow-2xl"
               style="background: linear-gradient(135deg, var(--red-light), var(--red-deep));">
            <svg viewBox="0 0 120 160" class="w-28 h-28 fill-white drop-shadow-lg">
              <path d="M60 8C30 55 5 80 5 110a55 55 0 00110 0c0-30-25-55-55-102z"/>
            </svg>
          </div>
          <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full text-white text-xs font-semibold shadow-md"
               style="background: var(--red-deep);">
            Groupe O+ universel
          </div>
        </div>

        <!-- Mini stats -->
        <div class="grid grid-cols-3 gap-4 w-full max-w-sm">
          <div v-for="s in heroStats" :key="s.label"
               class="stat-card bg-white rounded-2xl p-4 text-center shadow-sm border border-red-50 transition-all duration-300">
            <div class="font-display text-2xl font-bold mb-1" style="color: var(--red-mid);">{{ s.value }}</div>
            <div class="text-xs text-gray-400 leading-tight">{{ s.label }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== GROUPES SANGUINS ====== -->
  <section id="blood-types" class="py-20 px-6 bg-white">
    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-14">
        <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: var(--red-light);">Compatibilité</p>
        <h2 class="font-display text-4xl md:text-5xl" style="color: var(--dark);">Groupes sanguins</h2>
        <p class="text-gray-400 mt-4 max-w-md mx-auto">Sélectionnez votre groupe sanguin pour voir votre compatibilité avec les receveurs.</p>
      </div>

      <div class="grid grid-cols-4 md:grid-cols-8 gap-3 mb-10">
        <div v-for="bt in bloodTypes" :key="bt.type"
             @click="selectedBloodType = bt.type"
             class="blood-type-card rounded-2xl p-4 text-center border-2 transition-all duration-300"
             :class="selectedBloodType === bt.type ? 'text-white border-transparent shadow-lg' : 'bg-white border-red-100 text-gray-600'"
             :style="selectedBloodType === bt.type ? 'background: var(--red-mid); color: white;' : ''">
          <div class="font-display text-2xl font-bold">{{ bt.type }}</div>
          <span class="text-xs mt-1 block opacity-75">{{ bt.percent }}%</span>
        </div>
      </div>

      <!-- Compatibilité info -->
      <div v-if="selectedBloodType" class="rounded-3xl p-8 border border-red-100 bg-red-50/50 transition-all">
        <div class="flex flex-wrap items-start gap-8">
          <div>
            <p class="text-sm text-gray-500 mb-2">Peut donner à</p>
            <div class="flex gap-2 flex-wrap">
              <span v-for="r in currentBloodType.canGiveTo" :key="r"
                    class="px-3 py-1.5 rounded-full text-sm font-bold text-white shadow-sm"
                    style="background: var(--red-mid);">{{ r }}</span>
            </div>
          </div>
          <div>
            <p class="text-sm text-gray-500 mb-2">Peut recevoir de</p>
            <div class="flex gap-2 flex-wrap">
              <span v-for="r in currentBloodType.canReceiveFrom" :key="r"
                    class="px-3 py-1.5 rounded-full text-sm font-bold border-2"
                    style="border-color: var(--red-light); color: var(--red-deep);">{{ r }}</span>
            </div>
          </div>
          <div class="ml-auto">
            <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-display text-2xl font-bold shadow-lg"
                 style="background: var(--red-deep);">{{ selectedBloodType }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== POURQUOI DONNER ====== -->
  <section id="about" class="py-20 px-6" style="background: var(--dark);">
    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-14">
        <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: var(--red-light);">Impact réel</p>
        <h2 class="font-display text-4xl md:text-5xl text-white">Pourquoi donner ?</h2>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <div v-for="reason in reasons" :key="reason.title"
             class="p-8 rounded-3xl border transition-all hover:border-red-400"
             style="background: #2A0E0E; border-color: #3A1515;">
          <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-6"
               style="background: var(--red-pale);">
            <span class="text-2xl">{{ reason.icon }}</span>
          </div>
          <h3 class="font-display text-xl text-white mb-3">{{ reason.title }}</h3>
          <p class="text-gray-400 text-sm leading-relaxed">{{ reason.desc }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== PROCESSUS ====== -->
  <section id="process" class="py-20 px-6 bg-white">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-16">
        <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: var(--red-light);">Simple & rapide</p>
        <h2 class="font-display text-4xl md:text-5xl" style="color: var(--dark);">Comment donner ?</h2>
      </div>

      <div class="relative">
        <!-- Ligne de connexion -->
        <div class="hidden md:block absolute top-8 left-1/2 -translate-x-1/2 w-3/4 h-0.5 bg-red-100 z-0"></div>

        <div class="grid md:grid-cols-4 gap-8 relative z-10">
          <div v-for="(step, i) in steps" :key="step.title" class="text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-white font-display text-xl font-bold shadow-lg"
                 style="background: var(--red-mid);">{{ i + 1 }}</div>
            <h3 class="font-display text-lg mb-2" style="color: var(--dark);">{{ step.title }}</h3>
            <p class="text-sm text-gray-400 leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== CTA FINAL ====== -->
  <section class="py-24 px-6 text-center relative overflow-hidden"
           style="background: linear-gradient(135deg, var(--red-deep) 0%, var(--red-mid) 100%);">
    <div class="absolute inset-0 opacity-10">
      <svg viewBox="0 0 600 400" class="w-full h-full">
        <circle cx="500" cy="50" r="200" fill="white"/>
        <circle cx="50" cy="350" r="150" fill="white"/>
      </svg>
    </div>

    <div class="relative z-10 max-w-xl mx-auto">
      <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"
           style="background: rgba(255,255,255,0.15);">
        <svg viewBox="0 0 40 52" class="w-10 h-10 fill-white">
          <path d="M20 2C10 18 2 25 2 34a18 18 0 0036 0c0-9-8-16-18-32z"/>
        </svg>
      </div>
      <h2 class="font-display text-4xl md:text-5xl text-white mb-6">Prêt à sauver une vie ?</h2>
      <p class="text-red-100 text-lg mb-10 leading-relaxed">
        Un simple geste de votre part peut transformer le destin de quelqu'un. Rejoignez les milliers de donneurs qui font la différence chaque jour.
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <button @click="showModal = true"
                class="cta-btn px-8 py-4 rounded-full bg-white font-bold text-base shadow-xl transition-all hover:shadow-2xl"
                style="color: var(--red-mid);">
          S'inscrire comme donneur
        </button>
        <a href="#contact"
           class="px-8 py-4 rounded-full font-bold text-base border-2 border-white/40 text-white hover:bg-white/10 transition-all">
          Nous contacter
        </a>
      </div>
    </div>
  </section>

  <!-- ====== FOOTER ====== -->
  <footer id="contact" class="py-12 px-6" style="background: var(--dark);">
    <div class="max-w-5xl mx-auto">
      <div class="grid md:grid-cols-3 gap-10 mb-10">
        <div>
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--red-mid);">
              <svg viewBox="0 0 24 24" class="w-4 h-4 fill-white">
                <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0016 0c0-3.5-4-7-8-12z"/>
              </svg>
            </div>
            <span class="font-display text-lg text-white font-bold">HemoLife</span>
          </div>
          <p class="text-gray-500 text-sm leading-relaxed">Plateforme dédiée à la gestion et la promotion du don du sang. Ensemble pour sauver des vies.</p>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Navigation</h4>
          <div class="flex flex-col gap-2">
            <a v-for="item in navItems" :key="item.label" :href="item.href"
               class="text-gray-500 text-sm hover:text-red-400 transition-colors">{{ item.label }}</a>
          </div>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contact</h4>
          <div class="flex flex-col gap-3 text-sm text-gray-500">
            <p>📍 Centre National de Transfusion Sanguine, Maroc</p>
            <p>📞 +212 5XX-XXXXXX</p>
            <p>✉️ contact@hemolife.ma</p>
          </div>
        </div>
      </div>
      <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
        <p class="text-gray-600 text-xs">© 2025 HemoLife — Projet Fil Rouge. Tous droits réservés.</p>
        <p class="text-gray-600 text-xs">Fait avec ❤️ pour sauver des vies</p>
      </div>
    </div>
  </footer>

  <!-- ====== MODAL DON ====== -->
  <div v-if="showModal"
       class="fixed inset-0 z-50 flex items-center justify-center p-4"
       style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);"
       @click.self="showModal = false">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl relative">
      <button @click="showModal = false"
              class="absolute top-5 right-5 w-9 h-9 rounded-full flex items-center justify-center hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
        ✕
      </button>

      <div class="text-center mb-6">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
             style="background: var(--red-pale);">
          <svg viewBox="0 0 40 52" class="w-8 h-8" style="fill: var(--red-mid);">
            <path d="M20 2C10 18 2 25 2 34a18 18 0 0036 0c0-9-8-16-18-32z"/>
          </svg>
        </div>
        <h3 class="font-display text-2xl font-bold" style="color: var(--dark);">Inscription Donneur</h3>
        <p class="text-gray-400 text-sm mt-1">Rejoignez notre réseau de donneurs</p>
      </div>

      <form @submit.prevent="submitForm" class="flex flex-col gap-4">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Prénom</label>
            <input v-model="form.prenom" type="text" placeholder="Ahmed"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100" required />
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Nom</label>
            <input v-model="form.nom" type="text" placeholder="Benali"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100" required />
          </div>
        </div>
        <div>
          <label class="text-xs font-semibold text-gray-500 mb-1 block">Email</label>
          <input v-model="form.email" type="email" placeholder="ahmed@exemple.com"
                 class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100" required />
        </div>
        <div>
          <label class="text-xs font-semibold text-gray-500 mb-1 block">Groupe sanguin</label>
          <select v-model="form.groupe"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 bg-white" required>
            <option value="">Sélectionner...</option>
            <option v-for="bt in bloodTypes" :key="bt.type" :value="bt.type">{{ bt.type }}</option>
          </select>
        </div>
        <div>
          <label class="text-xs font-semibold text-gray-500 mb-1 block">Téléphone</label>
          <input v-model="form.tel" type="tel" placeholder="+212 6XX-XXXXXX"
                 class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100" />
        </div>

        <button type="submit"
                class="cta-btn w-full py-4 rounded-2xl text-white font-bold text-base mt-2 transition-all"
                style="background: var(--red-mid);">
          {{ formSubmitted ? '✓ Inscription envoyée !' : "S'inscrire comme donneur" }}
        </button>
      </form>
    </div>
  </div>

</div>
</template>

    



