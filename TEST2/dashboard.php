<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: index.php");
    exit();
}

require 'config.php';

$courses = [];
if ($conn) {
    $sql = "SELECT id, name, description, url FROM courses ORDER BY name";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $courseName = trim($row['name']);
            $courseSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $courseName));
            if ($courseSlug === '') {
                $courseSlug = 'course-' . $row['id'];
            }
            $courseUrl = !empty($row['url']) ? $row['url'] : null;
            $courses[] = [
                'id' => $row['id'],
                'name' => $courseName,
                'slug' => $courseSlug,
                'description' => $row['description'] ?? '',
                'url' => $courseUrl,
            ];
        }
        $result->free();
    }
    $conn->close();
}

$numCourses = count($courses);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scriptic - Coding Learning Platform</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="dashboard-functions.js"></script>
  <style>
    .button-anim {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .button-anim:hover {
      box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
    }
    .button-anim:active {
      transform: scale(0.98);
    }
    .ripple {
      position: relative;
      overflow: hidden;
    }
    .ripple::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }
    .ripple:active::before {
      width: 300px;
      height: 300px;
    }
  </style>
</head>
<body class="bg-gray-900 text-gray-100 font-mono relative">
  
  
  <div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 p-8 rounded-lg shadow-xl border border-gray-700 w-full max-w-md mx-4">
      <h1 class="text-3xl font-bold text-blue-300 mb-6 text-center">Scriptic</h1>
      <p class="text-gray-300 text-center mb-8">Welcome to your coding journey</p>
      <form id="login-form" onsubmit="handleLogin(event)" class="space-y-4">
        <div>
          <label class="block text-gray-400 text-sm mb-2">Email</label>
          <input id="login-email" type="email" placeholder="you@example.com" class="w-full bg-gray-700 px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-400 focus:outline-none transition" required>
        </div>
        <div>
          <label class="block text-gray-400 text-sm mb-2">Password</label>
          <input id="login-password" type="password" placeholder="••••••••" class="w-full bg-gray-700 px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-400 focus:outline-none transition" required>
        </div>
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-white font-semibold transition">Login</button>
      </form>
      <p class="text-gray-400 text-center text-sm mt-4">Demo: Use any email/password</p>
    </div>
  </div>

 
  <div id="app-content" class="min-h-screen w-full pt-20">
    <div class="absolute inset-0 opacity-5 bg-repeat" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2220%22 height=%2220%22><rect width=%2220%22 height=%2220%22 fill=%22none%22 stroke=%22%2300d4ff%22 stroke-width=%220.5%22/></svg>');"></div>

    <header class="fixed inset-x-0 top-0 z-40 border-b border-slate-800 bg-slate-950/95 backdrop-blur-xl shadow-slate-950/10">
      <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <button id="menu-toggle" class="md:hidden rounded-2xl bg-slate-900/90 p-2 text-sky-300 shadow-sm shadow-slate-950/20 hover:bg-slate-800 transition">
          ☰
        </button>
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-sky-500 to-violet-500 flex items-center justify-center text-lg font-bold text-white">S</div>
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sky-400">Scriptic</p>
            <h1 class="text-lg font-semibold text-white">Coding Learning platform</h1>
          </div>
        </div>

        <div class="hidden flex-1 items-center gap-3 md:flex">
          <label class="flex flex-1 max-w-md items-center gap-2 rounded-2xl border border-slate-800 bg-slate-900/90 px-3 py-2 text-slate-400 focus-within:border-sky-500">
            <span class="text-slate-500">🔎</span>
            <input type="search" placeholder="Search courses..." class="w-full bg-transparent text-slate-100 outline-none placeholder:text-slate-500" />
          </label>
        </div>

        <div class="ml-auto flex items-center gap-3">
          <div class="hidden sm:flex flex-col text-right text-slate-300">
            <span class="text-xs uppercase tracking-[0.2em]">Welcome back!</span>
            <span class="font-semibold text-white"><?= htmlspecialchars($_SESSION['name']) ?></span>
          </div>
          <button onclick="confirmLogout()" class="rounded-full bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-rose-500/20 transition hover:bg-rose-500">Logout</button>
        </div>
      </div>
    </header>

    <div class="mx-auto flex max-w-7xl gap-6 px-4 pb-12 sm:px-6 lg:px-8">
      <aside id="sidebar" class="hidden w-72 shrink-0 flex-col gap-5 border-r border-slate-800 bg-slate-950/95 p-5 md:flex">
        <div class="space-y-5">
          <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5">
            <p class="text-xs uppercase tracking-[0.3em] text-sky-400">Navigation</p>
            <h2 class="mt-4 text-xl font-semibold text-white">Explore</h2>
          </div>
          <nav class="space-y-1 text-sm text-slate-300">
            <button onclick="showDashboard()" class="w-full rounded-2xl px-4 py-3 text-left transition hover:bg-slate-800 focus:outline-none">🏠 Dashboard</button>
            <button onclick="showSection('courses-section','Courses'); toggleSubMenu('courses-submenu'); return false;" class="w-full rounded-2xl px-4 py-3 text-left transition hover:bg-slate-800 focus:outline-none">📚 Courses</button>
            <div id="courses-submenu" class="space-y-1 pl-6 hidden">
              <button onclick="loadLesson('js', 1)" class="w-full rounded-2xl px-4 py-2 text-left text-xs text-slate-400 transition hover:bg-slate-800">JavaScript Lesson 1</button>
              <button onclick="loadLesson('html', 1)" class="w-full rounded-2xl px-4 py-2 text-left text-xs text-slate-400 transition hover:bg-slate-800">HTML Lesson 1</button>
              <button onclick="loadLesson('css', 1)" class="w-full rounded-2xl px-4 py-2 text-left text-xs text-slate-400 transition hover:bg-slate-800">CSS Lesson 1</button>
            </div>
            <button onclick="showSection('challenges-section','Challenges'); return false;" class="w-full rounded-2xl px-4 py-3 text-left transition hover:bg-slate-800 focus:outline-none">⚡ Challenges</button>
            <button onclick="showSection('progress-section','Progress'); return false;" class="w-full rounded-2xl px-4 py-3 text-left transition hover:bg-slate-800 focus:outline-none">📊 Progress</button>
          </nav>
        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 text-slate-400">
          <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Tip</p>
          <p class="mt-3 text-sm leading-6">Consistency is key to mastering web development!</p>
        </div>
      </aside>

      <main class="min-w-0 flex-1 space-y-6 pb-6">
        <section class="rounded-[2rem] border border-slate-800 bg-slate-900/90 p-6 shadow-xl shadow-slate-950/20">
          <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="max-w-3xl">
              <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sky-400">Welcome back</p>
              <h2 id="page-title" class="mt-4 text-4xl font-semibold tracking-tight text-white">Dashboard</h2>
              <p class="mt-4 max-w-2xl text-slate-400">Learn to code the scriptic way!. Browse courses, track progress, and tackle challenges with confidence.</p>
            </div>

            <div class="grid w-full max-w-2xl gap-4 sm:grid-cols-3">
              <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-4 text-slate-200">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Courses</p>
                <p class="mt-3 text-3xl font-semibold text-white"><?= $numCourses ?></p>
              </div>
              <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-4 text-slate-200">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Challenges</p>
                <p class="mt-3 text-3xl font-semibold text-white">3</p>
              </div>
              <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-4 text-slate-200">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Progress</p>
                <p class="mt-3 text-3xl font-semibold text-white">42%</p>
              </div>
            </div>
          </div>
        </section>

        <section id="dashboard" class="grid gap-6 lg:grid-cols-3">
          <?php if ($numCourses > 0): ?>
            <?php foreach ($courses as $course): ?>
              <div class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($course['name']) ?></h3>
                <p class="mt-3 text-slate-400"><?= htmlspecialchars($course['description']) ?: 'Explore the ' . htmlspecialchars($course['name']) . ' course.' ?></p>
                <button onclick="window.location.href='<?= $course['url'] ? htmlspecialchars($course['url']) : '../courses ui/index.html?course=' . urlencode($course['slug']) ?>'" class="mt-6 w-full rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-400">Continue</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="lg:col-span-3 rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20">
              <h3 class="text-xl font-semibold text-white">No courses available</h3>
              <p class="mt-3 text-slate-400">Courses created in the admin panel will be shown here.</p>
            </div>
          <?php endif; ?>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
          <div class="space-y-6">
            <section id="courses-section" class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20 hidden">
              <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <h3 class="text-2xl font-semibold text-white">Courses</h3>
                  <p class="mt-2 text-slate-400">Select a course module and continue learning from the dashboard.</p>
                </div>
                <button onclick="window.location.href='../courses ui/index.html'" class="rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-400">Open Learning Hub</button>
              </div>
              <div class="mt-6 grid gap-4 md:grid-cols-3">
                <?php if ($numCourses > 0): ?>
                  <?php foreach ($courses as $course): ?>
                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                      <h4 class="font-semibold text-white"><?= htmlspecialchars($course['name']) ?></h4>
                      <p class="mt-3 text-sm text-slate-400"><?= htmlspecialchars($course['description']) ?: 'Explore the ' . htmlspecialchars($course['name']) . ' course.' ?></p>
                      <button onclick="window.location.href='<?= $course['url'] ? htmlspecialchars($course['url']) : '../courses ui/index.html?course=' . urlencode($course['slug']) ?>'" class="mt-5 w-full rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-400">View Lessons</button>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="lg:col-span-3 rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                    <h4 class="font-semibold text-white">No courses available</h4>
                    <p class="mt-3 text-sm text-slate-400">Create courses in the admin panel to show them here.</p>
                  </div>
                <?php endif; ?>
              </div>
            </section>

            <section id="challenges-section" class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20 hidden">
              <div>
                <h3 class="text-2xl font-semibold text-white">Challenges</h3>
                <p class="mt-2 text-slate-400">Practice what you learned with coding challenges designed for every level.</p>
              </div>
              <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                  <h4 class="font-semibold text-white">Beginner Challenge</h4>
                  <p class="mt-3 text-sm text-slate-400">Write a simple JavaScript function to format user input.</p>
                  <button onclick="window.location.href='../courses ui/index.html?module=challenge-basic'" class="mt-5 w-full rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-400">Start</button>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                  <h4 class="font-semibold text-white">HTML Practice</h4>
                  <p class="mt-3 text-sm text-slate-400">Build a responsive card layout using HTML and CSS fundamentals.</p>
                  <button onclick="window.location.href='../courses ui/index.html?module=challenge-html'" class="mt-5 w-full rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-400">Start</button>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                  <h4 class="font-semibold text-white">Java Logic</h4>
                  <p class="mt-3 text-sm text-slate-400">Solve a Java problem using loops and conditional statements.</p>
                  <button onclick="window.location.href='../courses ui/index.html?module=challenge-java'" class="mt-5 w-full rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-400">Start</button>
                </div>
              </div>
            </section>

            <section id="progress-section" class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20 hidden">
              <div>
                <h3 class="text-2xl font-semibold text-white">Progress</h3>
                <p class="mt-2 text-slate-400">Track your learning progress across courses and challenges.</p>
              </div>
              <div class="mt-6 space-y-4">
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                  <div class="flex justify-between text-sm text-slate-400"><span>JavaScript</span><span>25%</span></div>
                  <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-800"><div class="h-full w-3/4 rounded-full bg-sky-500"></div></div>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                  <div class="flex justify-between text-sm text-slate-400"><span>HTML & CSS</span><span>40%</span></div>
                  <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-800"><div class="h-full w-[40%] rounded-full bg-sky-500"></div></div>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                  <div class="flex justify-between text-sm text-slate-400"><span>Java</span><span>30%</span></div>
                  <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-800"><div class="h-full w-1/3 rounded-full bg-sky-500"></div></div>
                </div>
              </div>
            </section>
          </div>

          <aside class="space-y-6">
            <div class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20">
              <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Weekly Summary</p>
              <div class="mt-5 space-y-4 text-slate-300">
                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 p-4">
                  <span>Lessons completed</span><span class="font-semibold text-white">5</span>
                </div>
                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 p-4">
                  <span>New badges</span><span class="font-semibold text-white">2</span>
                </div>
                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 p-4">
                  <span>Focus time</span><span class="font-semibold text-white">3h 20m</span>
                </div>
              </div>
            </div>

            <div class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-6 shadow-lg shadow-slate-950/20">
              <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Quick Actions</p>
              <div class="mt-5 grid gap-3">
                <button onclick="showSection('courses-section','Courses');" class="rounded-2xl bg-slate-950/80 px-4 py-3 text-left text-sm text-slate-200 transition hover:bg-slate-800">Browse all courses</button>
                <button onclick="showSection('challenges-section','Challenges');" class="rounded-2xl bg-slate-950/80 px-4 py-3 text-left text-sm text-slate-200 transition hover:bg-slate-800">Open challenges</button>
                <button onclick="showSection('progress-section','Progress');" class="rounded-2xl bg-slate-950/80 px-4 py-3 text-left text-sm text-slate-200 transition hover:bg-slate-800">View progress</button>
              </div>
            </div>
          </aside>
        </section>

        <section id="lesson-content" class="rounded-[1.75rem] border border-slate-800 bg-slate-900/90 p-8 shadow-lg shadow-slate-950/20 hidden">
          <h2 id="lesson-title" class="text-3xl font-semibold text-white"></h2>
          <div id="lesson-body" class="mt-6 space-y-6 text-slate-300 leading-relaxed"></div>
        </section>
      </main>
    </div>
  </div>

  <script>
    
    function ensurePanelsVisible() {
      var sidebar = document.getElementById('sidebar');
      if (sidebar) sidebar.classList.remove('hidden');
    }

    function showSection(sectionId, title) {
      var sections = ['dashboard', 'courses-section', 'challenges-section', 'progress-section', 'lesson-content'];
      sections.forEach(function(id) {
        var element = document.getElementById(id);
        if (element) {
          element.classList.add('hidden');
        }
      });

      var selectedSection = document.getElementById(sectionId);
      if (selectedSection) {
        selectedSection.classList.remove('hidden');
      }

      var titleElement = document.getElementById('page-title');
      if (titleElement) {
        titleElement.textContent = title;
      }
    }

    function showDashboard() {
      showSection('dashboard', 'Dashboard');
    }

    function toggleSubMenu(submenuId) {
      var submenu = document.getElementById(submenuId);
      if (submenu) {
        submenu.classList.toggle('hidden');
      }
    }

    function loadLesson(course, lessonNum) {
      var lessonTitle = document.getElementById('lesson-title');
      var lessonBody = document.getElementById('lesson-body');

      showSection('lesson-content', course.toUpperCase() + ' Lesson ' + lessonNum);

      
      var lessons = {
        'js': {
          1: {
            title: 'JavaScript Lesson 1: Variables & Data Types',
            content: `
              <h3>Variables in JavaScript</h3>
              <p>Variables are containers for storing data values. In JavaScript, you declare variables using <code>var</code>, <code>let</code>, or <code>const</code>.</p>
              <pre><code>let name = "John";
const age = 25;
var isStudent = true;</code></pre>

              <h3>Data Types</h3>
              <ul>
                <li><strong>String:</strong> Text data</li>
                <li><strong>Number:</strong> Numeric data</li>
                <li><strong>Boolean:</strong> true/false values</li>
                <li><strong>Object:</strong> Complex data structures</li>
                <li><strong>Array:</strong> Lists of data</li>
              </ul>
            `
          },
          2: {
            title: 'JavaScript Lesson 2: Functions & Loops',
            content: `
              <h3>Functions</h3>
              <p>Functions are blocks of code designed to perform a particular task.</p>
              <pre><code>function greet(name) {
  return "Hello, " + name + "!";
}

console.log(greet("World"));</code></pre>

              <h3>Loops</h3>
              <p>Loops allow you to execute code repeatedly.</p>
              <pre><code>for (let i = 0; i < 5; i++) {
  console.log("Count: " + i);
}</code></pre>
            `
          }
        },
        'html': {
          1: {
            title: 'HTML Lesson 1: Structure',
            content: `
              <h3>HTML Document Structure</h3>
              <p>Every HTML document should have a basic structure:</p>
              <pre><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Page Title&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;h1&gt;Hello World&lt;/h1&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

              <h3>Common HTML Elements</h3>
              <ul>
                <li><code>&lt;h1&gt;-&lt;h6&gt;</code>: Headings</li>
                <li><code>&lt;p&gt;</code>: Paragraphs</li>
                <li><code>&lt;div&gt;</code>: Divisions/sections</li>
                <li><code>&lt;a&gt;</code>: Links</li>
                <li><code>&lt;img&gt;</code>: Images</li>
              </ul>
            `
          },
          2: {
            title: 'HTML Lesson 2: CSS Styling',
            content: `
              <h3>Adding CSS to HTML</h3>
              <p>You can style HTML elements using CSS in three ways:</p>
              <ol>
                <li>Inline styles</li>
                <li>Internal stylesheet</li>
                <li>External stylesheet</li>
              </ol>

              <h3>CSS Syntax</h3>
              <pre><code>selector {
  property: value;
}</code></pre>

              <p>Example:</p>
              <pre><code>h1 {
  color: blue;
  font-size: 24px;
}</code></pre>
            `
          }
        },
        'java': {
          1: {
            title: 'Java Lesson 1: Basics',
            content: `
              <h3>Hello World in Java</h3>
              <pre><code>public class HelloWorld {
  public static void main(String[] args) {
    System.out.println("Hello, World!");
  }
}</code></pre>

              <h3>Java Syntax Basics</h3>
              <ul>
                <li>Java is case-sensitive</li>
                <li>Every statement ends with a semicolon</li>
                <li>Classes are the building blocks</li>
                <li>The main method is the entry point</li>
              </ul>
            `
          },
          2: {
            title: 'Java Lesson 2: OOP Concepts',
            content: `
              <h3>Object-Oriented Programming</h3>
              <p>Java is an object-oriented programming language. Key concepts:</p>

              <h4>Classes and Objects</h4>
              <pre><code>public class Car {
  String color;
  int speed;

  void drive() {
    System.out.println("Driving...");
  }
}</code></pre>

              <h4>Inheritance</h4>
              <p>One class can inherit properties from another class.</p>

              <h4>Encapsulation</h4>
              <p>Wrapping data and methods into a single unit.</p>
            `
          }
        }
      };

      if (lessons[course] && lessons[course][lessonNum]) {
        if (lessonTitle) lessonTitle.textContent = lessons[course][lessonNum].title;
        if (lessonBody) lessonBody.innerHTML = lessons[course][lessonNum].content;
      }
    }

    
    document.getElementById('menu-toggle').addEventListener('click', function() {
      var sidebar = document.getElementById('sidebar');
      if (sidebar) {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('md:flex');
      }
    });

    
    window.addEventListener('load', function() {
      ensurePanelsVisible();
      showDashboard();
    });

    
    document.addEventListener('DOMContentLoaded', function() {
      const buttons = document.querySelectorAll('button');
      buttons.forEach(btn => {
        btn.classList.add('button-anim', 'ripple');
        
        btn.addEventListener('click', function() {
          console.log('Button clicked:', this.textContent.trim());
        });
      });
    });

    
    function confirmLogout() {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
      }
    }
  </script>
  <script src="../courses ui/functions.js"></script>
</body>
</html>
