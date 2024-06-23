document.addEventListener('DOMContentLoaded', function() {
    const monthYear = document.getElementById('monthYear');
    const dates = document.getElementById('dates');

    const now = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const daysInMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
    const firstDayIndex = new Date(now.getFullYear(), now.getMonth(), 1).getDay();

    monthYear.textContent = `${monthNames[now.getMonth()]} ${now.getFullYear()}`;

    for (let i = 0; i < firstDayIndex; i++) {
        const emptyDiv = document.createElement('div');
        dates.appendChild(emptyDiv);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement('div');
        dayDiv.textContent = day;
        if (day === now.getDate()) {
            dayDiv.classList.add('today');
        }
        dates.appendChild(dayDiv);
    }
});
