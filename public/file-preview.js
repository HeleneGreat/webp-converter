let fileInput = document.getElementById('file');
let preview = document.getElementById('preview');

    fileInput.addEventListener('change', function(event) {
        const [file] = fileInput.files;
        if (file) {
            preview.style.display= "block";
            preview.src = URL.createObjectURL(file);
            console.log(preview.src)
        }
    });
