// ========= Helper Functions =========
/**
 * Fetches JSON data from a given URL.
 * @param {string} url - The URL to fetch.
 * @param {object} options - Fetch options.
 * @returns {Promise<object>} - The JSON response.
 */
async function fetchJSON(url, options = {}) {
  try {
    const res = await fetch(url, options);
    if (!res.ok) {
      throw new Error(`HTTP Error: ${res.status}`);
    }
    return res.json();
  } catch (error) {
    showMessage(`Error: ${error.message}`);
    console.error('Fetch error:', error);
    return { success: false, message: error.message };
  }
}

/**
 * Shows a custom message box with a given message.
 * @param {string} message - The message to display.
 */
function showMessage(message) {
  const messageBox = document.getElementById('messageBox');
  const messageText = document.getElementById('messageText');
  messageText.textContent = message;
  messageBox.style.display = 'flex';
}

/**
 * Hides the custom message box.
 */
function hideMessage() {
  document.getElementById('messageBox').style.display = 'none';
}

/**
 * Toggles visibility of sections based on the active ID.
 * @param {string} id - The ID of the section to show.
 */
function showSection(id) {
  document.querySelectorAll('section.content').forEach(sec => sec.classList.remove('active'));
  document.getElementById(id).classList.add('active');

  // Load data for the active section
  switch (id) {
    case 'students':
      loadStudents();
      break;
    case 'programs':
      loadPrograms();
      break;
    case 'years-semesters':
      loadYears();
      loadSemesters();
      break;
    case 'subjects':
      loadSubjects();
      break;
    case 'enrollments':
      loadEnrollments();
      break;
  }
}

// ========= Students Management =========
async function loadStudents() {
  const res = await fetchJSON('api/students/getStudents.php');
  const table = document.getElementById('studentsTable');
  table.innerHTML = `
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Program</th>
      <th>Allowance</th>
      <th>Actions</th>
    </tr>`;

  if (res.success && res.data) {
    res.data.forEach(s => {
      table.innerHTML += `
        <tr>
          <td>${s.stud_id}</td>
          <td>${s.first_name} ${s.middle_name} ${s.last_name}</td>
          <td>${s.program_name}</td>
          <td>${s.allowance}</td>
          <td>
            <button class="editBtn" onclick="openEditStudentModal(${s.stud_id})">Edit</button>
            <button class="delBtn" onclick="deleteStudent(${s.stud_id})">Delete</button>
          </td>
        </tr>`;
    });
  }
}

async function deleteStudent(id) {
  const confirmed = window.confirm("Are you sure you want to delete this student?");
  if (confirmed) {
    const res = await fetchJSON('api/students/deleteStudent.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ stud_id: id })
    });
    if (res.success) {
      showMessage(res.message);
      loadStudents();
    } else {
      showMessage(res.message);
    }
  }
}

function openEditStudentModal(id) {
  // Logic to populate and show the modal
  const modal = document.getElementById('editStudentModal');
  modal.style.display = 'block';
  // You would typically fetch student data here to pre-fill the form
  // For now, let's just populate with a placeholder ID
  document.getElementById('edit-stud-id').value = id;
}

document.querySelector('.modal .close-btn').addEventListener('click', () => {
  document.getElementById('editStudentModal').style.display = 'none';
});

document.getElementById('addStudentForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const res = await fetchJSON('api/students/addStudents.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (res.success) {
    showMessage(res.message);
    e.target.reset();
    loadStudents();
  } else {
    showMessage(res.message);
  }
});

// ========= Programs Management =========
async function loadPrograms() {
  const res = await fetchJSON('api/programs/getPrograms.php');
  const table = document.getElementById('programsTable');
  table.innerHTML = `
    <tr>
      <th>ID</th>
      <th>Program</th>
      <th>Institute</th>
      <th>Actions</th>
    </tr>`;

  if (res.success && res.data) {
    res.data.forEach(p => {
      table.innerHTML += `
        <tr>
          <td>${p.program_id}</td>
          <td>${p.program_name}</td>
          <td>${p.ins_name}</td>
          <td><button class="delBtn" onclick="deleteProgram(${p.program_id})">Delete</button></td>
        </tr>`;
    });
  }
}

async function deleteProgram(id) {
  const confirmed = window.confirm("Are you sure you want to delete this program?");
  if (confirmed) {
    const res = await fetchJSON('api/programs/deleteProgram.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ program_id: id })
    });
    if (res.success) {
      showMessage(res.message);
      loadPrograms();
    } else {
      showMessage(res.message);
    }
  }
}

document.getElementById('addProgramForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const res = await fetchJSON('api/programs/addProgram.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (res.success) {
    showMessage(res.message);
    e.target.reset();
    loadPrograms();
  } else {
    showMessage(res.message);
  }
});

// ========= Years & Semesters Management =========
async function loadYears() {
  const res = await fetchJSON('api/year&semester/getYears.php');
  const table = document.getElementById('yearsTable');
  table.innerHTML = `
    <tr>
      <th>ID</th>
      <th>Year</th>
      <th>Actions</th>
    </tr>`;

  if (res.success && res.data) {
    res.data.forEach(y => {
      table.innerHTML += `
        <tr>
          <td>${y.year_id}</td>
          <td>${y.year_from} - ${y.year_to}</td>
          <td><button class="delBtn" onclick="deleteYear(${y.year_id})">Delete</button></td>
        </tr>`;
    });
  }
}

async function deleteYear(id) {
  const confirmed = window.confirm("Are you sure you want to delete this year?");
  if (confirmed) {
    const res = await fetchJSON('api/year&semester/deleteYears.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ year_id: id })
    });
    if (res.success) {
      showMessage(res.message);
      loadYears();
    } else {
      showMessage(res.message);
    }
  }
}

async function loadSemesters() {
  const res = await fetchJSON('api/year&semester/getSemesters.php');
  const table = document.getElementById('semestersTable');
  table.innerHTML = `
    <tr>
      <th>ID</th>
      <th>Semester</th>
      <th>Year ID</th>
      <th>Actions</th>
    </tr>`;

  if (res.success && res.data) {
    res.data.forEach(s => {
      table.innerHTML += `
        <tr>
          <td>${s.sem_id}</td>
          <td>${s.sem_name}</td>
          <td>${s.year_id}</td>
          <td><button class="delBtn" onclick="deleteSemester(${s.sem_id})">Delete</button></td>
        </tr>`;
    });
  }
}

async function deleteSemester(id) {
  const confirmed = window.confirm("Are you sure you want to delete this semester?");
  if (confirmed) {
    const res = await fetchJSON('api/year&semester/deleteSemesters.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ sem_id: id })
    });
    if (res.success) {
      showMessage(res.message);
      loadSemesters();
    } else {
      showMessage(res.message);
    }
  }
}

document.getElementById('addYearForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const res = await fetchJSON('api/year&semester/addYears.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (res.success) {
    showMessage(res.message);
    e.target.reset();
    loadYears();
  } else {
    showMessage(res.message);
  }
});

document.getElementById('addSemesterForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const res = await fetchJSON('api/year&semester/addSemesters.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (res.success) {
    showMessage(res.message);
    e.target.reset();
    loadSemesters();
  } else {
    showMessage(res.message);
  }
});

// ========= Subjects Management =========
async function loadSubjects() {
  const res = await fetchJSON('api/subject/getSubjects.php');
  const table = document.getElementById('subjectsTable');
  table.innerHTML = `
    <tr>
      <th>ID</th>
      <th>Subject</th>
      <th>Semester ID</th>
      <th>Actions</th>
    </tr>`;

  if (res.success && res.data) {
    res.data.forEach(s => {
      table.innerHTML += `
        <tr>
          <td>${s.subject_id}</td>
          <td>${s.subject_name}</td>
          <td>${s.sem_id}</td>
          <td><button class="delBtn" onclick="deleteSubject(${s.subject_id})">Delete</button></td>
        </tr>`;
    });
  }
}

async function deleteSubject(id) {
  const confirmed = window.confirm("Are you sure you want to delete this subject?");
  if (confirmed) {
    const res = await fetchJSON('api/subject/deleteSubjects.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ subject_id: id })
    });
    if (res.success) {
      showMessage(res.message);
      loadSubjects();
    } else {
      showMessage(res.message);
    }
  }
}

document.getElementById('addSubjectForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const res = await fetchJSON('api/subject/addSubjects.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (res.success) {
    showMessage(res.message);
    e.target.reset();
    loadSubjects();
  } else {
    showMessage(res.message);
  }
});

// ========= Enrollments Management =========
async function loadEnrollments() {
  const res = await fetchJSON('api/enrollment/getEnrollments.php');
  const table = document.getElementById('enrollmentsTable');
  table.innerHTML = `
    <tr>
      <th>ID</th>
      <th>Student</th>
      <th>Subject</th>
      <th>Actions</th>
    </tr>`;

  if (res.success && res.data) {
    res.data.forEach(e => {
      table.innerHTML += `
        <tr>
          <td>${e.load_id}</td>
          <td>${e.first_name} ${e.last_name}</td>
          <td>${e.subject_name}</td>
          <td><button class="delBtn" onclick="removeEnrollment(${e.load_id})">Remove</button></td>
        </tr>`;
    });
  }
}

async function removeEnrollment(id) {
  const confirmed = window.confirm("Are you sure you want to remove this enrollment?");
  if (confirmed) {
    const res = await fetchJSON('api/enrollment/removeEnrollments.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ load_id: id })
    });
    if (res.success) {
      showMessage(res.message);
      loadEnrollments();
    } else {
      showMessage(res.message);
    }
  }
}

document.getElementById('addEnrollmentForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const res = await fetchJSON('api/enrollment/enrollStudent.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (res.success) {
    showMessage(res.message);
    e.target.reset();
    loadEnrollments();
  } else {
    showMessage(res.message);
  }
});

// Initial load
window.onload = () => {
  showSection('students');
};
