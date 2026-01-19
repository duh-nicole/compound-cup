<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compound Interest Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .result-panel { min-height: 100px; }
    </style>
</head>
<body class="bg-[#1A0F0A] flex items-center justify-center min-h-screen p-4">

    <main class="w-full max-w-2xl bg-[#2D1B14] shadow-2xl rounded-2xl p-6 border border-[#3E2723]">
        
        <header class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#F5EFE6] leading-tight">
                Compound Interest Calculator
            </h1>
            <p class="mt-2 text-md text-[#D4A373]">
                Visualize the growth of your investments over time.
            </p>
        </header>    

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="principal" class="block text-sm font-medium text-[#F5EFE6]">Initial Investment ($)</label>
                    <input type="number" id="principal" class="mt-1 block w-full rounded-md bg-[#3E2723] text-[#F5EFE6] border-[#4E342E] shadow-sm focus:border-[#D4A373] focus:ring focus:ring-[#D4A373] focus:ring-opacity-50 p-2" placeholder="e.g., 1000">
                </div>
                <div>
                    <label for="contribution" class="block text-sm font-medium text-[#F5EFE6]">Monthly Contribution ($)</label>
                    <input type="number" id="contribution" class="mt-1 block w-full rounded-md bg-[#3E2723] text-[#F5EFE6] border-[#4E342E] shadow-sm focus:border-[#D4A373] focus:ring focus:ring-[#D4A373] focus:ring-opacity-50 p-2" placeholder="e.g., 100">
                </div>
                <div>
                    <label for="rate" class="block text-sm font-medium text-[#F5EFE6]">Annual Interest Rate (%)</label>
                    <input type="number" id="rate" class="mt-1 block w-full rounded-md bg-[#3E2723] text-[#F5EFE6] border-[#4E342E] shadow-sm focus:border-[#D4A373] focus:ring focus:ring-[#D4A373] focus:ring-opacity-50 p-2" placeholder="e.g., 5">
                </div>
                <div>
                    <label for="years" class="block text-sm font-medium text-[#F5EFE6]">Investment Years</label>
                    <input type="number" id="years" class="mt-1 block w-full rounded-md bg-[#3E2723] text-[#F5EFE6] border-[#4E342E] shadow-sm focus:border-[#D4A373] focus:ring focus:ring-[#D4A373] focus:ring-opacity-50 p-2" placeholder="e.g., 10">
                </div>
                <div>
                    <label for="frequency" class="block text-sm font-medium text-[#F5EFE6]">Compounding</label>
                    <select id="frequency" class="mt-1 block w-full rounded-md bg-[#3E2723] text-[#F5EFE6] border-[#4E342E] shadow-sm focus:border-[#D4A373] focus:ring focus:ring-[#D4A373] focus:ring-opacity-50 p-2">
                        <option value="1">Annually</option>
                        <option value="12" selected>Monthly</option>
                        <option value="365">Daily</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <button id="reset-btn" class="px-6 py-3 font-bold text-[#2D1B14] bg-[#F5EFE6] rounded-xl hover:bg-[#D4A373] transition-colors shadow-lg">
                    Reset
                </button>
                <button id="download-btn" class="px-6 py-3 font-bold text-[#F5EFE6] bg-[#3E2723] border border-[#D4A373] rounded-xl hover:bg-[#4E342E] transition-colors shadow-lg">
                    Download PDF
                </button>
            </div>
            
            <section id="results-section" class="mt-8 pt-6 border-t border-[#3E2723]">
                <div id="results-display" class="result-panel flex flex-col items-center justify-center p-6 bg-[#3E2723] rounded-xl border border-dashed border-[#4E342E]">
                    <p class="text-lg text-[#D4A373]">Enter details to see your growth.</p>
                </div>
            </section>

            <section id="chart-section" class="mt-8 pt-6 border-t border-[#3E2723] hidden">
                <h2 class="text-xl font-bold text-[#F5EFE6] text-center mb-4">Investment Growth</h2>
                <div class="bg-[#3E2723] p-4 rounded-xl h-64">
                    <canvas id="growthChart"></canvas>
                </div>
            </section>
        </div>

        <section class="mt-8 pt-4 border-t border-[#4E342E]">
            <div class="bg-[#1A0F0A] p-4 rounded-lg border-l-4 border-[#D4A373]">
                <h4 class="text-xs font-bold uppercase tracking-widest text-[#D4A373] mb-1">Espresso Shot of Wisdom</h4>
                <p id="tip-display" class="text-sm text-[#F5EFE6] italic">
                    "Compound interest is the eighth wonder of the world." — Albert Einstein
                </p>
            </div>
        </section>
    </main>

    <script>
        const { jsPDF } = window.jspdf;

        const tips = [
            "The SEC notes that even small amounts invested early can grow significantly due to compounding.",
            "According to the CFPB, a good rule of thumb is to keep your total debt-to-income ratio below 36%.",
            "SEC Tip: Diversification doesn't guarantee a profit, but it can help manage investment risk.",
            "The 'Rule of 72' is a quick way to estimate how long it takes for your money to double (72 ÷ Interest Rate).",
            "Government data shows that the earlier you start, the less you have to save per month to reach your goal.",
            "IRS Fact: Contributions to a traditional IRA may be tax-deductible depending on your income.",
            "The Social Security Administration reminds workers that benefits replace about 40% of pre-retirement income."
        ];

        const principalInput = document.getElementById('principal');
        const contributionInput = document.getElementById('contribution');
        const rateInput = document.getElementById('rate');
        const yearsInput = document.getElementById('years');
        const frequencyInput = document.getElementById('frequency');
        const resultsDisplay = document.getElementById('results-display');
        const resetBtn = document.getElementById('reset-btn');
        const downloadBtn = document.getElementById('download-btn');
        const chartSection = document.getElementById('chart-section');
        const chartCanvas = document.getElementById('growthChart');
        const tipDisplay = document.getElementById('tip-display');

        let myChart;
        let lastCalculation = null;

        function updateTip() {
            const randomTip = tips[Math.floor(Math.random() * tips.length)];
            tipDisplay.innerText = randomTip;
        }

        function renderChart(labels, data, contributionsData) {
            if (myChart) { myChart.destroy(); }
            const ctx = chartCanvas.getContext('2d');
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Total Value', data: data, borderColor: '#D4A373', backgroundColor: 'rgba(212, 163, 115, 0.2)', tension: 0.3, fill: true },
                        { label: 'Total Contributions', data: contributionsData, borderColor: '#F5EFE6', backgroundColor: 'rgba(245, 239, 230, 0.1)', tension: 0.3, fill: true }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { ticks: { color: '#F5EFE6' }, grid: { display: false } },
                        y: { ticks: { color: '#F5EFE6', callback: v => '$' + v.toLocaleString() }, grid: { color: '#4E342E' } }
                    },
                    plugins: { legend: { labels: { color: '#F5EFE6' } } }
                }
            });
        }

        function calculateAndDisplay() {
            const P = parseFloat(principalInput.value) || 0;
            const PMT = parseFloat(contributionInput.value) || 0;
            const r = (parseFloat(rateInput.value) / 100) || 0;
            const t = parseInt(yearsInput.value) || 0;
            const n = parseFloat(frequencyInput.value) || 12;

            if (P <= 0 && PMT <= 0 || t <= 0) {
                chartSection.classList.add('hidden');
                lastCalculation = null;
                return;
            }

            const compoundFactor = Math.pow((1 + r / n), (n * t));
            const contributionFactor = r === 0 ? t * n : (compoundFactor - 1) / (r / n);
            const finalAmount = P * compoundFactor + (PMT * contributionFactor);
            const totalContributed = P + (PMT * 12 * t);
            const interestEarned = finalAmount - totalContributed;

            lastCalculation = { P, PMT, r, t, finalAmount, totalContributed, interestEarned };

            const labels = []; const data = []; const contributionsData = [];
            for (let i = 0; i <= t; i++) {
                labels.push('Year ' + i);
                const amt = P * Math.pow((1 + r / n), (n * i)) + (PMT * (r === 0 ? i * n : (Math.pow((1 + r / n), (n * i)) - 1) / (r / n)));
                data.push(amt.toFixed(2));
                contributionsData.push((P + (PMT * 12 * i)).toFixed(2));
            }

            resultsDisplay.innerHTML = `
                <div class="text-center">
                    <div class="text-sm font-medium text-[#D4A373]">Final Balance</div>
                    <div class="text-5xl font-extrabold text-[#F5EFE6] mb-2">$${finalAmount.toLocaleString(undefined, {maximumFractionDigits: 2})}</div>
                    <div class="text-sm font-semibold text-[#D4A373]">Interest: <span class="text-[#F5EFE6]">$${interestEarned.toLocaleString(undefined, {maximumFractionDigits: 2})}</span></div>
                </div>`;
            
            chartSection.classList.remove('hidden');
            renderChart(labels, data, contributionsData);
        }

        function downloadPDF() {
            if (!lastCalculation) {
                alert("Please enter some numbers first!");
                return;
            }
            const doc = new jsPDF();
            const { P, PMT, r, t, finalAmount, totalContributed, interestEarned } = lastCalculation;

            doc.setFillColor(45, 27, 20);
            doc.rect(0, 0, 210, 40, 'F');
            
            doc.setTextColor(245, 239, 230);
            doc.setFontSize(22);
            doc.text("Investment Growth Report", 20, 25);

            doc.setTextColor(45, 27, 20);
            doc.setFontSize(12);
            doc.text(`Initial Investment: $${P.toLocaleString()}`, 20, 60);
            doc.text(`Monthly Contribution: $${PMT.toLocaleString()}`, 20, 70);
            doc.text(`Annual Interest Rate: ${r * 100}%`, 20, 80);
            doc.text(`Investment Period: ${t} Years`, 20, 90);

            doc.setDrawColor(212, 163, 115);
            doc.line(20, 100, 190, 100);

            doc.setFontSize(16);
            doc.text("Summary Results", 20, 115);
            doc.setFontSize(12);
            doc.text(`Total Contributions: $${totalContributed.toLocaleString()}`, 20, 130);
            doc.text(`Total Interest Earned: $${interestEarned.toLocaleString()}`, 20, 140);
            
            doc.setFontSize(18);
            doc.setTextColor(184, 134, 11);
            doc.text(`Final Projected Balance: $${finalAmount.toLocaleString()}`, 20, 160);

            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text("Generated by CodeLatte Financial Tools", 20, 280);

            doc.save("Investment_Scenario.pdf");
        }

        [principalInput, contributionInput, rateInput, yearsInput, frequencyInput].forEach(input => {
            input.addEventListener('input', calculateAndDisplay);
        });

        resetBtn.addEventListener('click', () => {
            [principalInput, contributionInput, rateInput, yearsInput].forEach(i => i.value = '');
            resultsDisplay.innerHTML = `<p class="text-lg text-[#D4A373]">Enter details to see your growth.</p>`;
            chartSection.classList.add('hidden');
            updateTip();
        });

        downloadBtn.addEventListener('click', downloadPDF);
    </script>
</body>
</html>
