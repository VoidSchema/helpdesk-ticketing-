<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Dashboard') }}
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
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-slate-100 rounded-full">
                            <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-sm font-medium text-slate-600">Total</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-sm font-medium text-blue-600">Open</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $stats['open'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-sm font-medium text-yellow-600">In Progress</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $stats['in_progress'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-sm font-medium text-green-600">Resolved</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $stats['resolved'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent/Admin Additional Stats -->
            @if($isAdminOrAgent)
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Unassigned</p>
                            <p class="text-xl font-semibold text-slate-900">{{ $stats['unassigned'] }}</p>
                        </div>
                        <a href="{{ route('tickets.index', ['filter' => 'unassigned']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            View all &rarr;
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">My Tickets</p>
                            <p class="text-xl font-semibold text-slate-900">{{ $stats['my_tickets'] }}</p>
                        </div>
                        <a href="{{ route('tickets.index', ['filter' => 'my']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            View all &rarr;
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Urgent</p>
                            <p class="text-xl font-semibold text-red-600">{{ $stats['urgent'] }}</p>
                        </div>
                        <a href="{{ route('tickets.index', ['priority' => 'urgent']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            View all &rarr;
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Tickets -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-slate-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-800">Recent Tickets</h3>
                                <a href="{{ route('tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all &rarr;</a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($recentTickets->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentTickets as $ticket)
                                        <a href="{{ route('tickets.show', $ticket) }}" class="block p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-xs font-medium text-slate-500">#{{ $ticket->id }}</span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                                            {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                            {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                                            {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                                            {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                                                            {{ $ticket->priority === 'low' ? 'bg-slate-100 text-slate-600' : '' }}">
                                                            {{ ucfirst($ticket->priority) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $ticket->title }}</p>
                                                    <div class="flex items-center gap-3 mt-1 text-xs text-slate-500">
                                                        <span>{{ $ticket->creator->name }}</span>
                                                        <span>&middot;</span>
                                                        <span>{{ $ticket->category->name ?? 'N/A' }}</span>
                                                        <span>&middot;</span>
                                                        <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                @if($isAdminOrAgent && $ticket->assignee)
                                                    <div class="ml-4 flex-shrink-0">
                                                        <span class="text-xs text-slate-500">→ {{ $ticket->assignee->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No tickets yet</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by creating a new ticket.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700">
                                            New Ticket
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Recent Activity</h3>
                        </div>
                        <div class="p-6">
                            @if($recentActivity->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentActivity as $comment)
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-slate-600">{{ substr($comment->user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-slate-900">
                                                    <span class="font-medium">{{ $comment->user->name }}</span>
                                                    commented on
                                                    <a href="{{ route('tickets.show', $comment->ticket) }}" class="font-medium text-blue-600 hover:text-blue-800">
                                                        #{{ $comment->ticket->id }}
                                                    </a>
                                                </p>
                                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ Str::limit($comment->body, 60) }}</p>
                                                <p class="text-xs text-slate-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 text-center py-4">No recent activity</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Quick Links</h3>
                        </div>
                        <div class="p-6 space-y-2">
                            <a href="{{ route('tickets.create') }}" class="block p-3 rounded-lg hover:bg-slate-50 border border-slate-200">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span class="text-sm font-medium text-slate-900">Submit New Ticket</span>
                                </div>
                            </a>
                            <a href="{{ route('tickets.index') }}" class="block p-3 rounded-lg hover:bg-slate-50 border border-slate-200">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                    </svg>
                                    <span class="text-sm font-medium text-slate-900">View All Tickets</span>
                                </div>
                            </a>
                            @if($isAdminOrAgent)
                            <a href="{{ route('tickets.index', ['filter' => 'unassigned']) }}" class="block p-3 rounded-lg hover:bg-slate-50 border border-slate-200">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    <span class="text-sm font-medium text-slate-900">Unassigned Tickets</span>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
