document.addEventListener("DOMContentLoaded", function () {
    const skillList = document.getElementById('skillList');
    const storedSkillsList = JSON.parse(localStorage.getItem('skillsList')) || [];

    // Clear any existing list in the sidebar
    skillList.innerHTML = '';

    // If there are skills in localStorage, display them in the sidebar
    if (storedSkillsList.length === 0) {
        skillList.innerHTML = "<li>No skills added yet.</li>";
    } else {
        storedSkillsList.forEach(skill => {
            const listItem = document.createElement('li');
            listItem.innerHTML = `<i data-lucide="check-circle"></i> ${skill}`;
            skillList.appendChild(listItem);
        });
    }
});
