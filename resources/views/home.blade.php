@extends('layouts.app')

@section('title', 'Home - Mental Health Kuisioner')

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Welcome Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h1 class="text-3xl font-bold text-green-700 mb-2">
                Selamat Datang, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-green-600 text-lg">
                Siap untuk mengecek kesehatan mental Anda hari ini?
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Kuisioner Selesai</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $totalQuizCount }}</p>
                    </div>
                    <div class="text-blue-500 text-4xl">📝</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Status Mental</p>
                        @if($latestResult && $latestResult->prediction_data && isset($latestResult->prediction_data['prediction']))
                            @php
                                $prediction = $latestResult->prediction_data['prediction'];
                                $predictionLabel = match($prediction) {
                                    'high_well_being' => 'Kesehatan Mental Normal',
                                    'low_well_being' => 'Kesehatan Mental Terganggu',
                                    default => $prediction
                                };
                            @endphp
                            <p class="text-xl font-bold text-green-700">{{ $predictionLabel }}</p>
                        @else
                            <p class="text-xl font-bold text-gray-400">Belum Diketahui</p>
                        @endif
                    </div>
                    <div class="text-green-500 text-4xl">🧠</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Terakhir Tes</p>
                        @if($latestResult)
                            <p class="text-xl font-bold text-purple-700">{{ $latestResult->completed_at->format('d M Y') }}</p>
                        @else
                            <p class="text-xl font-bold text-gray-400">-</p>
                        @endif
                    </div>
                    <div class="text-purple-500 text-4xl">📅</div>
                </div>
            </div>
        </div>

        <!-- Action Section -->
        <div class="rounded-2xl shadow-xl p-8 text-white text-center" style="background: linear-gradient(to right, #16a34a, #15803d);">
            <h2 class="text-2xl font-bold mb-4">Mulai Kuisioner Mental Health</h2>
            <p class="mb-6 opacity-90">
                Jawab pertanyaan-pertanyaan sederhana untuk mengetahui kondisi kesehatan mental Anda
            </p>
            <a href="/quiz" class="inline-block bg-white text-green-600 font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-green-50 transition transform hover:scale-105">
                Mulai Sekarang →
            </a>
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-xl font-bold text-green-700 mb-3">💡 Tips Kesehatan Mental</h3>
            <ul class="space-y-2 text-green-600">
                <li>✓ Tidur yang cukup (7-8 jam per hari)</li>
                <li>✓ Olahraga secara teratur</li>
                <li>✓ Kelola stres dengan baik</li>
                <li>✓ Jaga hubungan sosial yang positif</li>
                <li>✓ Jangan ragu untuk meminta bantuan profesional</li>
            </ul>
        </div>
    </div>
</div>
@endsection
