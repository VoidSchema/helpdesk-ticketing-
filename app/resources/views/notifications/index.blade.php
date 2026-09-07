<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if($notifications->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($notifications->count() === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="p-6 {{ $notification->isRead() ? 'bg-white' : 'bg-slate-50' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            @if(!$notification->isRead())
                                                <span class="inline-block w-2 h-2 bg-slate-500 rounded-full"></span>
                                            @endif
                                            <h3 class="text-sm font-medium text-gray-900">{{ $notification->title }}</h3>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                                        <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                            @if($notification->ticket_id)
                                                <a href="{{ route('tickets.show', $notification->ticket_id) }}" class="text-slate-600 hover:text-slate-800">
                                                    View Ticket →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @if(!$notification->isRead())
                                        <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700">
                                                Mark read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
