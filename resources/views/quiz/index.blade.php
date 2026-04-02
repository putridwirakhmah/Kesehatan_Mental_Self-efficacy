@extends('layouts.app')

@section('title', 'Pilih Kuisioner - Mental Health')

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-green-700 mb-4">Mulai Kuesioner</h1>
            <p class="text-xl text-green-600">Silakan mulai mengisi kuesioner yang tersedia</p>
        </div>

        <!-- Quiz Cards -->
        <div class="flex justify-center mb-12">
            <a href="{{ route('quiz.start', 'self_efficacy') }}"
                class="block bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition transform hover:scale-105 border-t-4 border-green-500 max-w-xl w-full">
                <div class="text-center">
                    <div class="text-green-500 text-6xl mb-4">💪</div>
                    <h2 class="text-3xl font-bold text-green-700 mb-3">Self Efficacy Factor</h2>
                    <p class="text-gray-600 mb-6">
                        Ukur tingkat kepercayaan diri dan keyakinan terhadap kemampuan diri sendiri
                    </p>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <span class="mr-2">📝</span> 10 Pertanyaan Self-Efficacy dan 18 Pertanyaan Well-being
                        </div>
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <span class="mr-2">⏱️</span> Estimasi 5-10 menit
                        </div>
                    </div>
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                        Mulai Kuisioner
                    </button>
                </div>
            </a>
        </div>

        <!-- History Section -->
        @php
        $allHistory = App\Models\QuizResult::where('user_id', Auth::id())
        ->latest('completed_at')
        ->paginate(5);
        @endphp

        @if($allHistory->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Kuisioner</h2>

            </div>

            <div class="space-y-3">
                @foreach($allHistory as $result)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-sky-50 transition cursor-pointer"
                    onclick="window.location='{{ route('quiz.resultById', ['id' => $result->id, 'type' => $result->quiz_type]) }}'">
                    <div class="flex items-center gap-4">
                        {{-- Icon --}}
                        <span class="text-2xl">{{ $result->icon }}</span>
                        <div>
                            <p class="font-medium text-gray-800">{{ $result->type_label }}</p>
                            <p class="text-sm text-gray-500">{{ $result->formatted_date }}</p>
                        </div>
                    </div>

                    {{-- Hasil --}}
                    <div class="flex items-center gap-4">
                        @php
                            $prediction = $result->prediction_data['prediction'] ?? null;
                            $isPositif = in_array($prediction, ['high_well_being', 'Normal']);
                        @endphp
                        <span
                            class="px-4 py-2 rounded-full text-sm font-semibold text-center min-w-24
                            {{ $isPositif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $result->history_result }}
                        </span>
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($allHistory->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $allHistory->appends(['page' => request()->page])->links() }}
            </div>
            @endif
        </div>
        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
            <div class="text-6xl mb-4">📝</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Belum Ada Riwayat</h2>
            <p class="text-gray-600 mb-6">Anda belum menyelesaikan kuisioner apapun</p>
        </div>
        @endif

    </div>
</div>
@endsection