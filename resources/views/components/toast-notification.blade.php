<div
  x-data="{
    notifications: [],
    add(message, type = 'success') {
      const id = Date.now() + Math.random();
      this.notifications.push({ id, message, type });
      setTimeout(() => this.remove(id), 4000);
    },
    remove(id) {
      this.notifications = this.notifications.filter(n => n.id !== id);
    }
  }"
  x-on:show-toast.window="add($event.detail.message, $event.detail.type)"
  class="fixed top-4 left-1/2 -translate-x-1/2 transform z-50 flex flex-col gap-2 w-full max-w-md px-4 pointer-events-none"
>
  <template x-for="note in notifications" :key="note.id">
    <div
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 -translate-y-2"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-2"
      class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg border bg-white"
      :class="{
        'border-l-4 border-l-green-500 text-gray-800': note.type === 'success',
        'border-l-4 border-l-red-500 text-gray-800': note.type === 'error',
        'border-l-4 border-l-blue-500 text-gray-800': note.type === 'info'
      }"
    >
      <span x-show="note.type === 'success'">✅</span>
      <span x-show="note.type === 'error'">❌</span>
      <span x-show="note.type === 'info'">ℹ️</span>

      <p class="text-sm font-medium" x-text="note.message"></p>

      <button type="button" x-on:click="remove(note.id)" class="ml-auto text-gray-400 hover:text-gray-600">
        ✖
      </button>
    </div>
  </template>
</div>






