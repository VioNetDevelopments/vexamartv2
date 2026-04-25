@extends('layouts.app')

@section('page-title', 'Transaksi Disimpan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-yellow-600 to-orange-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                    <i data-lucide="pause" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-navy-900 dark:text-white">Transaksi Disimpan</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $suspendedCarts->count() }} transaksi tertunda</p>
                </div>
            </div>
        </div>

        <!-- Suspended Carts -->
        <div class="space-y-4">
            @forelse($suspendedCarts as $cart)
                <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all animate-fade-in-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="font-mono font-bold text-slate-900 dark:text-white">{{ $cart->suspension_code }}</p>
                                <p class="text-sm text-slate-500">Disimpan {{ $cart->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 font-bold tracking-wider uppercase">{{ $cart->items_count ?? collect($cart->items)->sum('qty') }} item</p>
                        </div>
                    </div>

                    @if($cart->notes)
                        <div class="mb-4 p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ $cart->notes }}</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-3">
                        <form id="delete-form-{{ $cart->id }}" action="{{ route('cashier.suspended.destroy', $cart) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" @click="notify.confirm('Mau dihapus aja nih transaksinya, KING?', () => document.getElementById('delete-form-{{ $cart->id }}').submit())" 
                                class="px-4 py-2 text-danger hover:bg-danger/10 rounded-xl font-bold transition-all">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        <button onclick="resumeCart('{{ $cart->id }}')" class="px-6 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-yellow-500/30 transition-all flex items-center gap-2">
                            <i data-lucide="play" class="w-4 h-4"></i>
                            <span>Lanjutkan</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white dark:bg-navy-900 rounded-3xl shadow-lg">
                    <i data-lucide="pause" class="w-20 h-20 text-slate-300 mx-auto mb-6"></i>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Tidak Ada Transaksi Disimpan</h3>
                    <p class="text-slate-500 dark:text-slate-400">Semua transaksi sudah diproses</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function resumeCart(cartId) {
    fetch(`/cashier/suspended/${cartId}/resume`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to POS with cart data
            localStorage.setItem('suspendedCart', JSON.stringify(data.cart));
            window.location.href = '/cashier/pos';
        } else {
            notify.error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        notify.error('Terjadi kesalahan saat memuat transaksi');
    });
}

document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
@endpush
@endsection