<x-adm-dsh-nav>
    <div class="bg-white rounded-xl shadow-lg p-8 w-full">
        <h2 class="text-4xl font-bold text-gray-900 mb-6 tracking-wide flex items-center gap-2">
            📊 <span>Sales Dashboard</span>
        </h2>

        <div class="mb-6 bg-gray-100 p-5 rounded-xl shadow-sm">
            <form action="{{ route('sales.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                <label for="date" class="text-lg font-medium text-gray-700">Select Date:</label>
                <input type="date" name="date" value="{{ $selectedDate }}" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                    Filter
                </button>
            </form>
        </div>

        <div class="mt-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">📅 Monthly Sales</h3>
            <div class="overflow-hidden rounded-lg shadow-sm border border-gray-200">
                <table class="w-full text-center text-gray-700">
                    <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="py-3 px-6">Month</th>
                        <th class="py-3 px-6">Total Sales (MMK)</th>
                        <th class="py-3 px-6">Total Invoices</th>
                        <th class="py-3 px-6">Total Quantity</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($monthlySales as $sales)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $sales->month }}</td>
                            <td class="py-3 px-6">{{ number_format($sales->total_sales_mmk, 2) }}</td>
                            <td class="py-3 px-6">{{ $sales->total_invoices }}</td>
                            <td class="py-3 px-6">{{ $sales->total_quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">📅 Daily Sales</h3>
            <div class="overflow-hidden rounded-lg shadow-sm border border-gray-200">
                <table class="w-full text-left text-gray-700">
                    <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="py-3 px-6">Invoice No</th>
                        <th class="py-3 px-6">Shop ID</th>
                        <th class="py-3 px-6 text-right">Total (MMK)</th>
                        <th class="py-3 px-6 text-right">Quantity</th>
                        <th class="py-3 px-6 text-center">Payment Status</th>
                        <th class="py-3 px-6 text-center">Delivered</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($dailySales as $sale)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 {{ $sale->delivered == 0 ? 'bg-red-100' : 'bg-green-100' }}">
                            <td class="py-3 px-6">{{ $sale->invoice_no }}</td>
                            <td class="py-3 px-6">{{ $sale->partner_shops_id }}</td>
                            <td class="py-3 px-6 text-right">{{ number_format($sale->total_mmk, 2) }}</td>
                            <td class="py-3 px-6 text-right">{{ $sale->quantity }}</td>
                            <td class="py-3 px-6 text-center">{{ $sale->payment }}</td>
                            <td class="py-3 px-6 text-center">
                                @if($sale->delivered == 0)
                                    <span class="text-red-700 font-bold">Not Delivered</span>
                                @else
                                    <span class="text-green-700 font-bold">Delivered</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center">
                                <button type="button" onclick="showInvoiceDetails({{ $sale->invoice_no }})" class="bg-green-500 hover:bg-green-700 text-white font-semibold py-1 px-4 rounded-lg transition duration-300">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <div id="invoiceDetailsModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden z-[70] flex items-center justify-center" onclick="closeModal(event)">
            <div class="bg-white rounded-lg w-1/3 mx-auto p-8 shadow-lg transform transition-all duration-300 scale-95 hover:scale-100" onclick="event.stopPropagation()">
                <h3 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Invoice Details</h3>
                <div id="modalContent" class="text-lg text-gray-700"></div>
                <div class="mt-6 flex justify-center">
                    <button onclick="closeModal(event)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 hover:scale-105">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-adm-dsh-nav>

<!-- JavaScript to handle AJAX and Modal -->
<script>
    function showInvoiceDetails(invoiceNo) {
        const modal = document.getElementById('invoiceDetailsModal');
        const modalContent = document.getElementById('modalContent');

        // Check if modalContent exists before trying to set innerHTML
        if (!modalContent) {
            console.error('modalContent element not found');
            return;
        }

        // Show the modal
        modal.classList.remove('hidden');

        // Fetch invoice details from the server
        fetch(`/invoice/${invoiceNo}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error fetching invoice details');
                    return;
                }

                // Dynamically create content for the modal
                modalContent.innerHTML = `
                    <div class="space-y-4">
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Invoice No:</strong> <span class="font-medium text-gray-700">${data.invoice_no}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Sale Date:</strong> <span class="font-medium text-gray-700">${data.sale_date}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Total MMK:</strong> <span class="font-medium text-gray-700">${data.total_mmk}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Quantity:</strong> <span class="font-medium text-gray-700">${data.quantity}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Product Name:</strong> <span class="font-medium text-gray-700">${data.product_name}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Shop Name:</strong> <span class="font-medium text-gray-700">${data.shop_name}</span></p>
    </div>
                `;
            })
            .catch(error => {
                alert('Error fetching invoice details');
                console.error(error);
            });
    }

    // Function to close the modal
    function closeModal(event) {
        // Close modal if the background overlay (outside the modal content) or the close button is clicked
        if (event.target.id === 'invoiceDetailsModal' || event.target.closest('button')) {
            const modal = document.getElementById('invoiceDetailsModal');
            modal.classList.add('hidden');
        }
    }

    // Attach closeModal to the modal's background
    document.getElementById('invoiceDetailsModal').addEventListener('click', closeModal);
</script>

<!-- JavaScript to handle AJAX and Modal -->
<script>
    function showInvoiceDetails(invoiceNo) {
        const modal = document.getElementById('invoiceDetailsModal');
        const modalContent = document.getElementById('modalContent');

        // Check if modalContent exists before trying to set innerHTML
        if (!modalContent) {
            console.error('modalContent element not found');
            return;
        }

        // Show the modal
        modal.classList.remove('hidden');

        // Fetch invoice details from the server
        fetch(`/invoice/${invoiceNo}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error fetching invoice details');
                    return;
                }

                // Dynamically create content for the modal
                modalContent.innerHTML = `
                    <div class="space-y-4">
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Invoice No:</strong> <span class="font-medium text-gray-700">${data.invoice_no}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Sale Date:</strong> <span class="font-medium text-gray-700">${data.sale_date}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Total MMK:</strong> <span class="font-medium text-gray-700">${data.total_mmk}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Quantity:</strong> <span class="font-medium text-gray-700">${data.quantity}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Product Name:</strong> <span class="font-medium text-gray-700">${data.product_name}</span></p>
        <p class="text-lg text-gray-800"><strong class="text-blue-600">Shop Name:</strong> <span class="font-medium text-gray-700">${data.shop_name}</span></p>
    </div>
                `;
            })
            .catch(error => {
                alert('Error fetching invoice details');
                console.error(error);
            });
    }

    // Function to close the modal
    function closeModal(event) {
        // Close modal if the background overlay (outside the modal content) or the close button is clicked
        if (event.target.id === 'invoiceDetailsModal' || event.target.closest('button')) {
            const modal = document.getElementById('invoiceDetailsModal');
            modal.classList.add('hidden');
        }
    }

    // Attach closeModal to the modal's background
    document.getElementById('invoiceDetailsModal').addEventListener('click', closeModal);
</script>
