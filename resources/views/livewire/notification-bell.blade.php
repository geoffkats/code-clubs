<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell Button -->
    <button @click="open = !open" 
            class="relative p-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 12a8 8 0 018-8V3a1 1 0 011.414-1.414l2 2A1 1 0 0115 5v2a8 8 0 11-8 8h-3z"></path>
        </svg>
        
        <!-- Unread Count Badge -->
        @if($unreadCount > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
         class="absolute right-0 mt-2 w-80 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md rounded-xl shadow-2xl border border-slate-200/50 dark:border-slate-700/50 z-[9999]"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Notifications</h3>
                @if($unreadCount > 0)
                <button wire:click="markAllAsRead" 
                        class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    Mark all as read
                </button>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                <div class="p-4 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <div class="flex items-start space-x-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-600 flex items-center justify-center">
                                <svg class="w-4 h-4 {{ $this->getNotificationColor($notification->data['type']) }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getNotificationIcon($notification->data['type']) }}"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                    @if($notification->data['type'] === 'report_awaiting_approval')
                                        Report Awaiting Approval
                                    @elseif($notification->data['type'] === 'report_approved')
                                        Report Approved
                                    @elseif($notification->data['type'] === 'report_revision_requested')
                                        Report Revision Requested
                                    @elseif($notification->data['type'] === 'report_rejected')
                                        Report Rejected
                                    @else
                                        Notification
                                    @endif
                                </p>
                                
                                @if(!$notification->read_at)
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                @endif
                            </div>
                            
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                @if(isset($notification->data['report_name']))
                                    <strong>{{ $notification->data['report_name'] }}</strong><br>
                                    Student: {{ $notification->data['student_name'] ?? 'N/A' }}<br>
                                    Club: {{ $notification->data['club_name'] ?? 'N/A' }}
                                @else
                                    {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                @endif
                            </p>
                            
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col space-y-1">
                            @if(!$notification->read_at)
                            <button wire:click="markAsRead('{{ $notification->id }}')" 
                                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                Mark read
                            </button>
                            @endif
                            <button wire:click="deleteNotification('{{ $notification->id }}')" 
                                    class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 12a8 8 0 018-8V3a1 1 0 011.414-1.414l2 2A1 1 0 0115 5v2a8 8 0 11-8 8h-3z"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400">No notifications</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ $notifications->count() > 0 ? $this->getNotificationUrl($notifications->first()) : '#' }}" 
               class="block text-center text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                View all notifications
            </a>
        </div>
        @endif
    </div>
</div>