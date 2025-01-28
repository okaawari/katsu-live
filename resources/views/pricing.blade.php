<x-app-layout>
    <div class="relative min-h-screen bg-slate-900 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('http://localhost:8000/images/dunno.pn'); opacity: 0.1;"></div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-wrap w-full gap-8 py-16 sm:py-20 lg:py-28 justify-center">
                @foreach (range(1, 3) as $i)
                    <div class="min-w-[300px] max-w-[320px] sm:w-1/2 lg:w-1/3 bg-gradient-to-r from-indigo-900 to-indigo-600 p-6 rounded-xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/50">
                        <div class="space-y-4">
                            <p class="text-lg text-gray-300 font-medium">{{ $i }} сар</p>
                            <p class="text-4xl font-bold text-white">{{ $i*3 }},000₮<span class="text-sm text-gray-200"> / {{ $i*3 }}0 хоног</span></p>
                        </div>
                        <button onclick="showModal({{ $i }})" class="w-full py-3 mt-6 bg-indigo-500 text-white font-semibold rounded-full shadow-md hover:bg-indigo-400 transition-all duration-300 hover:shadow-indigo-500/50">
                            ЭРХ АВАХ
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Modal -->
            <div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center px-4 sm:px-0 z-50">
                <div class="bg-slate-800 p-8 rounded-lg shadow-2xl max-w-md w-full text-gray-300 transform transition-all duration-500 ease-in-out scale-95 hover:scale-100">
                    <h2 class="text-2xl font-bold mb-6 text-white">Транзакцийн дэлгэрэнгүй</h2>
                    <p id="modalContent" class="text-gray-300 mb-6"></p>
                    <div class="mt-6 flex justify-end gap-4">
                        <button onclick="closeModal()" class="px-6 py-2 font-semibold text-gray-900 bg-amber-400 hover:bg-amber-300 rounded-lg transition-all duration-300 hover:shadow-amber-500/50">Болих</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Background Shapes -->
        <div class="absolute inset-0 overflow-hidden z-0">
            <svg class="absolute top-0 left-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="0.05" d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,133.3C672,117,768,139,864,160C960,181,1056,203,1152,213.3C1248,224,1344,224,1392,213.3L1440,192V0H1392C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            </svg>
        </div>
    </div>

    <script>
        function showModal(plan) {
            const modal = document.getElementById('transactionModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.innerText = `Танай сонгосон төлөвлөгөө: ${plan}-сар.`;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('transactionModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>