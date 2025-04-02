// Редактирование поста
document.addEventListener('DOMContentLoaded', () => {
    // Обработчик кнопки редактирования
    document.querySelectorAll('.edit-post').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            window.location.href = `/edit_post.php?id=${postId}`;
        });
    });

    // Обработчик удаления поста
    document.querySelectorAll('.delete-post').forEach(btn => {
        btn.addEventListener('click', async function() {
            const postId = this.dataset.postId;
            
            if (confirm('Вы уверены, что хотите удалить этот пост?')) {
                try {
                    const response = await fetch(`/api/delete_post.php?id=${postId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        document.querySelector(`#post-${postId}`).remove();
                    }
                } catch (error) {
                    console.error('Ошибка при удалении:', error);
                }
            }
        });
    });

    // Смена аватарки
    const avatarBtn = document.getElementById('change-avatar-btn');
    if (avatarBtn) {
        avatarBtn.addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            
            input.onchange = async (e) => {
                const file = e.target.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('avatar', file);
                    
                    try {
                        const response = await fetch('/api/update_avatar.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        if (response.ok) {
                            location.reload();
                        }
                    } catch (error) {
                        console.error('Ошибка при загрузке аватарки:', error);
                    }
                }
            };
            
            input.click();
        });
    }
});