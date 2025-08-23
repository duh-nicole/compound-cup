<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compound Interest Calculator</title>
    <!-- Use Tailwind CSS for a modern, responsive design -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js library for creating the growth chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles to enhance the design */
        body {
            font-family: 'Inter', sans-serif;
        }
        .result-panel {
            min-height: 100px;
        }
    </style>
</head>
<body class="bg-stone-900 flex items-center justify-center min-h-screen p-4">

    <!-- Main application container -->
    <main class="w-full max-w-2xl bg-stone-800 shadow-xl rounded-2xl p-6 md:p-10 border border-stone-700">
        
        <!-- Header Section -->
        <header class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-extrabold text-stone-200 leading-tight">
                Compound Interest Calculator
            </h1>
            <p class="mt-2 text-md text-stone-400">
                Visualize the growth of your investments over time.
            </p>
        </header>

        <!-- Input form and result display -->
        <div class="space-y-6">

            <!-- Input fields -->
            <div class="space-y-4">
                <div>
                    <label for="principal" class="block text-sm font-medium text-stone-200">Initial Investment ($)</label>
                    <input type="number" id="principal" class="mt-1 block w-full rounded-md bg-stone-700 text-stone-200 border-stone-600 shadow-sm focus:border-lime-400 focus:ring focus:ring-amber-500 focus:ring-opacity-50" min="0" step="100" placeholder="e.g., 1000">
                </div>
                <div>
                    <label for="contribution" class="block text-sm font-medium text-stone-200">Monthly Contribution ($)</label>
                    <input type="number" id="contribution" class="mt-1 block w-full rounded-md bg-stone-700 text-stone-200 border-stone-600 shadow-sm focus:border-lime-400 focus:ring focus:ring-amber-500 focus:ring-opacity-50" min="0" step="50" placeholder="e.g., 100">
                </div>
                <div>
                    <label for="rate" class="block text-sm font-medium text-stone-200">Annual Interest Rate (%)</label>
                    <input type="number" id="rate" class="mt-1 block w-full rounded-md bg-stone-700 text-stone-200 border-stone-600 shadow-sm focus:border-lime-400 focus:ring focus:ring-amber-500 focus:ring-opacity-50" min="0" step="0.1" placeholder="e.g., 5">
                </div>
                <div>
                    <label for="years" class="block text-sm font-medium text-stone-200">Investment Years</label>
                    <input type="number" id="years" class="mt-1 block w-full rounded-md bg-stone-700 text-stone-200 border-stone-600 shadow-sm focus:border-lime-400 focus:ring focus:ring-amber-500 focus:ring-opacity-50" min="1" step="1" placeholder="e.g., 10">
                </div>
                <div>
                    <label for="frequency" class="block text-sm font-medium text-stone-200">Compounding Frequency</label>
                    <select id="frequency" class="mt-1 block w-full rounded-md bg-stone-700 text-stone-200 border-stone-600 shadow-sm focus:border-lime-400 focus:ring focus:ring-amber-500 focus:ring-opacity-50">
                        <option value="1">Annually</option>
                        <option value="2">Semi-Annually</option>
                        <option value="4">Quarterly</option>
                        <option value="12" selected>Monthly</option>
                        <option value="365">Daily</option>
                    </select>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-center mt-6">
                <button id="reset-btn" class="w-full px-6 py-3 font-semibold text-stone-900 bg-amber-500 rounded-xl hover:bg-amber-600 transition-colors shadow-lg">
                    Reset
                </button>
            </div>
            
            <!-- Live results display section -->
            <section id="results-section" class="mt-8 pt-6 border-t border-stone-700">
                <div id="results-display" class="result-panel flex flex-col items-center justify-center p-6 bg-stone-700 rounded-xl border border-dashed border-stone-600">
                    <p class="text-lg text-stone-400">
                        Enter your investment details to see the results.
                    </p>
                </div>
            </section>

            <!-- Chart section -->
            <section id="chart-section" class="mt-8 pt-6 border-t border-stone-700 hidden">
                <h2 class="text-2xl font-bold text-stone-200 text-center mb-4">Investment Growth</h2>
                <div class="bg-stone-700 p-4 rounded-xl">
                    <canvas id="growthChart"></canvas>
                </div>
            </section>
        </div>

    </main>

    <script>
        const principalInput = document.getElementById('principal');
        const contributionInput = document.getElementById('contribution');
        const rateInput = document.getElementById('rate');
        const yearsInput = document.getElementById('years');
        const frequencyInput = document.getElementById('frequency');
        const resultsDisplay = document.getElementById('results-display');
        const resetBtn = document.getElementById('reset-btn');
        const chartSection = document.getElementById('chart-section');
        const chartCanvas = document.getElementById('growthChart');

        let myChart; // Variable to hold the Chart.js instance

        /**
         * Renders a line chart to visualize the growth of the investment over time.
         * @param {string[]} labels - Array of year labels.
         * @param {number[]} data - Array of the final balance for each year.
         * @param {number[]} contributionsData - Array of total contributions for each year.
         */
        function renderChart(labels, data, contributionsData) {
            // Destroy previous chart instance if it exists to prevent overlap
            if (myChart) {
                myChart.destroy();
            }

            const ctx = chartCanvas.getContext('2d');
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Value',
                            data: data,
                            borderColor: '#facc15', // Amber 500
                            backgroundColor: 'rgba(252, 211, 77, 0.2)',
                            tension: 0.1,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: '#facc15'
                        },
                        {
                            label: 'Total Contributions',
                            data: contributionsData,
                            borderColor: '#4ade80', // Lime 400
                            backgroundColor: 'rgba(74, 222, 128, 0.2)',
                            tension: 0.1,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: '#4ade80'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            title: {
                                display: true,
                                text: 'Years',
                                color: '#e5e5e5'
                            },
                            ticks: {
                                color: '#e5e5e5'
                            }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            title: {
                                display: true,
                                text: 'Value ($)',
                                color: '#e5e5e5'
                            },
                            ticks: {
                                color: '#e5e5e5',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#e5e5e5'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        /**
         * Calculates and displays the compound interest results, including regular contributions.
         */
        function calculateAndDisplay() {
            // Get user inputs
            const P = parseFloat(principalInput.value) || 0;
            const PMT = parseFloat(contributionInput.value) || 0;
            const r = parseFloat(rateInput.value) / 100 || 0;
            const t = parseInt(yearsInput.value) || 0;
            const n = parseFloat(frequencyInput.value) || 12;

            // Check for valid inputs before calculating
            if (P <= 0 && PMT <= 0 || r < 0 || t <= 0) {
                resultsDisplay.innerHTML = `<p class="text-lg text-stone-400">Enter your investment details to see the results.</p>`;
                chartSection.classList.add('hidden');
                return;
            }

            // Calculate future value with regular contributions
            let finalAmount;
            if (r === 0) {
                // Handle 0% interest rate to avoid division by zero
                finalAmount = P + (PMT * 12 * t);
            } else {
                // Formula for future value with regular contributions
                const compoundFactor = Math.pow((1 + r / n), (n * t));
                const contributionFactor = ((compoundFactor - 1) / (r / n)) * (1 + r / n);
                finalAmount = P * compoundFactor + (PMT * 12 / n) * contributionFactor;
            }
            const interestEarned = finalAmount - P - (PMT * 12 * t);
            
            // Generate data for the chart
            const labels = [];
            const data = [];
            const contributionsData = [];
            let currentPrincipal = P;
            let totalContributions = 0;

            for (let year = 0; year <= t; year++) {
                labels.push(year);
                if (year === 0) {
                    data.push(P);
                    contributionsData.push(P);
                } else {
                    let yearAmount;
                    if (r === 0) {
                        yearAmount = P + (PMT * 12 * year);
                    } else {
                        const compoundFactor = Math.pow((1 + r / n), (n * year));
                        const contributionFactor = ((compoundFactor - 1) / (r / n)) * (1 + r / n);
                        yearAmount = P * compoundFactor + (PMT * 12 / n) * contributionFactor;
                    }
                    data.push(yearAmount);
                    totalContributions += PMT * 12;
                    contributionsData.push(P + totalContributions);
                }
            }
            
            // Display results
            resultsDisplay.innerHTML = `
                <div class="text-center">
                    <div class="text-sm font-medium text-stone-400">Final Balance</div>
                    <div class="text-5xl font-extrabold text-amber-500 mb-2">
                        $${finalAmount.toFixed(2)}
                    </div>
                    <div class="text-sm font-semibold text-stone-400">
                        Interest Earned: <span class="text-lime-400 font-bold">$${interestEarned.toFixed(2)}</span>
                    </div>
                </div>
            `;
            
            // Show the chart section and render the chart
            chartSection.classList.remove('hidden');
            renderChart(labels, data, contributionsData);
            
            // Save data after successful calculation
            saveData();
        }

        /**
         * Saves all current input data to localStorage.
         */
        function saveData() {
            const data = {
                principal: principalInput.value,
                contribution: contributionInput.value,
                rate: rateInput.value,
                years: yearsInput.value,
                frequency: frequencyInput.value
            };
            localStorage.setItem('compounderData', JSON.stringify(data));
        }

        /**
         * Loads saved data from localStorage and populates the form.
         */
        function loadData() {
            const savedData = localStorage.getItem('compounderData');
            if (savedData) {
                const data = JSON.parse(savedData);
                principalInput.value = data.principal || '';
                contributionInput.value = data.contribution || '';
                rateInput.value = data.rate || '';
                yearsInput.value = data.years || '';
                frequencyInput.value = data.frequency || '12';
            }
            calculateAndDisplay();
        }

        /**
         * Clears all input fields and removes data from localStorage.
         */
        function clearAll() {
            principalInput.value = '';
            contributionInput.value = '';
            rateInput.value = '';
            yearsInput.value = '';
            frequencyInput.value = '12';
            localStorage.removeItem('compounderData');
            calculateAndDisplay();
        }

        // Add event listeners for live calculation and saving
        [principalInput, contributionInput, rateInput, yearsInput, frequencyInput].forEach(input => {
            input.addEventListener('input', calculateAndDisplay);
        });

        // Add event listener for the reset button
        resetBtn.addEventListener('click', clearAll);

        // Initialize the app on page load by attempting to load saved data
        document.addEventListener('DOMContentLoaded', loadData);
    </script>
</body>
</html>
