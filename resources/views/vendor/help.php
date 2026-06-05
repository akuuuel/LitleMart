<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans"
    x-data="{ showSupport: false, helpTab: 'categories', supportSent: false }">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-gray-900 mb-3">How can we help you?</h2>
                    <p class="text-gray-500">Search our knowledge base or contact our support team.</p>
                </div>

                <div x-show="helpTab === 'categories'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div @click="helpTab = 'docs'"
                            class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-[#056526]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-1">Documentation</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">Detailed guides on how to manage your store
                                and products.</p>
                        </div>
                        <div @click="window.open('https://wa.me/6285343869700?text=Halo%20Admin%20LitleMart,%20saya%20vendor%20ingin%20bertanya%20mengenai...', '_blank')"
                            class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-1">WhatsApp Support</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">Fast response for urgent issues via our
                                WhatsApp Business line.</p>
                        </div>
                        <div @click="helpTab = 'faq'"
                            class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-1">FAQ</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">Quick answers to the most commonly asked
                                questions.</p>
                        </div>
                    </div>
                </div>

                <!-- Documentation Content -->
                <div x-show="helpTab === 'docs'" x-transition
                    class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm mb-12">
                    <button @click="helpTab = 'categories'"
                        class="text-primary text-sm font-bold flex items-center gap-2 mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Categories
                    </button>
                    <h3 class="text-2xl font-black text-gray-900 mb-6">Vendor Documentation</h3>
                    <div class="space-y-6">
                        <div class="p-6 bg-gray-50 rounded-2xl">
                            <h4 class="font-bold text-gray-900 mb-2">Getting Started</h4>
                            <p class="text-sm text-gray-600">Learn how to set up your store, verify your account, and
                                publish your first product.</p>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl">
                            <h4 class="font-bold text-gray-900 mb-2">Order Management</h4>
                            <p class="text-sm text-gray-600">Understand the order lifecycle from pending payment to
                                delivery completion.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Content -->
                <div x-show="helpTab === 'faq'" x-transition
                    class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm mb-12">
                    <button @click="helpTab = 'categories'"
                        class="text-primary text-sm font-bold flex items-center gap-2 mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Categories
                    </button>
                    <h3 class="text-2xl font-black text-gray-900 mb-6">Frequently Asked Questions</h3>
                    <div class="space-y-4">
                        <div x-data="{ open: false }" class="border-b border-gray-100 pb-4">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full text-left font-bold text-gray-900 py-2">
                                <span>How do I get paid?</span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" class="text-sm text-gray-500 mt-2">Payments are processed weekly every
                                Monday to your primary bank account.</div>
                        </div>
                        <div x-data="{ open: false }" class="border-b border-gray-100 pb-4">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full text-left font-bold text-gray-900 py-2">
                                <span>What are the platform fees?</span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" class="text-sm text-gray-500 mt-2">LitleMart takes a 5% commission on
                                each successful transaction.</div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-[#056526] rounded-3xl p-10 text-white flex flex-col md:flex-row items-center justify-between shadow-xl">
                    <div class="mb-6 md:mb-0">
                        <h3 class="text-2xl font-black mb-2">Need more specified help?</h3>
                        <p class="text-green-100 text-sm max-w-md">Our support team is available 24/7 to help you with
                            any technical issues.</p>
                    </div>
                    <button @click="showSupport = true"
                        class="px-8 py-3 bg-white text-[#056526] font-bold rounded-xl shadow-lg hover:bg-gray-50 transition-all">
                        Contact Support
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Support Modal -->
    <div x-show="showSupport" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl" @click.away="showSupport = false">
            <div x-show="!supportSent">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-black text-gray-900">Contact Vendor Support</h3>
                    <button @click="showSupport = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="#" @submit.prevent="supportSent = true" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Subject</label>
                        <input type="text" placeholder="e.g. Payment inquiry" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Message</label>
                        <textarea rows="4" placeholder="Describe your issue..." required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 resize-none"></textarea>
                    </div>
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3 bg-[#056526] text-white font-bold rounded-xl hover:bg-green-800 transition-all shadow-lg">
                            Send Request
                        </button>
                    </div>
                </form>
            </div>
            <div x-show="supportSent" class="p-10 text-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Message Sent!</h3>
                <p class="text-sm text-gray-500 mb-8">Your ticket has been submitted to our support team. We'll get back
                    to you via email shortly.</p>
                <button @click="showSupport = false; supportSent = false"
                    class="w-full py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                    Dismiss
                </button>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>