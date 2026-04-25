@extends('layouts.customer')

@section('title', 'Membership - Vexalyn Store')

@section('content')
    <div
        class="min-h-screen py-12 bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-16 animate-fade-in">
                <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4">Pilih Membership</h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg">Dapatkan keuntungan lebih dengan berlangganan</p>
            </div>

            <!-- Current Membership -->
            @if($currentSubscription && $currentSubscription->isActive())
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-3xl p-8 text-white mb-12 animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 mb-2">Membership Anda</p>
                            <h2 class="text-3xl font-black mb-4">{{ $currentSubscription->membership->name }}</h2>
                            <div class="flex items-center gap-4">
                                <span class="px-4 py-2 bg-white/20 rounded-full text-sm font-bold">Aktif hingga
                                    {{ $currentSubscription->end_date->format('d M Y') }}</span>
                                <span
                                    class="px-4 py-2 bg-white/20 rounded-full text-sm font-bold">{{ $currentSubscription->membership->discount_percentage }}%
                                    Discount</span>
                            </div>
                        </div>
                        <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center">
                            <i data-lucide="crown" class="w-12 h-12"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Membership Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($memberships as $membership)
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-300 hover:-translate-y-2 animate-fade-in"
                        style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <!-- Card Header -->
                        <div
                            class="bg-gradient-to-br @if($membership->name == 'Gold') from-yellow-400 to-yellow-600 @elseif($membership->name == 'Platinum') from-purple-400 to-purple-600 @else from-slate-400 to-slate-600 @endif p-8 text-white text-center">
                            <i data-lucide="crown" class="w-16 h-16 mx-auto mb-4"></i>
                            <h3 class="text-2xl font-black mb-2">{{ $membership->name }}</h3>
                            <p class="text-4xl font-black mb-2">Rp {{ number_format($membership->price, 0, ',', '.') }}</p>
                            <p class="text-sm opacity-80">/{{ $membership->duration_days }} hari</p>
                        </div>

                        <!-- Benefits -->
                        <div class="p-8">
                            <ul class="space-y-4 mb-8">
                                @foreach($membership->benefits ?? [] as $benefit)
                                    <li class="flex items-center gap-3">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 flex-shrink-0"></i>
                                        <span class="text-slate-600 dark:text-slate-400">{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <form action="{{ route('customer.membership.subscribe', $membership) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full py-4 bg-gradient-to-r @if($membership->name == 'Gold') from-yellow-500 to-yellow-600 @elseif($membership->name == 'Platinum') from-purple-500 to-purple-600 @else from-slate-500 to-slate-600 @endif text-white rounded-2xl font-bold hover:shadow-lg transition-all">
                                    Berlangganan Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () { lucide.createIcons(); });
        </script>
    @endpush
@endsection