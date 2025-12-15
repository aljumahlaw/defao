<div x-data="{ sidebarOpen: false }">
    {{-- Mobile Hamburger Button --}}
    <button @click="sidebarOpen = !sidebarOpen" 
            class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 shadow-lg border border-gray-200 dark:border-gray-700">
        <x-heroicon-o-bars-3 class="w-6 h-6" />
    </button>

    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="lg:hidden fixed inset-0 bg-gray-900/50 z-40"
         style="display: none;">
    </div>

    {{-- Sidebar --}}
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
           class="lg:translate-x-0 fixed right-0 top-0 h-screen w-64 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 z-40 transform transition-transform duration-300 ease-in-out">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-primary">نظام الوثائق</h1>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
        </div>
        <nav class="space-y-1 px-3">
            @php
            $menuItems = [
                ['route' => 'dashboard', 'icon' => 'home', 'label' => 'لوحة التحكم'],
                ['route' => 'tasks.index', 'icon' => 'clipboard-document-list', 'label' => 'المهام'],
                ['route' => 'documents.index', 'icon' => 'document-text', 'label' => 'الوثائق'],
                ['route' => '#', 'icon' => 'arrow-path', 'label' => 'سير العمل'],
                ['route' => '#', 'icon' => 'archive-box', 'label' => 'الأرشيف'],
                ['route' => 'profile.settings', 'icon' => 'user-circle', 'label' => 'الملف الشخصي'],
            ];
            @endphp

            @foreach($menuItems as $item)
                @php
                $iconComponent = match($item['icon']) {
                    'home' => 'heroicon-o-home',
                    'clipboard-document-list' => 'heroicon-o-clipboard-document-list',
                    'document-text' => 'heroicon-o-document-text',
                    'arrow-path' => 'heroicon-o-arrow-path',
                    'archive-box' => 'heroicon-o-archive-box',
                    'cog-6-tooth' => 'heroicon-o-cog-6-tooth',
                    'user-circle' => 'heroicon-o-user-circle',
                    default => 'heroicon-o-home'
                };
                @endphp
                <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors">
                    @if($iconComponent === 'heroicon-o-home')
                        <x-heroicon-o-home class="w-5 h-5" />
                    @elseif($iconComponent === 'heroicon-o-clipboard-document-list')
                        <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                    @elseif($iconComponent === 'heroicon-o-document-text')
                        <x-heroicon-o-document-text class="w-5 h-5" />
                    @elseif($iconComponent === 'heroicon-o-arrow-path')
                        <x-heroicon-o-arrow-path class="w-5 h-5" />
                    @elseif($iconComponent === 'heroicon-o-archive-box')
                        <x-heroicon-o-archive-box class="w-5 h-5" />
                    @elseif($iconComponent === 'heroicon-o-cog-6-tooth')
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                    @elseif($iconComponent === 'heroicon-o-user-circle')
                        <x-heroicon-o-user-circle class="w-5 h-5" />
                    @endif
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </aside>
</div>
