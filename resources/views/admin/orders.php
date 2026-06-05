<div class="glass-card rounded-[3rem] overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-white/50">
        <div>
            <h3 class="text-xl font-black text-slate-900">All Orders</h3>
            <p class="text-slate-500 text-sm mt-1"><?= count($orders) ?> total orders in system</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">
                    <th class="px-8 py-5">Order ID</th>
                    <th class="px-8 py-5">Customer</th>
                    <th class="px-8 py-5">Amount</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if(empty($orders)): ?>
                    <tr><td colspan="5" class="px-8 py-16 text-center text-slate-400 italic">No orders found.</td></tr>
                <?php else: ?>
                    <?php foreach($orders as $order): ?>
                        <?php
                            $sc = 'bg-slate-100 text-slate-600';
                            if($order['status'] === 'completed' || $order['status'] === 'paid') $sc = 'bg-emerald-50 text-emerald-700';
                            if($order['status'] === 'processing') $sc = 'bg-blue-50 text-blue-700';
                            if($order['status'] === 'pending')   $sc = 'bg-amber-50 text-amber-700';
                            if($order['status'] === 'cancelled' || $order['status'] === 'denied' || $order['status'] === 'expired') $sc = 'bg-red-50 text-red-700';
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-8 py-4 font-mono text-xs font-bold text-slate-700">#<?= strtoupper(substr($order['id'], 0, 12)) ?></td>
                            <td class="px-8 py-4">
                                <div class="font-bold text-slate-900"><?= htmlspecialchars($order['customer_name']) ?></div>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars($order['customer_email']) ?></div>
                            </td>
                            <td class="px-8 py-4 font-black text-slate-900">Rp<?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                            <td class="px-8 py-4">
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-tight <?= $sc ?>"><?= $order['status'] ?></span>
                            </td>
                            <td class="px-8 py-4 text-xs text-slate-400 font-bold"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
