<div class="glass-card rounded-[3rem] overflow-hidden">
    <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
        <div class="mx-8 mt-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-sm"><?= $msg ?></div>
    <?php endif; ?>

    <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-white/50">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Registered Vendors</h3>
            <p class="text-slate-500 text-sm mt-1"><?= count($vendors) ?> total vendor entities</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">
                    <th class="px-8 py-5">Store</th>
                    <th class="px-8 py-5">Owner</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5">Registered</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if(empty($vendors)): ?>
                    <tr><td colspan="5" class="px-8 py-16 text-center text-slate-400 italic">No vendors registered yet.</td></tr>
                <?php else: ?>
                    <?php foreach($vendors as $vendor): ?>
                        <tr class="hover:bg-slate-50 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-sm">
                                        <?= strtoupper(substr($vendor['store_name'] ?? 'V', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900"><?= htmlspecialchars($vendor['store_name'] ?? 'N/A') ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono">/<?= htmlspecialchars($vendor['store_slug'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($vendor['owner_name'] ?? '') ?></div>
                                <div class="text-slate-400 text-xs"><?= htmlspecialchars($vendor['user_email']) ?></div>
                            </td>
                            <td class="px-8 py-5">
                                <?php $active = !empty($vendor['is_active']); ?>
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter rounded-xl <?= $active ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?>">
                                    <?= $active ? '✓ Active' : '⏳ Pending' ?>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-xs text-slate-400 font-bold uppercase"><?= date('d M Y', strtotime($vendor['created_at'])) ?></td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-4">
                                    <?php if(!$active): ?>
                                        <form action="<?= url('/admin/vendors/verify') ?>" method="POST">
                                            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                                            <button type="submit" class="text-xs font-black text-emerald-600 hover:underline uppercase tracking-tighter">✓ Verify</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?= url('/admin/vendors/suspend') ?>" method="POST" onsubmit="return confirm('Suspend this vendor?')">
                                            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                                            <button type="submit" class="text-xs font-black text-amber-600 hover:underline uppercase tracking-tighter">⏸ Suspend</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
