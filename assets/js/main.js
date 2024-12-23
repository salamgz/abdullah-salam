document.addEventListener('DOMContentLoaded', function() {
    // Animasi scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.animate__animated');
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight;
            
            if(elementPosition < screenPosition) {
                element.style.visibility = 'visible';
                element.classList.add('animate__fadeInUp');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);

    // Fungsi untuk membuka modal edit
    window.openEditModal = function(id, sleepTime, wakeTime, mood, notes) {
        const modal = document.getElementById('editModal');
        const recordId = document.getElementById('edit_record_id');
        const sleepTimeInput = document.getElementById('edit_sleep_time');
        const wakeTimeInput = document.getElementById('edit_wake_time');
        const moodSelect = document.getElementById('edit_mood');
        const notesInput = document.getElementById('edit_notes');

        // Format tanggal untuk input datetime-local
        const formatDateTime = (dateStr) => {
            const date = new Date(dateStr);
            return date.toISOString().slice(0, 16);
        };

        recordId.value = id;
        sleepTimeInput.value = formatDateTime(sleepTime);
        wakeTimeInput.value = formatDateTime(wakeTime);
        moodSelect.value = mood || 'biasa';
        notesInput.value = notes || '';
        
        modal.style.display = 'block';
    };

    // Fungsi untuk konfirmasi hapus
    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f44336',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal-custom-popup',
                title: 'swal-custom-title',
                confirmButton: 'swal-custom-confirm',
                cancelButton: 'swal-custom-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'process/delete_sleep.php?id=' + id;
            }
        });
    };

    // Tutup modal saat klik tombol close
    const closeButtons = document.getElementsByClassName('close');
    Array.from(closeButtons).forEach(button => {
        button.onclick = function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
        };
    });

    // Tutup modal saat klik di luar modal
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    // Validasi form edit
    const editForm = document.querySelector('#editModal form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const sleepTime = new Date(document.getElementById('edit_sleep_time').value);
            const wakeTime = new Date(document.getElementById('edit_wake_time').value);

            if (wakeTime <= sleepTime) {
                e.preventDefault();
                alert('Jam bangun harus lebih besar dari jam tidur!');
            }
        });
    }
}); 