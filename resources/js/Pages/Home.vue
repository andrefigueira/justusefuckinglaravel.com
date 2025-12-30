<script setup>
import { ref, onMounted } from 'vue';
import Prism from 'prismjs';
import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/themes/prism-tomorrow.css';

const highlightCode = (code) => {
    return Prism.highlight(code, Prism.languages.php, 'php');
};

const reasons = [
    {
        title: "Batteries Included",
        desc: "Auth, queues, caching, mail, notifications, broadcasting... it's all there. Stop gluing random packages together."
    },
    {
        title: "Eloquent is Beautiful",
        desc: "An ORM that actually makes sense. Write expressive queries that read like English, not SQL nightmares."
    },
    {
        title: "Artisan Commands",
        desc: "Generate anything with a single command. Spend time building features, not boilerplate."
    },
    {
        title: "The Ecosystem",
        desc: "Forge, Vapor, Nova, Horizon, Pulse, Reverb. Real tools built by people who ship."
    },
    {
        title: "Documentation That Slaps",
        desc: "Actually readable docs. Examples that work. Updated with every release."
    },
    {
        title: "Community",
        desc: "Laracasts, Laracon, countless packages. You're never stuck alone."
    }
];

const codeExamples = [
    {
        title: "Queues",
        desc: "Process jobs in the background. Send emails, generate reports, resize images. Scale infinitely.",
        code: `// Dispatch a job
ProcessPodcast::dispatch($podcast)
    ->onQueue('processing')
    ->delay(now()->addMinutes(10));

// The job class
class ProcessPodcast implements ShouldQueue
{
    public function handle(): void
    {
        // Heavy processing here...
    }
}`
    },
    {
        title: "Events & Listeners",
        desc: "Decouple your code. React to anything. Build event-driven architectures that scale.",
        code: `// Fire an event
event(new OrderShipped($order));

// Listen and react
class SendShipmentNotification
{
    public function handle(OrderShipped $event)
    {
        $event->order->user->notify(
            new OrderShippedNotification($event->order)
        );
    }
}`
    },
    {
        title: "Task Scheduling",
        desc: "Cron jobs without the cron. Define your schedule in code. Monitor with Pulse.",
        code: `// In Console/Kernel.php
Schedule::command('reports:generate')
    ->dailyAt('08:00')
    ->emailOutputOnFailure('admin@app.com');

Schedule::job(new CleanupOldRecords)
    ->weekly()
    ->sundays()
    ->at('02:00');`
    },
    {
        title: "Database Transactions",
        desc: "ACID compliance made simple. Rollback on failure. Keep your data consistent.",
        code: `DB::transaction(function () {
    $user = User::create([...]);

    $user->wallet()->create([
        'balance' => 0
    ]);

    $user->settings()->create([
        'notifications' => true
    ]);
}); // All or nothing`
    },
    {
        title: "API Resources",
        desc: "Transform your data layer. Consistent JSON responses. Pagination built-in.",
        code: `class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'posts' => PostResource::collection(
                $this->whenLoaded('posts')
            ),
        ];
    }
}`
    },
    {
        title: "Real-time Events",
        desc: "WebSockets without the pain. Broadcast to channels. Build live dashboards.",
        code: `// Broadcast an event
broadcast(new MessageSent($message))
    ->toOthers();

// In your Vue component
Echo.private('chat.' + roomId)
    .listen('MessageSent', (e) => {
        this.messages.push(e.message);
    });`
    }
];

const testimonials = [
    { quote: "Finally mass refactored my side project after 3 years. It's live now.", author: "@overthinker_dev" },
    { quote: "Spent 6 months choosing between frameworks. Picked Laravel. Shipped in 2 weeks.", author: "@analysis_paralysis" },
    { quote: "My startup runs on Laravel. We have actual paying customers now.", author: "@former_tutorial_hell" },
    { quote: "Deleted my microservices. One Laravel app. Same features. 90% less AWS bill.", author: "@cloud_bankruptcy" },
];

const ecosystem = [
    { name: "Forge", desc: "Server management" },
    { name: "Vapor", desc: "Serverless deployment" },
    { name: "Nova", desc: "Admin panels" },
    { name: "Horizon", desc: "Queue monitoring" },
    { name: "Pulse", desc: "App insights" },
    { name: "Reverb", desc: "WebSockets" },
    { name: "Sanctum", desc: "API auth" },
    { name: "Breeze", desc: "Auth scaffolding" },
];

const laravelStats = [
    { value: "12+", label: "Years battle-tested" },
    { value: "78k+", label: "GitHub stars" },
    { value: "300M+", label: "Downloads" },
    { value: "1", label: "Framework to rule them all" },
];

const beforeCode = `// Setting up auth in "simple" Node.js
const express = require('express');
const passport = require('passport');
const session = require('express-session');
const MongoStore = require('connect-mongo');
const bcrypt = require('bcryptjs');
const LocalStrategy = require('passport-local');
const jwt = require('jsonwebtoken');
const cookieParser = require('cookie-parser');
// ... 200 more lines of config`;

const afterCode = `// Setting up auth in Laravel
php artisan make:auth

// Done. Go build your actual product.`;

const objections = [
    { q: "But what about Next.js?", a: "Great for React devs who love config files. Laravel has Inertia. Same result, less existential crisis." },
    { q: "But what about Rails?", a: "Ruby is beautiful. So is PHP 8. Laravel borrowed the best parts and added better docs." },
    { q: "But what about Django?", a: "Python is lovely. But have you tried Eloquent? Didn't think so." },
    { q: "But what about Go/Rust?", a: "You're building a CRUD app, not a database engine. Chill." },
    { q: "But what about microservices?", a: "You have 12 users. You don't need Kubernetes. You need to ship." },
    { q: "But what about serverless?", a: "Laravel Vapor exists. Next question." },
];

const loaded = ref(false);
const hoveredIndex = ref(null);
const copiedIndex = ref(null);
const secondsWasted = ref(0);
const hasPledged = ref(false);
const showConfetti = ref(false);

const copyCode = async (code, index) => {
    await navigator.clipboard.writeText(code);
    copiedIndex.value = index;
    setTimeout(() => copiedIndex.value = null, 2000);
};

const shareOnTwitter = () => {
    const text = "Just Use Fucking Laravel. Stop overthinking your stack and start shipping.";
    const url = "https://justusefuckinglaravel.com";
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank');
};

const pledge = () => {
    if (hasPledged.value) return;
    hasPledged.value = true;
    showConfetti.value = true;
    setTimeout(() => showConfetti.value = false, 3000);
};

onMounted(() => {
    setTimeout(() => loaded.value = true, 100);

    // Start the timer
    setInterval(() => {
        secondsWasted.value++;
    }, 1000);
});
</script>

<template>
    <div class="main-container">
        <!-- Background layers -->
        <div class="bg-gradient"></div>

        <!-- Confetti -->
        <div v-if="showConfetti" class="confetti-container">
            <div v-for="i in 50" :key="i" class="confetti" :style="{ '--i': i }"></div>
        </div>

        <!-- Timer bar -->
        <div class="timer-bar">
            <span>Time spent reading instead of coding: <strong>{{ secondsWasted }}s</strong></span>
        </div>

        <header class="hero" :class="{ loaded }">
            <div class="hero-content">
                <span class="eyebrow">STOP OVERCOMPLICATING IT</span>

                <h1 class="main-title">
                    <span class="title-line">JUST USE</span>
                    <span class="title-line accent">FUCKING</span>
                    <span class="title-line laravel">LARAVEL</span>
                </h1>

                <p class="tagline">
                    You don't need 47 microservices, a custom framework, or that hip new thing on Hacker News.
                    <br>You need to ship. Laravel ships.
                </p>

                <div class="cta-group">
                    <a href="https://laravel.com/docs" target="_blank" class="btn-primary">
                        Read the Docs
                        <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="https://github.com/laravel/laravel" target="_blank" class="btn-secondary">
                        GitHub
                    </a>
                </div>
            </div>

            <div class="scroll-indicator">
                <span>WHY?</span>
                <div class="scroll-line"></div>
            </div>
        </header>

        <section class="stats-section">
            <div class="stats-grid">
                <div v-for="(stat, index) in laravelStats" :key="index" class="stat-item">
                    <span class="stat-value">{{ stat.value }}</span>
                    <span class="stat-label">{{ stat.label }}</span>
                </div>
            </div>
        </section>

        <section class="reasons-section">
            <div class="section-header">
                <span class="section-number">01</span>
                <h2 class="section-title">Six reasons you already know</h2>
            </div>

            <div class="reasons-grid">
                <article
                    v-for="(reason, index) in reasons"
                    :key="index"
                    class="reason-card"
                    :class="{ hovered: hoveredIndex === index }"
                    @mouseenter="hoveredIndex = index"
                    @mouseleave="hoveredIndex = null"
                    :style="{ '--delay': `${index * 0.1}s` }"
                >
                    <span class="reason-index">{{ String(index + 1).padStart(2, '0') }}</span>
                    <h3 class="reason-title">{{ reason.title }}</h3>
                    <p class="reason-desc">{{ reason.desc }}</p>
                    <div class="card-accent"></div>
                </article>
            </div>
        </section>

        <section class="comparison-section">
            <div class="section-header">
                <span class="section-number">02</span>
                <h2 class="section-title">Before & After</h2>
            </div>

            <div class="comparison-grid">
                <div class="comparison-card before">
                    <span class="comparison-label">The "Simple" Way</span>
                    <div class="code-block">
                        <div class="code-window-header">
                            <div class="code-window-controls">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                        <pre class="language-js"><code class="language-js">{{ beforeCode }}</code></pre>
                    </div>
                </div>
                <div class="comparison-card after">
                    <span class="comparison-label">The Laravel Way</span>
                    <div class="code-block">
                        <div class="code-window-header">
                            <div class="code-window-controls">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                        <pre class="language-php"><code class="language-php">{{ afterCode }}</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <section class="ecosystem-section">
            <div class="section-header">
                <span class="section-number">03</span>
                <h2 class="section-title">The Ecosystem</h2>
            </div>
            <p class="section-subtitle">First-party tools that just work. No glue code required.</p>

            <div class="ecosystem-grid">
                <div v-for="(tool, index) in ecosystem" :key="index" class="ecosystem-item">
                    <span class="ecosystem-name">{{ tool.name }}</span>
                    <span class="ecosystem-desc">{{ tool.desc }}</span>
                </div>
            </div>
        </section>

        <section class="claude-section">
            <div class="section-header">
                <span class="section-number">04</span>
                <h2 class="section-title">Laravel + Claude = Ship Faster</h2>
            </div>
            <div class="claude-content">
                <p class="claude-text">
                    Laravel's expressive, readable syntax makes it one of the best frameworks for AI-assisted development.
                    Claude understands Laravel deeply. Eloquent models, Blade templates, Artisan commands, middleware, jobs, events.
                </p>

                <div class="context-callout">
                    <div class="context-header">
                        <span class="context-icon">📁</span>
                        <span class="context-title">.context</span>
                        <span class="context-badge">Pro Tip</span>
                    </div>
                    <div class="context-body">
                        <p>Create a <code>.context</code> folder in your project root with markdown files describing your architecture, conventions, and patterns.</p>
                        <p>Claude reads these automatically and writes code that fits your codebase perfectly. No more re-explaining your project structure every session.</p>
                        <div class="context-example">
                            <code>
.context/<br>
├── ARCHITECTURE.md<br>
├── CONVENTIONS.md<br>
├── DATABASE.md<br>
└── API-PATTERNS.md
                            </code>
                        </div>
                        <div class="context-links">
                            <a href="https://buildingbetter.tech/p/the-context-method" target="_blank" class="context-link">
                                The .context method →
                            </a>
                            <a href="https://buildingbetter.tech/p/documentation-as-code-as-context" target="_blank" class="context-link">
                                Documentation as code as context →
                            </a>
                        </div>
                    </div>
                </div>

                <p class="claude-text">
                    You describe what you want. Claude writes the Laravel code. You ship.
                    The combination of Laravel's conventions and Claude's understanding means less time explaining context
                    and more time building features.
                </p>
                <p class="claude-text highlight">
                    This entire site was built with Laravel, Inertia, Vue, and Claude. It's a joke site, but it's also a real Laravel app you can learn from.
                    Check out the <a href="https://github.com/andrefigueira/justusefuckinglaravel.com" target="_blank">source on GitHub</a>.
                </p>
            </div>
        </section>

        <section class="manifesto-section">
            <div class="manifesto-content">
                <blockquote class="manifesto-quote">
                    "The best framework is the one that gets out of your way and lets you build."
                </blockquote>
                <p class="manifesto-text">
                    Laravel has been battle-tested for over a decade. Millions of applications.
                    Fortune 500 companies. Solo developers. Startups that became unicorns.
                </p>
            </div>
        </section>

        <section class="code-section">
            <div class="section-header">
                <span class="section-number">02</span>
                <h2 class="section-title">Enterprise-Grade Architecture</h2>
            </div>
            <p class="section-subtitle">Everything you need to build scalable, maintainable applications. No extra packages required.</p>

            <div class="code-grid">
                <article
                    v-for="(example, index) in codeExamples"
                    :key="index"
                    class="code-card"
                >
                    <div class="code-card-header">
                        <h3 class="code-title">{{ example.title }}</h3>
                        <p class="code-desc">{{ example.desc }}</p>
                    </div>
                    <div class="code-block">
                        <div class="code-window-header">
                            <div class="code-window-controls">
                                <span></span><span></span><span></span>
                            </div>
                            <button
                                class="copy-code-btn"
                                @click="copyCode(example.code, index)"
                            >
                                <svg v-if="copiedIndex !== index" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>{{ copiedIndex === index ? 'Copied!' : 'Copy' }}</span>
                            </button>
                        </div>
                        <pre class="language-php"><code class="language-php" v-html="highlightCode(example.code)"></code></pre>
                    </div>
                </article>
            </div>

            <div class="laravel-cta">
                <a href="https://laravel.com" target="_blank" class="laravel-link">
                    <img src="/laravel-logo.svg" alt="Laravel" class="laravel-logo" />
                    <span class="laravel-text">
                        <strong>Go to Laravel.com</strong>
                        <small>Start building today</small>
                    </span>
                    <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>

        <section class="testimonials-section">
            <div class="section-header">
                <span class="section-number">05</span>
                <h2 class="section-title">Success Stories*</h2>
            </div>
            <p class="section-subtitle">*Testimonials may be entirely fictional. Results are real.</p>

            <div class="testimonials-grid">
                <div v-for="(testimonial, index) in testimonials" :key="index" class="testimonial-card">
                    <p class="testimonial-quote">"{{ testimonial.quote }}"</p>
                    <span class="testimonial-author">{{ testimonial.author }}</span>
                </div>
            </div>
        </section>

        <section class="objections-section">
            <div class="section-header">
                <span class="section-number">06</span>
                <h2 class="section-title">But what about...</h2>
            </div>

            <div class="objections-list">
                <div
                    v-for="(obj, index) in objections"
                    :key="index"
                    class="objection-item"
                >
                    <h3 class="objection-q">{{ obj.q }}</h3>
                    <p class="objection-a">{{ obj.a }}</p>
                </div>
            </div>
        </section>

        <section class="pledge-section">
            <div class="pledge-content">
                <h2 class="pledge-title">Ready to stop overthinking?</h2>
                <button
                    class="pledge-btn"
                    :class="{ pledged: hasPledged }"
                    @click="pledge"
                >
                    <span v-if="!hasPledged">I pledge to ship</span>
                    <span v-else>You're going to ship!</span>
                </button>
                <p class="pledge-note" v-if="hasPledged">Screenshot this. Hold yourself accountable.</p>
            </div>
        </section>

        <section class="final-cta">
            <div class="final-content">
                <h2 class="final-title">
                    <span>Still reading?</span>
                    <span class="accent">Start building.</span>
                </h2>
                <div class="command-block">
                    <code>composer create-project laravel/laravel my-app</code>
                    <button class="copy-btn" @click="navigator.clipboard.writeText('composer create-project laravel/laravel my-app')">
                        Copy
                    </button>
                </div>

                <button class="share-btn" @click="shareOnTwitter">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                    Share this with someone still deciding on a stack
                </button>
            </div>
        </section>

        <section class="outro-section">
            <div class="outro-content">
                <h2 class="outro-title">Real talk for a second</h2>
                <p class="outro-text">
                    The stack doesn't actually matter that much. What matters is that your codebase is
                    well-maintained, coherent, and accessible to modification. Pick something your team
                    knows, something with good docs, and something that won't fight you every step of the way.
                </p>
                <p class="outro-text">
                    Laravel happens to check all those boxes for a lot of people. But if Rails or Django
                    or whatever else gets you shipping, then ship with that. The best technology is the
                    one that disappears and lets you focus on the problem you're actually solving.
                </p>
                <p class="outro-text muted">
                    Now stop reading and go build something.
                </p>
            </div>
        </section>

        <section class="sponsor-section">
            <div class="sponsor-content">
                <span class="sponsor-label">Built by</span>
                <a href="https://polyxmedia.com" target="_blank" class="sponsor-card">
                    <div class="sponsor-logo-info">
                        <img src="/polyxlogo.png" alt="Polyx Media" class="sponsor-logo" />
                        <div class="sponsor-info">
                            <h3 class="sponsor-name">Polyxmedia</h3>
                            <p class="sponsor-tagline">We build products that ship. Fast.</p>
                        </div>
                    </div>
                    <div class="sponsor-cta">
                        <span>Work with us</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        </section>

        <!-- Fixed Twitter Follow -->
        <a href="https://twitter.com/voidmode" target="_blank" class="twitter-follow">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
            <span>Follow <strong>@voidmode</strong></span>
        </a>

        <footer class="site-footer">
            <div class="footer-content">
                <p>Made with Laravel + Inertia + Vue. Obviously.</p>
                <p class="footer-meta">Not affiliated with Laravel. Just fans who are tired of overthinking.</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;600;700&family=Space+Mono:wght@400;700&display=swap');

.main-container {
    --bg-dark: #050505;
    --bg-card: #0d0d0d;
    --text-primary: #ffffff;
    --text-muted: #888888;
    --accent: #FF2D20;
    --accent-glow: rgba(255, 45, 32, 0.4);
    --accent-secondary: #ff6b5b;

    min-height: 100vh;
    background: var(--bg-dark);
    color: var(--text-primary);
    font-family: 'Space Mono', monospace;
    position: relative;
    overflow-x: hidden;
}

/* Top gradient */
.bg-gradient {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60vh;
    background: linear-gradient(180deg, rgba(255, 45, 32, 0.08) 0%, transparent 100%);
    pointer-events: none;
    z-index: 0;
}

/* Timer bar */
.timer-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 900;
    background: var(--bg-card);
    padding: 0.5rem 1rem;
    text-align: center;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.timer-bar strong {
    color: var(--accent);
    font-family: 'JetBrains Mono', monospace;
}

/* Confetti */
.confetti-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
    overflow: hidden;
}

.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background: var(--accent);
    top: -10px;
    left: calc(var(--i) * 2%);
    animation: confettiFall 3s ease-out forwards;
    animation-delay: calc(var(--i) * 0.02s);
}

.confetti:nth-child(even) {
    background: #ff6b5b;
    width: 8px;
    height: 8px;
}

.confetti:nth-child(3n) {
    background: #fff;
    width: 6px;
    height: 6px;
}

@keyframes confettiFall {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

/* Stats Section */
.stats-section {
    padding: 4rem 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-value {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 3rem;
    color: var(--accent);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* Hero Section */
.hero {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 2rem;
    position: relative;
    background: radial-gradient(ellipse at 50% 0%, var(--accent-glow) 0%, transparent 50%);
}

.hero-content {
    text-align: center;
    max-width: 1000px;
}

.eyebrow {
    display: inline-block;
    font-size: 0.75rem;
    letter-spacing: 0.3em;
    color: var(--accent);
    margin-bottom: 2rem;
    padding: 0.5rem 1rem;
    border: 1px solid var(--accent);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.loaded .eyebrow {
    opacity: 1;
    transform: translateY(0);
}

.main-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(4rem, 15vw, 12rem);
    line-height: 0.9;
    letter-spacing: -0.02em;
    margin: 0;
}

.title-line {
    display: block;
    opacity: 0;
    transform: translateY(60px);
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.loaded .title-line:nth-child(1) { opacity: 1; transform: translateY(0); transition-delay: 0.1s; }
.loaded .title-line:nth-child(2) { opacity: 1; transform: translateY(0); transition-delay: 0.2s; }
.loaded .title-line:nth-child(3) { opacity: 1; transform: translateY(0); transition-delay: 0.3s; }

.title-line.accent {
    color: var(--accent);
    text-shadow: 0 0 60px var(--accent-glow);
}

.title-line.laravel {
    background: linear-gradient(135deg, var(--accent) 0%, #ff6b5b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.tagline {
    font-size: clamp(0.875rem, 2vw, 1.125rem);
    color: var(--text-muted);
    max-width: 600px;
    margin: 2rem auto;
    line-height: 1.8;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.4s;
}

.loaded .tagline {
    opacity: 1;
    transform: translateY(0);
}

.cta-group {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 3rem;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.5s;
}

.loaded .cta-group {
    opacity: 1;
    transform: translateY(0);
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--accent);
    color: white;
    padding: 1rem 2rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: white;
    color: var(--bg-dark);
    transform: translateY(-2px);
    box-shadow: 0 10px 40px var(--accent-glow);
}

.btn-primary .arrow {
    width: 1rem;
    height: 1rem;
    transition: transform 0.3s ease;
}

.btn-primary:hover .arrow {
    transform: translateX(4px);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 1rem 2rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-primary);
    border: 1px solid var(--text-muted);
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    border-color: var(--text-primary);
    background: rgba(255, 255, 255, 0.05);
}

.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    opacity: 0;
    animation: fadeIn 0.6s ease 1s forwards;
}

.scroll-indicator span {
    font-size: 0.625rem;
    letter-spacing: 0.3em;
    color: var(--text-muted);
}

.scroll-line {
    width: 1px;
    height: 60px;
    background: linear-gradient(to bottom, var(--accent), transparent);
    animation: scrollPulse 2s ease infinite;
}

@keyframes fadeIn {
    to { opacity: 1; }
}

@keyframes scrollPulse {
    0%, 100% { opacity: 0.3; transform: scaleY(0.5); }
    50% { opacity: 1; transform: scaleY(1); }
}

/* Reasons Section */
.reasons-section {
    padding: 8rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.section-header {
    display: flex;
    align-items: baseline;
    gap: 2rem;
    margin-bottom: 4rem;
}

.section-number {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    color: var(--accent);
}

.section-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 400;
    margin: 0;
    text-transform: uppercase;
}

.reasons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

.reason-card {
    background: var(--bg-card);
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.reason-card:hover {
    transform: translateY(-4px);
    border-color: var(--accent);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.reason-index {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.05);
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    transition: color 0.3s ease;
}

.reason-card:hover .reason-index {
    color: var(--accent-glow);
}

.reason-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.75rem;
    margin: 0 0 1rem;
    text-transform: uppercase;
}

.reason-desc {
    color: var(--text-muted);
    font-size: 0.875rem;
    line-height: 1.7;
    margin: 0;
}

.card-accent {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--accent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.reason-card:hover .card-accent {
    transform: scaleX(1);
}

/* Manifesto Section */
.manifesto-section {
    padding: 8rem 2rem;
    background: linear-gradient(180deg, transparent 0%, rgba(255, 45, 32, 0.03) 50%, transparent 100%);
}

.manifesto-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.manifesto-quote {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    line-height: 1.3;
    margin: 0 0 3rem;
    padding: 0;
    color: var(--text-primary);
    position: relative;
}

.manifesto-quote::before {
    content: '"';
    font-size: 8rem;
    color: var(--accent);
    opacity: 0.2;
    position: absolute;
    top: -3rem;
    left: 50%;
    transform: translateX(-50%);
    font-family: Georgia, serif;
}

.manifesto-text {
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.9;
}

/* Comparison Section */
.comparison-section {
    padding: 8rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.comparison-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.comparison-card {
    position: relative;
}

.comparison-label {
    display: inline-block;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    padding: 0.5rem 1rem;
    margin-bottom: 1rem;
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.comparison-card.before .comparison-label {
    color: var(--text-muted);
}

.comparison-card.after .comparison-label {
    color: var(--accent);
    border-color: var(--accent);
}

/* Ecosystem Section */
.ecosystem-section {
    padding: 8rem 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.ecosystem-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.ecosystem-item {
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    text-align: center;
    transition: border-color 0.3s ease;
}

.ecosystem-item:hover {
    border-color: var(--accent);
}

.ecosystem-name {
    display: block;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
}

.ecosystem-desc {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Claude Section */
.claude-section {
    padding: 8rem 2rem;
    max-width: 800px;
    margin: 0 auto;
}

.claude-content {
    margin-top: 2rem;
}

.claude-text {
    font-size: 1.1rem;
    line-height: 1.9;
    color: var(--text-primary);
    margin: 0 0 1.5rem;
}

.claude-text code {
    background: var(--bg-card);
    padding: 0.2rem 0.5rem;
    color: var(--accent);
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.9rem;
}

.claude-text.highlight {
    padding: 1.5rem;
    background: rgba(255, 45, 32, 0.05);
    border-left: 3px solid var(--accent);
}

.claude-text a {
    color: var(--accent);
    text-decoration: underline;
}

/* Context Callout */
.context-callout {
    background: var(--bg-card);
    border: 1px solid rgba(255, 45, 32, 0.3);
    margin: 2rem 0;
    overflow: hidden;
}

.context-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: rgba(255, 45, 32, 0.1);
    border-bottom: 1px solid rgba(255, 45, 32, 0.2);
}

.context-icon {
    font-size: 1.25rem;
}

.context-title {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--accent);
}

.context-badge {
    margin-left: auto;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 0.25rem 0.5rem;
    background: var(--accent);
    color: white;
}

.context-body {
    padding: 1.5rem;
}

.context-body p {
    margin: 0 0 1rem;
    font-size: 1rem;
    line-height: 1.7;
    color: var(--text-primary);
}

.context-example {
    background: #0a0a0a;
    padding: 1rem 1.5rem;
    margin: 1rem 0;
}

.context-example code {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.85rem;
    line-height: 1.8;
    color: var(--text-muted);
}

.context-links {
    display: flex;
    gap: 1.5rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.context-link {
    color: var(--accent);
    font-size: 0.9rem;
    text-decoration: none;
    transition: opacity 0.2s ease;
}

.context-link:hover {
    opacity: 0.8;
    text-decoration: underline;
}

/* Testimonials Section */
.testimonials-section {
    padding: 8rem 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.testimonial-card {
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 2rem;
}

.testimonial-quote {
    font-size: 1rem;
    line-height: 1.7;
    margin: 0 0 1rem;
    color: var(--text-primary);
}

.testimonial-author {
    font-size: 0.8rem;
    color: var(--accent);
}

/* Pledge Section */
.pledge-section {
    padding: 6rem 2rem;
    text-align: center;
    background: linear-gradient(180deg, transparent 0%, rgba(255, 45, 32, 0.03) 50%, transparent 100%);
}

.pledge-content {
    max-width: 500px;
    margin: 0 auto;
}

.pledge-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 2rem;
    margin: 0 0 2rem;
}

.pledge-btn {
    background: transparent;
    border: 2px solid var(--accent);
    color: var(--accent);
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    padding: 1rem 3rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.pledge-btn:hover {
    background: var(--accent);
    color: white;
}

.pledge-btn.pledged {
    background: var(--accent);
    color: white;
    cursor: default;
}

.pledge-note {
    margin-top: 1.5rem;
    font-size: 0.875rem;
    color: var(--text-muted);
}

/* Share Button */
.share-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 2rem;
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: var(--text-primary);
    font-family: 'Space Mono', monospace;
    font-size: 0.8rem;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.share-btn:hover {
    border-color: var(--text-primary);
    background: rgba(255, 255, 255, 0.05);
}

.share-btn svg {
    width: 16px;
    height: 16px;
}

/* Objections Section */
.objections-section {
    padding: 8rem 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.objections-list {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: rgba(255, 255, 255, 0.05);
}

.objection-item {
    padding: 2rem;
    background: var(--bg-dark);
}

.objection-q {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    margin: 0 0 0.75rem;
    color: var(--text-muted);
}

.objection-a {
    font-size: 1rem;
    line-height: 1.7;
    margin: 0;
    color: var(--text-primary);
}

/* Final CTA */
.final-cta {
    padding: 8rem 2rem;
    text-align: center;
}

.final-content {
    max-width: 700px;
    margin: 0 auto;
}

.final-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(2.5rem, 8vw, 5rem);
    margin: 0 0 3rem;
    text-transform: uppercase;
}

.final-title span {
    display: block;
}

.final-title .accent {
    color: var(--accent);
}

.command-block {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1rem 1.5rem;
    font-size: 0.875rem;
}

.command-block code {
    color: var(--accent);
}

.copy-btn {
    background: transparent;
    border: 1px solid var(--text-muted);
    color: var(--text-muted);
    padding: 0.5rem 1rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: all 0.3s ease;
}

.copy-btn:hover {
    border-color: var(--text-primary);
    color: var(--text-primary);
}

/* Code Section */
.code-section {
    padding: 8rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
}

.section-subtitle {
    color: var(--text-muted);
    font-size: 1rem;
    margin: -2rem 0 4rem;
    max-width: 600px;
}

.code-grid {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.code-card {
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.05);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.code-card:hover {
    border-color: rgba(255, 45, 32, 0.3);
    transform: translateY(-4px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.code-card-header {
    padding: 1.5rem 1.5rem 1rem;
}

.code-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    margin: 0 0 0.5rem;
    text-transform: uppercase;
    color: var(--accent);
}

.code-desc {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin: 0;
    line-height: 1.6;
}

.code-block {
    background: #1a1a1a;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    position: relative;
}

.code-window-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: #141414;
}

.code-window-controls {
    display: flex;
    gap: 6px;
}

.code-window-controls span {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

.code-window-controls span:first-child { background: #ff5f57; }
.code-window-controls span:nth-child(2) { background: #febc2e; }
.code-window-controls span:nth-child(3) { background: #28c840; }

.copy-code-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: var(--text-muted);
    padding: 6px 12px;
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 4px;
}

.copy-code-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
}

.copy-code-btn svg {
    width: 14px;
    height: 14px;
}

.code-block pre {
    margin: 0;
    padding: 1.5rem;
    overflow-x: auto;
    background: #1a1a1a !important;
}

.code-block pre code {
    font-family: 'JetBrains Mono', 'Fira Code', 'Space Mono', monospace !important;
    font-size: 0.8rem !important;
    line-height: 1.8 !important;
    background: transparent !important;
    text-shadow: none !important;
}

/* Laravel CTA */
.laravel-cta {
    margin-top: 4rem;
    text-align: center;
}

.laravel-link {
    display: inline-flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem 2.5rem;
    background: linear-gradient(135deg, rgba(255, 45, 32, 0.1) 0%, rgba(255, 45, 32, 0.05) 100%);
    border: 1px solid rgba(255, 45, 32, 0.3);
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.laravel-link:hover {
    background: var(--accent);
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 20px 60px var(--accent-glow);
}

.laravel-logo {
    width: 40px;
    height: 40px;
    transition: filter 0.3s ease;
}

.laravel-link:hover .laravel-logo {
    filter: brightness(1.3);
}

.laravel-text {
    text-align: left;
}

.laravel-text strong {
    display: block;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.laravel-text small {
    font-size: 0.75rem;
    color: var(--text-muted);
    transition: color 0.3s ease;
}

.laravel-link:hover .laravel-text small {
    color: rgba(255, 255, 255, 0.8);
}

.arrow-icon {
    width: 24px;
    height: 24px;
    color: var(--accent);
    transition: all 0.3s ease;
}

.laravel-link:hover .arrow-icon {
    color: white;
    transform: translateX(4px);
}

/* Outro Section */
.outro-section {
    padding: 6rem 2rem;
}

.outro-content {
    max-width: 680px;
    margin: 0 auto;
}

.outro-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    color: var(--text-muted);
    margin: 0 0 2rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.outro-text {
    font-size: 1.1rem;
    line-height: 1.9;
    color: var(--text-primary);
    margin: 0 0 1.5rem;
}

.outro-text.muted {
    color: var(--text-muted);
    font-style: italic;
    margin-top: 2rem;
}

/* Sponsor Section */
.sponsor-section {
    padding: 6rem 2rem;
    background: linear-gradient(180deg, transparent 0%, rgba(255, 45, 32, 0.02) 50%, transparent 100%);
}

.sponsor-content {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.sponsor-label {
    display: inline-block;
    font-size: 0.625rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.sponsor-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2rem 2.5rem;
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.sponsor-card:hover {
    border-color: var(--accent);
}

.sponsor-logo-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sponsor-logo {
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.sponsor-info {
    text-align: left;
}

.sponsor-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.25rem;
    letter-spacing: -0.01em;
}

.sponsor-name span {
    color: var(--text-primary);
}

.sponsor-tagline {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin: 0;
}

.sponsor-cta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--accent);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 700;
}

.sponsor-cta svg {
    width: 16px;
    height: 16px;
}

/* Footer */
.site-footer {
    padding: 4rem 2rem;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}

.twitter-follow {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 900;
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.75rem 1.25rem;
    background: var(--bg-card);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    text-decoration: none;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.twitter-follow:hover {
    border-color: var(--accent);
    background: rgba(255, 45, 32, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.twitter-follow svg {
    width: 16px;
    height: 16px;
}

.twitter-follow strong {
    color: var(--accent);
}

.footer-content p {
    margin: 0.5rem 0;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.footer-meta {
    font-size: 0.75rem !important;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .timer-bar {
        font-size: 0.65rem;
        padding: 0.4rem 0.5rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
    }

    .section-header {
        flex-direction: column;
        gap: 0.5rem;
    }

    .command-block {
        flex-direction: column;
        width: 100%;
    }

    .command-block code {
        font-size: 0.75rem;
        word-break: break-all;
    }

    .code-grid {
        grid-template-columns: 1fr;
    }

    .code-block pre {
        padding: 1rem;
    }

    .code-block code {
        font-size: 0.65rem;
    }

    .comparison-grid {
        grid-template-columns: 1fr;
    }

    .ecosystem-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .testimonials-grid {
        grid-template-columns: 1fr;
    }

    .laravel-link {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }

    .laravel-text {
        text-align: center;
    }

    .sponsor-card {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .sponsor-info {
        text-align: center;
    }

    .share-btn {
        font-size: 0.7rem;
        padding: 0.6rem 1rem;
    }
}
</style>
