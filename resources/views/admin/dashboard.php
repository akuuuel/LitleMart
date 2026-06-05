<div class="mb-10" x-data="{ announcementModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Marketplace Insights</h1>
            <p class="text-slate-500 font-medium mt-1">Real-time performance overview of your LitleMart instance.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </button>
            <button @click="announcementModal = true" class="px-5 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Announcement
            </button>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div x-show="announcementModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="announcementModal = false"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-xl font-black text-slate-900 mb-6">Broadcast Announcement</h3>
            <form action="<?= url('/admin/announcement') ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Title</label>
                    <input type="text" name="title" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="System Update Notice...">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Message</label>
                    <textarea name="message" rows="4" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Enter your announcement here..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Target Audience</label>
                    <select name="target" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="all">All Users</option>
                        <option value="vendors">Vendors Only</option>
                        <option value="customers">Customers Only</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="announcementModal = false" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20">Send Broadcast</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <?= $msg ?>
        </div>
    <?php endif; ?>
    <?php if ($err = \App\Core\Session::getFlash('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <?= $err ?>
        </div>
    <?php endif; ?>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
        <!-- Revenue Card -->
        <div class="glass-card p-4 md:p-6 rounded-2xl md:rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
            <div class="relative z-10">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-emerald-50 text-emerald-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-[9px] md:text-xs font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Gross Revenue</div>
                <div class="text-lg md:text-2xl font-black text-slate-900 leading-none">Rp.<?= number_format($stats['total_revenue'], 0, ',', '.') ?></div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Orders Card -->
        <div class="glass-card p-4 md:p-6 rounded-2xl md:rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
            <div class="relative z-10">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="text-[9px] md:text-xs font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Total Pesanan</div>
                <div class="text-lg md:text-2xl font-black text-slate-900 leading-none"><?= $stats['total_orders'] ?></div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Vendors Card -->
        <div class="glass-card p-4 md:p-6 rounded-2xl md:rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
            <div class="relative z-10">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-50 text-purple-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="text-[9px] md:text-xs font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Entitas Vendor</div>
                <div class="text-lg md:text-2xl font-black text-slate-900 leading-none"><?= $stats['total_vendors'] ?></div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Users Card -->
        <div class="glass-card p-4 md:p-6 rounded-2xl md:rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
            <div class="relative z-10">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-amber-50 text-amber-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="text-[9px] md:text-xs font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Total Audiens</div>
                <div class="text-lg md:text-2xl font-black text-slate-900 leading-none"><?= $stats['total_users'] ?></div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl"></div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mb-10">
        <div class="lg:col-span-2 glass-card p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem]">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-base md:text-lg font-black text-slate-900 uppercase tracking-tight italic">Revenue Velocity</h3>
                <select class="text-[10px] font-black text-slate-500 bg-slate-50 border-none rounded-lg px-3 py-1.5 focus:ring-emerald-500 uppercase">
                    <option>7 Hari Terakhir</option>
                    <option>30 Hari Terakhir</option>
                </select>
            </div>
            <div class="h-64 md:h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        
        <div class="glass-card p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem]">
            <h3 class="text-base md:text-lg font-black text-slate-900 uppercase tracking-tight italic mb-8">Market Segmentation</h3>
            <div class="h-48 md:h-64 relative">
                <canvas id="segmentChart"></canvas>
            </div>
            <div class="mt-8 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Vendors</span>
                    </div>
                    <span class="text-xs font-black text-slate-900"><?= round(($stats['total_vendors'] / max(1, $stats['total_users'])) * 100, 1) ?>%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-slate-200 shadow-sm"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Customers</span>
                    </div>
                    <span class="text-xs font-black text-slate-900"><?= 100 - round(($stats['total_vendors'] / max(1, $stats['total_users'])) * 100, 1) ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass-card rounded-[2rem] md:rounded-[3rem] overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/50">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-lg md:text-xl font-black text-slate-900 uppercase tracking-tight italic">Market Intelligence</h3>
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <p class="text-slate-500 text-[10px] md:text-sm font-bold uppercase tracking-widest">Dashboard aktif memperbarui ledger</p>
            </div>
        </div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">
                        <th class="px-8 py-5">Origin Hash</th>
                        <th class="px-8 py-5">Identity Pointer</th>
                        <th class="px-8 py-5">Value (IDR)</th>
                        <th class="px-8 py-5">Protocol Status</th>
                        <th class="px-8 py-5 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(empty($recent_orders)): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-slate-400 font-medium italic uppercase tracking-widest text-[10px]">Null set detected. No recent ledger entries.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($recent_orders as $order): ?>
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-slate-300 group-hover:bg-emerald-500 transition-colors"></div>
                                        <span class="font-mono text-xs font-bold text-slate-900 uppercase">#<?= substr($order['id'], 0, 12) ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-700"><?= htmlspecialchars($order['customer_name']) ?></span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-slate-900 italic">Rp<?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                                </td>
                                <td class="px-8 py-5">
                                    <?php 
                                        $statusClass = 'bg-slate-100 text-slate-600';
                                        if($order['status'] === 'completed') $statusClass = 'bg-emerald-50 text-emerald-600';
                                        if($order['status'] === 'pending') $statusClass = 'bg-amber-50 text-amber-600';
                                    ?>
                                    <span class="px-3 py-1 <?= $statusClass ?> text-[10px] font-black uppercase tracking-tighter rounded-xl">
                                        <?= strtoupper($order['status']) ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-slate-400 text-[10px]">
                                    <?= strtoupper(date('d M Y | H:i', strtotime($order['created_at']))) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile List (shown only on mobile) -->
        <div class="md:hidden divide-y divide-slate-50">
            <?php if(empty($recent_orders)): ?>
                <div class="px-6 py-12 text-center text-slate-400 text-[10px] uppercase font-black italic">Belum ada transaksi terbaru.</div>
            <?php else: ?>
                <?php foreach($recent_orders as $order): ?>
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">ID Transaksi</div>
                                <div class="font-mono text-[11px] font-black text-slate-900">#<?= substr($order['id'], 0, 12) ?></div>
                            </div>
                            <?php 
                                $statusClass = 'bg-slate-100 text-slate-600';
                                if($order['status'] === 'completed') $statusClass = 'bg-emerald-50 text-emerald-600';
                                if($order['status'] === 'pending') $statusClass = 'bg-amber-50 text-amber-600';
                            ?>
                            <span class="px-3 py-1 <?= $statusClass ?> text-[9px] font-black uppercase tracking-tighter rounded-lg italic">
                                <?= strtoupper($order['status']) ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-end">
                            <div>
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Pelanggan</div>
                                <div class="text-xs font-black text-slate-800 italic uppercase"><?= htmlspecialchars($order['customer_name']) ?></div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] font-black text-emerald-600 italic">Rp<?= number_format($order['total_amount'], 0, ',', '.') ?></div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tight mt-1"><?= date('d M, H:i', strtotime($order['created_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Velocity Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= json_encode($chart_data) ?>,
                borderColor: '#10B981',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
                    return gradient;
                },
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#10B981',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }
                }
            }
        }
    });

    // Market Segmentation Chart
    const segCtx = document.getElementById('segmentChart').getContext('2d');
    new Chart(segCtx, {
        type: 'doughnut',
        data: {
            labels: ['Vendors', 'Customers'],
            datasets: [{
                data: [<?= $stats['total_vendors'] ?>, <?= max(0, $stats['total_users'] - $stats['total_vendors']) ?>],
                backgroundColor: ['#10B981', '#E2E8F0'],
                borderWidth: 0,
                cutout: '80%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
