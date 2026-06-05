<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- 
    Mobile Chat optimization:
    - Fixed Header
    - Sticky bottom input with safe area padding
    - Full screen view on mobile
-->
<div class="bg-white md:bg-background min-h-screen md:min-h-0 md:h-[90vh] flex items-center justify-center p-0 md:p-6 lg:p-10">
    <div class="w-full max-w-7xl h-screen md:h-full bg-white md:rounded-[3rem] md:shadow-2xl md:shadow-emerald-900/10 md:border border-gray-100 overflow-hidden flex flex-col md:flex-row relative">

        <!-- Sidebar: Conversations List -->
        <div id="chat-sidebar" class="w-full md:w-96 border-r border-gray-50 flex flex-col flex-shrink-0 absolute inset-0 md:relative bg-white z-20 md:z-auto transition-transform duration-300">
            <div class="p-6 md:p-8 border-b border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black text-gray-900 italic tracking-tight">Pesan Saya</h2>
                    <a href="<?= url('/') ?>" class="md:hidden w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="relative">
                    <input type="text" id="chat-search" placeholder="Cari percakapan..."
                        class="w-full bg-gray-50 border-none rounded-2xl py-3 px-12 text-sm focus:ring-2 focus:ring-emerald-500/20 transition-all font-medium">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
            </div>

            <!-- Conversation list rendered by Firebase JS -->
            <div id="conversation-list" class="flex-1 overflow-y-auto no-scrollbar p-4 md:p-6 space-y-3">
                <div class="text-center py-20 text-gray-300">
                    <i class="fa-solid fa-spinner fa-spin text-3xl mb-3"></i>
                    <p class="text-sm font-medium">Memuat percakapan...</p>
                </div>
            </div>
        </div>

        <!-- Main Chat Window -->
        <div id="chat-main" class="flex-1 flex flex-col w-full h-full bg-white absolute inset-0 md:relative z-10 md:z-auto pointer-events-none md:pointer-events-auto opacity-0 md:opacity-100 transition-all duration-300">

            <!-- Empty State (default) -->
            <div id="chat-empty-state" class="flex-1 flex flex-col items-center justify-center text-center p-10 md:p-20 bg-gray-50/30">
                <div class="w-32 h-32 md:w-40 md:h-40 bg-emerald-50 rounded-full flex items-center justify-center mb-10 text-7xl shadow-inner text-emerald-100">
                    <i class="fa-regular fa-comment-dots"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4 italic">Mulai Percakapan</h2>
                <p class="text-xs md:text-sm text-gray-400 max-w-sm font-medium">Pilih percakapan di samping untuk mulai berkirim pesan dengan penjual.</p>
            </div>

            <!-- Active Chat UI (hidden until a chat is opened) -->
            <div id="chat-active" class="hidden flex-col h-full overflow-hidden pointer-events-none">
                <!-- Chat Header - FIXED/STICKY -->
                <div class="sticky top-0 p-4 md:p-6 border-b border-gray-100 flex items-center justify-between bg-white/90 backdrop-blur-md z-30 flex-shrink-0 pointer-events-auto shadow-sm">
                    <div class="flex items-center gap-3 md:gap-4">
                        <button onclick="closeChatMobile()" class="md:hidden w-10 h-10 flex items-center justify-center text-emerald-600 bg-emerald-50 active:scale-90 rounded-xl transition-all">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <div id="chat-avatar" class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-base md:text-lg font-black flex-shrink-0 shadow-sm">?</div>
                        <div class="min-w-0">
                            <h3 id="chat-partner-name" class="font-black text-gray-900 flex items-center gap-2 italic text-sm md:text-base truncate">
                                -
                            </h3>
                            <div class="flex items-center gap-2">
                                <span id="online-dot" class="w-2 h-2 bg-gray-300 rounded-full transition-colors duration-500"></span>
                                <p id="online-status-text" class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase tracking-wider">Offline</p>
                                <div id="typing-indicator" class="h-3 ml-1"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 md:p-10 space-y-6 md:space-y-8 bg-gray-50/30 no-scrollbar pointer-events-auto">
                    <!-- Messages injected by Firebase JS -->
                </div>

                <!-- Input Area - FIXED TO BOTTOM -->
                <div class="p-4 pb-8 md:pb-8 border-t border-gray-100 bg-white flex-shrink-0 pointer-events-auto z-30 shadow-[0_-10px_25px_rgba(0,0,0,0.02)]">
                    <div class="max-w-4xl mx-auto flex items-center gap-3 md:gap-6 bg-gray-50 p-2 md:p-3 rounded-[1.8rem] md:rounded-[2.5rem] border border-gray-100 shadow-inner group focus-within:ring-2 focus-within:ring-emerald-500/10 focus-within:border-emerald-200 transition-all">
                        <button class="w-10 h-10 text-gray-400 hover:text-emerald-600 transition-all hidden md:flex items-center justify-center">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                        <input type="text" id="chat-input" placeholder="Tulis pesan..."
                            class="flex-1 bg-transparent border-none focus:ring-0 text-base md:text-sm font-medium text-gray-700 placeholder-gray-400 pl-4 md:pl-0">
                        <button id="send-button"
                            class="w-10 h-10 md:w-14 md:h-14 flex-shrink-0 bg-emerald-600 text-white rounded-2xl md:rounded-[1.5rem] shadow-lg shadow-emerald-900/20 flex items-center justify-center hover:scale-105 active:scale-90 transition-all">
                            <i class="fa-solid fa-paper-plane text-xs md:text-base"></i>
                        </button>
                    </div>
                    <div class="mt-3 flex items-center gap-2 justify-center">
                        <p id="chat-brand-footer" class="text-[9px] text-gray-300 font-bold uppercase tracking-widest text-center flex items-center gap-2">
                             <i class="fa-solid fa-bolt text-[8px]"></i>
                             Powered by Firebase Realtime Engine
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Mobile Layout Fixes */
    @media (max-width: 767px) {
        footer { display: none !important; }
        body { overflow: hidden; height: 100%; position: fixed; width: 100%; }
        #chat-main.opacity-100 { pointer-events: auto !important; }
        
        /* Ensure input area sticks to bottom even with browser nav bars */
        #chat-active { 
            position: fixed;
            top: 56px; /* Offset for navbar if fixed */
            bottom: 0;
            left: 0;
            right: 0;
            height: auto;
            background: white;
            z-index: 50;
        }

        /* Adjust top offset based on global navbar height */
        <?php if (!isset($isDashboard) || !$isDashboard): ?>
        #chat-active { top: 56px; } 
        <?php else: ?>
        #chat-active { top: 0; }
        <?php endif; ?>
    }
</style>

<script>
    const currentUserId = '<?= \App\Core\Session::get('user_id') ?>';
    const initialTargetUserId = '<?= $activeVendor['user_id'] ?? '' ?>';
    const initialPartnerName  = '<?= htmlspecialchars($activeVendor['store_name'] ?? ($activeVendor['owner_name'] ?? ''), ENT_QUOTES) ?>';

    function openChatMobile() {
        if (window.innerWidth < 768) {
            document.getElementById('chat-sidebar').classList.add('-translate-x-full');
            const main = document.getElementById('chat-main');
            main.classList.remove('pointer-events-none', 'opacity-0');
            main.classList.add('opacity-100');
            
            const activePanel = document.getElementById('chat-active');
            activePanel.classList.remove('hidden', 'pointer-events-none');
            activePanel.classList.add('flex');
            
            // Scroll to bottom
            const container = document.getElementById('messages-container');
            if (container) container.scrollTop = container.scrollHeight;
        }
    }

    function closeChatMobile() {
        if (window.innerWidth < 768) {
            document.getElementById('chat-sidebar').classList.remove('-translate-x-full');
            const main = document.getElementById('chat-main');
            main.classList.add('pointer-events-none', 'opacity-0');
            main.classList.remove('opacity-100');
            
            const activePanel = document.getElementById('chat-active');
            activePanel.classList.add('hidden', 'pointer-events-none');
            activePanel.classList.remove('flex');
            
            window.history.pushState({}, '', '<?= url('/messages') ?>');
            currentActiveTarget = null;
        }
    }

    function renderConversationList(conversations) {
        const sidebar = document.getElementById('conversation-list');
        if (!sidebar) return;

        const urlParams = new URLSearchParams(window.location.search);
        const activeU = urlParams.get('u') || initialTargetUserId;

        if (conversations.length === 0) {
            sidebar.innerHTML = `<div class="text-center py-20 px-4"><p class="text-gray-400 text-sm italic">Belum ada percakapan.</p></div>`;
            return;
        }

        sidebar.innerHTML = conversations.map(c => {
            const isActive = String(c.partnerId) === String(activeU);
            return `
            <div onclick="switchChat('${c.partnerId}', '${(c.partnerName || 'User').replace(/'/g, "\\'")}')"
                 data-partner="${c.partnerId}"
                 class="p-4 md:p-5 ${isActive ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-gray-100'} rounded-3xl border flex items-center gap-4 cursor-pointer hover:bg-emerald-50/70 transition-all relative shadow-sm">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg md:text-xl font-black shadow-sm flex-shrink-0">
                    ${(c.partnerName && c.partnerName.length > 0) ? c.partnerName[0].toUpperCase() : '?'}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <h4 class="font-black text-gray-900 truncate text-sm">${c.partnerName || 'User'}</h4>
                        <span class="text-[8px] md:text-[9px] text-gray-400 font-bold flex-shrink-0 ml-1">
                            ${c.timestamp ? new Date(c.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}
                        </span>
                    </div>
                    <div class="flex justify-between items-center mt-0.5">
                        <p class="text-[11px] md:text-xs text-gray-500 truncate flex-1 font-medium">${c.lastMessage || ''}</p>
                        ${c.unreadCount > 0 ? `<span class="bg-emerald-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full ml-2 shadow-sm animate-pulse flex-shrink-0">${c.unreadCount}</span>` : ''}
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    let currentActiveTarget = null;
    let typingTimer = null;

    function switchChat(partnerId, partnerName) {
        if (String(partnerId) === String(currentActiveTarget)) {
            openChatMobile();
            return;
        }
        currentActiveTarget = String(partnerId);

        window.history.pushState({ partnerId, partnerName }, '', `<?= url('/messages') ?>?u=${partnerId}`);
        openChatMobile();

        document.getElementById('chat-empty-state').classList.add('hidden');
        document.getElementById('chat-empty-state').classList.remove('flex');
        
        const activePanel = document.getElementById('chat-active');
        activePanel.classList.remove('hidden');
        activePanel.classList.add('flex');

        document.getElementById('chat-partner-name').textContent = partnerName;
        document.getElementById('chat-avatar').textContent = partnerName ? partnerName[0].toUpperCase() : '?';
        document.getElementById('online-status-text').textContent = 'Offline';
        document.getElementById('online-dot').className = 'w-2 h-2 bg-gray-300 rounded-full transition-colors duration-500';
        document.getElementById('typing-indicator').innerHTML = '';

        document.querySelectorAll('[data-partner]').forEach(el => {
            const isActive = String(el.dataset.partner) === String(partnerId);
            el.classList.toggle('bg-emerald-50', isActive);
            el.classList.toggle('border-emerald-200', isActive);
            el.classList.toggle('bg-white', !isActive);
            el.classList.toggle('border-gray-100', !isActive);
        });

        document.getElementById('messages-container').innerHTML = '';
        if (typeof initChat === 'function') {
            initChat(currentUserId, partnerId);
        }
        bindSendEvents(partnerId);
    }

    function bindSendEvents(targetId) {
        const sendBtn = document.getElementById('send-button');
        const input   = document.getElementById('chat-input');
        if (!sendBtn || !input) return;

        const newSendBtn = sendBtn.cloneNode(true);
        sendBtn.parentNode.replaceChild(newSendBtn, sendBtn);
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);

        const performSend = () => {
            const text = newInput.value.trim();
            if (text && typeof sendMessage === 'function') {
                sendMessage(currentUserId, targetId, text);
                newInput.value = '';
                if (typeof updateTypingStatus === 'function') {
                    updateTypingStatus(currentUserId, targetId, false);
                }
            }
        };

        newSendBtn.addEventListener('click', performSend);
        newInput.addEventListener('keypress', e => { if (e.key === 'Enter') performSend(); });
        newInput.addEventListener('input', () => {
            if (typeof updateTypingStatus === 'function') {
                updateTypingStatus(currentUserId, targetId, true);
            }
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                if (typeof updateTypingStatus === 'function') {
                    updateTypingStatus(currentUserId, targetId, false);
                }
            }, 2000);
        });
        
        if (window.innerWidth >= 768) {
            newInput.focus();
        }
    }

    function displayMessage(msg, currentUserId, msgKey) {
        const container = document.getElementById('messages-container');
        if (!container) return;

        const isMe = String(msg.senderId) === String(currentUserId);
        container.insertAdjacentHTML('beforeend', `
            <div class="flex ${isMe ? 'justify-end' : ''} gap-3 md:gap-4 max-w-[90%] md:max-w-[80%] ${isMe ? 'ml-auto' : ''}" data-id="${msgKey}">
                ${!isMe ? `<div class="w-6 h-6 md:w-8 md:h-8 rounded-lg bg-emerald-100 flex-shrink-0 flex items-center justify-center text-[10px] md:text-xs font-black text-emerald-600">${msg.fromName ? msg.fromName[0] : '?'}</div>` : ''}
                <div class="${isMe ? 'bg-emerald-600 text-white' : 'bg-white text-gray-700'} p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] ${isMe ? 'rounded-tr-none' : 'rounded-tl-none'} shadow-sm border ${isMe ? 'border-emerald-600' : 'border-emerald-50'}">
                    <p class="text-sm leading-relaxed font-medium">${msg.text}</p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-[8px] md:text-[9px] ${isMe ? 'text-white/60' : 'text-gray-300'} font-black uppercase tracking-widest block">
                            ${msg.timestamp ? new Date(msg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}
                        </span>
                        ${isMe ? `<div class="ticks-container">${getTicksHtml(msg.status)}</div>` : ''}
                    </div>
                </div>
            </div>
        `);
        container.scrollTop = container.scrollHeight;
    }

    function getTicksHtml(status) {
        if (status === 'read')     return '<i class="fa-solid fa-check-double text-emerald-200 ml-1 text-xs"></i>';
        if (status === 'received') return '<i class="fa-solid fa-check-double text-white/40 ml-1 text-xs"></i>';
        return '<i class="fa-solid fa-check text-white/40 ml-1 text-xs"></i>';
    }

    window.onpopstate = () => {
        const u = new URLSearchParams(window.location.search).get('u');
        if (u) {
            const el = document.querySelector(`[data-partner="${u}"]`);
            const name = el ? el.querySelector('h4')?.textContent.trim() : 'User';
            currentActiveTarget = null;
            switchChat(u, name);
        } else if (window.innerWidth < 768) {
            closeChatMobile();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        if (initialTargetUserId) {
            switchChat(initialTargetUserId, initialPartnerName || 'User');
        } else if (window.innerWidth < 768) {
           closeChatMobile();
        }
    });

    // Handle viewport height issues on mobile
    window.addEventListener('resize', () => {
        if (window.innerWidth < 768) {
            const activePanel = document.getElementById('chat-active');
            if (activePanel && !activePanel.classList.contains('hidden')) {
                const container = document.getElementById('messages-container');
                if (container) container.scrollTop = container.scrollHeight;
            }
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>