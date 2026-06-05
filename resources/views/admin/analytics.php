<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Analytics Hub</h1>
        <p class="text-slate-500 mt-1">Comprehensive business intelligence from your marketplace data.</p>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="glass-card p-8 rounded-[2.5rem]">
        <h3 class="text-lg font-extrabold text-slate-900 mb-6">Monthly Revenue — Last 12 Months</h3>
        <div class="h-72">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Products -->
        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="text-lg font-extrabold text-slate-900 mb-6">Top Selling Products</h3>
            <?php if(empty($topProducts)): ?>
                <p class="text-slate-400 text-sm italic">No product sales data yet.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach($topProducts as $i => $p): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-black text-xs"><?= $i + 1 ?></div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 text-sm truncate"><?= htmlspecialchars($p['name']) ?></div>
                                <div class="text-xs text-slate-400"><?= $p['total_sold'] ?> units · Rp<?= number_format($p['revenue'], 0, ',', '.') ?></div>
                            </div>
                            <div class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex-shrink-0">
                                <?= $p['total_sold'] ?> sold
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Growth -->
        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="text-lg font-extrabold text-slate-900 mb-6">New User Registrations</h3>
            <?php if(empty($userGrowth)): ?>
                <p class="text-slate-400 text-sm italic">No registration data available.</p>
            <?php else: ?>
                <div class="h-52">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue
    const monthly    = <?= json_encode($monthly) ?>;
    const mLabels    = monthly.map(r => r.month);
    const mData      = monthly.map(r => parseFloat(r.total));

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: mLabels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: mData,
                backgroundColor: 'rgba(16,185,129,0.15)',
                borderColor: '#10B981',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { ticks: { callback: v => 'Rp' + (v/1000).toFixed(0) + 'k', font: { size: 10 }, color: '#94a3b8' }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8' } }
            }
        }
    });

    <?php if(!empty($userGrowth)): ?>
    // User growth
    const ug = <?= json_encode($userGrowth) ?>;
    new Chart(document.getElementById('userGrowthChart'), {
        type: 'line',
        data: {
            labels: ug.map(r => r.month),
            datasets: [{
                data: ug.map(r => parseInt(r.count)),
                borderColor: '#6366f1',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(99,102,241,0.08)',
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { ticks: { stepSize: 1, font: { size: 10 }, color: '#94a3b8' }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8' } }
            }
        }
    });
    <?php endif; ?>
});
</script>
