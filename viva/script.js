document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('addMember').addEventListener('click', function () {
        const newMember = document.createElement('div');
        newMember.classList.add('member');
        newMember.innerHTML = `
            <label>Username: <input type="text" name="username[]" required></label>
            <label>Name: <input type="text" name="name[]" required></label>
        `;
        document.getElementById('teamMembers').appendChild(newMember);
    });

    document.getElementById('vivaForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        fetch('submit.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
          .then(data => {
              alert(data);
          });
    });
});