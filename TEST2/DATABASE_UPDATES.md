# Database and Course Management Updates

## Overview
Your course management system has been upgraded with the following enhancements:

### New Database Structure

#### 1. **Courses Table Updates**
The `courses` table now includes:
- `id` - Primary key
- `name` - Course name (required)
- `description` - Course description (optional, LONGTEXT)
- `url` - Course URL/link (optional, VARCHAR 500)
- `created_at` - Timestamp when course was created

#### 2. **New User Courses Table**
A new `user_courses` table has been created to track user course enrollments:

```
Columns:
- id - Primary key
- user_id - References users table
- course_id - References courses table
- enrolled_at - When user enrolled
- started_at - When user started the course
- completed_at - When user completed the course
- progress_percentage - Course progress (0-100)
- status - Enrollment status (enrolled, in_progress, completed, paused)
- last_accessed - Last access timestamp

Unique Constraint: user_id + course_id (one enrollment per user per course)
Foreign Keys: Cascading deletes from users and courses tables
```

---

## How to Set Up

### Step 1: Run Database Migration
1. Open your browser and navigate to: `http://localhost/TEST2/setup_database.php`
2. The script will:
   - Add `description` column to courses table
   - Add `url` column to courses table
   - Add `created_at` column to courses table
   - Create the `user_courses` table for enrollment tracking
3. You should see success messages for each operation

### Step 2: Update Admin Course Management
The admin courses panel (`/admin-users/courses.php`) now includes:

**Create/Edit Fields:**
- Course Name (required)
- Course URL (optional) - Points to external course content
- Course Description (optional) - Detailed course info

**Table Display:**
- Shows course ID, Name, Description preview, and URL
- Edit button allows updating all three fields
- Delete button removes courses (cascades to user_courses table)

---

## How to Use

### For Admins

#### Creating a Course
1. Go to Admin Panel → Courses
2. Fill in the form:
   - **Course Name**: e.g., "Python Basics"
   - **Course URL**: e.g., "https://example.com/python-course" (optional)
   - **Description**: e.g., "Learn Python fundamentals from scratch"
3. Click "Create Course"

#### Editing a Course
1. Click the "Edit" button next to a course
2. You'll be prompted to edit:
   - Course name
   - Course description
   - Course URL
3. Click OK to save changes

#### Deleting a Course
1. Click the "Delete" button next to a course
2. Confirm the deletion
3. All user enrollments for that course are automatically removed (CASCADE)

---

### For Users

#### Viewing Courses
1. Users log in and see the dashboard
2. Available courses are displayed dynamically from the database
3. Each course card shows:
   - Course name
   - Course description (if provided by admin)
   - "Continue" button

#### Accessing Courses
- If a **URL** was set by the admin, clicking "Continue" takes users to that URL
- If no URL is set, users are directed to the course learning interface

#### Course Enrollment Tracking
- The system tracks when users start courses
- Progress can be tracked in the `user_courses` table
- Status automatically updates (enrolled → in_progress → completed)

---

## Database Queries

### View All Courses with User Count
```sql
SELECT c.id, c.name, c.description, c.url, COUNT(uc.id) as user_count
FROM courses c
LEFT JOIN user_courses uc ON c.id = uc.course_id
GROUP BY c.id, c.name
ORDER BY c.name;
```

### Get User's Course Progress
```sql
SELECT u.id, u.name, c.name as course_name, uc.status, uc.progress_percentage, uc.started_at
FROM users u
JOIN user_courses uc ON u.id = uc.user_id
JOIN courses c ON c.id = uc.course_id
WHERE u.id = ?
ORDER BY uc.enrolled_at DESC;
```

### Start Course for User
```php
$stmt = $pdo->prepare("
    UPDATE user_courses 
    SET status = 'in_progress', started_at = NOW() 
    WHERE user_id = ? AND course_id = ? AND status = 'enrolled'
");
$stmt->execute([$userId, $courseId]);
```

### Mark Course as Completed
```php
$stmt = $pdo->prepare("
    UPDATE user_courses 
    SET status = 'completed', completed_at = NOW(), progress_percentage = 100 
    WHERE user_id = ? AND course_id = ?
");
$stmt->execute([$userId, $courseId]);
```

---

## File Changes Summary

### Modified Files
- `dashboard.php` - Now fetches and displays courses with descriptions and URLs dynamically
- `admin-users/courses.php` - Updated form and table to include description and URL fields
- `create_course.php` - Accepts description and URL in addition to name
- `update_course.php` - Updates all three fields (name, description, URL)
- `dashboard-functions.js` - Updated editCourse() to handle all three fields

### New Files
- `setup_database.php` - Database migration/setup script

---

## Next Steps

1. ✅ Run `setup_database.php` to initialize the database
2. ✅ Create/manage courses with descriptions and URLs from admin panel
3. 📝 Users will see courses dynamically on their dashboard
4. 📊 Track user enrollment using the `user_courses` table
5. 🔗 External course URLs will direct users outside the platform when clicked

---

## Troubleshooting

### "FOREIGN KEY constraint failed"
- Ensure the `users` table exists before creating user_courses
- Make sure user_id and course_id values are valid

### "Column already exists"
- The migration script checks for existing columns before adding them
- Safe to run multiple times

### Courses not showing
- Check that courses exist in the database: `SELECT * FROM courses;`
- Verify user is logged in with valid session
- Check browser console for JavaScript errors

---

## Security Notes

- Course URLs are validated on input
- User-submitted data is escaped with `htmlspecialchars()`
- Foreign keys enforce referential integrity
- DELETE operations cascade to user_courses (no orphaned enrollments)

