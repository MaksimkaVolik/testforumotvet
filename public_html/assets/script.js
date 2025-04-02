// Конфигурация
const REACT_API_URL = '/public/api/react.php';
const DEBOUNCE_DELAY = 300; // Задержка для предотвращения спама

// Обработчик реакций с улучшениями
document.addEventListener('DOMContentLoaded', () => {
    // Делегирование событий для динамически добавляемых элементов
    document.body.addEventListener('click', async (e) => {
        const btn = e.target.closest('.like-btn, .dislike-btn');
        if (!btn) return;

        // Добавляем индикатор загрузки
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '⏳';
        btn.disabled = true;

        try {
            const postId = btn.dataset.postId;
            const type = btn.classList.contains('like-btn') ? 'like' : 'dislike';
            
            // Отправка запроса с обработкой ошибок
            const response = await fetch(REACT_API_URL, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    post_id: postId, 
                    type: type 
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status) {
                // Обновляем UI
                updateReactionUI(postId, result, btn);
            }
        } catch (error) {
            console.error('Reaction error:', error);
            showToast('Ошибка при отправке реакции', 'error');
        } finally {
            // Восстанавливаем кнопку
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    });
});

// Функция обновления интерфейса
function updateReactionUI(postId, result, clickedBtn) {
    // Обновляем счетчики
    const likeBtn = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
    const dislikeBtn = document.querySelector(`.dislike-btn[data-post-id="${postId}"]`);
    
    if (likeBtn && dislikeBtn) {
        likeBtn.querySelector('.count').textContent = result.likes || 0;
        dislikeBtn.querySelector('.count').textContent = result.dislikes || 0;

        // Сбрасываем активные состояния
        likeBtn.classList.remove('active');
        dislikeBtn.classList.remove('active');

        // Устанавливаем новое состояние
        if (result.status === 'added') {
            clickedBtn.classList.add('active');
        } else if (result.status === 'changed') {
            clickedBtn.classList.add('active');
        }
        
        // Анимация
        if (result.status !== 'removed') {
            animateReaction(clickedBtn);
        }
    }
}

// Анимация реакции
function animateReaction(button) {
    button.style.transform = 'scale(1.2)';
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 300);
}

// Вспомогательная функция для уведомлений
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Оптимизация: предотвращение быстрых повторных нажатий
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
};