<!-- Firebase App (the core Firebase SDK) -->
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<!-- Firebase Realtime Database -->
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-database-compat.js"></script>

<script>
    // Deteksi Otomatis: LitleMart akan mencoba URL US dan Singapore
    const projectId = "litlemart-f4c8f";
    const firebaseConfig = {
        apiKey: "AIzaSyBmTWuaSIPsv7wP6uwUNLeH_zMClHJQSb4",
        authDomain: `${projectId}.firebaseapp.com`,
        projectId: projectId,
        storageBucket: `${projectId}.appspot.com`,
        messagingSenderId: "108726641685",
        appId: "1:108726641685:web:f223631d1e2fe9c6051a1c",
        // Mencoba fallback URL jika default gagal
        databaseURL: (window.location.hostname === 'localhost' || window.location.hostname.includes('ngrok')) ?
            `https://litlemart-f4c8f-default-rtdb.asia-southeast1.firebasedatabase.app` :
            `https://${projectId}-default-rtdb.firebaseio.com`
    };

    // Initialize Firebase
    let database;
    if (firebaseConfig.apiKey && firebaseConfig.apiKey !== "YOUR_API_KEY") {
        try {
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }
            database = firebase.database();
            console.log("LitleMart Firebase: Berhasil!");

            const currentUserId = '<?= \App\Core\Session::get('user_id') ?>';
            if (currentUserId && currentUserId !== '') {
                initPresence(currentUserId);
                initNotifications(currentUserId);
                initConversationList(currentUserId);
            }
        } catch (e) {
            console.error("Firebase Error:", e);
        }

        // 0. SISTEM KEHADIRAN (ONLINE/OFFLINE)
        function initPresence(userId) {
            const statusRef = database.ref('status/' + userId);
            const connectedRef = database.ref('.info/connected');

            connectedRef.on('value', (snapshot) => {
                if (snapshot.val() === false) return;
                statusRef.onDisconnect().set({ state: 'offline', last_changed: firebase.database.ServerValue.TIMESTAMP });
                statusRef.set({ state: 'online', last_changed: firebase.database.ServerValue.TIMESTAMP });
            });
        }

        // 1. FUNGSI REALTIME CHAT (Upgrade: Typing & Read Status)
        let _activeChatRef = null;
        let _activeTypingRef = null;
        let _activeStatusRef = null;

        function initChat(currentUserId, targetUserId) {
            if (!database) return;

            // Matikan semua listener lama sebelum init baru
            if (_activeChatRef)   { _activeChatRef.off();   _activeChatRef   = null; }
            if (_activeTypingRef) { _activeTypingRef.off(); _activeTypingRef = null; }
            if (_activeStatusRef) { _activeStatusRef.off(); _activeStatusRef = null; }

            const chatId = getChatRoomId(currentUserId, targetUserId);
            const chatRef = database.ref('chats/' + chatId);
            _activeChatRef = chatRef;

            // Tandai sudah dibaca untuk partner ini
            clearNotifications(currentUserId, targetUserId);

            // Update status pesan ke "read" saat dibuka
            chatRef.once('value', (snapshot) => {
                snapshot.forEach((child) => {
                    const msg = child.val();
                    if (String(msg.senderId) === String(targetUserId) && msg.status !== 'read') {
                        child.ref.update({ status: 'read' });
                    }
                });
            });

            // Listen Indikator Mengetik
            _activeTypingRef = database.ref('typing/' + chatId + '/' + targetUserId);
            _activeTypingRef.on('value', (snapshot) => {
                const el = document.getElementById('typing-indicator');
                if (el) {
                    el.innerHTML = snapshot.val()
                        ? '<span class="italic text-emerald-500 animate-pulse text-[10px]">Sedang mengetik...</span>'
                        : '';
                }
            });

            // Listen Status Online Partner
            _activeStatusRef = database.ref('status/' + targetUserId);
            _activeStatusRef.on('value', (snapshot) => {
                const dot  = document.getElementById('online-dot');
                const text = document.getElementById('online-status-text');
                const isOnline = snapshot.val()?.state === 'online';
                if (dot) {
                    dot.classList.toggle('bg-emerald-500', isOnline);
                    dot.classList.toggle('bg-gray-400', !isOnline);
                }
                if (text) text.textContent = isOnline ? 'Online' : 'Offline';
            });

            // Bersihkan kontainer pesan
            const container = document.getElementById('messages-container');
            if (container) container.innerHTML = '';

            // Dengarkan pesan masuk
            chatRef.on('child_added', (snapshot) => {
                const msg = snapshot.val();
                if (String(msg.senderId) === String(targetUserId)) {
                    clearNotifications(currentUserId, targetUserId);
                }
                if (typeof displayMessage === 'function') {
                    displayMessage(msg, currentUserId, snapshot.key);
                }
            });

            chatRef.on('child_changed', (snapshot) => {
                updateMessageUI(snapshot.key, snapshot.val());
            });
        }

        function updateMessageUI(msgKey, msgData) {
            const msgElement = document.querySelector(`[data-id="${msgKey}"] .ticks-container`);
            if (msgElement) {
                msgElement.innerHTML = getTicksHtml(msgData.status);
            }
        }

        function getTicksHtml(status) {
            if (status === 'read') return '<i class="fa-solid fa-check-double text-blue-400 ml-1"></i>';
            if (status === 'received') return '<i class="fa-solid fa-check-double text-gray-400 ml-1"></i>';
            return '<i class="fa-solid fa-check text-gray-400 ml-1"></i>';
        }

        function sendMessage(currentUserId, targetUserId, text) {
            if (!targetUserId || !currentUserId) return;

            database.ref('status/' + targetUserId).once('value', (snapshot) => {
                const isOnline = snapshot.val()?.state === 'online';
                
                const roomId = getChatRoomId(currentUserId, targetUserId);
                const msgData = {
                    senderId: currentUserId,
                    receiverId: targetUserId,
                    text: text,
                    fromName: '<?= \App\Core\Session::get('user_name') ?>',
                    status: isOnline ? 'received' : 'sent',
                    timestamp: firebase.database.ServerValue.TIMESTAMP
                };

                database.ref('chats/' + roomId).push().set(msgData);
                
                // Update Conversations
                const partnerName = document.querySelector('h3.font-black')?.textContent.trim() || 'Partner';
                database.ref('conversations/' + currentUserId + '/' + targetUserId).update({
                    partnerId: targetUserId, partnerName: partnerName, lastMessage: text, timestamp: firebase.database.ServerValue.TIMESTAMP
                });

                database.ref('conversations/' + targetUserId + '/' + currentUserId).transaction((curr) => {
                    return {
                        partnerId: currentUserId, partnerName: '<?= \App\Core\Session::get('user_name') ?>',
                        lastMessage: text, unreadCount: (curr?.unreadCount || 0) + 1, timestamp: firebase.database.ServerValue.TIMESTAMP
                    };
                });

                database.ref('notifications/' + targetUserId).push({ ...msgData, isRead: false });
            });
        }

        // Fungsi Update Mengetik
        let typingTimeout;
        function updateTypingStatus(currentUserId, targetUserId, isTyping) {
            const chatId = getChatRoomId(currentUserId, targetUserId);
            database.ref('typing/' + chatId + '/' + currentUserId).set(isTyping);
        }

        function clearNotifications(userId, partnerId) {
            if (!database) return;
            // 1. Bersihkan Notifikasi Toast/Badge Global
            const notifRef = database.ref('notifications/' + userId);
            notifRef.once('value', (snapshot) => {
                snapshot.forEach((child) => {
                    const notif = child.val();
                    if (notif.senderId === partnerId) {
                        child.ref.remove();
                    }
                });
                updateChatBadge(0);
            });

            // 2. Reset Unread Count di Sidebar untuk Partner ini
            database.ref('conversations/' + userId + '/' + partnerId).update({
                unreadCount: 0
            });
        }

        // 2. FUNGSI LIST PERCAKAPAN
        function initConversationList(userId) {
            const convRef = database.ref('conversations/' + userId);
            convRef.on('value', (snapshot) => {
                const conversations = [];
                snapshot.forEach((child) => {
                    conversations.push(child.val());
                });
                // Urutkan berdasarkan waktu terbaru
                conversations.sort((a, b) => b.timestamp - a.timestamp);

                if (typeof renderConversationList === 'function') {
                    renderConversationList(conversations);
                }
            });
        }

        let unreadChatCount = 0;

        // 2. FUNGSI REALTIME NOTIFICATION & BADGES
        function initNotifications(userId) {
            const notifRef = database.ref('notifications/' + userId);
            
            // Check if we are on notifications page - if so, clear them from Firebase
            const path = window.location.pathname;
            if (path.includes('/notifications') || path.includes('/vendor/notifications')) {
                clearSystemNotifications(userId);
            }

            // A. Update Badges (Real-time sync with database state)
            notifRef.on('value', (snapshot) => {
                let systemCount = 0;
                let chatCount = 0;
                
                snapshot.forEach((child) => {
                    const notif = child.val();
                    if (notif.senderId === 'system') {
                        systemCount++;
                    } else {
                        chatCount++;
                    }
                });
                
                updateNotificationBadge(systemCount);
                updateChatBadge(chatCount);
            });

            // B. Fresh Arrivals (Toast & Sound)
            notifRef.limitToLast(1).on('child_added', (snapshot) => {
                const notif = snapshot.val();
                if (notif.timestamp > pageLoadTime) {
                    showToastNotification(notif);
                }
            });
        }

        function clearSystemNotifications(userId) {
            if (!database) return;
            const notifRef = database.ref('notifications/' + userId);
            notifRef.once('value', (snapshot) => {
                snapshot.forEach((child) => {
                    if (child.val().senderId === 'system') {
                        child.ref.remove();
                    }
                });
            });
        }

        function updateChatBadge(count) {
            // Navbar badge (uses hidden class)
            const navBadge = document.getElementById('chat-badge');
            if (navBadge) {
                navBadge.textContent = count > 9 ? '9+' : count;
                navBadge.classList.toggle('hidden', count === 0);
            }
            // Sidebar badge (uses inline style)
            const sidebarBadge = document.getElementById('chat-badge-sidebar');
            if (sidebarBadge) {
                sidebarBadge.textContent = count > 9 ? '9+' : count;
                sidebarBadge.style.display = count > 0 ? 'inline-flex' : 'none';
            }
            // Dashboard stat card
            const dashboardStat = document.getElementById('dashboard-chat-count');
            if (dashboardStat) {
                dashboardStat.textContent = count;
            }
        }

        function updateNotificationBadge(count) {
            // Navbar badge (uses hidden class)
            const navBadge = document.getElementById('notification-badge');
            if (navBadge) {
                navBadge.textContent = count > 9 ? '9+' : count;
                navBadge.classList.toggle('hidden', count === 0);
            }
            // Sidebar badge (uses inline style)
            const sidebarBadge = document.getElementById('notification-badge-sidebar');
            if (sidebarBadge) {
                sidebarBadge.textContent = count > 9 ? '9+' : count;
                sidebarBadge.style.display = count > 0 ? 'inline-flex' : 'none';
            }
        }

        // Catat waktu halaman dimuat untuk membedakan pesan baru vs lama
        const pageLoadTime = Date.now();

        // Audio system with Mobile Autoplay bypass
        const notifSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
        notifSound.crossOrigin = "anonymous";
        
        // Unlock audio on first user interaction (standard mobile requirement)
        let audioUnlocked = false;
        function unlockAudio() {
            if (audioUnlocked) return;
            notifSound.play().then(() => {
                notifSound.pause();
                notifSound.currentTime = 0;
                audioUnlocked = true;
                console.log("Audio unlocked for mobile.");
            }).catch(e => console.warn("Audio unlock failed:", e));
        }
        document.addEventListener('click', unlockAudio, { once: true });
        document.addEventListener('touchstart', unlockAudio, { once: true });

        function showToastNotification(notif) {
            const urlParams = new URLSearchParams(window.location.search);
            const activePartnerId = urlParams.get('u');
            
            // Always play sound for new message if audio is unlocked
            if (audioUnlocked) {
                notifSound.play().catch(e => console.warn("Sound play failed:", e));
            } else {
                // Fallback attempt
                notifSound.play().catch(() => {});
            }

            // Skip TOAST ONLY if we're already in that specific chat room
            if (notif.senderId !== 'system' && notif.senderId === activePartnerId) {
                return;
            }

            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 z-[9999] bg-white border-l-4 border-primary p-4 rounded-xl shadow-2xl flex items-start gap-4 animate-bounce-in max-w-sm cursor-pointer hover:bg-gray-50 transition-all';
            
            const isSystem = notif.senderId === 'system';
            const title = isSystem ? notif.title : notif.fromName;
            const text = isSystem ? notif.message : notif.text;
            const icon = isSystem ? 'fa-bell' : 'fa-comment-dots';
            const targetUrl = isSystem ? '<?= url('/notifications') ?>' : `<?= url('/messages?u=') ?>${notif.senderId}`;

            toast.onclick = () => {
                window.location.href = targetUrl;
            };

            toast.innerHTML = `
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-primary flex-shrink-0">
                    <i class="fa-solid ${icon}"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-black text-xs text-gray-900 mb-1">${title}</h4>
                        <i class="fa-solid fa-up-right-from-square text-[8px] text-gray-300"></i>
                    </div>
                    <p class="text-[11px] text-gray-500 line-clamp-2">${text}</p>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => { 
                toast.classList.add('opacity-0'); 
                setTimeout(() => toast.remove(), 500); 
            }, 6000);
        }

        function getChatRoomId(u1, u2) {
            if (!u1 || !u2) return 'default';
            const s1 = String(u1);
            const s2 = String(u2);
            return s1 < s2 ? s1 + '_' + s2 : s2 + '_' + s1;
        }
    } else {
        console.warn("Firebase belum dikonfigurasi. Silakan masukkan API Key di realtime_script.php");
    }
</script>