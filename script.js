// ========= Helper ==========
async function fetchJSON(url, options = {}) {
  const res = await fetch(url, options);
  if (!res.ok) throw new Error("HTTP Error " + res.status);
  return res.json();
}

function showSection(id) {
  document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
  document.getElementById(id).classList.add("active");
  // auto-load data when switching
  if (id === "students") loadStudents();
  if (id === "programs") loadPrograms();
  if (id === "years") loadYears();
  if (id === "semesters") loadSemesters();
  if (id === "subjects") loadSubjects();
  if (id === "enrollments") loadEnrollments();
}

// ========= Students =========
async function loadStudents() {
  const res = await fetchJSON("api/students/getStudents.php");
  const table = document.getElementById("studentsTable");
  table.innerHTML = "<tr><th>ID</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Program ID</th><th>Allowance</th><th>Actions</th></tr>";
  if (res.success && res.data) {
    res.data.forEach(s => {
      table.innerHTML += `
        <tr>
          <td>${s.stud_id}</td>
          <td>${s.first_name}</td>
          <td>${s.middle_name || ''}</td>
          <td>${s.last_name}</td>
          <td>${s.program_id}</td>
          <td>${s.allowance}</td>
          <td>
            <button class="editBtn" onclick="openEditStudentModal(${s.stud_id})">Edit</button>
            <button class="delBtn" onclick="deleteStudent(${s.stud_id})">Delete</button>
          </td>
        </tr>`;
    });
  } else {
    table.innerHTML += `<tr><td colspan="7">Error loading students: ${res.message}</td></tr>`;
  }
}

document.getElementById("addStudentForm").addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());

  if (Object.keys(data).length === 0) {
    alert("Please fill out the form.");
    return;
  }
  
  const res = await fetchJSON("api/students/addStudent.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  
  if (res.success) {
    alert(res.message);
    e.target.reset();
    loadStudents();
  } else {
    alert(res.message);
  }
});

function openEditStudentModal(id) {
  fetchJSON(`api/students/getStudentById.php?id=${id}`)
    .then(res => {
      if (res.success && res.data) {
        const student = res.data;
        document.getElementById('edit-stud-id').value = student.stud_id;
        document.getElementById('edit-first-name').value = student.first_name;
        document.getElementById('edit-middle-name').value = student.middle_name;
        document.getElementById('edit-last-name').value = student.last_name;
        document.getElementById('edit-program-id').value = student.program_id;
        document.getElementById('edit-allowance').value = student.allowance;
        document.getElementById('editStudentModal').style.display = 'block';
      } else {
        alert("Could not load student data.");
      }
    });
}

document.getElementById('editStudentForm').addEventListener('submit', async e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());

  const res = await fetchJSON("api/students/updateStudent.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  if (res.success) {
    alert(res.message);
    document.getElementById('editStudentModal').style.display = 'none';
    loadStudents();
  } else {
    alert(res.message);
  }
});

// ... (Your existing functions)

// ========= Programs Management =========

async function loadPrograms() {
  const res = await fetchJSON("api/programs/getPrograms.php");
  const table = document.getElementById("programsTable");
  table.innerHTML = "<tr><th>ID</th><th>Name</th><th>Institute ID</th><th>Actions</th></tr>";
  if (res.success && res.data) {
    res.data.forEach(p => {
      table.innerHTML += `
        <tr>
          <td>${p.program_id}</td>
          <td>${p.program_name}</td>
          <td>${p.ins_id}</td>
          <td>
            <button class="editBtn" onclick="openEditProgramModal(${p.program_id})">Edit</button>
            <button class="delBtn" onclick="deleteProgram(${p.program_id})">Delete</button>
          </td>
        </tr>`;
    });
  }
}

// Function para kunin ang program data at buksan ang modal
function openEditProgramModal(id) {
  fetchJSON(`api/programs/getProgramsById.php?id=${id}`)
    .then(res => {
      if (res.success && res.data) {
        const program = res.data;
        document.getElementById('edit-program-id').value = program.program_id;
        document.getElementById('edit-program-name').value = program.program_name;
        document.getElementById('edit-program-ins-id').value = program.ins_id;
        document.getElementById('editProgramModal').style.display = 'block';
      } else {
        alert("Could not load program data.");
      }
    });
}

// Event listener para sa pag-update ng program
document.getElementById('editProgramForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());

  const res = await fetchJSON("api/programs/updatePrograms.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  if (res.success) {
    alert(res.message);
    document.getElementById('editProgramModal').style.display = 'none';
    loadPrograms();
  } else {
    alert(res.message);
  }
});

// ========= Years =========
async function loadYears() {
  const res = await fetchJSON("api/year&semester/getYears.php");
  const table = document.getElementById("yearsTable");
  table.innerHTML = "<tr><th>ID</th><th>From</th><th>To</th><th>Actions</th></tr>";
  if (res.success && res.data) {
    res.data.forEach(y => {
      table.innerHTML += `
        <tr>
          <td>${y.year_id}</td>
          <td>${y.year_from}</td>
          <td>${y.year_to}</td>
          <td><button class="editBtn" onclick="EditYear(${y.year_id})">Edit</button>
          <button class="delBtn" onclick="deleteYear(${y.year_id})">Delete</button</td>
          
        </tr>`;
    });
  }
}

document.getElementById("addYearForm").addEventListener("submit", async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  await fetchJSON("api/year&semester/addYears.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  e.target.reset();
  loadYears();
});

async function deleteYear(id) {
  const confirmed = window.confirm("Are you sure you want to delete this year?");
  if (confirmed) {
    const res = await fetchJSON("api/year&semester/deleteYears.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ year_id: id })
    });
    if (res.success) {
      alert(res.message);
      loadYears();
    } else {
      alert(res.message);
    }
  }
}

// ========= Semesters =========
async function loadSemesters() {
  const res = await fetchJSON("api/year&semester/getSemesters.php");
  const table = document.getElementById("semestersTable");
  table.innerHTML = "<tr><th>ID</th><th>Name</th><th>Year ID</th><th>Actions</th></tr>";
  if (res.success && res.data) {
    res.data.forEach(s => {
      table.innerHTML += `
        <tr>
          <td>${s.sem_id}</td>
          <td>${s.sem_name}</td>
          <td>${s.year_id}</td>
          <td>
            <button class="editBtn" onclick="openEditSemesterModal(${s.sem_id})">Edit</button>
            <button class="delBtn" onclick="deleteSemester(${s.sem_id})">Delete</button>
          </td>
        </tr>`;
    });
  }
}

document.getElementById("addSemesterForm").addEventListener("submit", async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  await fetchJSON("api/year&semester/addSemesters.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  e.target.reset();
  loadSemesters();
});

async function deleteSemester(id) {
  const confirmed = window.confirm("Are you sure you want to delete this semester?");
  if (confirmed) {
    const res = await fetchJSON("api/year&semester/deleteSemesters.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ sem_id: id })
    });
    if (res.success) {
      alert(res.message);
      loadSemesters();
    } else {
      alert(res.message);
    }
  }
}

function openEditSemesterModal(id) {
  fetchJSON(`api/year&semester/getSemestersById.php?id=${id}`)
    .then(res => {
      if (res.success && res.data) {
        const semester = res.data;
        document.getElementById('edit-sem-id').value = semester.sem_id;
        document.getElementById('edit-sem-name').value = semester.sem_name;
        document.getElementById('edit-sem-year-id').value = semester.year_id;
        document.getElementById('editSemesterModal').style.display = 'block';
      } else {
        alert("Could not load semester data.");
      }
    });
}

document.getElementById('editSemesterForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());

  const res = await fetchJSON("api/year&semester/updateSemesters.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  if (res.success) {
    alert(res.message);
    document.getElementById('editSemesterModal').style.display = 'none';
    loadSemesters();
  } else {
    alert(res.message);
  }
});

// ========= Subjects =========
async function loadSubjects() {
  const res = await fetchJSON("api/subjects/getSubjects.php");
  const table = document.getElementById("subjectsTable");
  table.innerHTML = "<tr><th>ID</th><th>Name</th><th>Semester ID</th><th>Actions</th></tr>";
  if (res.success && res.data) {
    res.data.forEach(s => {
      table.innerHTML += `
        <tr>
          <td>${s.subject_id}</td>
          <td>${s.subject_name}</td>
          <td>${s.sem_id}</td>
          <td>
            <button class="delBtn" onclick="deleteSubject(${s.subject_id})">Delete</button>
            <button class="editBtn" onclick="editSubject(${s.subject_id})">Edit</button>
          </td>
        </tr>`;
    });
  }
}

document.getElementById("addSubjectForm").addEventListener("submit", async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  await fetchJSON("api/subject/addSubjects.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  e.target.reset();
  loadSubjects();
});

async function deleteSubject(id) {
  const confirmed = window.confirm("Are you sure you want to delete this subject?");
  if (confirmed) {
    const res = await fetchJSON("api/subject/deleteSubjects.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ subject_id: id })
    });
    if (res.success) {
      alert(res.message);
      loadSubjects();
    } else {
      alert(res.message);
    }
  }
}

// ========= Enrollments =========
async function loadEnrollments() {
  const res = await fetchJSON("api/enrollment/getEnrollments.php");
  const table = document.getElementById("enrollmentsTable");
  table.innerHTML = "<tr><th>Load ID</th><th>Student ID</th><th>Subject ID</th><th>Actions</th></tr>";
  if (res.success && res.data) {
    res.data.forEach(e => {
      table.innerHTML += `
        <tr>
          <td>${e.load_id}</td>
          <td>${e.stud_id}</td>
          <td>${e.subject_id}</td>
          <td>
            <button class="delBtn" onclick="removeEnrollment(${e.load_id})">Delete</button>
             <button class="editBtn" onclick="updateEnrollment(${e.load_id})">Edit</button>
          </td>
        </tr>`;
    });
  }
}
document.getElementById("addEnrollmentForm").addEventListener("submit", async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  await fetchJSON("api/enrollment/enrollStudent.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  e.target.reset();
  loadEnrollments();
});

async function removeEnrollment(id) {
  const confirmed = window.confirm("Are you sure you want to remove this enrollment?");
  if (confirmed) {
    const res = await fetchJSON("api/enrollment/removeEnrollments.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ load_id: id })
    });
    if (res.success) {
      alert(res.message);
      loadEnrollments();
    } else {
      alert(res.message);
    }
  }
}

// ========= Initialize =========
document.addEventListener("DOMContentLoaded", () => {
  loadStudents(); // default
});