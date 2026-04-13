@extends('layouts.customer')
@section('title', 'Developer Credits')
@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-20">
    <!-- Hero Section -->
    <div class="relative w-full py-20 px-10 bg-slate-900 rounded-[48px] overflow-hidden mb-20 group">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-transparent to-purple-600/10 z-0"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        
        <div class="relative z-10 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-[10px] font-black uppercase tracking-[0.3em] mb-8 animate-reveal">
                <i data-lucide="award" class="w-3 h-3"></i> Excellence in Engineering
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight mb-8 animate-reveal">The Minds Behind <span class="text-brand">VexaMart.</span></h1>
            <p class="text-slate-400 text-lg md:text-xl font-medium leading-relaxed mb-10 animate-reveal">Kami adalah tim yang berdedikasi untuk menciptakan standar baru dalam industri e-commerce global melalui teknologi inovatif.</p>
            
            <div class="flex flex-wrap justify-center gap-6 animate-reveal">
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <i data-lucide="users" class="w-5 h-5 text-blue-500"></i>
                    <span class="text-white text-sm font-bold">2+ Visionaries</span>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <i data-lucide="code" class="w-5 h-5 text-purple-500"></i>
                    <span class="text-white text-sm font-bold">Modern Tech Stack</span>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <i data-lucide="shield-check" class="w-5 h-5 text-green-500"></i>
                    <span class="text-white text-sm font-bold">Security First</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-40">
        @foreach([
            [
                'name' => 'Vexalyn',
                'role' => 'Principal Architect',
                'icon' => 'zap',
                'color' => 'from-blue-600 to-indigo-600',
                'desc' => 'Lead designer and visionary behind the Vexa architecture. Spearheading the shift towards modular SaaS infrastructures.',
                'stats' => ['UI/UX' => 98, 'Backend' => 92, 'Logic' => 95]
            ],
            [
                'name' => 'Vio Atmajaya',
                'role' => 'Lead Systems Engineer',
                'icon' => 'cpu',
                'color' => 'from-purple-600 to-pink-600',
                'desc' => 'Technical lead focused on database integrity and performance optimization. Ensuring VexaMart runs at lightning speeds.',
                'stats' => ['Database' => 99, 'Systems' => 96, 'Security' => 94]
            ]
        ] as $dev)
        <div class="group relative bg-white dark:bg-slate-900 rounded-[40px] p-10 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 hover-lift overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-gradient-to-br {{ $dev['color'] }} opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row gap-8 items-start">
                <div class="w-24 h-24 bg-gradient-to-br {{ $dev['color'] }} rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-blue-500/20 shrink-0">
                    <i data-lucide="{{ $dev['icon'] }}" class="w-10 h-10"></i>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $dev['name'] }}</h2>
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    </div>
                    <p class="text-[11px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em] mb-6">{{ $dev['role'] }}</p>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-8">{{ $dev['desc'] }}</p>
                    
                    <div class="space-y-4">
                        @foreach($dev['stats'] as $label => $val)
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center px-1">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                                <span class="text-[10px] font-black text-slate-900 dark:text-white">{{ $val }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $dev['color'] }} transition-all duration-1000" style="width: {{ $val }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex gap-4 pt-8 border-t border-slate-50 dark:border-slate-800 relative z-10">
                @foreach(['github', 'twitter', 'linkedin'] as $s)
                <a href="#" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition active:scale-95">
                    <i data-lucide="{{ $s }}" class="w-4 h-4"></i>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tech Stack Detailed -->
    <div class="bg-slate-50 dark:bg-slate-900/40 rounded-[48px] p-12 md:p-20 border border-slate-100 dark:border-slate-800">
        <div class="text-center mb-20">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em] mb-4">The Infrastructure</p>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white">Cutting Edge <span class="text-brand">Technologies.</span></h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-x-8 gap-y-16">
            @php $stack = [
                ['name'=>'Laravel 11', 'icon'=>'box', 'color'=>'text-red-500'],
                ['name'=>'Tailwind CSS', 'icon'=>'palette', 'color'=>'text-sky-400'],
                ['name'=>'Alpine.js', 'icon'=>'zap', 'color'=>'text-slate-800 dark:text-white'],
                ['name'=>'MySQL 9', 'icon'=>'database', 'color'=>'text-blue-500'],
                ['name'=>'Gemini AI', 'icon'=>'sparkles', 'color'=>'text-purple-500'],
                ['name'=>'Vite', 'icon'=>'fast-forward', 'color'=>'text-indigo-500'],
            ]; @endphp
            @foreach($stack as $s)
            <div class="flex flex-col items-center group">
                <div class="w-20 h-20 bg-white dark:bg-slate-900 rounded-[28px] flex items-center justify-center shadow-lg border border-slate-50 dark:border-slate-800 mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all">
                    <i data-lucide="{{ $s['icon'] }}" class="w-8 h-8 {{ $s['color'] }}"></i>
                </div>
                <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ $s['name'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Final CTA -->
    <div class="mt-40 text-center animate-reveal">
        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-8">Want to join the revolution?</h3>
        <a href="{{ route('customer.index') }}" class="inline-flex items-center gap-4 px-10 py-5 bg-brand text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-2xl shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all">
            Back To Storefront <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </a>
    </div>
</div>
@endsection
