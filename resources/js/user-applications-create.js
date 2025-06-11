document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('resume');
    const btn = document.getElementById('resume-btn');
    const list = document.getElementById('resume-list');
    let files = [];

    btn.addEventListener('click', () => input.click());

    input.addEventListener('change', (e) => {
        for (const file of e.target.files) {
            files.push(file);
        }
        renderList();
        input.value = '';
    });

    function renderList() {
        list.innerHTML = '';
        files.forEach((file, idx) => {
            const li = document.createElement('li');
            li.className = "flex items-center gap-2 mb-1";
            li.innerHTML = `
                <span>${file.name}</span>
                <button type="button" class="text-red-500 text-lg" onclick="removeFile(${idx})">&times;</button>
            `;
            list.appendChild(li);
        });
    }

    window.removeFile = function(idx) {
        files.splice(idx, 1);
        renderList();
    };

    document.querySelector('form').addEventListener('submit', function(e) {
        if (files.length) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.delete('resume[]');
            files.forEach(file => formData.append('resume[]', file));
            fetch(this.action, {
                method: this.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                }
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    window.location.reload();
                }
            });
        }
    });
});