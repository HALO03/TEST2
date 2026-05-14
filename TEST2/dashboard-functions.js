// User Management Functions
async function editUser(name, id) {
    const newName = prompt("Edit Name:", name);
    if (newName !== null && newName.trim() !== "") {
        try {
            const response = await fetch('../update_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    newName: newName.trim(),
                    id: id
                })
            });
            const result = await response.json();
            if (result.success) {
                alert("User '" + name + "' updated to '" + newName + "' with id: " + id);
                location.reload(); 
            } else {
                alert("Error updating user: " + result.error);
            }
        } catch (error) {
            alert("Error: " + error.message);
        }
    }
}

async function deleteUser(id) {
    const confirmed = confirm("Are you sure you want to delete the user with id '" + id + "'? This cannot be undone.");
    if (confirmed) {
        try {
            const response = await fetch('../delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });
            const result = await response.json();
            if (result.success) {
                alert("User with id '" + id + "' has been deleted");
                location.reload(); 
            } else {
                alert("Error deleting user: " + result.error);
            }
        } catch (error) {
            alert("Error: " + error.message);
        }
    }
}

async function createUser() {
    const name = prompt("Enter user name:");
    if (!name || !name.trim()) return;

    const email = prompt("Enter user email:");
    if (!email || !email.trim()) return;

    const password = prompt("Enter user password:");
    if (!password || !password.trim()) return;

    const role = prompt("Enter user role (admin or user):", "user");
    if (!role || !role.trim()) return;

    try {
        const response = await fetch('../create_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name.trim(),
                email: email.trim(),
                password: password.trim(),
                role: role.trim().toLowerCase()
            })
        });
        const result = await response.json();
        if (result.success) {
            alert("User '" + name + "' created successfully");
            location.reload();
        } else {
            alert("Error creating user: " + result.error);
        }
    } catch (error) {
        alert("Error: " + error.message);
    }
}

// Course Management Functions
async function editCourse(name, description, url, id) {
    const newName = prompt("Edit Course Name:", name);
    if (newName !== null && newName.trim() !== "") {
        const newDescription = prompt("Edit Course Description:", description);
        if (newDescription !== null) {
            const newUrl = prompt("Edit Course URL:", url);
            if (newUrl !== null) {
                try {
                    const response = await fetch('../update_course.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            newName: newName.trim(),
                            newDescription: newDescription.trim(),
                            newUrl: newUrl.trim(),
                            id: id
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert("Course '" + name + "' updated successfully");
                        location.reload(); 
                    } else {
                        alert("Error updating course: " + result.error);
                    }
                } catch (error) {
                    alert("Error: " + error.message);
                }
            }
        }
    }
}

async function deleteCourse(id) {
    const confirmed = confirm("Are you sure you want to delete course with id '" + id + "'? This will affect all enrolled students.");
    if (confirmed) {
        try {
            const response = await fetch('../delete_course.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });
            const result = await response.json();
            if (result.success) {
                alert("Course with id '" + id + "' has been deleted");
                location.reload(); 
            } else {
                alert("Error deleting course: " + result.error);
            }
        } catch (error) {
            alert("Error: " + error.message);
        }
    }
}

async function createCourse() {
    const name = prompt("Enter Course Name:");
    if (name !== null && name.trim() !== "") {
        try {
            const response = await fetch('../create_course.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name.trim()
                })
            });
            const result = await response.json();
            if (result.success) {
                alert("Course '" + name + "' created successfully");
                location.reload(); 
            } else {
                alert("Error creating course: " + result.error);
            }
        } catch (error) {
            alert("Error: " + error.message);
        }
    }
}

// Settings Functions
function saveChanges() {
    const platformName = document.getElementById('platformName').value;
    const platformUrl = document.getElementById('platformUrl').value;
    const supportEmail = document.getElementById('supportEmail').value;
    const timezone = document.getElementById('timezoneSelect').value;
    const maintenanceMode = document.getElementById('maintenanceToggle').checked;
    const registrationEnabled = document.getElementById('registrationToggle').checked;
    const sessionTimeout = document.getElementById('sessionTimeout').value;
    const notificationEmail = document.getElementById('notificationEmail').value;

    if (!platformName.trim() || !platformUrl.trim() || !supportEmail.trim() || !notificationEmail.trim()) {
        alert("Please fill in all required fields.");
        return;
    }

    alert(
        "Settings saved successfully!\n\n" +
        "Platform Name: " + platformName + "\n" +
        "Platform URL: " + platformUrl + "\n" +
        "Support Email: " + supportEmail + "\n" +
        "Notification Email: " + notificationEmail + "\n" +
        "Timezone: " + timezone + "\n" +
        "Maintenance Mode: " + (maintenanceMode ? 'Enabled' : 'Disabled') + "\n" +
        "User Registration: " + (registrationEnabled ? 'Enabled' : 'Disabled') + "\n" +
        "Session Timeout: " + sessionTimeout + " minutes"
    );
}
