<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('tickets.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                    #{{ $ticket->id }} {{ $ticket->title }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Ticket Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->priority === 'low' ? 'bg-slate-100 text-slate-600' : '' }}
                                    {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $ticket->category->name }}
                                </span>
                            </div>

                            <div class="prose prose-slate max-w-none">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>

                            <!-- Attachments -->
                            @if($ticket->attachments->count() > 0)
                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <h4 class="text-sm font-medium text-slate-700 mb-2">Attachments</h4>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($ticket->attachments as $attachment)
                                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-md text-sm">
                                                @if($attachment->isImage())
                                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                @endif
                                                <a href="{{ route('attachments.download', $attachment) }}" class="text-slate-600 hover:text-slate-800 underline">
                                                    {{ $attachment->original_name }}
                                                </a>
                                                <span class="text-slate-400">({{ $attachment->human_size }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t border-slate-200 text-sm text-slate-500">
                                Created {{ $ticket->created_at->diffForHumans() }} by {{ $ticket->creator->name }}
                                @if($ticket->resolved_at)
                                    &middot; Resolved {{ $ticket->resolved_at->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Comments ({{ $ticket->comments->count() }})</h3>
                        </div>
                        <div class="p-6">
                            @forelse($ticket->comments->sortBy('created_at') as $comment)
                                <div class="mb-6 last:mb-0">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center">
                                                <span class="text-sm font-medium text-slate-600">{{ substr($comment->user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-slate-900">{{ $comment->user->name }}</span>
                                                @if($comment->is_internal)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Internal Note
                                                    </span>
                                                @endif
                                                <span class="text-sm text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-1 text-slate-700 prose prose-sm max-w-none">
                                                {!! nl2br(e($comment->body)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-slate-500 py-8">No comments yet. Be the first to add one.</p>
                            @endforelse

                            <!-- Add Comment Form -->
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <form method="POST" action="{{ route('comments.store', $ticket) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <label for="body" class="block text-sm font-medium text-slate-700">Add Comment</label>
                                        <textarea name="body" id="body" rows="4" required
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm"
                                            placeholder="Write your comment here..."></textarea>
                                    </div>
                                    <div class="mt-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Attachments</label>
                                        <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.log,.csv"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                                        <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF, PDF, DOC, TXT, LOG, CSV (max 10MB each, up to 5 files)</p>
                                    </div>
                                    @if(!Auth::user()->isUser())
                                    <div class="mt-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_internal" value="1" class="rounded border-slate-300 text-slate-600 shadow-sm focus:ring-slate-500">
                                            <span class="ml-2 text-sm text-slate-600">Internal note (only visible to agents)</span>
                                        </label>
                                    </div>
                                    @endif
                                    <div class="mt-3 flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Post Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Actions</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Status Change Buttons based on Role -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Change Status</label>
                                <div class="flex flex-wrap gap-2">

                                    {{-- AGENT BUTTONS --}}
                                    @if(Auth::user()->isAgent())
                                        @if($ticket->status === 'open')
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">
                                                    Start Progress
                                                </button>
                                            </form>
                                        @endif

                                        @if($ticket->status === 'in_progress')
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-100 text-green-800 rounded-md hover:bg-green-200">
                                                    Mark Resolved
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- ADMIN BUTTONS --}}
                                    @if(Auth::user()->isAdmin())
                                        @if($ticket->status === 'open')
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">
                                                    Start Progress
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-100 text-green-800 rounded-md hover:bg-green-200">
                                                    Mark Resolved
                                                </button>
                                            </form>
                                        @endif

                                        @if($ticket->status === 'in_progress')
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="open">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                                    Reopen
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-100 text-green-800 rounded-md hover:bg-green-200">
                                                    Mark Resolved
                                                </button>
                                            </form>
                                        @endif

                                        @if($ticket->status === 'resolved')
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200">
                                                    Reopen
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- USER BUTTONS (ticket creator) --}}
                                    @if(Auth::user()->isUser() && $ticket->created_by === Auth::id())
                                        @if($ticket->status === 'resolved')
                                            <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-orange-100 text-orange-800 rounded-md hover:bg-orange-200">
                                                    Still Broken - Reopen
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                </div>
                            </div>

                            <!-- Assign (Agent/Admin only) -->
                            @if(!Auth::user()->isUser())
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Assigned To</label>
                                <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                                    @csrf
                                    <select name="assigned_to" class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 text-sm" onchange="this.form.submit()">
                                        <option value="">Unassigned</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Details</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Category</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $ticket->category->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Created By</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $ticket->creator->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Assigned To</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $ticket->assignee->name ?? 'Unassigned' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Created</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $ticket->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $ticket->updated_at->format('M d, Y H:i') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
