<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../dashboard-functions.js"></script>
</head>

<body class="bg-gray-900 text-gray-100 font-mono">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-gray-800 p-6 shadow-lg border-r border-gray-700">
            <h2 class="text-2xl font-bold text-blue-300 mb-8 tracking-wide">Admin Panel</h2>
            <nav class="space-y-3">
                <a href="../dashboard-admin.html"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 transition-all duration-300">
                    <span class="mr-3">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="list.php"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 transition-all duration-300">
                    <span class="mr-3">👥</span>
                    <span>Users</span>
                </a>
                <a href="courses.php"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 transition-all duration-300">
                    <span class="mr-3">📚</span>
                    <span>Courses</span>
                </a>
                <a href="Settings.php"
                    class="flex items-center py-3 px-4 rounded-lg bg-gray-700 text-white transition-all duration-300">
                    <span class="mr-3">⚙️</span>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-gray-800 p-6 flex justify-between items-center border-b border-gray-700">
                <div>
                    <h1 class="text-2xl font-bold">Settings</h1>
                    <p class="text-sm text-gray-400 mt-1">Configure platform preferences, security, and system behavior.</p>
                </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full border-2 border-blue-300 flex items-center justify-center">
                            <span class="font-bold">A</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Admin User</p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                    </div>
            </header>

            <main class="flex-1 p-8 overflow-auto">
                <div class="grid gap-6 lg:grid-cols-[1.5fr_0.9fr]">
                    <section class="bg-gray-800 p-8 rounded-lg shadow-lg border border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-3xl font-bold text-blue-300">Platform Settings</h2>
                                <p class="text-gray-400 mt-1">Update your platform settings and global configuration.</p>
                            </div>
                            <button onclick="saveChanges()"
                                class="bg-blue-500 hover:bg-blue-600 px-5 py-3 rounded-lg text-white font-semibold transition">Save Changes</button>
                        </div>

                        <div class="space-y-6">
                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2" for="platformName">Platform Name</label>
                                    <input id="platformName" type="text" value="Scriptic"
                                        class="w-full rounded-xl border border-gray-700 bg-gray-900 px-4 py-3 text-gray-100 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2" for="platformUrl">Platform URL</label>
                                    <input id="platformUrl" type="url" value="https://scriptic.com"
                                        class="w-full rounded-xl border border-gray-700 bg-gray-900 px-4 py-3 text-gray-100 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                                </div>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2" for="supportEmail">Support Email</label>
                                    <input id="supportEmail" type="email" value="support@scriptic.com"
                                        class="w-full rounded-xl border border-gray-700 bg-gray-900 px-4 py-3 text-gray-100 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2" for="timezoneSelect">Timezone</label>
                                    <select id="timezoneSelect"
                                        class="w-full rounded-xl border border-gray-700 bg-gray-900 px-4 py-3 text-gray-100 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                        <option value="UTC">UTC</option>
                                        <option value="America/New_York">America/New_York</option>
                                        <option value="Europe/London">Europe/London</option>
                                        <option value="Asia/Tokyo">Asia/Tokyo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="rounded-xl border border-gray-700 bg-gray-900 p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-300">Maintenance Mode</p>
                                            <p class="text-xs text-gray-500">Prevent new user signups and show maintenance notice.</p>
                                        </div>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input id="maintenanceToggle" type="checkbox" class="form-checkbox h-5 w-5 text-blue-500" />
                                        </label>
                                    </div>
                                </div>
                                <div class="rounded-xl border border-gray-700 bg-gray-900 p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-300">User Registration</p>
                                            <p class="text-xs text-gray-500">Allow new users to sign up on the platform.</p>
                                        </div>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input id="registrationToggle" type="checkbox" checked class="form-checkbox h-5 w-5 text-blue-500" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-gray-700 bg-gray-900 p-6">
                                <h3 class="text-lg font-semibold text-gray-200 mb-4">Security & Notifications</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2" for="sessionTimeout">Session Timeout</label>
                                        <input id="sessionTimeout" type="number" value="30" min="5"
                                            class="w-full rounded-xl border border-gray-700 bg-gray-900 px-4 py-3 text-gray-100 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                                        <p class="text-xs text-gray-500 mt-1">Minutes until idle users are signed out.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2" for="notificationEmail">Notification Email</label>
                                        <input id="notificationEmail" type="email" value="alerts@scriptic.com"
                                            class="w-full rounded-xl border border-gray-700 bg-gray-900 px-4 py-3 text-gray-100 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <aside class="space-y-6">
                        <div class="bg-gray-800 p-6 rounded-3xl shadow-lg border border-gray-700">
                            <h3 class="text-xl font-bold text-blue-300 mb-3">Quick Actions</h3>
                            <p class="text-gray-400 mb-4">Use quick actions to manage system state and logs.</p>
                            <div class="space-y-3">
                                <button onclick="alert('Cache cleared.')"
                                    class="w-full rounded-xl bg-blue-500 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-600 transition">Clear Cache</button>
                                <button onclick="alert('Logs exported.')"
                                    class="w-full rounded-xl border border-gray-700 bg-transparent px-4 py-3 text-sm font-semibold text-white hover:bg-gray-700 transition">Export Logs</button>
                                <button onclick="alert('Support request opened.')"
                                    class="w-full rounded-xl border border-gray-700 bg-transparent px-4 py-3 text-sm font-semibold text-white hover:bg-gray-700 transition">Contact Support</button>
                            </div>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-3xl shadow-lg border border-gray-700">
                            <h3 class="text-xl font-bold text-blue-300 mb-3">System Info</h3>
                            <div class="space-y-4 text-sm text-gray-400">
                                <div class="flex items-center justify-between">
                                    <span>App Version</span>
                                    <span class="font-semibold text-gray-100">1.0.1</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Environment</span>
                                    <span class="font-semibold text-gray-100">Development</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Active Users</span>
                                    <span class="font-semibold text-gray-100">1483</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
<script>
function saveChanges() {
    alert('Settings have been saved successfully!');
}
</script>

</body>

</html>