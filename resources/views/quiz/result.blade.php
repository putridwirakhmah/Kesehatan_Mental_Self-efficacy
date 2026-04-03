@extends('layouts.app')

@section('title', 'Hasil Kuisioner - Mental Health')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto flex flex-col gap-6">
        <section class="flex flex-col text-white rounded-lg overflow-hidden shadow-sm" style="background-color: #166534;">
            <section class="flex rounded-md  flex-col ">
                <p class="px-6 py-2 text-xs flex items-center" style="background-color: #14532d;">Summary Kuisioner</p>
                <section class=" px-6 py-4 flex justify-between border-b-[1px] border-dashed gap-2 ">
                    <section>
                        <p class="font-semibold text-xl">{{ Auth::user()->name }}</p>
                        <p class="text-sm" style="color: #bbf7d0;">{{ Auth::user()->getQuizCountByType($result->quiz_type) }}
                            Kuisoner diambil</p>
                    </section>
                    <section>
                        <p class="text-sm" style="color: #bbf7d0;">{{ $result->completed_at->format('d M Y') }}</p>
                    </section>
                </section>
            </section>
            <section class=" px-6 py-8">
                <section class="flex flex-col gap-1">
                    @if($result->prediction_data && isset($result->prediction_data['prediction']))
                    @php
                    $prediction = $result->prediction_data['prediction'];
                    $predictionId = match($prediction) {

                    'high_well_being' => 'Kesehatan Mental Normal',
                    'low_well_being' => 'Kesehatan Mental Terganggu',
                    
                    default => $prediction
                    };
                    @endphp
                    <h2 class="text-4xl font-semibold">{{ $predictionId }}</h2>
                    <p class="text-sm mt-1" style="color: #bbf7d0;">{{ $type === 'family_social' ? 'Family Social Factor' : 'Self
                        Efficacy' }}</p>
                    @else
                    @php
                    $categoryLabel = $result->category === 'tinggi' ? 'Tinggi' : ($result->category === 'sedang' ?
                    'Sedang' : 'Rendah');
                    @endphp
                    <h2 class="text-4xl font-semibold">{{ $categoryLabel }}</h2>
                    <p class="text-sm text-sky-200 mt-1">Skor: {{ $result->total_score }} / {{ $result->max_score }}</p>
                    @endif
                </section>
            </section>
        </section>
        <!-- reason -->
          <section class="flex flex-col bg-white text-neutral-800  rounded-lg overflow-hidden shadow-sm">
            <section class="flex  rounded-md  flex-col ">
                <p class="px-6 py-2 text-xs flex items-center text-white" style="background-color: #14532d;">Penjelasan Hasil</p>
            </section>
            <section class=" px-6 py-4 text-sm">
              <p>{{ $result->prediction_data['explanation'] ?? 'Penjelasan tidak tersedia.' }}</p>
            </section>
        </section>
        {{-- dashboard insight --}}
        <section class="space-y-6">
            {{-- Charts Container - Side by Side --}}
            @if($type === 'self_efficacy')
            <div class="grid md:grid-cols-2 gap-6">
                {{-- DASHBOARD KATEGORI --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">
                        Kategori Mental Health
                    </h3>
                    @php
                        $confidence = $result->prediction_data['confidence'] ?? [];
                        $high = ($confidence['high_well_being'] ?? 0) * 100;
                        $low = ($confidence['low_well_being'] ?? 0) * 100;
                        $maxValue = max($high, $low, 1);
                    @endphp
                    <div class="flex items-end justify-between gap-10" style="width: 130px; margin:auto; height:256px;">
                        {{-- HIGH --}}
                        <div class="flex flex-col items-center justify-end h-full">
                            {{-- VALUE --}}
                            <span class="text-xs font-semibold text-gray-700 mb-1">
                                {{ round($high, 1) }}%
                            </span>
                            {{-- BAR --}}
                            <div 
                                style="
                                    width:50px;
                                    height: {{ max(($high / $maxValue) * 120, 10) }}px;
                                    background: #22c55e;
                                    border-radius: 6px 6px 0 0;
                                    transition: 0.5s;
                                ">
                            </div>
                            {{-- LABEL --}}
                            <span class="text-sm font-semibold mt-2">High</span>
                        </div>
                        {{-- LOW --}}
                        <div class="flex flex-col items-center justify-end h-full">
                            {{-- VALUE --}}
                            <span class="text-xs font-semibold text-gray-700 mb-1">
                                {{ round($low, 1) }}%
                            </span>
                            {{-- BAR --}}
                            <div 
                                style="
                                    width:50px;
                                    height: {{ max(($low / $maxValue) * 120, 10) }}px;
                                    background: #ef4444;
                                    border-radius: 6px 6px 0 0;
                                    transition: 0.5s;
                                ">
                            </div>
                            {{-- LABEL --}}
                            <span class="text-sm font-semibold mt-2">Low</span>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN SKOR --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">
                        Rincian Skor
                    </h3>
                    <div class="overflow-x-auto">
                        <div class="flex items-end justify-center gap-2" style="min-width: 400px; height: 256px;">
                            @if(isset($result->prediction_data['scores']))
                            @php
                                $subscales = $result->prediction_data['scores']['subscales'] ?? [];
                                $se_avg = $result->prediction_data['scores']['self_efficacy']['average'] ?? 0;

                                // gabungkan data
                                $all_scores = $subscales;
                                $all_scores['Self_Efficacy'] = $se_avg;

                                $maxValue = max($all_scores);
                            @endphp

                            @foreach($all_scores as $key => $value)

                                @php
                                    $isSE = $key === 'Self_Efficacy';

                                    // normalisasi tinggi bar
                                    $max = $isSE ? 4 : 21;
                                    $height = max(($value / $maxValue) * 120, 10);

                                    // warna
                                    $color = '#10b981';

                                    $label = match($key) {
                                        'Autonomy' => 'Auto',
                                        'Environmental_Mastery' => 'Env',
                                        'Personal_Growth' => 'Growth',
                                        'Positive_Relations' => 'Relation',
                                        'Purpose_in_Life' => 'Purpose',
                                        'Self_Acceptance' => 'Accept',
                                        'Self_Efficacy' => 'SelfEff',
                                        default => $key
                                    };
                                @endphp

                                <div class="flex flex-col items-center w-14">
                                    {{-- VALUE --}}
                                    <span class="text-xs font-bold">{{ $value }}</span>
                                    {{-- BAR --}}
                                    <div 
                                        style="
                                            width:50px;
                                            height: {{ $height }}px;
                                            background: {{ $color }};
                                            border-radius: 6px 6px 0 0;
                                            transition: 0.5s;
                                        ">
                                    </div>
                                    {{-- LABEL --}}
                                    <span class="text-[10px] mt-3" style="transform: rotate(-45deg); display: inline-block;">
                                        {{ $label }}
                                    </span>
                                </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- history kuis --}}
            @php
            // Get history data dengan pagination - ambil SEMUA kuis dengan tipe yang sama KECUALI current result
            $historyPaginator = \App\Models\QuizResult::where('user_id', Auth::id())
                ->where('quiz_type', $result->quiz_type)
                ->where('id', '!=', $result->id) // Exclude current result
                ->latest('completed_at')
                ->paginate(5, ['*'], 'history_page');
            @endphp

            @if($historyPaginator->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Kuisioner Terakhir</h3>
                <div class="space-y-3">
                    @foreach($historyPaginator as $historyItem)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-sky-50 transition">
                        <div class="flex items-center gap-4">
                            {{-- Icon dan Type --}}
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $historyItem->icon }}</span>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $historyItem->type_label }}</p>
                                    <p class="text-sm text-gray-500">{{ $historyItem->formatted_date }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Hasil --}}
                        <div class="flex items-center gap-4">
                            @php
                                $prediction = $historyItem->prediction_data['prediction'] ?? null;
                                $isPositif = in_array($prediction, ['high_well_being', 'Normal']);
                            @endphp
                            <span class="px-4 py-2 rounded-full text-sm font-semibold text-center min-w-24
                                {{ $isPositif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $historyItem->history_result }}
                            </span>
                            <a href="{{ route('quiz.resultById', ['id' => $historyItem->id, 'type' => $historyItem->quiz_type]) }}"
                               class="text-sky-600 hover:text-sky-700 font-semibold text-sm whitespace-nowrap">
                                Lihat →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($historyPaginator->hasPages())
                <div class="mt-4 flex justify-center">
                    {{ $historyPaginator->appends(['page' => request()->page])->links() }}
                </div>
                @endif
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="grid md:grid-cols-1 gap-4">
                <a href="{{ route('quiz.start', $result->quiz_type) }}"
                    class="flex items-center justify-center gap-2 text-white font-semibold py-4 px-6 rounded-xl shadow-md transition transform hover:scale-[1.01] active:scale-[0.98]" style="background-color: #15803d;">
                    Kerjakan Lagi
                </a>
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-green-700 font-semibold py-4 px-6 rounded-xl shadow-md border-2 border-green-300 transition transform hover:scale-[1.01] active:scale-[0.98]">
                    Kembali ke Home
                </a>
            </div>
        </section>
    </div>
</div>
@endsection