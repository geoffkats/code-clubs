@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-white via-blue-100 to-indigo-100 bg-clip-text text-transparent">Student Account Management</h1>
                <p class="mt-2 text-slate-300">Create and manage student accounts with login credentials</p>
            </div>
            <div class="flex space-x-3">
                @php
                    $studentsWithoutIds = $students->where('student_id_number', null)->count() + 
                                         $students->where('student_id_number', '')->count();
                @endphp
                
                @if($studentsWithoutIds > 0)
                    <form method="POST" action="{{ route('admin.students.bulk-update-ids') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-all transform hover:scale-105 shadow-lg"
                                onclick="return confirm('Generate Student IDs for {{ $studentsWithoutIds }} students without IDs?')">
                            <i class="fas fa-magic mr-2"></i>Generate Missing IDs ({{ $studentsWithoutIds }})
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.students.create') }}" 
                   class="bg-gradient-to-r from-yellow-500 to-red-600 text-white px-6 py-3 rounded-lg hover:from-yellow-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Create Student Account
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500/20 rounded-lg">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-400">Total Students</p>
                        <p class="text-2xl font-bold text-white">{{ $students->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500/20 rounded-lg">
                        <i class="fas fa-key text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-400">Accounts Ready</p>
                        <p class="text-2xl font-bold text-white">{{ $students->where('password', '!=', null)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500/20 rounded-lg">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-400">Pending Setup</p>
                        <p class="text-2xl font-bold text-white">{{ $students->where('password', null)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-500/20 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-400">Missing IDs</p>
                        <p class="text-2xl font-bold text-white">
                            {{ $students->where('student_id_number', null)->count() + $students->where('student_id_number', '')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500/20 rounded-lg">
                        <i class="fas fa-building text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-400">Schools</p>
                        <p class="text-2xl font-bold text-white">{{ $schools->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search, Filters and Bulk Actions -->
        <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 p-6 mb-6">
            <form method="GET" action="{{ route('admin.students.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-slate-300 mb-1">Search</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Name, email, or ID..."
                               class="w-full px-3 py-2 border border-slate-600 rounded-md bg-slate-700 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- School Filter -->
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-slate-300 mb-1">School</label>
                        <select id="school_id" 
                                name="school_id" 
                                class="w-full px-3 py-2 border border-slate-600 rounded-md bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->school_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-300 mb-1">Status</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-3 py-2 border border-slate-600 rounded-md bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="with_password" {{ request('status') === 'with_password' ? 'selected' : '' }}>Account Ready</option>
                            <option value="no_password" {{ request('status') === 'no_password' ? 'selected' : '' }}>Needs Setup</option>
                        </select>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-1"></i>Search
                        </button>
                        <a href="{{ route('admin.students.index') }}" 
                           class="bg-slate-600 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition-colors">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Bulk Enroll -->
            <div class="mt-6 border-t border-slate-700 pt-6">
                <h3 class="text-lg font-semibold text-white mb-4">Bulk Enroll Students to a Club</h3>
                <form method="POST" action="{{ route('admin.students.bulk-enroll') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label for="bulk_school_id" class="block text-sm font-medium text-slate-300 mb-1">School</label>
                        <select id="bulk_school_id" name="school_id" class="w-full px-3 py-2 border border-slate-600 rounded-md bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="bulk_club_id" class="block text-sm font-medium text-slate-300 mb-1">Club</label>
                        <select id="bulk_club_id" name="club_id" class="w-full px-3 py-2 border border-slate-600 rounded-md bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Club</option>
                            @foreach(\App\Models\Club::with('school')->orderBy('club_name')->get() as $club)
                                <option value="{{ $club->id }}">{{ $club->club_name }} @if($club->school) ({{ $club->school->school_name }}) @endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" onclick="return confirm('Enroll all students from the selected school into this club?')" class="bg-emerald-600 text-white px-6 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-users mr-2"></i>Enroll All Students
                        </button>
                    </div>
                </form>
                @if(session('success'))
                    <p class="text-emerald-400 text-sm mt-3">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p class="text-red-400 text-sm mt-3">{{ session('error') }}</p>
                @endif
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-700/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Students ({{ $students->total() }} total)</h2>
                <div class="text-sm text-slate-400">
                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }}
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700">
                    <thead class="bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'student_first_name', 'direction' => request('sort') === 'student_first_name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-white">
                                    <span>Student</span>
                                    <i class="fas fa-sort text-xs"></i>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => request('sort') === 'email' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-white">
                                    <span>Email</span>
                                    <i class="fas fa-sort text-xs"></i>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'student_grade_level', 'direction' => request('sort') === 'student_grade_level' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-white">
                                    <span>Grade</span>
                                    <i class="fas fa-sort text-xs"></i>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">School</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-800 divide-y divide-slate-700">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ $student->profile_image_url }}" 
                                                 alt="{{ $student->full_name }}"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&background=random'">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">
                                                {{ $student->full_name }}
                                            </div>
                                            <div class="text-sm text-slate-400">
                                                ID: {{ $student->student_id_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $student->email }}</div>
                                    <div class="text-xs text-slate-400">
                                        @if($student->email_verified_at)
                                            <span class="inline-flex items-center text-green-400">
                                                <i class="fas fa-check-circle mr-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-yellow-400">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    Grade {{ $student->student_grade_level }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $student->school->school_name ?? 'No School' }}</div>
                                    <div class="text-xs text-slate-400">{{ $student->clubs->count() }} clubs</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->password)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400">
                                            <i class="fas fa-key mr-1"></i>Ready
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Setup Needed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <!-- View Details Button -->
                                        <a href="{{ route('admin.students.show', $student) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-400 bg-blue-500/20 rounded-md hover:bg-blue-500/30 transition-colors"
                                           title="View Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.students.edit', $student) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 text-yellow-400 bg-yellow-500/20 rounded-md hover:bg-yellow-500/30 transition-colors"
                                           title="Edit Student">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <!-- Reset Password Button -->
                                        <a href="{{ route('admin.students.reset-password', $student) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 text-purple-400 bg-purple-500/20 rounded-md hover:bg-purple-500/30 transition-colors"
                                           title="Reset Password">
                                            <i class="fas fa-key text-sm"></i>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this student account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-400 bg-red-500/20 rounded-md hover:bg-red-500/30 transition-colors"
                                                    title="Delete Student">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-slate-400">
                                        <i class="fas fa-search text-4xl mb-4"></i>
                                        <p class="text-lg">No students found</p>
                                        <p class="text-sm">
                                            @if(request()->hasAny(['search', 'school_id', 'status']))
                                                Try adjusting your search criteria.
                                            @else
                                                Create your first student account to get started.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($students->hasPages())
                <div class="px-6 py-4 border-t border-slate-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-400">
                            Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} results
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
