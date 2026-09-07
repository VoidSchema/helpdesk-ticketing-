<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('My Tickets') }}
            </h2>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="-ml-0.5 me-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Submit New Ticket
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

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center">
                    <p class="text-2xl font-semibold text-slate-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm text-slate-600">Total Tickets</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center">
                    <p class="text-2xl font-semibold text-yellow-600">{{ $stats['in_progress'] ?? 0 }}</p>
                    <p class="text-sm text-slate-600">In Progress</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center">
                    <p class="text-2xl font-semibold text-green-600">{{ $stats['resolved'] ?? 0 }}</p>
                    <p class="text-sm text-slate-600">Resolved</p>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6 flex gap-2">
                <a href="{{ route('portal.my-tickets') }}" class="px-4 py-2 text-sm font-medium rounded-md {{ !request('filter') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    All My Tickets
                </a>
                <a href="{{ route('portal.my-tickets', ['filter' => 'open']) }}" class="px-4 py-2 text-sm font-medium rounded-md {{ request('filter') === 'open' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    Open
                </a>
                <a href="{{ route('portal.my-tickets', ['filter' => 'in_progress']) }}" class="px-4 py-2 text-sm font-medium rounded-md {{ request('filter') === 'in_progress' ? 'bg-yellow-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}>
                    In Progress
                </a>
                <a href="{{ route('portal.my-tickets', ['filter' => 'resolved']) }}" class="px-4 py-2 text-sm font-medium rounded-md {{ request('filter') === 'resolved' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    Resolved
                </a>
            </div>

            <!-- Tickets List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($tickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket) }}" class="block p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-sm font-medium text-slate-500">#{{ $ticket->id }}</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                                    {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                                    {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ $ticket->priority === 'low' ? 'bg-slate-100 text-slate-600' : '' }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </div>
                                            <h4 class="text-sm font-semibold text-slate-900 mb-1">{{ $ticket->title }}</h4>
                                            <p class="text-sm text-slate-600 line-clamp-2">{{ Str::limit($ticket->description, 120) }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-slate-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                                    </svg>
                                                    {{ $ticket->category->name }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $ticket->created_at->diffForHumans() }}
                                                </span>
                                                @if($ticket->assignee)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                        </svg>
                                                        {{ $ticket->assignee->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if($tickets->hasPages())
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">No tickets found</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                @if(request('filter'))
                                    No tickets with this status.
                                @else
                                    You haven't submitted any tickets yet.
                                @endif
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700">
                                    <svg class="-ml-0.5 me-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Submit New Ticket
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
