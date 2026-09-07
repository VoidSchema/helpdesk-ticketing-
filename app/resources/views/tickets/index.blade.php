<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Tickets') }}
            </h2>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="-ml-0.5 me-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Quick Filters -->
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('tickets.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ !request('filter') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    All
                </a>
                <a href="{{ route('tickets.index', ['filter' => 'my']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('filter') === 'my' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    My Tickets
                </a>
                @if(!Auth::user()->isUser())
                <a href="{{ route('tickets.index', ['filter' => 'unassigned']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('filter') === 'unassigned' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    Unassigned
                </a>
                @endif
                <a href="{{ route('tickets.index', ['filter' => 'high']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('filter') === 'high' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    High Priority
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('tickets.index') }}" class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-slate-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search tickets..."
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 text-sm">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-medium text-slate-700 mb-1">Priority</label>
                        <select name="priority" id="priority" class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 text-sm">
                            <option value="">All Priority</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                        <select name="category_id" id="category_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-md text-sm font-medium hover:bg-slate-700">
                        Apply Filters
                    </button>
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-white text-slate-600 rounded-md text-sm font-medium border border-slate-300 hover:bg-slate-50">
                        Clear
                    </a>
                </div>
            </form>

            <!-- Tickets Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Category</th>
                                @if(!Auth::user()->isUser())
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Assigned To</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        #{{ $ticket->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="text-sm font-medium text-slate-900 hover:text-slate-600">
                                            {{ Str::limit($ticket->title, 50) }}
                                        </a>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            by {{ $ticket->creator->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $ticket->priority === 'low' ? 'bg-slate-100 text-slate-600' : '' }}
                                            {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $ticket->category->name ?? 'N/A' }}
                                    </td>
                                    @if(!Auth::user()->isUser())
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $ticket->assignee->name ?? 'Unassigned' }}
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->isUser() ? 6 : 7 }}" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-slate-900">No tickets found</h3>
                                        <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or create a new ticket.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700">
                                                New Ticket
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
