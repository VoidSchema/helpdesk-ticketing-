<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Submit New Ticket') }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="text-sm text-slate-600 hover:text-slate-900">
                &larr; Back to Tickets
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm"
                                    placeholder="Brief description of your issue">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-slate-700">Category</label>
                                <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Priority</label>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input type="radio" name="priority" id="priority-low" value="low" {{ old('priority') === 'low' ? 'checked' : '' }}
                                            class="focus:ring-slate-500 h-4 w-4 text-slate-600 border-slate-300">
                                        <label for="priority-low" class="ml-2 text-sm text-slate-700">
                                            <span class="font-medium">Low</span> - Not urgent, can wait
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="priority" id="priority-medium" value="medium" {{ old('priority', 'medium') === 'medium' ? 'checked' : '' }}
                                            class="focus:ring-slate-500 h-4 w-4 text-slate-600 border-slate-300">
                                        <label for="priority-medium" class="ml-2 text-sm text-slate-700">
                                            <span class="font-medium">Medium</span> - Normal priority
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="priority" id="priority-high" value="high" {{ old('priority') === 'high' ? 'checked' : '' }}
                                            class="focus:ring-slate-500 h-4 w-4 text-slate-600 border-slate-300">
                                        <label for="priority-high" class="ml-2 text-sm text-slate-700">
                                            <span class="font-medium">High</span> - Affecting work, needs prompt attention
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="priority" id="priority-urgent" value="urgent" {{ old('priority') === 'urgent' ? 'checked' : '' }}
                                            class="focus:ring-slate-500 h-4 w-4 text-slate-600 border-slate-300">
                                        <label for="priority-urgent" class="ml-2 text-sm text-slate-700">
                                            <span class="font-medium">Urgent</span> - Critical issue, blocking work completely
                                        </label>
                                    </div>
                                </div>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                                <textarea name="description" id="description" rows="6" required
                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm"
                                    placeholder="Please provide as much detail as possible about your issue...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Include error messages, steps to reproduce, and what you've already tried.</p>
                            </div>

                            <!-- File Attachments -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Attachments</label>
                                <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.log,.csv"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                                <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF, PDF, DOC, TXT, LOG, CSV (max 10MB each, up to 5 files)</p>
                                @error('attachments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('attachments.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
