<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-4 h-[600px]">
            
            <div class="w-1/4 bg-white p-4 rounded-lg shadow overflow-y-auto">
                <h3 class="font-bold text-lg mb-4 text-gray-700">Daftar Chat</h3>
                <div class="space-y-2">
                    @foreach($rooms as $r)
                        <a href="{{ route('chat.show', $r->id) }}" 
                           class="block p-3 rounded-lg border {{ isset($room) && $room->id == $r->id ? 'bg-blue-500 text-white' : 'bg-gray-50 hover:bg-gray-100 text-gray-700' }}">
                            {{ $r->type == 'group' ? '[Grup] '.$r->name : 'Private Chat #'.$r->id }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="w-2/4 bg-white p-4 rounded-lg shadow flex flex-col justify-between">
                @if(isset($room))
                    <div>
                        <h2 class="font-bold text-xl pb-3 border-b text-gray-800">
                            {{ $room->type == 'group' ? $room->name : 'Chat Room' }}
                        </h2>
                    </div>

                    <div id="chat-box" class="flex-1 overflow-y-auto my-4 p-2 space-y-3 bg-gray-50 rounded">
                        @foreach($messages as $msg)
                            <div id="msg-{{ $msg->id }}" class="flex flex-col {{ $msg->user_id == auth()->id() ? 'items-end' : 'items-start' }}">
                                <span class="text-xs text-gray-500">{{ $msg->user->name }}</span>
                                <div class="p-2 rounded-lg max-w-xs {{ $msg->user_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form id="chat-form" class="flex gap-2 border-t pt-3">
                        @csrf
                        <input type="text" id="btn-input" class="flex-1 border border-gray-300 rounded-lg p-2 focus:outline-none" placeholder="Ketik pesan di sini..." required autocomplete="off">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Kirim</button>
                    </form>
                @else
                    <div class="flex items-center justify-center h-full text-gray-400">
                        Silakan pilih salah satu obrolan di menu kiri untuk memulai chat.
                    </div>
                @endif
            </div>

            <div class="w-1/4 bg-white p-4 rounded-lg shadow overflow-y-auto">
                <h3 class="font-bold text-lg mb-2 text-gray-700">Anggota Online</h3>
                <p class="text-xs text-gray-400 mb-4">Melacak pengguna aktif secara real-time</p>
                <ul id="presence-list" class="space-y-2 text-sm text-gray-600">
                    @if(!isset($room))
                        <li class="text-gray-400 italic">Pilih room untuk melihat status online</li>
                    @endif
                </ul>
            </div>

        </div>
    </div>

    @if(isset($room))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roomId = "{{ $room->id }}";
            const currentUserId = "{{ auth()->id() }}";
            
            const chatBox = document.getElementById('chat-box');
            const chatForm = document.getElementById('chat-form');
            const btnInput = document.getElementById('btn-input');
            const presenceList = document.getElementById('presence-list');

            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            // 1. INTEGRASI WEBSOCKET & PRESENCE TRACKING
            Echo.join(`chat.${roomId}`)
                .here((users) => {
                    users.forEach(user => tambahUserOnline(user));
                })
                .joining((user) => {
                    tambahUserOnline(user);
                })
                .leaving((user) => {
                    const userElement = document.getElementById(`user-${user.id}`);
                    if (userElement) userElement.remove();
                })
                .listen('MessageSent', (e) => {
                    buatBalonChat(e.message);
                });

            // 2. FITUR KIRIM CHAT VIA AJAX (AXIOS)
            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const pesanTeks = btnInput.value;
                if (!pesanTeks.trim()) return;

                btnInput.value = '';

                axios.post(`/chat/${roomId}`, { message: pesanTeks })
                    .then(response => {
                        buatBalonChat(response.data);
                    });
            });

            function buatBalonChat(data) {
                // PENCEGAH DUPLIKAT: Jika elemen HTML dengan ID pesan ini sudah ada di layar, stop! Jangan digambar lagi.
                if (data.id && document.getElementById(`msg-${data.id}`)) {
                    return;
                }

                const isMe = data.user_id == currentUserId;
                const wrapper = document.createElement('div');
                
                // Berikan ID unik pada elemen balon chat baru agar bisa dicek pada baris kode di atas jika WebSocket mengirimkannya lagi
                if (data.id) {
                    wrapper.id = `msg-${data.id}`;
                }
                
                wrapper.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'}`;
                
                const namaPengirim = data.user ? data.user.name : "{{ auth()->user()->name }}";

                wrapper.innerHTML = `
                    <span class="text-xs text-gray-500">${namaPengirim}</span>
                    <div class="p-2 rounded-lg max-w-xs ${isMe ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'}">
                        ${data.message}
                    </div>
                `;
                chatBox.appendChild(wrapper);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            function tambahUserOnline(user) {
                if (!document.getElementById(`user-${user.id}`)) {
                    const item = document.createElement('li');
                    item.id = `user-${user.id}`;
                    item.className = "flex items-center gap-2 bg-green-50 p-2 rounded";
                    item.innerHTML = `<span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span> <b>${user.name}</b>`;
                    presenceList.appendChild(item);
                }
            }
        });
    </script>
    @endif
</x-app-layout>