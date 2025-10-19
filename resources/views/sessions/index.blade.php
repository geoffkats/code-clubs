<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="calendarData()">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-purple-900 to-pink-900 dark:from-white dark:via-purple-100 dark:to-pink-100 bg-clip-text text-transparent">
                                Sessions
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Manage club sessions and schedules</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="showCreate = true" 
                                class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Session
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Sessions</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $sessions->total() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Upcoming</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $sessions->where('session_date', '>', now())->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Completed</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $sessions->where('session_date', '<', now())->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Session Calendar</h2>
                    <div class="flex items-center space-x-2">
                        <button @click="previousMonth()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-sm font-medium" x-text="currentMonth"></span>
                        <button @click="nextMonth()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-2">
                    <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                        <div class="text-center text-sm font-medium text-slate-500 dark:text-slate-400 py-2" x-text="day"></div>
                    </template>
                    
                    <template x-for="day in calendarDays" :key="day.date">
                        <div class="relative">
                            <div class="text-center py-2 text-sm rounded-lg transition-colors cursor-pointer"
                                 :class="getDayClasses(day)"
                                 @click="selectDate(day)">
                                <span x-text="day.day"></span>
                                <template x-if="day.hasSession">
                                    <div class="absolute -top-1 -right-1 w-2 h-2 bg-purple-500 rounded-full"></div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Session Details Modal -->
                <div x-show="showSessionModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-white/20 backdrop-blur-md flex items-center justify-center z-50"
                     @click="closeSessionModal()">
                    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-lg rounded-2xl p-6 max-w-md w-full mx-4 border border-white/20 dark:border-slate-700/20 shadow-2xl"
                         @click.stop>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4" x-text="selectedDate"></h3>
                        <template x-if="selectedSessions.length === 0">
                            <p class="text-slate-600 dark:text-slate-400">No sessions scheduled for this date.</p>
                        </template>
                        <template x-if="selectedSessions.length > 0">
                            <div class="space-y-3">
                                <template x-for="session in selectedSessions" :key="session.id">
                                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-3">
                                        <h4 class="font-semibold text-slate-900 dark:text-white" x-text="session.club_name"></h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">Week <span x-text="session.week_number"></span></p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400" x-text="session.attendance_count + ' students attended'"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <div class="mt-6 flex justify-end">
                            <button @click="closeSessionModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                                Close
                            </button>
                        </div>
                        </div>
                </div>
            </div>

            <!-- Sessions List -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Recent Sessions</h2>
                </div>
                
                @if($sessions->count() > 0)
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($sessions as $session)
                            <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                                {{ $session->club->club_name ?? 'Unknown Club' }}
                                            </h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }} • Week {{ $session->session_week_number }}
                                            </p>
                                            <p class="text-sm text-slate-500 dark:text-slate-500">
                                                {{ $session->attendance_records_count ?? 0 }} students attended
                                            </p>
                                        </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                        <a href="{{ route('attendance.grid', ['club_id' => $session->club_id, 'week' => $session->session_week_number]) }}" 
                                           class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                </svg>
                                            </a>
                                        <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $sessions->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No sessions found</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Get started by creating your first session.</p>
                        <button @click="showCreate = true" 
                                class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Session
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Session Modal -->
        <div x-show="showCreate" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click="showCreate = false">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-md w-full mx-4"
                 @click.stop>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Create New Session</h3>
                <form method="POST" action="{{ route('sessions.store') }}">
                    @csrf
                    <div class="space-y-4">
                    <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club</label>
                            <select name="club_id" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Select a club</option>
                            @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->club_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Session Date</label>
                            <input type="date" name="session_date" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Week Number</label>
                            <input type="number" name="session_week_number" min="1" max="52" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showCreate = false" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg hover:from-purple-600 hover:to-pink-700 transition-colors">
                            Create Session
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alpine.js Calendar Functions -->
        <script>
            function calendarData() {
                return {
                    showCreate: false,
                    selectedClub: '',
                    clubs: @js($clubs ?? []),
                    currentDate: new Date(),
                    currentMonth: '',
                    calendarDays: [],
                    showSessionModal: false,
                    selectedSessions: [],
                    selectedDate: '',
                    sessions: @js($sessions->items() ?? []),
                    
                    init() {
                        this.updateCalendar();
                    },
                    
                    updateCalendar() {
                        const year = this.currentDate.getFullYear();
                        const month = this.currentDate.getMonth();
                        
                        this.currentMonth = new Date(year, month).toLocaleDateString('en-US', { 
                            month: 'long', 
                            year: 'numeric' 
                        });
                        
                        const firstDay = new Date(year, month, 1);
                        const lastDay = new Date(year, month + 1, 0);
                        const startDate = new Date(firstDay);
                        startDate.setDate(startDate.getDate() - firstDay.getDay());
                        
                        this.calendarDays = [];
                        const currentDate = new Date(startDate);
                        
                        for (let i = 0; i < 42; i++) {
                            const dateStr = currentDate.toISOString().split('T')[0];
                            const daySessions = this.sessions.filter(session => {
                                return new Date(session.session_date).toISOString().split('T')[0] === dateStr;
                            });
                            
                            this.calendarDays.push({
                                date: dateStr,
                                day: currentDate.getDate(),
                                hasSession: daySessions.length > 0,
                                sessions: daySessions,
                                isCurrentMonth: currentDate.getMonth() === month,
                                isToday: dateStr === new Date().toISOString().split('T')[0]
                            });
                            
                            currentDate.setDate(currentDate.getDate() + 1);
                        }
                    },
                    
                    previousMonth() {
                        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                        this.updateCalendar();
                    },
                    
                    nextMonth() {
                        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                        this.updateCalendar();
                    },
                    
                    getDayClasses(day) {
                        let classes = 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700';
                        
                        if (!day.isCurrentMonth) {
                            classes = 'text-slate-300 dark:text-slate-600';
                        } else if (day.isToday) {
                            classes = 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 font-semibold';
                        } else if (day.hasSession) {
                            classes = 'text-slate-900 dark:text-white hover:bg-purple-50 dark:hover:bg-purple-900/20 font-medium';
                        }
                        
                        return classes;
                    },
                    
                    selectDate(day) {
                        if (day.hasSession) {
                            this.selectedDate = new Date(day.date).toLocaleDateString('en-US', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            });
                            this.selectedSessions = day.sessions;
                            this.showSessionModal = true;
                        }
                    },
                    
                    closeSessionModal() {
                        this.showSessionModal = false;
                        this.selectedSessions = [];
                    }
                }
            }
        </script>
    </div>
</x-layouts.app>
