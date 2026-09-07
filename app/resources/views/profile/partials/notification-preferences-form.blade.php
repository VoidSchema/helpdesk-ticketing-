<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Notification Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Choose which notifications you want to receive.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.notifications') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-900">New Ticket Created</p>
                    <p class="text-xs text-gray-500">Get notified when a new ticket is created</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notification_preferences[new_ticket]" value="1" class="sr-only peer" {{ ($user->notification_preferences['new_ticket'] ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-900">Ticket Assigned</p>
                    <p class="text-xs text-gray-500">Get notified when a ticket is assigned to you</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notification_preferences[ticket_assigned]" value="1" class="sr-only peer" {{ ($user->notification_preferences['ticket_assigned'] ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-900">Status Changed</p>
                    <p class="text-xs text-gray-500">Get notified when ticket status changes</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notification_preferences[status_changed]" value="1" class="sr-only peer" {{ ($user->notification_preferences['status_changed'] ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-900">New Comment</p>
                    <p class="text-xs text-gray-500">Get notified when someone comments on your ticket</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notification_preferences[new_comment]" value="1" class="sr-only peer" {{ ($user->notification_preferences['new_comment'] ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-600"></div>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Preferences') }}</x-primary-button>
        </div>
    </form>
</section>
