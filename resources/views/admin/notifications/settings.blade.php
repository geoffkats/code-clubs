<x-layouts.app>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Notification Settings</h1>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Email Notifications</h2>
                
                <form method="POST" action="{{ route('admin.notifications.update') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="email_notifications" id="email_notifications" checked
                                   class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                            <label for="email_notifications" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                Enable Email Notifications
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="push_notifications" id="push_notifications" checked
                                   class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                            <label for="push_notifications" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                Enable Push Notifications
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="report_notifications" id="report_notifications" checked
                                   class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                            <label for="report_notifications" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                Report Notifications
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="assessment_notifications" id="assessment_notifications" checked
                                   class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                            <label for="assessment_notifications" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                Assessment Notifications
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
