<div class="bg-red-800 rounded-lg text-white w-full sm:w-1/3 sm:ml-0 ml-4">
    <div class="p-2">
        <p class="text-xs">Você economizou até agora com a Manda Bem</p>
        <span class="font-bold">R$ {{ number_format($totalEconomia , 2, ',', '.') ?: '0,00' }}</span>
    </div>
    <div class="p-2 flex justify-between">
        <p class="text-sm font-bold">Economia do Mês</p>
        <span>R$ {{ number_format($totalEconomiaDoMes , 2, ',', '.') ?: '0,00' }}</span>
    </div>
    <div class="p-2 flex justify-between">
        <p class="text-sm font-bold">Saldo</p>
        <span>R$ {{ number_format($totalSaldo['saldo_total_value'] , 2, ',', '.') ?: '0,00' }}</span>
    </div>
    <div class="p-2 flex justify-between">
        <p class="text-sm font-bold">Divergências</p>
        <span>R$ {{ number_format($totalDivergencia , 2, ',', '.') ?: '0,00' }}</span>
    </div>
    <div class="border-t border-gray-200 border-dotted p-2 flex justify-between items-center">
        <div>
        <h1 class="font-bold text-3xl">Total</h1>
        <p class="text-xs">{{ $mesAtual }} {{ $anoAtual }}</p>
        </div>
        <p>R$ {{ number_format($valorTotal , 2, ',', '.') ?: '0,00' }}</p>
    </div>
</div>