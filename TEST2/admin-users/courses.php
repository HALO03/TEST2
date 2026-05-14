<?php
require '../config.php';

$sql = "SELECT id, name, description, url FROM courses";
// Execute the SQL query
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 transition-all duration-300">
                    <span class="mr-3">⚙️</span>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-gray-800 p-6 flex justify-between items-center border-b border-gray-700">
                <h1 class="text-2xl font-bold" id="page-title">Dashboard</h1>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-500 rounded-full border-2 border-blue-300 flex items-center justify-center">
                            <span class="font-bold">A</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Admin User</p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                    </div>

                </div>
            </header>


            <main class="flex-1 p-8 overflow-auto">
                <section id="users-section" class="bg-gray-800 p-8 rounded-lg shadow-lg border border-gray-700">
                    <div class="flex flex-col gap-4 mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-3xl font-bold text-blue-300">Courses Management</h2>
                            
                        </div>
                        <?php if (isset($_GET['created']) && $_GET['created'] == '1'): ?>
                            <div class="rounded-lg bg-emerald-700/20 border border-emerald-500 text-emerald-100 p-4 text-sm">
                                Course created successfully.
                            </div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="rounded-lg bg-red-700/20 border border-red-500 text-red-100 p-4 text-sm">
                                <?= htmlspecialchars($_GET['error']) ?>
                            </div>
                        <?php endif; ?>
                        <form action="../create_course.php" method="POST" class="flex flex-col gap-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <input type="text" name="name" placeholder="Course name (required)"
                                    class="w-full rounded-xl border border-gray-600 bg-gray-900 px-4 py-3 text-gray-100 outline-none focus:border-blue-400"
                                    required />
                                <input type="text" name="url" placeholder="Course URL (optional)"
                                    class="w-full rounded-xl border border-gray-600 bg-gray-900 px-4 py-3 text-gray-100 outline-none focus:border-blue-400" />
                            </div>
                            <textarea name="description" placeholder="Course description (optional)" rows="2"
                                class="w-full rounded-xl border border-gray-600 bg-gray-900 px-4 py-3 text-gray-100 outline-none focus:border-blue-400"></textarea>
                            <button type="submit"
                                class="w-full md:w-auto rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold transition">Create Course</button>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b border-gray-600">
                                <tr>
                                    <th class="pb-3 text-gray-300">ID</th>
                                    <th class="pb-3 text-gray-300">Name</th>
                                    <th class="pb-3 text-gray-300">Description</th>
                                    <th class="pb-3 text-gray-300">URL</th>
                                    <th class="pb-3 text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                                            <td class="py-3"><?= $row['id'] ?></td>
                                            <td class="py-3"><?= htmlspecialchars($row['name']) ?></td>
                                            <td class="py-3 text-sm text-gray-300"><?= htmlspecialchars(substr($row['description'] ?? '', 0, 50)) ?><?= (strlen($row['description'] ?? '') > 50) ? '...' : '' ?></td>
                                            <td class="py-3 text-sm text-gray-300"><a href="<?= htmlspecialchars($row['url'] ?? '#') ?>" target="_blank" class="text-blue-400 hover:text-blue-300"><?= htmlspecialchars(substr($row['url'] ?? '', 0, 40)) ?><?= (strlen($row['url'] ?? '') > 40) ? '...' : '' ?></a></td>
                                            <td class="py-3">
                                                <button onclick="editCourse('<?= addslashes($row['name']) ?>', '<?= addslashes($row['description'] ?? '') ?>', '<?= addslashes($row['url'] ?? '') ?>', <?= $row['id'] ?>)"
                                                    class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-white text-sm font-semibold transition mr-2">Edit</button>
                                                <button onclick="deleteCourse(<?= $row['id'] ?>)"
                                                    class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-white text-sm font-semibold transition">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="py-3 text-center text-gray-400">No courses found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
    

</body>

</html>