<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>BA Transport | Smart Auto-Fill & Refined Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
        .city-card { transition: all 0.2s; cursor: pointer; border-radius: 1.2rem; }
        .city-card:hover { transform: translateY(-5px); box-shadow: 0 20px 30px -12px rgba(0,0,0,0.15); }
        .service-card { transition: all 0.15s; cursor: pointer; border-radius: 1rem; }
        .service-card:hover { transform: scale(1.02); background: #eef2ff; }
        .form-card { background: white; border-radius: 1.5rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05); }
        .step-indicator { background: #e2e8f0; border-radius: 2rem; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; }
        .step-active { background: #2563eb; color: white; }
        .service-details-section { background: #fefce8; border-left: 4px solid #eab308; transition: all 0.2s; }
        .service-details-section h3 { font-size: 1rem; font-weight: 700; color: #854d0e; }
        .required-dot:after { content: "*"; color: #e11d48; margin-left: 3px; }
        table th, table td { vertical-align: middle; }
        .amount-input { width: 110px; }
    </style>
</head>
<body class="p-4 md:p-6">

    <!-- ======================= SCREEN 1: DASHBOARD WITH CITIES + FILTERABLE PENDING ======================= -->
    <div id="screen1Dashboard" class="max-w-7xl mx-auto">
        <div class="flex flex-wrap justify-between items-center mb-6">
            <div><h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">🚛 BA Transport</h1><p class="text-gray-500 text-sm">Permits, Taxes, Insurance & Vehicle Services — Pakistan</p></div>
            <div class="mt-2 md:mt-0 bg-white px-5 py-2 rounded-full shadow text-sm font-semibold text-gray-700"><i class="fas fa-truck text-sky-600 mr-1"></i> Transport Management</div>
        </div>
        <div class="mb-8"><h2 class="text-xl font-bold text-gray-700 mb-4"><i class="fas fa-map-marker-alt text-amber-600 mr-2"></i> Select a City to Start</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-5">
                <div data-city="Karachi" class="city-card bg-gradient-to-br from-emerald-50 to-green-50 p-5 rounded-xl shadow text-center"><i class="fas fa-city text-3xl text-emerald-700 mb-2"></i><h3 class="font-bold">Karachi</h3></div>
                <div data-city="Lasbella" class="city-card bg-gradient-to-br from-sky-50 to-blue-50 p-5 rounded-xl shadow text-center"><i class="fas fa-map-pin text-3xl text-sky-700 mb-2"></i><h3 class="font-bold">Lasbella</h3></div>
                <div data-city="Quetta" class="city-card bg-gradient-to-br from-amber-50 to-orange-50 p-5 rounded-xl shadow text-center"><i class="fas fa-mountain text-3xl text-amber-700 mb-2"></i><h3 class="font-bold">Quetta</h3></div>
                <div data-city="Peshawar" class="city-card bg-gradient-to-br from-indigo-50 to-purple-50 p-5 rounded-xl shadow text-center"><i class="fas fa-landmark text-3xl text-indigo-700 mb-2"></i><h3 class="font-bold">Peshawar</h3></div>
                <div data-city="Gilgit" class="city-card bg-gradient-to-br from-cyan-50 to-teal-50 p-5 rounded-xl shadow text-center"><i class="fas fa-mountain text-3xl text-cyan-700 mb-2"></i><h3 class="font-bold">Gilgit</h3></div>
                <div data-city="Punjab" class="city-card bg-gradient-to-br from-lime-50 to-green-50 p-5 rounded-xl shadow text-center"><i class="fas fa-seedling text-3xl text-lime-700 mb-2"></i><h3 class="font-bold">Punjab</h3></div>
                <div data-city="Other" class="city-card bg-gradient-to-br from-gray-50 to-stone-50 p-5 rounded-xl shadow text-center"><i class="fas fa-globe text-3xl text-gray-600 mb-2"></i><h3 class="font-bold">Other</h3></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 mb-6">
            <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
                <h2 class="text-xl font-bold"><i class="fas fa-filter text-indigo-500 mr-2"></i> Pending Items (Filter)</h2>
                <div class="flex flex-wrap gap-3">
                    <select id="filterCity" class="border rounded-xl px-4 py-2 text-sm bg-gray-50"><option value="all">All Cities</option><option value="Karachi">Karachi</option><option value="Lasbella">Lasbella</option><option value="Quetta">Quetta</option><option value="Peshawar">Peshawar</option><option value="Gilgit">Gilgit</option><option value="Punjab">Punjab</option><option value="Other">Other</option></select>
                    <select id="filterService" class="border rounded-xl px-4 py-2 text-sm bg-gray-50"><option value="all">All Services</option><option value="Transfer">Transfer</option><option value="Alteration">Alteration</option><option value="Route Permit">Route Permit</option><option value="FC">FC</option><option value="Insurance">Insurance</option><option value="Tax">Tax</option><option value="File Return">File Return</option><option value="Others">Others</option></select>
                    <button id="applyFilterBtn" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm"><i class="fas fa-search mr-1"></i> Apply</button>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div><h3 class="font-semibold text-rose-600 mb-2"><i class="fas fa-rupee-sign"></i> Pending Payments</h3><div id="filteredPaymentsList" class="space-y-2 max-h-64 overflow-y-auto"></div></div>
                <div><h3 class="font-semibold text-amber-600 mb-2"><i class="fas fa-folder-open"></i> Pending Cases</h3><div id="filteredCasesList" class="space-y-2 max-h-64 overflow-y-auto"></div></div>
            </div>
        </div>
        <div class="text-center text-xs text-gray-400 mt-4">👉 Click any city card to proceed</div>
    </div>

    <!-- ======================= SCREEN 2: SERVICE SELECTION ======================= -->
    <div id="screen2Service" class="max-w-6xl mx-auto hidden">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <button id="backToCitiesBtn" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-full font-medium flex items-center gap-2"><i class="fas fa-arrow-left"></i> Back to Cities</button>
            <div class="bg-white shadow-md px-5 py-2 rounded-full font-bold"><i class="fas fa-city text-emerald-600 mr-2"></i><span id="selectedCityName">Karachi</span></div>
            <span class="step-indicator step-active">Step 1: City</span><span class="step-indicator">Step 2: Service</span>
        </div>
        <div class="form-card p-6">
            <h2 class="text-xl font-bold mb-5"><i class="fas fa-concierge-bell text-blue-600 mr-2"></i> Which service do you need?</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" id="servicesGrid"></div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 mb-8 border-l-8 border-l-amber-400 mt-5">
            <h2 class="text-lg font-bold"><i class="fas fa-clock text-amber-500 mr-2"></i> Pending Cases — <span id="pendingCityName">Karachi</span></h2>
            <div id="cityPendingCasesList" class="mt-3 space-y-2 text-sm"></div>
        </div>
    </div>

    <!-- ======================= SCREEN 3: ENTRY FORM ======================= -->
    <div id="screen3Form" class="max-w-7xl mx-auto hidden">
        <div class="flex flex-wrap justify-between items-center mb-5 gap-3">
            <button id="backToServiceScreenBtn" class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-full font-medium flex items-center gap-2"><i class="fas fa-chevron-left"></i> Back to Services</button>
            <div class="bg-white shadow-md px-4 py-2 rounded-full text-sm"><i class="fas fa-map-marker-alt text-emerald-600"></i> City: <strong id="formCityLabel">--</strong></div>
            <div class="flex gap-2"><span class="step-indicator bg-green-100 text-green-800">✓ City</span><span class="step-indicator bg-green-100 text-green-800">✓ Service</span><span class="step-indicator step-active">Form</span></div>
        </div>

        <!-- Vehicle & Party Details: two columns + comment box full width -->
        <div class="form-card p-5 md:p-7 mb-6">
            <h2 class="text-xl font-bold text-gray-800 border-b pb-3 mb-5"><i class="fas fa-truck text-blue-600 mr-2"></i> Vehicle & Party Details</h2>
            <div id="dynamicCommonFieldsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
        </div>

        <!-- DYNAMIC DETAILS SECTIONS (inline) -->
        <div id="dynamicDetailsContainer" class="space-y-4 mb-6"></div>

        <!-- Services Table (organized headers) -->
        <div class="form-card p-5 md:p-7 mb-5">
            <div class="flex justify-between items-center border-b pb-3 mb-4 flex-wrap gap-2">
                <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-list-ul text-indigo-600 mr-2"></i> Services & Charges</h2>
                <button id="addMoreServiceBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm flex items-center gap-1 shadow"><i class="fas fa-plus"></i> Add Another Service</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" id="finalServicesTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left rounded-l-lg w-2/5">Service Type</th>
                            <th class="p-3 text-left w-1/5">Amount (₨)</th>
                            <th class="p-3 text-left rounded-r-lg w-2/5">Action</th>
                        </tr>
                    </thead>
                    <tbody id="finalServicesTableBody"></tbody>
                </table>
            </div>
            <p class="text-xs text-gray-400 mt-3"><i class="fas fa-info-circle"></i> For Transfer, Alteration & File Return, details will be auto-filled across services.</p>
        </div>

        <!-- Totals Section -->
        <div class="form-card p-5 md:p-7 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div><label class="block font-semibold">Total Amount (₨)</label><input type="number" id="finalTotalAmount" readonly class="w-full bg-gray-100 border rounded-xl p-3 font-bold"></div>
                <div><label class="block font-semibold">Received Amount (₨)</label><input type="number" id="finalReceivedAmount" value="0" class="w-full border rounded-xl p-3"></div>
                <div><label class="block font-semibold">Remaining Amount (₨)</label><input type="number" id="finalRemainingAmount" readonly class="w-full bg-gray-100 border rounded-xl p-3 font-bold text-rose-700"></div>
            </div>
            <div class="mt-5 flex justify-end"><button id="finalSaveRecordBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-bold shadow"><i class="fas fa-save mr-2"></i> Save Record</button></div>
        </div>
    </div>

    <script>
        // ---------- MOCK DATA ----------
        const pendingPaymentsDB = [
            { city: "Karachi", service: "Transfer", party: "Al-Rehman", vehicle: "ABC-789", amount: 12500 },
            { city: "Quetta", service: "Route Permit", party: "Northern Cargo", vehicle: "LE-123", amount: 8200 },
            { city: "Peshawar", service: "FC", party: "Jan Traders", vehicle: "TB-4567", amount: 5750 },
            { city: "Punjab", service: "Tax", party: "Zahid Ent.", vehicle: "LEB-909", amount: 3500 },
            { city: "Gilgit", service: "Insurance", party: "Northern Travels", vehicle: "GIL-111", amount: 6200 }
        ];
        const pendingCasesDB = [
            { city: "Karachi", service: "File Return", title: "NIC mismatch", desc: "Documents pending" },
            { city: "Punjab", service: "File Return", title: "Tax period overdue", desc: "Submit within 3 days" },
            { city: "Gilgit", service: "Insurance", title: "Policy expired", desc: "Renewal required" },
            { city: "Lasbella", service: "Route Permit", title: "Permit renewal", desc: "RTA approval" }
        ];
        function normalizeCity(c) { return (c === "Lahore") ? "Punjab" : c; }
        function renderFilteredItems() {
            let city = document.getElementById('filterCity').value;
            let serv = document.getElementById('filterService').value;
            let payments = pendingPaymentsDB.filter(p => (city === 'all' || normalizeCity(p.city) === city) && (serv === 'all' || p.service === serv));
            let cases = pendingCasesDB.filter(c => (city === 'all' || normalizeCity(c.city) === city) && (serv === 'all' || c.service === serv));
            document.getElementById('filteredPaymentsList').innerHTML = payments.length ? payments.map(p => `<div class="bg-rose-50 p-3 rounded-lg flex justify-between"><div><span class="font-medium">${p.city} - ${p.service}</span><br><span class="text-xs">${p.party} (${p.vehicle})</span></div><span class="font-bold text-rose-600">₨ ${p.amount}</span></div>`).join('') : '<div class="text-center text-gray-400 p-3">No payments</div>';
            document.getElementById('filteredCasesList').innerHTML = cases.length ? cases.map(c => `<div class="bg-amber-50 p-3 rounded-lg"><div class="flex gap-2"><i class="fas fa-folder-open text-amber-500"></i><div><span class="font-medium">${c.city} - ${c.service}</span><br><span class="text-xs">${c.title} - ${c.desc}</span></div></div></div>`).join('') : '<div class="text-center text-gray-400 p-3">No cases</div>';
        }

        // ---------- GLOBAL STATE ----------
        let currentCity = '';
        let currentServicesRows = [];  // { id, serviceType, amount, detailsData }
        let nextId = 1;

        // Helper: generate details fields HTML (with prefilled values)
        function generateDetailsHTML(serviceType, existingData = {}) {
            if (serviceType === 'Transfer' || serviceType === 'Alteration' || serviceType === 'File Return') {
                return `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold">From Name</label><input type="text" class="detail-fromName w-full border rounded-lg p-2" value="${existingData.fromName || ''}"></div>
                        <div><label class="block text-sm font-semibold">From S/O</label><input type="text" class="detail-fromSo w-full border rounded-lg p-2" value="${existingData.fromSo || ''}"></div>
                        <div><label class="block text-sm font-semibold">From NIC No</label><input type="text" class="detail-fromNic w-full border rounded-lg p-2" value="${existingData.fromNic || ''}"></div>
                        <div><label class="block text-sm font-semibold">To Name</label><input type="text" class="detail-toName w-full border rounded-lg p-2" value="${existingData.toName || ''}"></div>
                        <div><label class="block text-sm font-semibold">To S/O</label><input type="text" class="detail-toSo w-full border rounded-lg p-2" value="${existingData.toSo || ''}"></div>
                        <div><label class="block text-sm font-semibold">To NIC No</label><input type="text" class="detail-toNic w-full border rounded-lg p-2" value="${existingData.toNic || ''}"></div>
                    </div>
                `;
            } else if (serviceType === 'Route Permit') {
                return `<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="col-span-2"><label class="block text-sm font-semibold">Details (Route)</label><textarea class="detail-details w-full border rounded-lg p-2" rows="2">${existingData.details || ''}</textarea></div><div><label>RTA/PTA</label><input class="detail-rtaPta w-full border rounded-lg p-2" value="${existingData.rtaPta || ''}"></div></div>`;
            } else if (serviceType === 'FC') {
                return `<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label>Truck/Trailer</label><select class="detail-truckType w-full border rounded-lg p-2"><option ${existingData.truckType === 'Truck' ? 'selected' : ''}>Truck</option><option ${existingData.truckType === 'Trailer' ? 'selected' : ''}>Trailer</option></select></div><div class="col-span-2"><label>FC Details</label><textarea class="detail-fcDetails w-full border rounded-lg p-2" rows="2">${existingData.fcDetails || ''}</textarea></div></div>`;
            } else if (serviceType === 'Insurance') {
                return `<div><label>Remarks</label><textarea class="detail-remarks w-full border rounded-lg p-2" rows="3">${existingData.remarks || ''}</textarea></div>`;
            } else if (serviceType === 'Tax') {
                return `<div><label>Upto (Date/Period)</label><input class="detail-upto w-full border rounded-lg p-2" value="${existingData.upto || ''}"></div>`;
            } else if (serviceType === 'Others') {
                return `<div><label>Other Details</label><textarea class="detail-otherDetails w-full border rounded-lg p-2" rows="3">${existingData.otherDetails || ''}</textarea></div>`;
            }
            return `<div class="text-gray-500 text-sm">No additional fields.</div>`;
        }

        // AUTO-FILL: copy details from any Transfer/Alteration/File Return to all such services
        function syncTransferDetails() {
            const transferLikeRows = currentServicesRows.filter(r => ['Transfer','Alteration','File Return'].includes(r.serviceType));
            if (transferLikeRows.length === 0) return;
            // use first non-empty details as master, or get from any
            let masterData = null;
            for (let row of transferLikeRows) {
                if (row.detailsData && (row.detailsData.fromName || row.detailsData.fromNic || row.detailsData.toName)) {
                    masterData = { ...row.detailsData };
                    break;
                }
            }
            if (!masterData && transferLikeRows[0].detailsData) masterData = { ...transferLikeRows[0].detailsData };
            if (!masterData) masterData = { fromName: '', fromSo: '', fromNic: '', toName: '', toSo: '', toNic: '' };
            // apply to all
            for (let row of transferLikeRows) {
                if (!row.detailsData) row.detailsData = {};
                row.detailsData.fromName = masterData.fromName || '';
                row.detailsData.fromSo = masterData.fromSo || '';
                row.detailsData.fromNic = masterData.fromNic || '';
                row.detailsData.toName = masterData.toName || '';
                row.detailsData.toSo = masterData.toSo || '';
                row.detailsData.toNic = masterData.toNic || '';
            }
            // re-render all details sections to reflect synced values
            renderAllDetailsSections();
        }

        // render all inline sections
        function renderAllDetailsSections() {
            const container = document.getElementById('dynamicDetailsContainer');
            if (!container) return;
            container.innerHTML = '';
            currentServicesRows.forEach((row) => {
                const sectionDiv = document.createElement('div');
                sectionDiv.className = 'service-details-section bg-yellow-50 p-5 rounded-xl shadow-sm border border-yellow-200';
                sectionDiv.id = `details-section-${row.id}`;
                sectionDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-base font-bold text-amber-800"><i class="fas fa-file-alt mr-2"></i> ${row.serviceType} — Details</h3>
                        <button class="remove-details-btn text-gray-400 hover:text-red-500 text-sm" data-id="${row.id}"><i class="fas fa-trash-alt"></i> Remove Service</button>
                    </div>
                    <div class="details-fields-wrapper" data-service-type="${row.serviceType}" data-row-id="${row.id}">
                        ${generateDetailsHTML(row.serviceType, row.detailsData || {})}
                    </div>
                `;
                container.appendChild(sectionDiv);
            });
            // remove buttons
            document.querySelectorAll('.remove-details-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = parseInt(btn.getAttribute('data-id'));
                    if (currentServicesRows.length === 1) { alert("At least one service required."); return; }
                    currentServicesRows = currentServicesRows.filter(r => r.id !== id);
                    renderFinalServicesTable();
                    renderAllDetailsSections();
                    updateFinalTotals();
                    updateCommonFieldsBasedOnPrimaryService();
                });
            });
            attachDetailsAutoSyncEvents();
        }

        function attachDetailsAutoSyncEvents() {
            // For each Transfer/Alteration/File Return row, add input listeners to sync across all such services
            for (let row of currentServicesRows) {
                if (!['Transfer','Alteration','File Return'].includes(row.serviceType)) continue;
                const section = document.getElementById(`details-section-${row.id}`);
                if (!section) continue;
                const wrapper = section.querySelector('.details-fields-wrapper');
                const inputs = wrapper.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.removeEventListener('input', () => triggerSync());
                    input.addEventListener('input', () => triggerSync());
                });
            }
        }

        function triggerSync() {
            // capture current master data from first Transfer-like row that has values
            for (let row of currentServicesRows) {
                if (!['Transfer','Alteration','File Return'].includes(row.serviceType)) continue;
                const section = document.getElementById(`details-section-${row.id}`);
                if (!section) continue;
                const wrapper = section.querySelector('.details-fields-wrapper');
                const masterData = {
                    fromName: wrapper.querySelector('.detail-fromName')?.value || '',
                    fromSo: wrapper.querySelector('.detail-fromSo')?.value || '',
                    fromNic: wrapper.querySelector('.detail-fromNic')?.value || '',
                    toName: wrapper.querySelector('.detail-toName')?.value || '',
                    toSo: wrapper.querySelector('.detail-toSo')?.value || '',
                    toNic: wrapper.querySelector('.detail-toNic')?.value || ''
                };
                // update all rows' detailsData
                for (let targetRow of currentServicesRows) {
                    if (!['Transfer','Alteration','File Return'].includes(targetRow.serviceType)) continue;
                    if (!targetRow.detailsData) targetRow.detailsData = {};
                    targetRow.detailsData.fromName = masterData.fromName;
                    targetRow.detailsData.fromSo = masterData.fromSo;
                    targetRow.detailsData.fromNic = masterData.fromNic;
                    targetRow.detailsData.toName = masterData.toName;
                    targetRow.detailsData.toSo = masterData.toSo;
                    targetRow.detailsData.toNic = masterData.toNic;
                }
                // refresh UI sync but preserve values without infinite loop (re-render)
                renderAllDetailsSections();
                break;
            }
        }

        function renderFinalServicesTable() {
            const tbody = document.getElementById('finalServicesTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            currentServicesRows.forEach((row) => {
                const tr = document.createElement('tr');
                tr.className = 'border-b hover:bg-gray-50';
                // Service dropdown
                const tdType = document.createElement('td'); tdType.className = 'p-2';
                const select = document.createElement('select');
                select.className = 'border rounded-lg p-2 text-sm w-full';
                const allSvcs = ['Transfer','Alteration','Route Permit','FC','Insurance','Tax','File Return','Others'];
                allSvcs.forEach(opt => { const option = document.createElement('option'); option.value = opt; option.innerText = opt; if(row.serviceType === opt) option.selected = true; select.appendChild(option); });
                select.addEventListener('change', (e) => {
                    const oldType = row.serviceType;
                    const newType = e.target.value;
                    row.serviceType = newType;
                    if (!row.detailsData) row.detailsData = {};
                    if (!['Transfer','Alteration','File Return'].includes(newType)) {
                        // keep whatever but no auto-sync
                    } else {
                        // ensure synced from existing transfer-like data
                        syncTransferDetails();
                    }
                    renderFinalServicesTable();
                    renderAllDetailsSections();
                    updateCommonFieldsBasedOnPrimaryService();
                    updateFinalTotals();
                });
                tdType.appendChild(select);
                // Amount input
                const tdAmount = document.createElement('td'); tdAmount.className = 'p-2';
                const amountInput = document.createElement('input'); amountInput.type = 'number'; amountInput.value = row.amount || 0; amountInput.className = 'amount-input border rounded-lg p-2 text-right';
                amountInput.addEventListener('input', (e) => { row.amount = parseFloat(e.target.value) || 0; updateFinalTotals(); });
                tdAmount.appendChild(amountInput);
                // Remove action
                const tdAction = document.createElement('td'); tdAction.className = 'p-2';
                const removeBtn = document.createElement('button'); removeBtn.innerHTML = '<i class="fas fa-trash-alt text-red-500 mr-1"></i> Remove'; removeBtn.className = 'text-xs bg-red-50 px-3 py-1.5 rounded-full hover:bg-red-100';
                removeBtn.addEventListener('click', () => {
                    if (currentServicesRows.length === 1) { alert("At least one service required"); return; }
                    currentServicesRows = currentServicesRows.filter(r => r.id !== row.id);
                    renderFinalServicesTable();
                    renderAllDetailsSections();
                    updateFinalTotals();
                    updateCommonFieldsBasedOnPrimaryService();
                });
                tdAction.appendChild(removeBtn);
                tr.appendChild(tdType); tr.appendChild(tdAmount); tr.appendChild(tdAction);
                tbody.appendChild(tr);
            });
            updateFinalTotals();
        }

        function updateFinalTotals() {
            let total = currentServicesRows.reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
            document.getElementById('finalTotalAmount').value = total.toFixed(2);
            let received = parseFloat(document.getElementById('finalReceivedAmount').value) || 0;
            document.getElementById('finalRemainingAmount').value = (total - received).toFixed(2);
        }

        function updateCommonFieldsBasedOnPrimaryService() {
            let primary = currentServicesRows.length ? currentServicesRows[0].serviceType : 'Transfer';
            const isFull = ['Transfer', 'Alteration', 'File Return'].includes(primary);
            const container = document.getElementById('dynamicCommonFieldsContainer');
            if (isFull) {
                container.innerHTML = `
                    <div><label class="required-dot">Vehicle No</label><input id="comm_vehicleNo" class="w-full border rounded-xl p-2"></div>
                    <div><label>Vehicle Make</label><input id="comm_vehicleMake" class="w-full border rounded-xl p-2"></div>
                    <div><label>Vehicle Model</label><input id="comm_vehicleModel" class="w-full border rounded-xl p-2"></div>
                    <div><label>Engine No</label><input id="comm_engineNo" class="w-full border rounded-xl p-2"></div>
                    <div><label>Chassis No</label><input id="comm_chassisNo" class="w-full border rounded-xl p-2"></div>
                    <div><label>Party Name</label><input id="comm_partyName" class="w-full border rounded-xl p-2"></div>
                    <div><label>Party Mobile No</label><input id="comm_partyMobile" class="w-full border rounded-xl p-2"></div>
                    <div><label>Date</label><input type="date" id="comm_date" class="w-full border rounded-xl p-2"></div>
                    <div class="md:col-span-2"><label>Comment / Remarks</label><textarea id="comm_comment" rows="2" class="w-full border rounded-xl p-2" placeholder="Any additional comment..."></textarea></div>
                `;
            } else {
                container.innerHTML = `
                    <div><label class="required-dot">Vehicle No</label><input id="comm_vehicleNo" class="w-full border rounded-xl p-2"></div>
                    <div><label>Party Name</label><input id="comm_partyName" class="w-full border rounded-xl p-2"></div>
                    <div><label>Party Mobile No</label><input id="comm_partyMobile" class="w-full border rounded-xl p-2"></div>
                    <div><label>Date</label><input type="date" id="comm_date" class="w-full border rounded-xl p-2"></div>
                    <div class="md:col-span-2"><label>Comment / Remarks</label><textarea id="comm_comment" rows="2" class="w-full border rounded-xl p-2" placeholder="Any additional comment..."></textarea></div>
                `;
            }
            const today = new Date().toISOString().split('T')[0];
            const dateField = document.getElementById('comm_date');
            if (dateField && !dateField.value) dateField.value = today;
        }

        function addServiceRow(serviceType = 'Transfer') {
            const newId = nextId++;
            const newRow = { id: newId, serviceType: serviceType, amount: 0, detailsData: {} };
            // if service is Transfer-like, sync existing details from any existing Transfer-like row
            if (['Transfer','Alteration','File Return'].includes(serviceType)) {
                const existingTransfer = currentServicesRows.find(r => ['Transfer','Alteration','File Return'].includes(r.serviceType));
                if (existingTransfer && existingTransfer.detailsData) {
                    newRow.detailsData = { ...existingTransfer.detailsData };
                } else {
                    newRow.detailsData = { fromName: '', fromSo: '', fromNic: '', toName: '', toSo: '', toNic: '' };
                }
            }
            currentServicesRows.push(newRow);
            renderFinalServicesTable();
            renderAllDetailsSections();
            updateCommonFieldsBasedOnPrimaryService();
        }

        function loadCityPendingCases(city) {
            const filtered = pendingCasesDB.filter(c => normalizeCity(c.city) === city);
            const container = document.getElementById('cityPendingCasesList');
            container.innerHTML = filtered.length ? filtered.map(c => `<div class="flex gap-2 border-b pb-2"><i class="fas fa-exclamation-circle text-amber-500"></i><div><span class="font-medium">${c.service} - ${c.title}</span><br><span class="text-xs">${c.desc}</span></div></div>`).join('') : '<div class="text-gray-400">No pending cases</div>';
            document.getElementById('pendingCityName').innerText = city;
        }

        function showScreen2(city) {
            currentCity = city;
            document.getElementById('selectedCityName').innerText = city;
            loadCityPendingCases(city);
            const grid = document.getElementById('servicesGrid');
            const services = ['Transfer','Alteration','Route Permit','FC','Insurance','Tax','File Return','Others'];
            grid.innerHTML = '';
            services.forEach(serv => {
                const card = document.createElement('div');
                card.className = 'service-card bg-white border p-4 rounded-xl shadow-sm text-center';
                card.innerHTML = `<i class="fas ${serv === 'Transfer' ? 'fa-exchange-alt' : serv === 'Route Permit' ? 'fa-road' : serv === 'FC' ? 'fa-truck' : serv === 'Insurance' ? 'fa-shield-alt' : serv === 'Tax' ? 'fa-money-bill-wave' : serv === 'File Return' ? 'fa-folder-open' : 'fa-ellipsis-h'} text-3xl text-blue-600 mb-2"></i><h3 class="font-bold">${serv}</h3>`;
                card.addEventListener('click', () => {
                    currentServicesRows = [];
                    const newId = nextId++;
                    currentServicesRows.push({ id: newId, serviceType: serv, amount: 0, detailsData: {} });
                    renderFinalServicesTable();
                    renderAllDetailsSections();
                    updateCommonFieldsBasedOnPrimaryService();
                    document.getElementById('formCityLabel').innerText = currentCity;
                    document.getElementById('finalReceivedAmount').value = '0';
                    updateFinalTotals();
                    document.getElementById('screen2Service').classList.add('hidden');
                    document.getElementById('screen3Form').classList.remove('hidden');
                });
                grid.appendChild(card);
            });
            document.getElementById('screen1Dashboard').classList.add('hidden');
            document.getElementById('screen2Service').classList.remove('hidden');
        }

        // Event Listeners
        document.querySelectorAll('.city-card').forEach(card => card.addEventListener('click', () => showScreen2(card.getAttribute('data-city'))));
        document.getElementById('backToCitiesBtn').addEventListener('click', () => { document.getElementById('screen2Service').classList.add('hidden'); document.getElementById('screen1Dashboard').classList.remove('hidden'); renderFilteredItems(); });
        document.getElementById('backToServiceScreenBtn').addEventListener('click', () => { document.getElementById('screen3Form').classList.add('hidden'); showScreen2(currentCity); });
        document.getElementById('addMoreServiceBtn').addEventListener('click', () => { addServiceRow(currentServicesRows[0]?.serviceType || 'Transfer'); });
        document.getElementById('finalReceivedAmount').addEventListener('input', updateFinalTotals);
        document.getElementById('applyFilterBtn').addEventListener('click', renderFilteredItems);
        document.getElementById('finalSaveRecordBtn').addEventListener('click', () => {
            const vehicle = document.getElementById('comm_vehicleNo')?.value;
            if (!vehicle) { alert("Please fill Vehicle Number"); return; }
            alert(`✅ Record Saved!\nCity: ${currentCity}\nVehicle: ${vehicle}\nServices: ${currentServicesRows.map(s => `${s.serviceType} (₨${s.amount})`).join(', ')}\nTotal: ₨${document.getElementById('finalTotalAmount').value}\nReceived: ₨${document.getElementById('finalReceivedAmount').value}`);
        });
        renderFilteredItems();
    </script>
</body>
</html>
