
document.addEventListener('DOMContentLoaded', function () {
  
  var nameInput = document.getElementById('name');
  var greetBtn = document.getElementById('greetBtn');
  var colorBtn = document.getElementById('colorBtn');
  var output = document.getElementById('output');
  var card = document.querySelector('.card');

  function greet() {
    var name = nameInput ? nameInput.value.trim() : '';
    if (output) output.textContent = name ? ('Hello, ' + name + '! 👋') : 'Hello — please enter a name.';
    if (output && output.animate) {
      output.animate([{opacity:0},{opacity:1}], {duration:240, fill:'forwards'});
    }
  }

  function randomColor() {
    var hue = Math.floor(Math.random() * 360);
    var color = 'hsl(' + hue + ', 80%, 65%)';
    if (card) {
      card.style.background = color;
      card.style.color = '#fff';
    }
  }

  if (greetBtn) greetBtn.addEventListener('click', greet);
  if (nameInput) nameInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') greet(); });
  if (colorBtn) colorBtn.addEventListener('click', randomColor);

  
  window.toggleCoursesMenu = function () {
    var cms = document.getElementById('courses-submenu');
    if (cms) cms.classList.toggle('show');
  };

  window.toggleSubMenu = function (id) {
    var sub = document.getElementById(id);
    if (sub) sub.classList.toggle('show');
  };

  window.showDashboard = function () {
    
    var dashboard = document.getElementById('dashboard');
    var courseContent = document.getElementById('course-content');
    var lessonContent = document.getElementById('lesson-content');
    if (dashboard) dashboard.classList.remove('hidden');
    if (courseContent) courseContent.classList.add('hidden');
    if (lessonContent) lessonContent.classList.add('hidden');
    var title = document.getElementById('page-title'); if (title) title.textContent = 'Dashboard';
  };

  window.hideAllSections = function () {
    var d = document.getElementById('dashboard'); if (d) d.classList.add('hidden');
    var cc = document.getElementById('course-content'); if (cc) cc.classList.add('hidden');
    var lc = document.getElementById('lesson-content'); if (lc) lc.classList.add('hidden');
  };

  window.loadCourseOverview = function (course) {
    window.hideAllSections();
    var courseContent = document.getElementById('course-content');
    if (!courseContent) return;
    courseContent.classList.remove('hidden');

    var pageTitle = document.getElementById('page-title'); if (pageTitle) pageTitle.textContent = course.charAt(0).toUpperCase() + course.slice(1) + ' Course';

    var courseData = {
      js: { title: 'JavaScript Basics', icon: '💻', description: 'Learn JS fundamentals.', progress: 75, lessons: [{num:1, title:'Variables & Data Types'}, {num:2, title:'Functions & Loops'}, {num:3, title:'Arrays & Objects'}] },
      html: { title: 'HTML & CSS', icon: '🌐', description: 'Markup and styling basics.', progress: 90, lessons: [{num:1, title:'HTML Structure'}, {num:2, title:'CSS Styling'}, {num:3, title:'Forms & Inputs'}] },
      java: { title: 'Java Intro', icon: '☕', description: 'Intro to Java and OOP.', progress: 50, lessons: [{num:1, title:'Basics of Java'}, {num:2, title:'OOP Concepts'}, {num:3, title:'Exception Handling'}] }
    };

    var data = courseData[course] || courseData.js;
    var titleEl = document.getElementById('course-title'); if (titleEl) titleEl.textContent = data.title;
    var iconImg = document.getElementById('course-icon-img');
    var iconEmoji = document.getElementById('course-icon-emoji');
    var customIcon = null;
    try { customIcon = localStorage.getItem('icon_' + course); } catch (e) { customIcon = null; }
    if (iconImg) {
      if (customIcon) {
        iconImg.src = customIcon;
        iconImg.style.display = 'inline-block';
        if (iconEmoji) iconEmoji.style.display = 'none';
      } else {
        iconImg.style.display = 'none';
        if (iconEmoji) { iconEmoji.textContent = data.icon; iconEmoji.style.display = 'inline'; }
      }
    } else if (iconEmoji) {
      iconEmoji.textContent = data.icon;
    }
    var descEl = document.getElementById('course-description'); if (descEl) descEl.textContent = data.description;
    var progressPath = document.getElementById('course-progress'); if (progressPath) progressPath.style.strokeDasharray = data.progress + ', 100';
    var progressText = document.getElementById('course-progress-text'); if (progressText) progressText.textContent = data.progress + '% Complete';

    var lessonsContainer = document.getElementById('course-lessons');
    if (lessonsContainer) {
      lessonsContainer.innerHTML = '';
      data.lessons.forEach(function (l) {
        var card = document.createElement('div');
        card.className = 'bg-gray-700 p-6 rounded-lg border border-gray-600';
        card.innerHTML = '<h3 class="font-bold text-lg mb-2">' + l.title + '</h3>' +
          '<p class="mb-4 text-gray-300">A short lesson description.</p>' +
          '<button class="bg-blue-500 px-6 py-3 rounded-lg text-white font-semibold hover:bg-blue-600 transition" onclick="loadLesson(\'' + course + '\',' + l.num + ')">Open Lesson</button>';
        lessonsContainer.appendChild(card);
      });
    }
   
    var detail = document.getElementById('detail-course'); if (detail) detail.textContent = data.description;
    var right = document.getElementById('right-panel'); if (right) right.classList.remove('hidden');
  };

  window.loadLesson = function (course, lessonNum) {
    window.hideAllSections();

    window.currentCourse = course;
    window.currentLesson = lessonNum;
    var lessonContent = document.getElementById('lesson-content'); if (!lessonContent) return;
    lessonContent.classList.remove('hidden');
    var lessonTitle = document.getElementById('lesson-title'); if (lessonTitle) lessonTitle.textContent = course.toUpperCase() + ': Lesson ' + lessonNum;
    
    // Define lesson contents
    var lessonContents = {
      js: {
        1: '<h3>Variables & Data Types</h3><p>In JavaScript, variables are containers for storing data values. You can declare variables using <code>var</code>, <code>let</code>, or <code>const</code>.</p><p>Data types include:</p><ul><li><strong>Strings:</strong> Text data, e.g., "Hello World"</li><li><strong>Numbers:</strong> Numeric values, e.g., 42</li><li><strong>Booleans:</strong> True or false values</li><li><strong>Objects:</strong> Complex data structures</li><li><strong>Arrays:</strong> Lists of values</li></ul><p>Example: <code>let name = "John"; const age = 30;</code></p>',
        2: '<h3>Functions & Loops</h3><p>Functions are blocks of code designed to perform a particular task. They are defined using the <code>function</code> keyword.</p><p>Example:</p><pre><code>function greet(name) {\n  return "Hello, " + name + "!";\n}</code></pre><p>Loops allow you to execute code repeatedly. Common loops include <code>for</code> and <code>while</code>.</p><p>Example:</p><pre><code>for (let i = 0; i < 5; i++) {\n  console.log(i);\n}</code></pre>',
        3: '<h3>Arrays & Objects</h3><p>Arrays are used to store multiple values in a single variable. Objects are collections of properties.</p><p>Array example:</p><pre><code>let fruits = ["Apple", "Banana", "Cherry"];\nconsole.log(fruits[0]); // Apple</code></pre><p>Object example:</p><pre><code>let person = {\n  name: "John",\n  age: 30,\n  city: "New York"\n};\nconsole.log(person.name); // John</code></pre>'
      },
      html: {
        1: '<h3>HTML Structure</h3><p>HTML (HyperText Markup Language) is the standard markup language for creating web pages. It consists of elements enclosed in angle brackets.</p><p>Basic structure:</p><pre><code>&lt;!DOCTYPE html&gt;\n&lt;html&gt;\n  &lt;head&gt;\n    &lt;title&gt;Page Title&lt;/title&gt;\n  &lt;/head&gt;\n  &lt;body&gt;\n    &lt;h1&gt;Heading&lt;/h1&gt;\n    &lt;p&gt;Paragraph&lt;/p&gt;\n  &lt;/body&gt;\n&lt;/html&gt;</code></pre><p>Common elements: <code>&lt;h1&gt;</code> to <code>&lt;h6&gt;</code> for headings, <code>&lt;p&gt;</code> for paragraphs, <code>&lt;a&gt;</code> for links.</p>',
        2: '<h3>CSS Styling</h3><p>CSS (Cascading Style Sheets) is used to describe the presentation of a document written in HTML. It controls layout, colors, fonts, and more.</p><p>You can add CSS in three ways:</p><ol><li>Inline: <code>&lt;p style="color: red;"&gt;Text&lt;/p&gt;</code></li><li>Internal: Inside <code>&lt;style&gt;</code> tags in the head</li><li>External: Link to a .css file</li></ol><p>Example: <code>p { color: blue; font-size: 14px; }</code></p>',
        3: '<h3>Forms & Inputs</h3><p>HTML forms are used to collect user input. The <code>&lt;form&gt;</code> element contains input elements.</p><p>Common input types:</p><ul><li><code>&lt;input type="text"&gt;</code> for text input</li><li><code>&lt;input type="password"&gt;</code> for passwords</li><li><code>&lt;input type="email"&gt;</code> for email addresses</li><li><code>&lt;textarea&gt;</code> for multi-line text</li><li><code>&lt;select&gt;</code> for dropdown lists</li></ul><p>Example:</p><pre><code>&lt;form&gt;\n  &lt;input type="text" name="username"&gt;\n  &lt;input type="submit" value="Submit"&gt;\n&lt;/form&gt;</code></pre>'
      },
      java: {
        1: '<h3>Basics of Java</h3><p>Java is a high-level, class-based, object-oriented programming language. It is designed to have as few implementation dependencies as possible.</p><p>Key features:</p><ul><li>Platform independent (Write Once, Run Anywhere)</li><li>Object-oriented</li><li>Robust and secure</li><li>Multithreaded</li></ul><p>Basic program structure:</p><pre><code>public class HelloWorld {\n  public static void main(String[] args) {\n    System.out.println("Hello, World!");\n  }\n}</code></pre>',
        2: '<h3>OOP Concepts</h3><p>Object-oriented programming (OOP) is a programming paradigm based on the concept of "objects", which can contain data and code.</p><p>Main concepts:</p><ul><li><strong>Class:</strong> A blueprint for creating objects</li><li><strong>Object:</strong> An instance of a class</li><li><strong>Inheritance:</strong> A class can inherit properties from another class</li><li><strong>Polymorphism:</strong> Ability to take many forms</li><li><strong>Encapsulation:</strong> Hiding internal details</li><li><strong>Abstraction:</strong> Showing only essential features</li></ul><p>Example:</p><pre><code>class Animal {\n  void eat() {\n    System.out.println("eating...");\n  }\n}\n\nclass Dog extends Animal {\n  void bark() {\n    System.out.println("barking...");\n  }\n}</code></pre>',
        3: '<h3>Exception Handling</h3><p>Exception handling in Java is managed via try-catch blocks. Exceptions are events that occur during the execution of programs that disrupt the normal flow.</p><p>Basic structure:</p><pre><code>try {\n  // code that may throw an exception\n} catch (ExceptionType e) {\n  // handle the exception\n}</code></pre><p>Common exceptions: <code>IOException</code>, <code>NullPointerException</code>, <code>ArithmeticException</code>.</p><p>Use <code>finally</code> block for code that always executes.</p>'
      }
    };
    
    var lessonBody = document.getElementById('lesson-body');
    if (lessonBody) {
      var content = lessonContents[course] && lessonContents[course][lessonNum] ? lessonContents[course][lessonNum] : '<p>This is lesson ' + lessonNum + ' for ' + course + '.</p>';
      lessonBody.innerHTML = content;
    }
    
    var pageTitle = document.getElementById('page-title'); if (pageTitle) pageTitle.textContent = 'Lesson';

    try {
      var videoEl = document.getElementById('lesson-video');
      var videoSrc = document.getElementById('lesson-video-src');
      var savedVideoURL = localStorage.getItem('videoURL_' + course + '_' + lessonNum);
      if (savedVideoURL && videoEl) {
       
        if (videoSrc) videoSrc.src = savedVideoURL;
        videoEl.load();
      } else if (videoSrc) {
        videoSrc.src = '';
        if (videoEl) try { videoEl.load(); } catch(e){}
      }

      
      var note = localStorage.getItem('note_' + course + '_' + lessonNum) || '';
      var noteEl = document.getElementById('lesson-note'); if (noteEl) noteEl.value = note;
      var urlInput = document.getElementById('video-url-input'); if (urlInput) urlInput.value = savedVideoURL || '';
    } catch (e) {}
  };

 
  window.showChallenges = function () {
    window.hideAllSections();
    var mainContent = document.getElementById('main-content');
    if (!mainContent) return;
    mainContent.innerHTML = `
      <section class="bg-gray-800 p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-3xl font-bold mb-6 text-blue-300">Challenges</h2>
        <p class="mb-6 text-gray-300 leading-relaxed">Test your skills with coding challenges.</p>
        <div class="space-y-4">
          <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
            <h3 class="font-bold text-lg mb-2">Challenge 1: FizzBuzz</h3>
            <p class="mb-4 text-gray-300">Write a program that prints numbers from 1 to 100, replacing multiples of 3 with "Fizz" and multiples of 5 with "Buzz".</p>
            <button class="bg-blue-500 px-6 py-3 rounded-lg text-white font-semibold hover:bg-blue-600 transition">Start Challenge</button>
          </div>
          <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
            <h3 class="font-bold text-lg mb-2">Challenge 2: Palindrome Checker</h3>
            <p class="mb-4 text-gray-300">Create a function to check if a string is a palindrome.</p>
            <button class="bg-blue-500 px-6 py-3 rounded-lg text-white font-semibold hover:bg-blue-600 transition">Start Challenge</button>
          </div>
        </div>
      </section>
    `;
    var title = document.getElementById('page-title'); if (title) title.textContent = 'Challenges';
  };

  
  window.showProgress = function () {
    window.hideAllSections();
    var mainContent = document.getElementById('main-content');
    if (!mainContent) return;
    mainContent.innerHTML = `
      <section class="bg-gray-800 p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-3xl font-bold mb-6 text-blue-300">Progress</h2>
        <p class="mb-6 text-gray-300 leading-relaxed">Track your learning journey.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
            <h3 class="font-bold text-lg mb-4">Overall Progress</h3>
            <div class="flex justify-center mb-4">
              <svg class="w-20 h-20" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#374151" stroke-width="2" stroke-dasharray="100, 100"></path>
                <path id="overall-progress" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#00d4ff" stroke-width="2" stroke-dasharray="0, 100" stroke-linecap="round"></path>
              </svg>
            </div>
            <p class="text-center text-sm text-gray-300">70% Complete</p>
          </div>
          <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
            <h3 class="font-bold text-lg mb-4">Streak</h3>
            <p class="text-4xl font-bold text-blue-400 mb-2">7 Days</p>
            <p class="text-gray-300">Keep it up!</p>
          </div>
        </div>
      </section>
    `;
    var title = document.getElementById('page-title'); if (title) title.textContent = 'Progress';

    setTimeout(function () {
      var progressPath = document.getElementById('overall-progress');
      if (progressPath) {
        progressPath.style.strokeDasharray = '70, 100';
        progressPath.style.transition = 'stroke-dasharray 1.8s ease-out';
      }
    }, 80);
  };

  window.attachLessonVideo = function (fileInput, course, lessonNum) {
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) return;
    var file = fileInput.files[0];
    try {
      var url = URL.createObjectURL(file);
      var videoSrc = document.getElementById('lesson-video-src');
      var videoEl = document.getElementById('lesson-video');
      if (videoSrc) videoSrc.src = url;
      if (videoEl) { videoEl.load(); videoEl.play().catch(function(){}); }
    } catch (e) { console.error(e); }
  };

  window.saveVideoURL = function (course, lessonNum) {
    var urlInput = document.getElementById('video-url-input');
    if (!urlInput) return;
    var url = urlInput.value.trim();
    if (!url) return;
    try {
      localStorage.setItem('videoURL_' + course + '_' + lessonNum, url);
      var videoSrc = document.getElementById('lesson-video-src');
      var videoEl = document.getElementById('lesson-video');
      if (videoSrc) videoSrc.src = url;
      if (videoEl) { videoEl.load(); videoEl.play().catch(function(){}); }
    } catch (e) { console.error(e); }
  };

  window.saveLessonNote = function (course, lessonNum) {
    var noteEl = document.getElementById('lesson-note');
    if (!noteEl) return;
    try {
      localStorage.setItem('note_' + course + '_' + lessonNum, noteEl.value || '');
    } catch (e) { console.error(e); }
  };

  window.clearLessonNote = function (course, lessonNum) {
    var noteEl = document.getElementById('lesson-note');
    if (noteEl) noteEl.value = '';
    try { localStorage.removeItem('note_' + course + '_' + lessonNum); } catch(e){}
  };

  window.resetAppState = function () {
    try {
      
      var d = document.getElementById('dashboard'); if (d) d.classList.remove('hidden');
      var cc = document.getElementById('course-content'); if (cc) cc.classList.add('hidden');
      var lc = document.getElementById('lesson-content'); if (lc) lc.classList.add('hidden');
     
      var menus = ['courses-submenu','js-submenu','html-submenu','java-submenu'];
      menus.forEach(function(id){ var el = document.getElementById(id); if (el) el.classList.remove('show'); });
      var detail = document.getElementById('detail-course'); if (detail) detail.textContent = 'Select a course to see details.';
      var right = document.getElementById('right-panel'); if (right) right.classList.add('hidden');
      var title = document.getElementById('page-title'); if (title) title.textContent = 'Dashboard';
    } catch (e) {
     
    }
  };

  window.showDashboardWithTransition = function () {
    var main = document.getElementById('main-content');
    if (!main) { window.resetAppState(); return; }
    try {
  
      var fadeOut = main.animate([{opacity:1},{opacity:0.2}], {duration:200, fill:'forwards'});
      fadeOut.onfinish = function() {
        window.resetAppState();
        main.animate([{opacity:0.2},{opacity:1}], {duration:260, fill:'forwards'});
      };
    } catch (e) {
      window.resetAppState();
    }
  };

  var menuToggle = document.getElementById('menu-toggle');
  if (menuToggle) menuToggle.addEventListener('click', function () {
    var sidebar = document.getElementById('sidebar'); if (sidebar) sidebar.classList.toggle('hidden');
    var right = document.getElementById('right-panel'); if (right) right.classList.toggle('hidden');
  });
});