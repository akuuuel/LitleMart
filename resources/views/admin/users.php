<div class="glass-card rounded-[3rem] overflow-hidden">
    <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
        <div class="mx-8 mt-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-sm"><?= $msg ?></div>
    <?php endif; ?>

    <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between bg-white/50">
        <div>
            <h3 class="text-lg md:text-xl font-black text-slate-900 tracking-tight uppercase italic">Registered Users</h3>
            <p class="text-slate-500 text-[10px] md:text-sm font-bold uppercase tracking-widest mt-1"><?= count($users) ?> total registered accounts</p>
        </div>
    </div>

    <!-- Desktop Table (hidden on mobile) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">
                    <th class="px-8 py-5">User</th>
                    <th class="px-8 py-5">Email</th>
                    <th class="px-8 py-5">Roles</th>
                    <th class="px-8 py-5">Joined</th>
                    <th class="px-8 py-5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($users as $user): ?>
                    <tr class="hover:bg-slate-50 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-black text-sm">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <span class="font-bold text-slate-900"><?= htmlspecialchars($user['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-5 font-medium text-slate-600"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-8 py-5">
                            <?php foreach(explode(', ', $user['roles'] ?? 'customer') as $role): ?>
                                <span class="inline-block px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-tighter mr-1
                                    <?= $role === 'admin' ? 'bg-red-50 text-red-600' : ($role === 'vendor' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500') ?>">
                                    <?= $role ?>
                                </span>
                            <?php endforeach; ?>
                        </td>
                        <td class="px-8 py-5 text-xs text-slate-400 font-bold uppercase tracking-tighter"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td class="px-8 py-5 text-right">
                            <?php if(!in_array('admin', explode(', ', $user['roles'] ?? ''))): ?>
                                <form action="<?= url('/admin/users/deactivate') ?>" method="POST" onsubmit="return confirm('Deactivate this user? They will lose all roles.')">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="text-xs font-black text-red-500 hover:underline uppercase tracking-tighter">Deactivate</button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-slate-300 font-bold">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards (shown only on mobile) -->
    <div class="md:hidden divide-y divide-slate-50">
        <?php foreach($users as $user): ?>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-black text-sm">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-black text-slate-900 italic uppercase"><?= htmlspecialchars($user['name']) ?></div>
                        <div class="text-[10px] text-slate-400 font-bold lowercase"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex flex-wrap gap-1">
                        <?php foreach(explode(', ', $user['roles'] ?? 'customer') as $role): ?>
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-tighter
                                <?= $role === 'admin' ? 'bg-red-50 text-red-600' : ($role === 'vendor' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500') ?>">
                                <?= $role ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <?php if(!in_array('admin', explode(', ', $user['roles'] ?? ''))): ?>
                        <form action="<?= url('/admin/users/deactivate') ?>" method="POST" onsubmit="return confirm('Deactivate this user?')">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="text-[9px] font-black text-red-500 uppercase tracking-widest px-3 py-1 bg-red-50 rounded-lg">Nonaktifkan</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
